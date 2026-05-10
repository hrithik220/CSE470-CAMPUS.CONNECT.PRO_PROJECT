<?php

namespace App\Http\Controllers\Karma;

use App\Http\Controllers\Controller;
use App\Services\KarmaService;
use App\Services\SustainabilityService;

class KarmaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load('badges', 'karmaLogs');

        $recentLogs = $user->karmaLogs()->latest()->take(20)->get();
        $badges = $user->badges;

        return view('karma.index', compact('user', 'recentLogs', 'badges'));
    }

    public function leaderboard(KarmaService $karmaService)
    {
        $monthly = $karmaService->getMonthlyLeaderboard();
        $allTime = $karmaService->getAllTimeLeaderboard();
        $userRank = null;

        if (auth()->check()) {
            $user = auth()->user();
            $rank = \App\Models\User::active()
                ->where('karma_points', '>', $user->karma_points)
                ->count() + 1;
            $userRank = $rank;
        }

        return view('karma.leaderboard', compact('monthly', 'allTime', 'userRank'));
    }

    public function sustainability(SustainabilityService $sustainabilityService)
    {
        $data = $sustainabilityService->getDashboardSummary();
        return view('karma.sustainability', compact('data'));
    }
}
