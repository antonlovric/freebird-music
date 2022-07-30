<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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
            "cart_id" => "exists:carts,id",
        ]);
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
