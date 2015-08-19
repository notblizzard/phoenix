<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Post;
use App\Comment;
use App\Hub;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;

class PostController extends Controller
{

    /**
     * Stickies the specific post.
     * @param integer id
     * @return Response
     */

    public function sticky($id)
    {
        if (Auth::check()) {
            $post = Post::find($id);
            $hub = Hub::find($post->hub_id);
            $moderators = explode(',', $hub->moderators);
            if (in_array(Auth::user()->id, $moderators) || Auth::user()->is_admin) {
                $post->is_stickied = true;
                $post->push();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } else {
            return response()->json(['success' => false]);
        }
    }

    /**
     * Upvotes the specific post.
     * @param string $title
     * @param string $slug
     * @return Response
     */

    public function upvote($title, $slug)
    {
        $post = Post::where('slug','=', $slug)->firstOrFail();
        if (!Auth::check()) {
            return response()->json(['error' => 'You need to log in to do that.']);
        }
        if ($post->user_id === Auth::user()->id) {
            return response()->json(['error' => 'You can\'t vote on your own post.']);
        }
        $hub = Hub::where('name', '=', $title)->firstOrFail();
        if ($post->hub_id === $hub->id) {
            $voted = explode(',',$post->voted);
            if (!in_array(Auth::user()->id, $voted)) {
                // because no arrays.
                $post->voted .= Auth::user()->id . ',';
                $post->upvotes += 1;
                $post->push();
                return response()->json(["success" => true]);
            }
        }
    }

    /**
     * Downvotes the specific post.
     * @param string $title
     * @param string $slug
     * @return Response
     */

    public function downvote($title, $slug)
    {
        $post = Post::where('slug','=', $slug)->firstOrFail();
        if (!Auth::check()) {
            return response()->json(['error' => 'You need to log in to do that.']);
        }
        if ($post->user_id === Auth::user()->id) {
            return response()->json(['error' => 'You can\'t vote on your own post.']);
        }
        $hub = Hub::where('name', '=', $title)->firstOrFail();
        if ($post->hub_id === $hub->id) {
            $voted = explode(',',$post->voted);
            if (!in_array(Auth::user()->id, $voted)) {
                // because no arrays.
                $post->voted .= Auth::user()->id . ',';
                $post->downvotes += 1;
                $post->push();
                return response()->json(["success" => true]);
            }
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($title)
    {
        return view('post.create', ['name' => $title]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PostRequest $request, $title)
    {
        $post = new Post($request->all());
        $post->user_id = Auth::user()->id;
        $post->hub_id = Hub::where('name','=', $title)->first()->id;
        $post->slug = preg_replace('/[^A-z0-9-]+/', '-', $post->title);
        Auth::user()->posts()->save($post);
        return redirect("/hub/$title");
    }

    /**
     * Display the specified resource.
     *
     * @param  string $title, string $slug
     * @return Response
     */
    public function show($title, $slug)
    {
        $post = Post::where('slug', '=', $slug)->firstOrFail();
        $user = User::where('id', '=', $post->user_id)->first();
        $hub = Hub::where('name','=', $title)->firstOrFail();
        $ids = [];
        foreach(explode(',',$hub->moderators) as $moderator_id) {
            if ($moderator_id !== '') {
                $ids[] = $moderator_id;
            }
        }
        if (in_array($user->id, $ids)) {
            $user->moderator = true;
        } else {
            $user->moderator = false;
        }
        $comments = Comment::where('post_id', '=', $post->id)->get();
        foreach ($comments as $comment) {
            $user = User::where('id', '=', $comment->user_id)->firstOrFail();
            $comment->email = md5($user->email);
            $comment->username = $user->username;
            $comment->user_is_admin = $user->is_admin;
            if (in_array($user->id, $ids)) {
                $comment->user_is_mod = true;
            } else {
                $comment->user_is_mod = false;
            }
        }
        return view('post.show', ['post' => $post, 'user' => $user, 'title' => $title, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
        $post = Post::find($id);
        if (!Auth::check()) {
            return response()->json(['error' => 'You\'re not logged in.']);
        }
        if (Auth::user()->id === $post->user_id || Auth::user()->is_admin) {
            Post::destroy($id);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => "You don't have the authority to delete this post."]);
        }
    }
}
