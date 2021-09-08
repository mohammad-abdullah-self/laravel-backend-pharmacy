<?php

namespace App\Http\Controllers\Backend;

use App\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Banner as BannerResource;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
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
        return  BannerResource::collection(Banner::orderBy('id', 'DESC')->get());
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
            'picture' => 'required',
        ]);

        // $bannerPicture = 'banner.jpg';

        if (request()->hasFile('picture')) {

            $files = request()->file("picture");
            foreach ($files as $file) {
                $picture_make = \Image::make($file)->fit(2075, 768, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode();
                $givePictureName = time() . "-" . $file->getClientOriginalName();
                Storage::put($givePictureName, $picture_make);
                Storage::move($givePictureName, 'public/banner/' . $givePictureName);
                $bannerPicture = $givePictureName;
                Banner::create([
                    'picture' =>  $bannerPicture,
                ]);
            }
        }

        return  BannerResource::collection(Banner::orderBy('id', 'DESC')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        request()->validate([
            'picture' => 'required|image',
        ]);

        $picture_path =  $banner->picture;
        $pathinfo = pathinfo($picture_path);
        $bannerPicture = $pathinfo['filename'] . '.' . $pathinfo['extension'];

        if ($request->hasFile('picture')) {



            if ($bannerPicture && $bannerPicture != "banner.jpg") {
                Storage::delete('public/banner/' . $bannerPicture);
            }

            $file = $request->file("picture");
            $picture_make = \Image::make($file)->fit(2075, 768, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();
            $givePictureName = time() . "-" . request("picture")->getClientOriginalName();
            Storage::put($givePictureName, $picture_make);
            Storage::move($givePictureName, 'public/banner/' . $givePictureName);
            $bannerPicture = $givePictureName;
        }

        $banner->update([
            'picture' =>  $bannerPicture,
        ]);

        return  BannerResource::collection(Banner::orderBy('id', 'DESC')->get());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {


        $picture_path =  $banner->picture;
        $pathinfo = pathinfo($picture_path);
        $bannerPicture = $pathinfo['filename'] . '.' . $pathinfo['extension'];

        if ($bannerPicture && $bannerPicture != "banner.jpg") {
            Storage::delete('public/banner/' . $bannerPicture);
        }

        $banner->forceDelete();

        return  BannerResource::collection(Banner::orderBy('id', 'DESC')->get());
    }
}
