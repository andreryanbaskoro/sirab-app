<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __invoke()
    {
        if (auth()->check()) {
            return match(auth()->user()->role) {
                'admin_pu' => redirect()->route('admin.dashboard'),
                'kepala_tukang' => redirect()->route('tukang.dashboard'),
                'konsumen' => redirect()->route('konsumen.dashboard'),
                default => redirect()->route('login'),
            };
        }
        return redirect()->route('login');
    }
}
