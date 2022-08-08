<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        $requestTitle = $request->query("title");
        $requestFormat = $request->query("format");
        $requestMediaCondition = $request->query("media_condition");
        $requestSleeveCondition = $request->query("sleeve_condition");
        $requestGenre = $request->query("genre");
        $requestMinPrice = $request->query("min_price");
        $requestMaxPrice = $request->query("max_price");

        return Product::query()->with(["media_condition", "sleeve_condition", "product_type"])->when($requestTitle, function($query, $title) {
            $query->where("title", "LIKE", "%" . $title . "%");
        })->when($requestFormat, function($query, $format) {
            $query->where("product_type_id", "=", $format);
        })->when($requestMediaCondition, function($query, $media_condition) {
            $query->where("media_condition", "=", $media_condition);
        })->when($requestSleeveCondition, function($query, $sleeve_condition) {
            $query->where("sleeve_condition", "=", $sleeve_condition);
        })->when($requestGenre, function($query, $genre) {
            $query->where("genre_id", "=", $genre);
        })->when($requestMinPrice, function($query, $min_price) {
            $query->where("initial_price", ">=", $min_price);
        })->when($requestMaxPrice, function($query, $max_price) {
            $query->where("initial_price", "<=", $max_price);
        })->paginate($pageSize);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            "title" => "required|string",
            "initial_price" => ["integer", "required"],
            "description" => "string",
            "sleeve_condition" => "exists:conditions,id",
            "media_condition" => "exists:conditions,id",
            "sku" => "string",
            "rating" => "integer",
            "product_type_id" => "exists:product_types,id",
            "author" => "string",
            "genre_id" => "exists:genres,id",
            "edition" => "string",
            "discount" => "sometimes|nullable|integer",
            "image" => "mimes:jpg,jpeg,png,webp"
        ]);
        $path = $request->file('image')->store('images', 'public');
        $arrayElements = ["title", "description","initial_price", "description","sleeve_condition", "media_condition"
        ,"sku","rating", "number_of_ratings", "product_type_id", "stock", "author", "genre_id" , "edition", "discount"];
        $values = [];
        foreach ($arrayElements as $element) {
            $values[$element] = $request[$element];
        }
         return Product::create([...$values, "filename" => basename($path), "url" => Storage::disk('public')->url($path)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        if(!is_numeric($id)) return Response::json(["message" => "error"], 400);
        return Product::query()->with(["media_condition", "sleeve_condition", "product_type", "genre"])->find($id);
    }

    /**
     * Display the specific resources.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function showMultiple(Request $request)
    {
        $request->validate(["ids" => "required|array"]);
        return Product::query()->with(["media_condition", "sleeve_condition", "product_type", "genre"])->findMany($request["ids"]);
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
    public function searchTitle(Request $request, $title = "" )
    {
        $pageSize = $request->page_size ?? 10;
        return Product::query()
            ->when($title, function ($q, $title) {
            return $q->where('title', 'LIKE', "%{$title}%");
    })->paginate($request->page_size ?? 10);
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
    /**
     * Get featured products.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFeatured()
    {
        return Product::query()->where("featured", "=", 1)->paginate(4, ["id", "title", "url"]);
    }

    /**
     * Get featured products.
     *
     * @return \Illuminate\Http\Response
     */
    public function decreaseStock($id)
    {
        return Product::query()->where("id", "=", $id)->decrement("stock");
    }

    /**
     * Remove Multiple Products.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyProducts(Request $request)
    {
        $ids = $request->ids;
        return ["response" => Product::whereIn("id",$ids)->delete(), "status" => 204];
    }
}
