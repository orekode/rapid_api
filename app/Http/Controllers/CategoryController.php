<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Resources\CategoryResource;

use Illuminate\Http\Request;
use App\Services\GeneralService;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $filter     = new GeneralService();
        $queryItems = $filter->transform($request);

        $category = Category::where($queryItems);

        if($request->query('subs')) {
            $category->with('subs');
        }


        return CategoryResource::collection(
            $category->paginate()->appends($request->query())
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $image = $request->file('image')->store('images/categories');

        $ancestors = "";

        if(isset($request->parent_id)) {

            $parent = Category::find($request->parent_id);

            if($parent) {
                $ancestors .= " {{$parent->id}} {{$parent->ancestors}} ";
            }
            
        }

        return new CategoryResource(Category::create([
            ...$request->all(),
            "ancestors" => $ancestors,
            "image"     => $image
        ]));
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Request $request)
    {
        //
        if($request->query('subs')) {
            $subsPerPage = $request->query('subsPerPage', 15);
            
            $category = $category->load([
                'subs' => function ($query) use ($subsPerPage) {
                    $query->paginate($subsPerPage);
                }
            ]);
        }

        // return $category;
        

        return new CategoryResource($category);
    }

    public function showSubCategories(Request $request) 
    {
        $filter     = new GeneralService();
        $queryItems = $filter->transform($request);

        $data = $request->validate([
            'identifier' => 'required'
        ]);

        $parent = Category::where('id', $data['identifier'])->orWhere('name', $data['identifier'])->first();

        return CategoryResource::collection(
            Category::where($queryItems)->where('ancestors', 'like', "%{$parent->id}%")->paginate()
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $image = $category->image;
        

        if(isset($request->image)) {
            $image = $request->file('image')->store('images/categories');
        }

        if(isset($request->parent_id) && !strpos($category->ancestors, $request->parent_id) && $request->parent_id !== $category->id) {

            $new_parent = Category::find($request->parent_id);
            $new_ancestors = " {{$new_parent->id}} {{$new_parent->ancestors}} ";

            if($new_parent) {
                
                $children = Category::where('ancestors', 'like', "%{{$category->id}}%")->get();
    
                foreach($children as $child) {
                    $child->update([
                        "ancestors" => str_replace(
                            "{{$category->ancestors}}", 
                            "{{$new_ancestors}}"
                        )
                    ]);
                }

                $category->update([
                    "ancestors" => $new_ancestors,
                    "parent_id" => $new_parent->id,
                ]);
            }

        }

        $category->update([
            'name' => $request->name,
            'image' => $image,
        ]);

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        return $category->delete();
    }
}
