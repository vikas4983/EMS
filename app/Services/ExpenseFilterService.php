<?php

namespace App\Services;

use App\Models\Expense;

class ExpenseFilterService
{
    public function filter(array $filters)
    {
        return Expense::query()

            ->when(!empty($filters['start_date']), function ($query) use ($filters) {
                $query->whereDate('created_at', '>=', $filters['start_date']);
            })

            ->when(!empty($filters['end_date']), function ($query) use ($filters) {
                $query->whereDate('created_at', '<=', $filters['end_date']);
            })

            ->when(!empty($filters['employee_id']), function ($query) use ($filters) {
                $query->where('user_id', $filters['employee_id']);
            })

            ->when(!empty($filters['category_id']), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })

            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })

            ->with(['user', 'category'])
            ->latest();
    }
}
