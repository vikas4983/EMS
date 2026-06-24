<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function dashbaord()
    {
        $user = auth()->user();
        $cacheKey = 'dashboard_' . $user->id;
        $data = cache::remember($cacheKey, 300, function () use ($user) {
            return [
                'totalExpenses' => Expense::approvedThisMonth()->sum('amount'),
                'pendingExpenses' => Expense::pending()->count(),
                'topCategories' => $this->getTopCategories(),
                'expensesCount' => Expense::count(),
                'employeePedningExpenses' => Expense::pending()->where('user_id', $user->id)->count(),
                'employeeApprovedExpenses' => Expense::approved()->where('user_id', $user->id)->count(),
                'managerPedningExpenses' => Expense::pending()->count(),
                'managerApprovedExpenses' => Expense::approved()->count(),
            ];
        });

        return view('dashboard', $data);
    }

    private function getTopCategories()
    {
        return Expense::selectRaw('category_id, SUM(amount) as total')->where('status', 'approved')->with('category')->groupBy('category_id')->orderByDesc('total')->limit(5)->get();
    }
}
