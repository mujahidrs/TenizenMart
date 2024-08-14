<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class BiodataController extends Controller
{
    public function index()
    {
        $profile = Profile::first();

        return view('biodata', compact('profile'));
    }
}
