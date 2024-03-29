<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CartItem::all();
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
            "cart_id" => "exists:carts,id",
            "product_id" => "exists:products,id",
            "quantity" => "integer",
            "price" => "required|numeric|between:0,1000000.99",
        ]);
        if ($existingProduct = CartItem::query()->where([["product_id", "=", $request["product_id"]], ["cart_id", "=", $request["cart_id"]]])->first()) {
            return $existingProduct->update(["quantity" => $existingProduct["quantity"] + 1]);
        }

        return CartItem::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return CartItem::query()
            ->where("cart_id", "=", $id)
            ->with(["products", "products.media_condition", "products.product_type","products.sleeve_condition"])
            ->get(["quantity", "product_id", "price"]);
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
        return CartItem::where("id", $id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return CartItem::query()->where("product_id", "=", $id)->delete();
    }
}
