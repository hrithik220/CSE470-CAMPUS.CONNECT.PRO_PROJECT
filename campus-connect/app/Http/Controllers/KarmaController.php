<?php

namespace App\Http\Controllers;

use App\Models\KarmaPoint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KarmaController extends Controller
{
    /**
     * Show karma dashboard for the authenticated user.
     */
    public function index()
    {
        $user    = Auth::user();
        $history = KarmaPoint::where('user_id', $user->id)
                             ->latest()
                             ->paginate(15);

        $summary = KarmaPoint::where('user_id', $user->id)
                             ->select('module', DB::raw('SUM(points) as total'))
                             ->groupBy('module')
                             ->pluck('total', 'module');

        // Next badge info
        $nextBadge  = null;
        $pointsLeft = null;
        foreach (KarmaPoint::BADGES as $threshold => $badge) {
            if ($user->karma_total < $threshold) {
                $nextBadge  = $badge;
                $pointsLeft = $threshold - $user->karma_total;
                break;
            }
        }

        return view('karma.index', compact(
            'user', 'history', 'summary', 'nextBadge', 'pointsLeft'
        ));
    }

    /**
     * Leaderboard — top 20 users by karma.
     */
    public function leaderboard()
    {
        $leaders = User::orderByDesc('karma_total')
                       ->select('id', 'name', 'karma_total', 'karma_badge')
                       ->limit(20)
                       ->get();

        $myRank = User::where('karma_total', '>', Auth::user()->karma_total)->count() + 1;

        return view('karma.leaderboard', compact('leaders', 'myRank'));
    }

    // ── Internal service method (called from other controllers) ───────────────

    /**
     * Award (or deduct) karma points for an action.
     *
     * Usage from any controller:
     *   KarmaController::award($userId, 'ride_completed_driver', 'rides', $rideId, Ride::class);
     */
    public static function award(
        int    $userId,
        string $action,
        string $module,
        ?int   $referenceId   = null,
        ?string $referenceType = null,
        ?string $description   = null
    ): KarmaPoint {
        $points = KarmaPoint::ACTION_MAP[$action] ?? 0;

        $entry = KarmaPoint::create([
            'user_id'        => $userId,
            'points'         => $points,
            'action'         => $action,
            'module'         => $module,
            'description'    => $description ?? ucfirst(str_replace('_', ' ', $action)),
            'reference_id'   => $referenceId,
            'reference_type' => $referenceType,
        ]);

        // Update cached total & badge
        $user              = User::find($userId);
        $user->karma_total = max(0, $user->karma_total + $points);
        $user->karma_badge = KarmaPoint::badgeForTotal($user->karma_total);
        $user->save();

        return $entry;
    }
}
