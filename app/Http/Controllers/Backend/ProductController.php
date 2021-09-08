<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductUpdateValidation;
use App\Http\Requests\ProductValidation;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Product as ProductResource;

class ProductController extends Controller
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
        return  ProductResource::collection(Product::orderBy('id', 'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductValidation $request)
    {
        $productPicture = 'product.jpg';
        if (request()->hasFile('picture')) {

            $file = request()->file("picture");
            $picture_make = \Image::make($file)->fit(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();
            $givePictureName = time() . "-" . request("picture")->getClientOriginalName();
            Storage::put($givePictureName, $picture_make);
            Storage::move($givePictureName, 'public/product/' . $givePictureName);
            $productPicture = $givePictureName;
        }

        Product::create([
            'category_id' => $request->category_id,
            'picture' =>  $productPicture,
            'name' =>  $request->name,
            'generic' => $request->generic,
            'type' => $request->type,
            'manufactured' => $request->manufactured,
            'size' => $request->size,
            'quantity' => $request->quantity,
            'pieces_per_pata' => $request->pieces_per_pata,
            'dose' => $request->dose,
            'old_mrp' => $request->old_mrp,
            'mrp' => $request->mrp,
        ]);



        return  ProductResource::collection(Product::orderBy('id', 'DESC')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateValidation $request, Product $product)
    {

        $category_id =  $product->category_id;
        $picture_path =  $product->picture;
        $pathinfo = pathinfo($picture_path);
        $productPicture = $pathinfo['filename'] . '.' . $pathinfo['extension'];

        if ($request->hasFile('picture')) {

            if ($productPicture && $productPicture != "product.jpg") {
                Storage::delete('public/product/' . $productPicture);
            }

            $file = $request->file("picture");
            $picture_make = \Image::make($file)->fit(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();
            $givePictureName = time() . "-" . request("picture")->getClientOriginalName();
            Storage::put($givePictureName, $picture_make);
            Storage::move($givePictureName, 'public/product/' . $givePictureName);
            $productPicture = $givePictureName;
        }

        if ($request->category_id) {

            $category_id =  $request->category_id;
        }

        $product->update([
            'category_id' => $category_id,
            'picture' =>  $productPicture,
            'name' =>  $request->name,
            'generic' => $request->generic,
            'type' => $request->type,
            'manufactured' => $request->manufactured,
            'size' => $request->size,
            'quantity' => $request->quantity,
            'pieces_per_pata' => $request->pieces_per_pata,
            'dose' => $request->dose,
            'old_mrp' => $request->old_mrp,
            'mrp' => $request->mrp,
        ]);

        return  ProductResource::collection(Product::orderBy('id', 'DESC')->get());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return  ProductResource::collection(Product::orderBy('id', 'DESC')->get());
    }

    public function productTrash()
    {

        return  ProductResource::collection(Product::onlyTrashed()->get());
    }

    public function productRestore(Request $request)
    {
        Product::onlyTrashed()->where('id', $request->id)->restore();

        return  ProductResource::collection(Product::onlyTrashed()->get());
    }

    public function productForceDelete(Request $request)
    {


        foreach ($request->selected as $select) {


            $product =  Product::onlyTrashed()
                ->where('id', $select)->first();

            $picture_path =  $product->picture;
            $pathinfo = pathinfo($picture_path);
            $productPicture = $pathinfo['filename'] . '.' . $pathinfo['extension'];

            if ($productPicture && $productPicture != "product.jpg") {
                Storage::delete('public/product/' . $productPicture);
            }

            $product->forceDelete();
        }

        return  ProductResource::collection(Product::onlyTrashed()->get());
    }
}
