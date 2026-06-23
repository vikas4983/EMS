<?php

namespace App\Http\Controllers\Auth\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\ExpenseRequest;
use App\Models\Category;
use App\Models\Expense;
use App\Models\ExpenseReceipt;
use App\Models\User;
use App\Services\ExpenseFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function create(Expense $expense)
    {
        $categories = Category::active()->select('name', 'id')->get();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->user()->id;
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
        $categories = Category::active()->select('name', 'id')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
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
        return view('expenses.index', compact('expenses'));
    }
    public function download($receipt)
    {
        $receipt = ExpenseReceipt::findOrFail($receipt);
        return Storage::disk('public')->download($receipt->file_path, $receipt->file_name);
    }

    public function approve(Request $request)
    {
        $expense = Expense::findOrFail($request->id);

        $expense->update([
            'status' => 'approved',
            'manager_comment' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Expense approved successfully.',
        ]);
    }

    public function reject(Request $request)
    {
        $expense = Expense::findOrFail($request->id);

        $expense->update([
            'status' => 'rejected',
            'manager_comment' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Expense rejected successfully.',
        ]);
    }
}
