<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        $requestHeading = $request->query("heading");

        return Post::with("images")->when($requestHeading, function($query, $heading) {
            $query->where("heading", "LIKE", "%" . $heading . "%");
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
        $request->validate([
            "heading" => "required|string",
            "body" => "required|string",
            "session_id" => "required|exists:users,session_id"
        ]);

        $user_id = User::query()->where("session_id", "=", $request["session_id"])->first("id")["id"];
        $request->request->remove("session_id");
        $request->request->add(["user_id" => $user_id]);
        
        return Post::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::with("images")->find($id);
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
        return Post::where("id", $id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Post::destroy($id);
    }

    /**
     * Get last 3 posts
     *
     * @param  string  $title
     * @return \Illuminate\Http\Response
     */
    public function recentPosts()
    {
        return Post::with("images")->orderBy("id", "desc")->take(3)->get();
    }

    /**
     * Remove Multiple Posts.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyPosts(Request $request)
    {
        $ids = $request->ids;
        return ["response" => Post::whereIn("id",$ids)->delete(), "status" => 200];
    }
}
