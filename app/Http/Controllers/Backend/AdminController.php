<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('backend.admin.index', compact('user'));
    }
}
