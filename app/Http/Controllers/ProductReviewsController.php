<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReviews;
use App\Models\User;
use Illuminate\Http\Request;

class ProductReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductReviews::all();
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
            "review" => "string",
            "session_id" => "required|exists:users,session_id",
            "product_id" => "required|exists:products,id",
            "rating" => "required|integer",
        ]);
        $user_id = User::query()->where("session_id", "=", $request["session_id"])->first("id")["id"];
        $request->request->remove("session_id");
        $request->request->add(["user_id" => $user_id]);

        $current_rating = Product::query()->where("id", "=", $request["product_id"])->first("rating")["rating"];
        $number_of_ratings = count(ProductReviews::query()->where("id", "=", $request["product_id"])->get()) + 1;
        $new_rating = ($current_rating * ($number_of_ratings - 1) + $request["rating"]) / $number_of_ratings;
        $new_review = ProductReviews::create($request->all());
        Product::query()->where("id", "=", $request["product_id"])->update(["rating" => $new_rating]);

        return $new_review;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
