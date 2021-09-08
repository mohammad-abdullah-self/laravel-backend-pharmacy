<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'verified']);
        $this->middleware('role:Super Admin|Admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  CategoryResource::collection(Category::orderBy('id', 'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([

            'name' => 'required|unique:categories,name',
        ]);

        if ($request->name) {
            Category::create([
                'name' => $request->name,
            ]);
        }

        return  CategoryResource::collection(Category::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        request()->validate([

            'name' => 'required|unique:categories,name',
        ]);

        if ($request->name) {
            $category->update([
                'name' => $request->name,
            ]);
        }

        return  CategoryResource::collection(Category::all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return response()->json("haveProduct");
        }
        $category->delete();
        return  CategoryResource::collection(Category::all());
    }
}
