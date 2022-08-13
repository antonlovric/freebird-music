<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        return User::query()->paginate($pageSize);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($session_id)
    {
        return User::query()->where("session_id", "=", $session_id)->first(["first_name", "last_name", "email", "phone"]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSession(Request $request)
    {
        $request->validate([
            "session_id" => "exists:users,session_id"
        ]); 
        return User::query()->with(["orders", "orders.order_status"])->where("session_id", "=", $request["session_id"])->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $session_id)
    {
        return User::where("session_id", $session_id)->update($request->all());
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

    /**
     * Remove Multiple Users.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyUsers(Request $request)
    {
        $ids = $request->ids;
        return ["response" => User::whereIn("id",$ids)->delete(), "status" => 204];
    }
}
