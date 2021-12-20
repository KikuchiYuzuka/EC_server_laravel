<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $links = Link::where('user_id', $user->id)->get();

        return $links->map(function (Link $links) {
            $orders = Order::where('code', $links->code)->where('complete', 1)->get();
            return [
                'code' => $links->code,
                'count' => $orders->count(),
                'revenue' => $orders->sum(fn(Order $order) => $orders->ambassador_revenue)
            ];
        });
    }

    public function rankings()
    {

        return $rankings->sortByDesc('revenue')->values();
    }
}
