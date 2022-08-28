<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $requestFormat = json_decode($request->query("format"));
        $requestMediaCondition = json_decode($request->query("media_condition"));
        $requestSleeveCondition = json_decode($request->query("sleeve_condition"));
        $requestGenre = json_decode($request->query("genre"));
        $requestMinPrice = $request->query("min_price");
        $requestMaxPrice = $request->query("max_price");
        $requestTags = json_decode($request->query("tags"));

        return Product::query()->with(["media_condition", "sleeve_condition", "product_type"])->when($requestTitle, function($query, $title) {
            $query->where("title", "LIKE", "%" . $title . "%");
        })->when($requestFormat, function($query, $format) {
            $query->whereIn("product_type_id", $format);
        })->when($requestMediaCondition, function($query, $media_condition) {
            $query->whereIn("media_condition", $media_condition);
        })->when($requestSleeveCondition, function($query, $sleeve_condition) {
            $query->whereIn("sleeve_condition", $sleeve_condition);
        })->when($requestGenre, function($query, $genre) {
            $query->whereIn("genre_id", $genre);
        })->when($requestMinPrice, function($query, $min_price) {
            $query->where("initial_price", ">=", $min_price);
        })->when($requestMaxPrice, function($query, $max_price) {
            $query->where("initial_price", "<=", $max_price);
        })->when($requestTags, function($query, $tags) {
            $query->whereHas("tags", function ($q) use ($tags) {
                $q->whereIn("tag_id", $tags);
            });
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
            "tags" => "sometimes|nullable",
            "discount" => "sometimes|nullable|integer",
            "image" => "mimes:jpg,jpeg,png,webp"
        ]);
        $path = $request->file('image')->store('images', 's3');
        Storage::disk("s3")->setVisibility($path, "public");
        $arrayElements = ["title", "description","initial_price", "description","sleeve_condition", "media_condition"
        ,"sku","rating", "number_of_ratings", "product_type_id", "stock", "author", "genre_id" , "edition", "discount"];
        $values = [];
        foreach ($arrayElements as $element) {
            $values[$element] = $request[$element];
        }
        $new_product = Product::create([...$values, "filename" => basename($path), "url" => Storage::disk('s3')->url($path)]);
        $tags = $request["tags"];
        Product::findOrFail($new_product["id"])->tags()->sync($tags);
        return $new_product;
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
        $tags = $request["tags"];
        $product = Product::find($id);
        $product->tags()->sync($tags);

        //removing tags to use all() function
        $request->request->remove("tags");
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
