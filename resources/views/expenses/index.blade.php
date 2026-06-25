@extends('layouts.app')
@section('title', 'Expenses')
@section('content')

    <div class="dashboard-main-body">
        <div id="alertContainer"></div>
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

        @if (isset($hasData))
            @if ($hasData)
                <div class="alert alert-success alert-dismissible fade show m-16" role="alert">
                    <i class="ri-check-line me-2"></i>
                    <strong>{{ $totalRecords }}</strong> expense(s) found.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @else
                <div class="alert alert-info alert-dismissible fade show m-16" role="alert">
                    <i class="ri-information-line me-2"></i>
                    No expenses found. Try adjusting your filters.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endif

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

                    <span class="successMessage alert alert-success" style="width: 100%; display:none;">
                    </span>
                    <span class="errorMessage alert alert-danger" style="width: 100%; display:none;">
                    </span>
                    <div class="p-0 result table-responsive">
                        <table class="table bordered-table mb-0 data-table dataTable" id="dataTable"
                            data-page-length='10'>
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
                            <tbody class="noResult">

                                @forelse ($expenses as $count => $expense)
                                    <tr data-expense-id="{{ $expense->id }}">
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
                                        <td class="expense-amount">
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
                                                class="status-badge {{ $expense->status_badge_class }} px-24 py-4 radius-4 fw-medium text-sm">
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
                                        <td class="manager-comment">
                                            {{ $expense->manager_comment ?? '' }}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="text-primary-light text-xl"
                                                    data-bs-toggle="dropdown" data-bs-display="static"
                                                    aria-expanded="false">
                                                    <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                                                    <li>
                                                        @if ($expense->status === 'pending')
                                                    <li>
                                                        @can('expense.edit')
                                                            <x-buttons.action-group-component type="edit"
                                                                :url="route('expenses.edit', $expense->id)" />
                                                        @endcan
                                                    </li>
                                                    @role('manager')
                                                        <li>
                                                            <x-buttons.action-group-component type="approve"
                                                                :id="$expense->id" />
                                                        </li>
                                                        <li>
                                                            <x-buttons.action-group-component type="reject"
                                                                :id="$expense->id" />
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
                        <td colspan="11" class="text-center">No Data Found!</td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('statusForm');
            if (!form) return;

            const submitBtn = document.getElementById('submitBtn');
            const modal = document.getElementById('statusModal');
            const modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);


            modal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('modalDescription').value = '';
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit';
            });

            document.querySelectorAll('.statusBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const status = this.dataset.status;
                    const url = this.dataset.url;

                    document.getElementById('expenseId').value = id;
                    document.getElementById('statusInput').value = status;
                    form.action = url;

                    if (status === 'approved') {
                        document.getElementById('modalTitle').innerText = 'Approve Expense';
                        document.getElementById('modalMessage').innerText =
                            'Are you sure you want to approve this expense?';
                        submitBtn.className = 'btn btn-success';
                        submitBtn.innerHTML = 'Approve';
                    } else {
                        document.getElementById('modalTitle').innerText = 'Reject Expense';
                        document.getElementById('modalMessage').innerText =
                            'Are you sure you want to reject this expense?';
                        submitBtn.className = 'btn btn-danger';
                        submitBtn.innerHTML = 'Reject';
                    }
                });
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

                try {
                    const formData = new FormData(this);
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: formData
                    });

                    const data = await response.json();


                    modalInstance.hide();

                    if (data.success) {
                        showAlert('success', data.message || 'Expense status updated successfully!');
                        updateRowStatus(data.data);
                        updateNotificationCount();
                    } else {
                        showAlert('danger', data.message || 'Something went wrong!');
                    }

                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Submit';

                } catch (error) {
                    console.error('Error:', error);
                    modalInstance.hide();
                    showAlert('danger', 'Network error! Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Submit';
                }
            });
        });


        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            if (!alertContainer) return;


            alertContainer.innerHTML = '';

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show m-16`;
            alertDiv.role = 'alert';
            alertDiv.style.animation = 'slideDown 0.5s ease';
            alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="ri-${type === 'success' ? 'check-line' : 'error-warning-line'} me-2 fs-5"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

            alertContainer.appendChild(alertDiv);


            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.style.animation = 'slideUp 0.5s ease';
                    setTimeout(() => {
                        alertDiv.remove();
                    }, 500);
                }
            }, 5000);
        }


        function updateRowStatus(expense) {
            const rows = document.querySelectorAll('tbody tr');
            let found = false;

            rows.forEach(row => {
                const link = row.querySelector('a.text-success');
                if (link && link.textContent.trim() === expense.title) {
                    found = true;


                    const statusBadge = row.querySelector('.status-badge');
                    if (statusBadge) {
                        let badgeClass = 'bg-warning-100 text-warning-600';
                        let statusText = 'Pending';

                        if (expense.status === 'approved') {
                            badgeClass = 'bg-success-100 text-success-600';
                            statusText = 'Approved';
                        } else if (expense.status === 'rejected') {
                            badgeClass = 'bg-danger-100 text-danger-600';
                            statusText = 'Rejected';
                        }

                        statusBadge.className = `status-badge ${badgeClass} px-24 py-4 radius-4 fw-medium text-sm`;
                        statusBadge.textContent = statusText;
                    }


                    const commentCell = row.querySelector('.manager-comment');
                    if (commentCell) {
                        commentCell.textContent = expense.manager_comment || '';
                    }


                    const actionDropdown = row.querySelector('.btn-group');
                    if (actionDropdown && expense.status !== 'pending') {
                        const buttons = actionDropdown.querySelectorAll('[data-status]');
                        buttons.forEach(btn => {
                            const li = btn.closest('li');
                            if (li) li.style.display = 'none';
                        });
                    }
                }
            });

            if (!found) {
                location.reload();
            }
        }


        function updateNotificationCount() {
            fetch('/notifications/latest', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (window.notificationManager) {
                        window.notificationManager.updateUnreadCountUI(data.unread_count || 0);
                    }
                })
                .catch(() => {});
        }


        const style = document.createElement('style');
        style.textContent = `
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
    `;
        document.head.appendChild(style);
    </script>

@endsection
