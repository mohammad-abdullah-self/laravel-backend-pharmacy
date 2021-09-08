<?php

namespace App\Http\Controllers\Backend;

use App\Blog;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Blog as BlogResource;
use Illuminate\Support\Str;

class BlogController extends Controller
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
        return  BlogResource::collection(Blog::orderBy('id', 'DESC')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogValidation $request)
    {

        $blogPicture = 'blog.jpg';
        if (request()->hasFile('picture')) {

            $file = request()->file("picture");
            $picture_make = \Image::make($file)->fit(768, 350, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();
            $givePictureName = time() . "-" . request("picture")->getClientOriginalName();
            Storage::put($givePictureName, $picture_make);
            Storage::move($givePictureName, 'public/blog/' . $givePictureName);
            $blogPicture = $givePictureName;
        }

        $published_at = $request->published_date . " " . $request->published_time . ":00";

        Blog::create([
            'picture' =>  $blogPicture,
            'title' =>  $request->title,
            'slug' => Str::slug($request->title . "-" . time()),
            'body' => $request->body,
            'published_at' => $published_at,
        ]);

        return  BlogResource::collection(Blog::orderBy('id', 'DESC')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(BlogValidation $request, $blogId)
    {
        $blog = Blog::findOrFail($blogId);
        $picture_path =  $blog->picture;
        $pathinfo = pathinfo($picture_path);
        $blogPicture = $pathinfo['filename'] . '.' . $pathinfo['extension'];

        if ($request->hasFile('picture')) {



            if ($blogPicture &&  $blogPicture != "blog.jpg") {
                Storage::delete('public/blog/' .  $blogPicture);
            }

            $file = request()->file("picture");
            $picture_make = \Image::make($file)->fit(768, 350, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();
            $givePictureName = time() . "-" . $file->getClientOriginalName();
            Storage::put($givePictureName, $picture_make);
            Storage::move($givePictureName, 'public/blog/' . $givePictureName);
            $blogPicture = $givePictureName;
        }

        $published_at = $request->published_date . " " . $request->published_time . ":00";

        $blog->update([
            'picture' =>  $blogPicture,
            'title' =>  $request->title,
            'slug' => Str::slug($request->title . "-" . time()),
            'body' => $request->body,
            'published_at' => $published_at,
        ]);

        return  BlogResource::collection(Blog::orderBy('id', 'DESC')->get());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy($blogId)
    {
        $blog = Blog::findOrFail($blogId);
        $blog->delete();
        return  BlogResource::collection(Blog::orderBy('id', 'DESC')->get());
    }

    public function blogTrash()
    {

        return  BlogResource::collection(Blog::onlyTrashed()->get());
    }

    public function blogRestore(Request $request)
    {
        Blog::onlyTrashed()->where('id', $request->id)->restore();

        return  BlogResource::collection(Blog::onlyTrashed()->get());
    }

    public function blogForceDelete(Request $request)
    {


        foreach ($request->selected as $select) {
            $blog = Blog::onlyTrashed()
                ->where('id', $select)->first();

            $picture_path =  $blog->picture;
            $pathinfo = pathinfo($picture_path);
            $blogPicture = $pathinfo['filename'] . '.' . $pathinfo['extension'];


            if ($blogPicture &&  $blogPicture != "blog.jpg") {
                Storage::delete('public/blog/' .  $blogPicture);
            }

            $blog->forceDelete();
        }

        return  BlogResource::collection(Blog::onlyTrashed()->get());
    }
}
