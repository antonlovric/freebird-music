<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        return Order::query()->paginate($pageSize);
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
            "total" => "numeric|between:0,100000.99",
            "order_status_id" => "required|exists:order_statuses,id",
            "billing_address" => "required|string",
            "shipping_address" => "string",
            "phone" => "nullable|string",
            "email" => "required|string",
            "billing_city" => "string",
            "shipping_city" => "string",
            "billing_country" => "string",
            "shipping_country" => "string",
            "billing_zipcode" => "string",
            "shipping_zipcode" => "string",
            "session_id" => "nullable|sometimes|exists:users,session_id",
            "cart_id" => "nullable|sometimes|exists:carts,id",
            "comment" => "nullable|string",
            "payment_type" => "string",
            "shipping_type" => "string",
        ]);
        $user_id = User::query()->where("session_id", "=", $request["session_id"])->first("id");
        if ($user_id) $user_id = $user_id["id"];
        $request->request->remove("session_id");
        $request->request->add(["user_id" => $user_id]);
        return Order::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Order::find($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOrderedProducts(Request $request)
    {
        $request->validate(["session_id" => "required|exists:users,session_id"]);

        $user_id = User::query()->where("session_id", "=", $request["session_id"])->first("id");
        if ($user_id) $user_id = $user_id["id"];
        else return response("User not found!", 404);
        $products = [];
        $userInfo = Order::with(["cart", "cart.products"])->where("user_id", "=", $user_id)->get();
        for ($i = 0; $i < count($userInfo); $i++) {
            if (!isset ($userInfo[$i]["cart"])) continue;
            if (count($userInfo[$i]["cart"]["products"]) > 0) {
              for($j = 0; $j < count($userInfo[$i]["cart"]["products"]); $j++) {
                $products[] = $userInfo[$i]["cart"]["products"][$j];
              }  
            } 
        }

        return $products;
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
        return Order::where("id", $id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Order::destroy($id);
    }
}
