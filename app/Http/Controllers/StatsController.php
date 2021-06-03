<?php

namespace App\Http\Controllers;

use App\Http\Resources\LinkResource;
use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $links = Link::with('orders')->whereUserId($user->id)->get();

        return LinkResource::collection($links);
    }

    public function rankings(Request $request)
    {
        $ambos = User::ambassadors()->get();

        // $rankings = $ambos->map(fn (User $user) => [
        //     'name' => $user->name,
        //     'revenue' => $user->revenue
        // ]);

        // return $rankings->sortByDesc('revenue')->values();

        return Redis::zrevrange('rankings', 0, -1 , 'WITHSCORES');
    }
}
