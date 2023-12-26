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
            $category->paginate()->appends($request->query()))
        ;
    }//
        // if($request->query('subs')) {
        //     $category = $category->with('subs')->get();
        // }

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

        return new CategoryResource(Category::create([
            ...$request->all(),
            "image" => $image
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
