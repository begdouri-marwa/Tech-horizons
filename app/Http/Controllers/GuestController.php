<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\Article;

class GuestController extends Controller
{
    public function index()
	{
	    $issues = Issue::where('status', 'published')
		    ->whereHas('articles', function ($query) {
		        $query->where('status', 'accepted')
		              ->where('target', 'public');
		    })
		    ->with(['articles' => function ($query) {
		        $query->where('status', 'accepted')
		              ->where('target', 'public');
		    }])
		    ->latest('id') // Latest issues first
		    ->get();

	    return view('guest.index', compact('issues')); // Homepage
	}
}
