<?php

namespace App\Http\Controllers\Fontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Banner;
use App\Http\Resources\Banner as BannerResource;
use App\Footer;
use App\Http\Resources\Footer as FooterResource;
use App\Product;
use App\Http\Resources\Product as ProductResource;
use App\Blog;
use App\Http\Resources\Blog as BlogResource;
use App\Category;
use App\Http\Resources\Category as CategoryResource;
use App\UserFeedback;
use App\Http\Resources\UserFeedback as UserFeedbackResource;;

use Spatie\Permission\Models\Role;

class FontendController extends Controller
{
    public function bannerIndex()
    {
        // Role::create(['name' => 'Super Admin']);
        // Role::create(['name' => 'Admin']);
        // Role::create(['name' => 'User']);

        return  BannerResource::collection(Banner::all());
    }

    public function footerIndex()
    {
        return new FooterResource(Footer::first());
    }

    public function productIndex()
    {
        return  ProductResource::collection(Product::all());
    }

    public function blogIndex()
    {
        return  BlogResource::collection(Blog::published()->orderBy('id', 'DESC')->paginate(4));
    }

    public function showBlog($slug, Blog $blog)
    {
        return new BlogResource($blog->whereSlug($slug)->first());
    }

    public function allProductIndex()
    {
        return  ProductResource::collection(Product::paginate(16));
    }

    public function categoryIndex()
    {
        return  CategoryResource::collection(Category::all());
    }

    public function userFeedbackIndex()
    {
        return  UserFeedbackResource::collection(UserFeedback::published()->get());
    }
}
