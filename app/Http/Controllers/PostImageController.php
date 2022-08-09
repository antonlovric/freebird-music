<?php

namespace App\Http\Controllers;

use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class PostImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        return PostImage::query()->paginate($pageSize);
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
            "is_display" => "required|integer"
        ]);

        if (!$request->file('image')->isValid()) return Response::json(["message" => "error"], 422);
        
        $path = $request->file('image')->store('images', 's3');
        $request->request->add(["filename" => $_FILES["image"]["name"], "url" => Storage::disk('s3')->url($path)]);
         return PostImage::create($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return PostImage::query()->where("filename", "=", $request["filename"])->delete();
    }

    /**
     * Assign images to a post.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignToPost(Request $request)
    {
        $ids = $request->ids;
        return ["response" => DB::table("post_images")->whereIn("id", $ids)->update(["post_id" => $request["post_id"]]), "status" => 201];
    }
}
