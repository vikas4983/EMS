<?php

namespace App\Http\Controllers\Auth\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\ExpenseRequest;
use App\Models\Expense;
use App\Models\Category;
use App\Models\User;
use App\Models\ExpenseReceipt;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendNotificationJob;

class ExpenseController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        auth()->user()->can('expense.view') ?: abort(403);
        $expenses = auth()
            ->user()
            ->expenses()
            ->with(['category', 'receipts'])
            ->withTrashed()
            ->latest()
            ->paginate(10);
        $categories = Category::active()->get();
        $employees = User::employee()->get();
        return view('expenses.index', compact('expenses', 'categories', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        auth()->user()->can('expense.create') ?: abort(403);
        $categories = Category::active()->select('name', 'id')->get();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Store a newly created expense
     */
    public function store(ExpenseRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['status'] = 'pending';

        DB::beginTransaction();
        try {
            $expense = Expense::create($validatedData);

            if ($request->hasFile('receipts')) {
                foreach ($request->file('receipts') as $file) {
                    $path = $file->store('expenses/receipts', 'public');
                    $expense->receipts()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                    ]);
                }
            }

            $employee = auth()->user();
            $managerIds = User::role('manager')->pluck('id');

            foreach ($managerIds as $managerId) {
                SendNotificationJob::dispatch($managerId, "New expense '{$expense->title}' (₹{$expense->amount}) submitted by {$employee->name}. Please review and take action.", 'info', $expense->id);
            }

            SendNotificationJob::dispatch($employee->id, "Your expense '{$expense->title}' (₹{$expense->amount}) has been submitted successfully. Waiting for manager approval.", 'info', $expense->id);

            DB::commit();
            return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $categories = Category::active()->select('name', 'id')->get();
        return view('expenses.show', compact('expense', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        auth()->user()->can('expense.edit') ?: abort(403);
        $categories = Category::active()->select('name', 'id')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        auth()->user()->can('expense.edit') ?: abort(403);
        if ($expense->status !== 'pending') {
            return back()->with('error', 'Only pending expenses can be updated.');
        }
        $validatedData = $request->validated();
        unset($validatedData['receipts']);
        $expense->update($validatedData);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        auth()->user()->can('expense.delete') ?: abort(403);
        $expense->delete();
        return back()->with('error', 'Expense deleted successfully.');
    }
    public function restore($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->restore();
        return back()->with('success', 'Expense restored successfully.');
    }
    public function forceDelete($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->forceDelete();
        return back()->with('error', 'Expense deleted permanently.');
    }
    public function trashed()
    {
        $expenses = Expense::onlyTrashed()->latest()->paginate(10);
        $categories = Category::active()->get();
        $employees = User::employee()->get();
        return view('expenses.index', compact('expenses', 'categories', 'employees'));
    }
    public function download($receipt)
    {
        $receipt = ExpenseReceipt::findOrFail($receipt);
        return Storage::disk('public')->download($receipt->file_path, $receipt->file_name);
    }

    public function getExpense()
    {
        if (
            !auth()
                ->user()
                ->hasAnyRole(['admin', 'manager'])
        ) {
            abort(403, 'Unauthorized access.');
        }
        $expenses = Expense::latest()->paginate(10);
        $categories = Category::active()->get();
        $employees = User::employee()->get();
        return view('expenses.index', compact('expenses', 'categories', 'employees'));
    }

    public function approve(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:expenses,id',
            'description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $expense = Expense::findOrFail($request->id);

            if ($expense->status !== 'pending') {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This expense has already been ' . $expense->status,
                    ],
                    400,
                );
            }

            $expense->update([
                'status' => 'approved',
                'manager_comment' => $request->description,
            ]);

            $employee = User::find($expense->user_id);
            $manager = auth()->user();

            if ($employee) {
                $message = "Your expense '{$expense->title}' (₹{$expense->amount}) has been APPROVED by {$manager->name}.";
                if ($request->description) {
                    $message .= " Manager Comment: {$request->description}";
                }

                SendNotificationJob::dispatch($employee->id, $message, 'success', $expense->id);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Expense approved successfully.',
                'data' => $expense->fresh(['user', 'category']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to approve expense: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function reject(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:expenses,id',
            'description' => 'required|string|min:5|max:500',
        ]);

        DB::beginTransaction();
        try {
            $expense = Expense::findOrFail($request->id);

            if ($expense->status !== 'pending') {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This expense has already been ' . $expense->status,
                    ],
                    400,
                );
            }

            $expense->update([
                'status' => 'rejected',
                'manager_comment' => $request->description,
            ]);

            $employee = User::find($expense->user_id);
            $manager = auth()->user();

            if ($employee) {
                $message = "Your expense '{$expense->title}' (₹{$expense->amount}) has been REJECTED by {$manager->name}.";
                $message .= " Reason: {$request->description}";

                SendNotificationJob::dispatch($employee->id, $message, 'error', $expense->id);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Expense rejected successfully.',
                'data' => $expense->fresh(['user', 'category']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to reject expense: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
