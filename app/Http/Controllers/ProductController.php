<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        return Product::query()->paginate($pageSize);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string",
            "initial_price" => "required|integer",
            "description" => "string",
            "sleeve_condition" => "exists:conditions,id",
            "media_condition" => "exists:conditions,id",
            "sku" => "string",
            "rating" => "decimal",
            "product_type" => "exists:product_types,id",
            "author" => "string",
            "genre" => "exists:genres",
            "edition" => "string",
            "discount" => "sometimes|nullable|exists:discounts",
            "image" => "required|mimes:png,jpg,jpeg|max:10000"
        ]);
        $path = $request->file('image')->store('images', 's3');

        return Product::create([$request->all(), "filename" => basename($path), "url" => Storage::disk('s3')->url($path)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return Product::where("id", $id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }

    /**
     * Search for a product title.
     *
     * @param  string  $title
     * @return \Illuminate\Http\Response
     */
    public function searchTitle($title, Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        return Product::where("title", "LIKE", "%" . $title . "%")->paginate($pageSize);
    }
    /**
     * Filter by rating.
     *
     * @param  decimal  $minRating
     * @param  decimal  $maxRating
     * @return \Illuminate\Http\Response
     */
    public function filterRating($minRating, $maxRating, Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        return Product::where([["rating", ">=", $minRating], ["rating", "<=", $maxRating]])->paginate($pageSize);
    }

    public function filterProductType($productType, Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        return Product::where(["product_type_id", "=", $productType])->paginate($pageSize);
    }

    /**
     * Rate product.
     *
     * @param  integer  $id
     * @param  integer  $rating
     * @return \Illuminate\Http\Response
     */
    public function rateProduct($id, Request $request)
    {
        $product = $this->show($id);
        $newNumberOfRatings = $product["number_of_ratings"] + 1;
        $newRating = ($product["rating"] + $request->rating) / $newNumberOfRatings;

        return Product::where("id", $id)->update(["rating" => $newRating, "number_of_ratings" => $newNumberOfRatings]);
    }
}
