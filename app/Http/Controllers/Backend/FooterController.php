<?php

namespace App\Http\Controllers\Backend;

use App\Footer;
use App\Http\Controllers\Controller;
use App\Http\Requests\FooterValidation;
use Illuminate\Http\Request;
use App\Http\Resources\Footer as FooterResource;
use Illuminate\Support\Facades\Storage;

class FooterController extends Controller
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
        return new FooterResource(Footer::first());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FooterValidation $request)
    {
        $footer = Footer::all()->count();
        if (!$footer > 0) {
            $logo = 'logo.png';
            if (request()->hasFile('logo')) {

                $file = request()->file("logo");
                $picture_make = \Image::make($file)->fit(30, 30, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode();
                $givePictureName = time() . "-" .  $file->getClientOriginalName();
                Storage::put($givePictureName, $picture_make);
                Storage::move($givePictureName, 'public/logo/' . $givePictureName);
                $logo = $givePictureName;
            }

            Footer::create([

                'logo' =>  $logo,
                'name' => $request->name,
                'description' => $request->description,
                'f_link' => $request->f_link,
                't_link' => $request->t_link,
                'y_link' => $request->y_link,
                'phone' => $request->phone,
                'houre' => $request->houre,
                'email' => $request->email,
                'address' => $request->address,
            ]);

            return new FooterResource(Footer::first());
        } else {
            return response()->json("haveFooter");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Footer  $footer
     * @return \Illuminate\Http\Response
     */
    public function show(Footer $footer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Footer  $footer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Footer $footer)
    {

        $name = $request->name ? $request->name : $footer->name;
        $description = $request->description ? $request->description : $footer->description;
        $f_link = $request->f_link ? $request->f_link : $footer->f_link;
        $t_link = $request->t_link ? $request->t_link : $footer->t_link;
        $y_link = $request->y_link ? $request->y_link : $footer->y_link;
        $phone = $request->phone ? $request->phone : $footer->phone;
        $houre = $request->houre ? $request->houre : $footer->houre;
        $email = $request->email ? $request->email : $footer->email;
        $address = $request->address ? $request->address : $footer->address;


        $picture_path =  $footer->logo;
        $pathinfo = pathinfo($picture_path);
        $logo = $pathinfo['filename'] . '.' . $pathinfo['extension'];


        if (request()->hasFile('logo')) {

            if ($logo  && $logo  != "logo.png") {
                Storage::delete('public/logo/' .  $logo);
            }

            $file = request()->file("logo");
            $picture_make = \Image::make($file)->fit(30, 30, function ($constraint) {
                $constraint->aspectRatio();
            })->encode();
            $givePictureName = time() . "-" .  $file->getClientOriginalName();
            Storage::put($givePictureName, $picture_make);
            Storage::move($givePictureName, 'public/logo/' . $givePictureName);
            $logo = $givePictureName;
        }

        $footer->update([
            'logo' =>  $logo,
            'name' => $name,
            'description' => $description,
            'f_link' => $f_link,
            't_link' => $t_link,
            'y_link' => $y_link,
            'phone' => $phone,
            'houre' => $houre,
            'email' => $email,
            'address' => $address,
        ]);

        return new FooterResource(Footer::first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Footer  $footer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Footer $footer)
    {
        //
    }
}
