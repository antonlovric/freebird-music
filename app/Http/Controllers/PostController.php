<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return Post::query()->paginate($pageSize);
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
        $post = Post::where("id", $id)->update($request->all());
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
        return Post::destroy($id);
    }

    /**
     * Search for a post title.
     *
     * @param  string  $title
     * @return \Illuminate\Http\Response
     */
    public function searchTitle($heading)
    {
        return Post::where("heading", "like", "%" . $heading . "%")->get();
    }
    /**
     * Search for a post author.
     *
     * @param  string  $title
     * @return \Illuminate\Http\Response
     */
    public function searchAuthor($user)
    {
        return Post::where("user_id", "like", "%" . $user . "%")->get();
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
}
