<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Post;
use App\Hub;
use App\User;
use App\Comment;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Shows the home page for the authenticated user.
     * @return Response
     */

    public function home()
    {
        $subscriptions = explode(',',Auth::user()->subscriptions);
        $hubs = Hub::whereIn('name', $subscriptions)->get();
        $posts = [];
        $ids = [];
        foreach ($hubs as $hub) {
            $ids[] = $hub->id;
        }


        $posts = Post::whereIn('hub_id', $ids)->get()->sortByDesc('upvotes');
        foreach ($posts as $post) {
            $hub = Hub::find($post->hub_id);
            if (in_array($post->user_id, explode(',', $hub->moderators))) {
                $post->user_is_mod = true;
            } else {
                $post->user_is_mod = false;
            }
            $user = User::find($post->user_id);
            if ($user->is_admin) {
                $post->user_is_admin = true;
            } else {
                $post->user_is_admin = false;
            }
            $post->username = User::where('id', '=', $post->user_id)->firstOrFail()->username;
            $post->comment_count = Comment::where('post_id', '=', $post->id)->count();
            $post->hub_name = Hub::where('id', '=', $post->hub_id)->firstOrFail()->name;
        }
        return view('user.home', ['posts' => $posts]);
    }

    /**
     * Shows the specific user.
     * @param string $username
     * @return Response
     */

    public function show($username)
    {
        $post_upvotes = 0;
        $user = User::where('username','=', $username)->firstOrFail();
        $user_posts = Post::where('user_id', '=', $user->id)->take(3)->get();
        foreach ($user_posts as $post) {
            $post->username = $user->username;
            $post->comment_count = Comment::where('post_id', '=', $post->id)->count();
            $post->hub_name = Hub::where('id', '=', $post->hub_id)->firstOrFail()->name;
            $post_upvotes = $post_upvotes + (int) $post->upvotes;

            $hub = Hub::find($post->hub_id);
            if (in_array($post->user_id, explode(',', $hub->moderators))) {
                $post->user_is_mod = true;
            } else {
                $post->user_is_mod = false;
            }
            $user = User::find($post->user_id);
            if ($user->is_admin) {
                $post->user_is_admin = true;
            } else {
                $post->user_is_admin = false;
            }
        }
        $comment_upvotes = 0;
        $user_comments = Comment::where('user_id', '=', $user->id)->take(3)->get();
        foreach ($user_comments as $comment) {
            $comment_upvotes = $comment_upvotes + (int) $comment->upvotes;
            $comment->email = md5($user->email);
        }


        return view('user.show', ['user' => $user,
            'email' => md5($user->email),
            'post_upvotes' => $post_upvotes,
            'comment_upvotes' => $comment_upvotes,
            'comments' => $user_comments,
            'posts' => $user_posts]);
    }

}
