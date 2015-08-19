<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Comment;
use App\Post;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Upvotes the specific comment.
     * @param string $title
     * @param string $slug
     * @return Response
     */
    public function upvote($title, $slug)
    {
        $post = Post::where('slug','=', $slug)->firstOrFail();
        $hub = Hub::where('name', '=', $title)->firstOrFail();
        if ($post->hub_id === $hub->id) {
            $voted = explode(',',$post->voted);
            if (!in_array(Auth::user()->id, $voted)) {
                $post->voted .= Auth::user()->id . ',';
                $post->upvotes += 1;
                $post->push();
                return response()->json(["success" => true]);
            }
        }
    }

    /**
     * Downvotes the specific comment.
     * @param string $title
     * @param string $slug
     * @return Response
     */

    public function downvote($title, $slug)
    {
        $post = Post::where('slug','=', $slug)->firstOrFail();
        $hub = Hub::where('name', '=', $title)->firstOrFail();
        if ($post->hub_id === $hub->id) {
            $voted = explode(',',$post->voted);
            if (!in_array(Auth::user()->id, $voted)) {
                $post->voted .= Auth::user()->id . ',';
                $post->downvotes += 1;
                $post->push();
                return response()->json(["success" => true]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CommentRequest $request, $title, $slug)
    {
        $comment = new Comment($request->all());
        $comment->user_id = Auth::user()->id;
        $comment->post_id = Post::where('slug', '=', $slug)->first()->id;
        Auth::user()->posts()->save($comment);
        return redirect("/hub/$title/$slug");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
