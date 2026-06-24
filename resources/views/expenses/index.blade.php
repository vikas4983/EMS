@extends('layouts.app')
@section('title', 'Expenses')
@section('content')

    <div class="dashboard-main-body">
        @can('expense.create')
            <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <a href="{{ route('expenses.create') }}" class="btn btn-primary-600 d-flex align-items-center gap-6 ">
                    <span class="d-flex text-md">
                        <i class="ri-add-large-line"></i>
                    </span>
                    Add Expense
                </a>
            </div>
        @endcan
        @include('alerts.alert')
        <div class="mt-24">
            <div class="card h-100">
                <div class="card-body p-0 dataTable-wrapper">
                    @role('admin')
                        <div
                            class="d-flex align-items-center justify-content-between flex-wrap gap-16 px-20 py-12 border-bottom border-neutral-200">
                            <div class="d-flex flex-wrap align-items-center gap-16">

                                <div class="dropdown">
                                    <button type="button"
                                        class="px-12 py-5-px border border-neutral-300 radius-8 d-flex align-items-center gap-20"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="d-flex align-items-center gap-1 text-secondary-light text-sm">
                                            Filter
                                        </span>
                                        <span class="">
                                            <i class="ri-arrow-down-s-line"></i>
                                        </span>
                                    </button>
                                    <div class="dropdown-menu border bg-base shadow dropdown-menu-lg p-0">
                                        <div class="d-flex align-items-center justify-content-between border-bottom py-8 px-16">
                                            <span class="fw-semibold text-lg text-primary-light">Filter</span>
                                            <button type="button">
                                                <i class="ri-close-large-line"></i>
                                            </button>
                                        </div>

                                        <form action="{{ route('expense.filter') }}" method="get"
                                            class="p-16 d-grid grid-cols-2 gap-16">

                                            <div class="">
                                                <label for="date"
                                                    class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                                    Start Date
                                                </label>
                                                <input type="date" name="start_date" class="form-control">
                                            </div>

                                            <div class="">
                                                <label for="date"
                                                    class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                                    End Date
                                                </label>
                                                <input type="date" name="end_date" class="form-control">
                                            </div>

                                            <div class="">
                                                <label for="status"
                                                    class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                                    Approval Status
                                                </label>
                                                <select id="status" name="status" class="form-control form-select">
                                                    <option value="">All Status</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                            </div>

                                            <div class="">
                                                <label for="employee_id"
                                                    class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                                    Employee
                                                </label>
                                                <select id="employee_id" name="employee_id" class="form-control form-select">
                                                    <option value="">All Employees</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="">
                                                <label for="category_id"
                                                    class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                                    Category
                                                </label>
                                                <select id="category_id" name="category_id" class="form-control form-select">
                                                    <option value="">All Categories</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- CSV Download Checkbox --}}
                                            <div class="col-span-2">
                                                <div class="form-check mt-8">
                                                    <input class="form-check-input" type="checkbox" name="download_csv"
                                                        value="1" id="download_csv"> <label class="form-check-label"
                                                        for="download_csv">
                                                        Download report
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="">
                                                <button type="reset" class="btn btn-danger-200 text-danger-600 w-100">
                                                    Reset
                                                </button>
                                            </div>

                                            <div class="">
                                                <button type="submit" class="btn btn-primary-600 w-100">
                                                    Apply
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endrole

                    <span class="successMessage  alert alert-success" style="width: 100%; display:none;">
                    </span>
                    <span class="errorMessage  alert alert-danger" style="width: 100%; display:none;">
                    </span>
                    <div class="p-0 result table-responsive">
                        <table class="table bordered-table mb-0 data-table dataTable" id="dataTable" data-page-length='10'>
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <div class="form-check style-check d-flex align-items-center">
                                            <label class="form-check-label">
                                                S.L
                                            </label>
                                        </div>
                                    </th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Expense Date</th>
                                    <th scope="col">Category </th>
                                    <th scope="col">Description </th>
                                    <th scope="col">Status </th>
                                    <th scope="col">Receipts </th>
                                    <th scope="col">Manager Comment</th>
                                    <th scope="col">Action</th>
                                </tr>
                                <tr class="error-row" style="display:none;">
                                    <th colspan="4" class="text-danger text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="noResult"></tbody>

                            @forelse ($expenses as $count => $expense)
                                <tr>
                                    <td>
                                        <div class="form-check style-check d-flex align-items-center">

                                            <label class="form-check-label">
                                                {{ $count + 1 }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('expenses.show', $expense->id) }}" class="text-success">
                                            {{ $expense->title }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $expense->amount }}
                                    </td>
                                    <td>
                                        {{ $expense->expense_date->format('d-M-y') }}
                                    </td>
                                    <td>
                                        {{ $expense->category->name }}
                                    </td>
                                    <td>
                                        {{ $expense->description }}
                                    </td>
                                    <td>
                                        <span
                                            class="{{ $expense->status_badge_class }} px-24 py-4 radius-4 fw-medium text-sm">
                                            {{ ucfirst($expense->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        @forelse($expense->receipts as $receipt)
                                            <a href="{{ route('receipts.download', $receipt->id) }}"
                                                title="Download Receipt" target="_blank">
                                                <i class="ri-file-pdf-line"></i>
                                            </a>

                                        @empty
                                            <span class="text-muted">
                                                No Receipts
                                            </span>
                                        @endforelse
                                    </td>
                                    <td>
                                        {{ $expense->manager_comment ?? '' }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="text-primary-light text-xl"
                                                data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                                                <li>
                                                    @if ($expense->status === 'pending')
                                                <li>
                                                   @can('expense.edit')
                                                    <x-buttons.action-group-component type="edit" :url="route('expenses.edit', $expense->id)" />
                                                   @endcan
                                                </li>
                                                @role('manager')
                                                <li>
                                                    <x-buttons.action-group-component type="approve" :id="$expense->id" />
                                                </li>
                                                <li>
                                                    <x-buttons.action-group-component type="reject" :id="$expense->id" />
                                                </li>
                                                @endrole
                            @endif

                            </li>
                            <li>
                                @role('admin')
                                 @if ($expense->trashed())
                                    <x-buttons.action-group-component type="restore" :url="route('expenses.restore', $expense->id)" />
                                    <x-buttons.action-group-component type="permanent-delete" :url="route('expenses.forceDelete', $expense->id)" />
                                @else
                                    <x-buttons.action-group-component type="delete" :url="route('expenses.destroy', $expense->id)" />
                                @endif
                                @endrole
                               

                            </li>
                            </ul>
                    </div>
                    </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <h6>No record found!</h6>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                    </table>
                  <x-buttons.expense-status-modal-component />
                   <div class="row text-right">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
   {{-- @push('scripts')
@if(session('auto_download') || isset($auto_download) && $auto_download)
<script>
    document.addEventListener('DOMContentLoaded', function() {
       
        window.location.href = '{{ route("expense.export") }}';
    });
</script>
@endif
@endpush --}}
@endsection
