<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $packages = Package::with('category')->where('is_active', true)->get();
        $categories = \App\Models\Category::all();

        return view('user.catalog.index', compact('packages', 'categories'));
    }
}
