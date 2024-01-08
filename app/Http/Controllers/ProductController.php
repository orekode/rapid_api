<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductProperty;

use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductResource::collection(Product::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //0542659270 Mr Koomson
        $image = $request->file('image')->store('images/products');

        $product = Product::create([
            ...$request->all(),
            "image" => $image,
        ]);

        $images = $request->file('images');

        foreach($images as $image) {
            $image = $image->store('/images/products');
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $image
            ]);
        }

        foreach($request->categories as $category) {
            ProductCategory::create([
                'product_id' => $product->id,
                'category_id' => $category,
            ]);
        }

        foreach($request->properties as $property) {
            ProductProperty::create([
                'product_id' => $product->id,
                ...$property
            ]);
        }

        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //

        return new ProductResource($product->load(['categories', 'properties', 'images']));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {

        $total_images = count($product->images) - count($request->previous_images ?? []) + count($request->images ?? []);
        $errors = [];

        if($total_images < 3) {
            $errors["images"] = "Please upload at least 3 images";
        }

        if(count($errors) > 0) {
            return response()->json([
                "errors" => $errors
            ], 422);
        }
        
        $this->updateCategories($product, $request->categories);
        $this->updateProperties($product, $request->properties);

        if(isset($request->previous_images)) {
            $this->removeImages($product, $request->previous_images);
        }

        if(isset($request->images)) {
            $images = $request->file('images');
            $this->uploadImages($product, $images);
        }

        $image = $product->image;

        if(isset($request->image)) {
            $image = $request->file('image')->store('images/products');
        }

        $product->update([
            ...$request->all(),
            "image" => $image,
            "long_description" => $request->long_description ?? "",
        ]);

        return $request->all();
    }

    public function updateCategories(Product $product, array $categories) : void
    {
        ProductCategory::where('product_id', $product->id)->delete();

        foreach($categories as $category) {
            ProductCategory::create([
                'product_id' => $product->id,
                'category_id' => $category,
            ]);
        }
    }

    public function updateProperties(Product $product, array $properties) : void
    {
        ProductProperty::where('product_id', $product->id)->delete();

        foreach($properties as $property) {
            ProductProperty::create([
                'product_id' => $product->id,
                ...$property
            ]);
        }
    }

    public function removeImages(Product $product, array $images) : void
    {
        foreach($images as $image) {
            $image = str_replace(env('APP_URL')."/", "", $image);
            ProductImage::where('image', $image)->delete();
        }
    }

    public function uploadImages(Product $product, array $images) : void
    {
        foreach($images as $image) {
            $link = $image->store('images/products');

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $link,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return $product->delete();
    }
}
