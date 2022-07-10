<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
            "price" => "required|integer",
            "description" => "string",
            "sleeve_condition" => "string",
            "media_condition" => "string",
            "sku" => "string",
            "rating" => "decimal",
        ]);
        return Product::create($request->all());
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
        return Product::where("rating", ">=", $minRating)
            ->where("rating", "<=", $maxRating)
            ->paginate($pageSize);
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
