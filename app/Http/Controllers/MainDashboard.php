<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\TestAttempt;
use App\Models\User;

class MainDashboard extends Controller
{
    public function create()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
    
        $user = auth()->user();
        
        if ($user->permissions === "teacher" || $user->permissions === "admin") {
            // Get all attempts for teacher's tests with student data
            $attempts = TestAttempt::whereHas('test', function($query) use ($user) {
                $query->where('created_by', $user->id);
            })->with(['test', 'user'])
              ->orderByDesc('attempt_date')
              ->get();
    
            // Get top 5 students
            $topStudents = TestAttempt::whereHas('test', function($query) use ($user) {
                    $query->where('created_by', $user->id);
                })
                ->select('user_id', DB::raw('AVG(score) as average_score'))
                ->groupBy('user_id')
                ->orderByDesc('average_score')
                ->with('user')
                ->take(5)
                ->get();
    
            // Prepare chart data
            // Fetch all user names by their user_id
            $userNames = \App\Models\User::whereIn('id', $attempts->pluck('user_id')->unique())->pluck('name', 'id')->toArray();
    
            $chartData = [
                'labels' => $attempts->groupBy('user_id')
                    ->sortKeys() // Sort by user_id to maintain order
                    ->map(function ($studentAttempts) use ($userNames) {
                        return $userNames[$studentAttempts->first()->user_id];
                    })
                    ->values() // Reset to numeric keys
                    ->toArray(),
                
                'scores' => $attempts->groupBy('user_id')
                    ->sortKeys() // Match sorting with labels
                    ->map(function ($studentAttempts) {
                        return $studentAttempts->avg('score');
                    })
                    ->values() // Reset to numeric keys
                    ->toArray()
            ];
            //dd($chartData);
            return view('dashboards.main', [
                'attempts' => $attempts,
                'topStudents' => $topStudents,
                'chartData' => $chartData
            ]);
        }
    
        // For students
        $attempts = TestAttempt::where('user_id', $user->id)
            ->with('test')
            ->orderByDesc('attempt_date')
            ->get();
    
        $progressData = [
            'labels' => $attempts->pluck('attempt_date')->map(fn($date) => $date->format('M d'))->toArray(),
            'scores' => $attempts->pluck('score')
        ];
        //dd($attempts);
        return view('dashboards.main', [
            'attempts' => $attempts,
            'progressData' => $progressData
        ]);
    }
}
