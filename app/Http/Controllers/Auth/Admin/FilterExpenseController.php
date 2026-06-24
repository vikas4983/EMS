<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Services\ExpenseFilterService;
use Illuminate\Http\Request;

class FilterExpenseController extends Controller
{
    public function filter(Request $request, ExpenseFilterService $expenseFilterService)
    {
        $query = $expenseFilterService->filter($request->all());

        if ($request->filled('download_csv')) {
           
            return $this->export($request, $expenseFilterService);
        }

        return view('expenses.index', [
            'expenses' => $query->paginate(10)->withQueryString(),
            'categories' => Category::active()->get(),
            'employees' => User::employee()->get(),
        ]);
    }

    public function export(Request $request, ExpenseFilterService $expenseFilterService)
    {
        $filters = $request->except(['download_csv', '_token']) ?: session('auto_download', []);

        $expenses = $expenseFilterService->filter($filters)->get();

        if ($expenses->isEmpty()) {
            return back()->with('error', 'No records available for export.');
        }

        return $this->generateCsvResponse($expenses);
    }

    private function generateCsvResponse($expenses)
    {
        $fileName = 'expense-report-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(
            function () use ($expenses) {
                $file = fopen('php://output', 'w');
                fwrite($file, "\xEF\xBB\xBF");

                fputcsv($file, ['ID', 'Employee', 'Category', 'Title', 'Amount', 'Status', 'Expense Date']);

                foreach ($expenses as $expense) {
                    fputcsv($file, [$expense->id, $expense->user?->name ?? 'N/A', $expense->category?->name ?? 'N/A', $expense->title, $expense->amount, $expense->status, $expense->expense_date]);
                }

                $this->addCsvSummary($file, $expenses);

                fclose($file);
            },
            $fileName,
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }

    private function addCsvSummary($file, $expenses)
    {
        $userTotals = $expenses->groupBy('user_id')->map(function ($userExpenses) {
            return [
                'name' => $userExpenses->first()->user?->name ?? 'N/A',
                'total' => $userExpenses->sum('amount'),
                'count' => $userExpenses->count(),
            ];
        });

        fputcsv($file, []);
        fputcsv($file, ['--- EMPLOYEE WISE SUMMARY ---']);
        fputcsv($file, ['Employee', 'Total Expenses', 'Number of Records']);

        foreach ($userTotals as $data) {
            fputcsv($file, [$data['name'], number_format($data['total'], 2), $data['count']]);
        }

        fputcsv($file, []);
        fputcsv($file, ['GRAND TOTAL', number_format($expenses->sum('amount'), 2), $expenses->count()]);
    }
}
