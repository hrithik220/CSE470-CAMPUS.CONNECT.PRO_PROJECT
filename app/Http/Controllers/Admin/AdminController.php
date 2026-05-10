<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Only admins can access this page.');
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();

        $totalUsers = User::count();
        $totalItems = Item::count();
        $flaggedItems = Item::where('is_flagged', true)->count();
        $recentFlaggedItems = Item::where('is_flagged', true)
            ->with('seller')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalItems',
            'flaggedItems',
            'recentFlaggedItems'
        ));
    }

    public function items()
    {
        $this->checkAdmin();

        $items = Item::with('seller')
            ->latest()
            ->paginate(20);

        return view('admin.items', compact('items'));
    }
}