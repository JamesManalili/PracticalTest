<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with relevant statistics
     */
    public function index()
    {
        $user = Auth::user();

        // Gather statistics based on user role
        $stats = $this->getStats($user);

        // Get recent activity (last 5 users for admins/managers)
        $recentUsers = $user->isManager()
            ? User::latest()->take(5)->get()
            : collect();

        return view('dashboard.index', [
            'user' => $user,
            'stats' => $stats,
            'recentUsers' => $recentUsers,
        ]);
    }

    /**
     * Get dashboard statistics based on user role
     */
    protected function getStats(User $user): array
    {
        // Basic stats available to all users
        $stats = [
            'lastLogin' => $user->last_login_at?->diffForHumans() ?? 'First login',
        ];

        // Extended stats for managers and admins
        if ($user->isManager()) {
            $userStats = User::query()
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active')
                ->selectRaw('SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as new_this_month', [
                    now()->startOfMonth()
                ])
                ->first();

            $stats['totalUsers'] = $userStats->total;
            $stats['activeUsers'] = $userStats->active;
            $stats['newThisMonth'] = $userStats->new_this_month;

            // Role distribution
            $stats['roleDistribution'] = User::query()
                ->select('role', DB::raw('COUNT(*) as count'))
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray();
        }

        return $stats;
    }
}
