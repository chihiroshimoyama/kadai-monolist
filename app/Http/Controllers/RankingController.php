<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;

class RankingController extends Controller
{
    public function want()
    {
        $items = \DB::table('item_user')->join('items', 'item_user.item_id', '=', 'items.id')->select('items.*', \DB::raw('COUNT(*) as want_count'))->whereRaw("type = 'want'")->groupBy('items.id', 'items.code', 'items.name', 'items.url', 'items.image_url','items.created_at', 'items.updated_at')->orderBy('want_count', 'DESC')->take(10)->get();

        return view('ranking.want', [
            'items' => $items,
        ]);
    }
    
    public function have()
    {
        $items = \DB::table('item_user')->join('items', 'item_user.item_id', '=', 'items.id')->select('items.*', \DB::raw('COUNT(*) as have_count'))->whereRaw("type = 'have'")->groupBy('items.id', 'items.code', 'items.name', 'items.url', 'items.image_url','items.created_at', 'items.updated_at')->orderBy('have_count', 'DESC')->take(10)->get();

        return view('ranking.have', [
            'items' => $items,
        ]);
    }
}
