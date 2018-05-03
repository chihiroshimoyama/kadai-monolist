<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Item;

class WelcomeController extends Controller
{
    public function index()
    {
        $items = Item::orderBy('updated_at', 'desc')->paginate(20);
        return view('welcome', [
            'items' => $items,
        ]);
    }
}
