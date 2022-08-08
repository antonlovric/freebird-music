<?php

namespace App\Http\Controllers;

use App\Models\OpenRequest;
use Illuminate\Http\Request;

class OpenRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        return OpenRequest::query()->paginate($pageSize);
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
            "request" => "required|string",
            "name" => "required|string",
            "email" => "required|string",
        ]);
        return OpenRequest::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return OpenRequest::with("user")->whereIn("id", [$id])->get();
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
        $user_id = $request->user()->id;
        $openRequest = ["request" => $request["request"], "user_id" => $user_id];
        $post = OpenRequest::where("id", $id)->update($openRequest);
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return OpenRequest::destroy($id);
    }

    /**
     * Show all user requests.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userRequests($id)
    {
        return OpenRequest::with("user")->whereIn("user_id", [$id])->get();
    }
}
