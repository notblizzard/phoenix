<?php

namespace App\Http\Controllers;

use Auth;
use App\Hub;
use App\Post;
use App\User;
use App\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\HubRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HubController extends Controller
{
    /**
     * Subscribes to the specific hub.
     * @param string $title
     * @return Response
     */
    public function subscribe($title)
    {
        $hub = Hub::where('name', '=', $title)->firstOrFail();
        Auth::user()->subscriptions .= "$hub->name,";
        Auth::user()->push();
        return redirect("/hub/$hub->name");
    }

    /**
     * Unsubscribes to the specific hub.
     * @param string $title
     * @return Response
     */
    public function unsubscribe($title)
    {
        $hub = Hub::where('name','=', $title)->firstOrFail();
        Auth::user()->subscriptions = str_replace("$title,", "", Auth::user()->subscriptions);
        Auth::user()->push();
        return redirect("/hub/$hub->name");
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $hubs = Hub::all();
        return view('hub.index', ['hubs' => $hubs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('hub.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(HubRequest $request)
    {
        $hub = new Hub($request->all());
        $hub->moderators .= Auth::user()->id . ",";
        Auth::user()->hubs()->save($hub);
        return redirect("/hub/$request->name");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($title)
    {
        $hub = Hub::where('name', 'LIKE', $title)->firstOrFail();
        $posts = Post::where('hub_id', '=', $hub->id)->get()->sortByDesc('upvotes');
        $mods;
        $ids = [];
        foreach(explode(',',$hub->moderators) as $moderator_id) {
            if ($moderator_id !== '') {
            array_push($ids, $moderator_id);

            }
        }
        $stickied_posts = [];
        $moderators = User::whereIn('id', $ids)->get();
        foreach ($posts as $post) {
            $post->username = User::where('id', '=', $post->user_id)->firstOrFail()->username;
            $post->comment_count = Comment::where('post_id', '=', $post->id)->count();
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
        $posts = $posts->sortByDesc('is_stickied');
        $subscription_count = User::where('subscriptions', 'LIKE', "%$hub->name%")->count();
        if (Auth::check()) {
            $subscriptions = explode(',',Auth::user()->subscriptions);
            if (in_array($hub->name, $subscriptions)) {
                return view('hub.show', ['subscriptions' => $subscription_count, 'moderators' => $moderators, 'hub' => $hub, 'posts' => $posts, 'name' => $title, 'subscribed' => true, 'subscriptions' => $subscription_count]);
            } else {
                return view('hub.show', ['subscriptions' => $subscription_count, 'moderators' => $moderators, 'hub' => $hub, 'posts' => $posts, 'name' => $title, 'subscribed' => false, 'subscriptions' => $subscription_count]);

            }
        } else {
            return view('hub.show', ['subscriptions' => $subscription_count, 'moderators' => $moderators, 'hub' => $hub, 'posts' => $posts, 'name' => $title, 'subscribed' => false]);
        }
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
        //
    }
}
