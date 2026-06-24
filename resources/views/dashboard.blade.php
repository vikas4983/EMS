@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="dashboard-main-body">

        @role('admin')
            <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <div class="">
                    <h6 class="fw-semibold mb-0">Dashboard</h6>
                    <p class="text-neutral-600 mt-4 mb-0">Track expenses, monitor approvals, and analyze spending patterns.
                        net worth.</p>
                </div>
            </div>
            <div class="mt-24">
                <div class="row gy-4">
                    <div class="col-xxl-8">
                        <div class="row gy-4">
                            <div class="col-xxl-4 col-sm-6">
                                <div class="card shadow-1 radius-8 gradient-bg-end-1 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                            <div
                                                class="w-44-px h-44-px bg-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                                                <img src="../assets/images/dashboard-icon5.png" alt="Icon">
                                            </div>
                                            <p class="fw-medium text-primary-light mb-1">Total Expenses This Month</p>
                                        </div>
                                        <h6 class="mb-0">₹{{ intval($totalExpenses) }}</h6>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6">
                                <div class="card shadow-1 radius-8 gradient-bg-end-2 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                            <div
                                                class="w-44-px h-44-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                                                <img src="../assets/images/parent-widget-icon1.png" alt="Icon">
                                            </div>
                                            <p class="fw-medium text-primary-light mb-1">Expenses Pending Approval</p>
                                        </div>
                                        <h6 class="mb-0">{{ intval($pendingExpenses) }}</h6>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6">
                                <div class="card shadow-1 radius-8 gradient-bg-end-3 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                            <div
                                                class="w-44-px h-44-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                                                <img src="../assets/images/dashboard-icon5.png" alt="Icon">
                                            </div>
                                            <p class="fw-medium text-primary-light mb-1">Total Categories</p>
                                        </div>
                                        @foreach ($topCategories as $category)
                                            <h6 class="mb-0">
                                                {{ $category->category->name ?? 'Uncategorized' }}:
                                                ₹{{ intval($category->total ?? 0) }}
                                            </h6>
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endrole
        @role('employee')
            <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <div class="">
                    <h6 class="fw-semibold mb-0">Dashboard</h6>
                    <p class="text-neutral-600 mt-4 mb-0">Track expenses, monitor approvals, and analyze spending patterns.
                        net worth.</p>
                </div>
            </div>
            <div class="mt-24">
                <div class="row gy-4">
                    <div class="col-xxl-8">
                        <div class="row gy-4">
                            <div class="col-xxl-4 col-sm-6">
                                <div class="card shadow-1 radius-8 gradient-bg-end-1 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                            <div
                                                class="w-44-px h-44-px bg-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                                                <img src="../assets/images/dashboard-icon5.png" alt="Icon">
                                            </div>
                                            <p class="fw-medium text-primary-light mb-1">Total Expenses </p>
                                        </div>
                                        <h6 class="mb-0">{{ $expensesCount ?? '' }}</h6>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6">
                                <div class="card shadow-1 radius-8 gradient-bg-end-2 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                            <div
                                                class="w-44-px h-44-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                                                <img src="../assets/images/parent-widget-icon1.png" alt="Icon">
                                            </div>
                                            <p class="fw-medium text-primary-light mb-1">Expenses Pending Approval</p>
                                        </div>
                                        <h6 class="mb-0">{{ $employeePedningExpenses ?? '' }}</h6>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6">
                                <div class="card shadow-1 radius-8 gradient-bg-end-2 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                            <div
                                                class="w-44-px h-44-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                                                <img src="../assets/images/parent-widget-icon1.png" alt="Icon">
                                            </div>
                                            <p class="fw-medium text-primary-light mb-1">Expenses Approved </p>
                                        </div>
                                        <h6 class="mb-0">{{ $employeeApprovedExpenses ?? '' }}</h6>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        @endrole
        @role('manager')
            <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <div class="">
                    <h6 class="fw-semibold mb-0">Dashboard</h6>
                    <p class="text-neutral-600 mt-4 mb-0">Track expenses, monitor approvals, and analyze spending patterns.
                        net worth.</p>
                </div>
            </div>
            <div class="mt-24">
                <div class="row gy-4">
                    <div class="col-xxl-8">
                        <div class="row gy-4">
                            <div class="col-xxl-4 col-sm-6">
                                <div class="card shadow-1 radius-8 gradient-bg-end-1 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                            <div
                                                class="w-44-px h-44-px bg-warning-600 rounded-circle d-flex justify-content-center align-items-center">
                                                <img src="../assets/images/dashboard-icon5.png" alt="Icon">
                                            </div>
                                            <p class="fw-medium text-primary-light mb-1">Total Expenses </p>
                                        </div>
                                        <h6 class="mb-0">{{ $expensesCount ?? '' }}</h6>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6">
                                <div class="card shadow-1 radius-8 gradient-bg-end-2 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                            <div
                                                class="w-44-px h-44-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                                                <img src="../assets/images/parent-widget-icon1.png" alt="Icon">
                                            </div>
                                            <p class="fw-medium text-primary-light mb-1">Expenses Pending Approval</p>
                                        </div>
                                        <h6 class="mb-0">{{ $managerPedningExpenses ?? '' }}</h6>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6">
                                <div class="card shadow-1 radius-8 gradient-bg-end-2 h-100">
                                    <div class="card-body p-20">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                            <div
                                                class="w-44-px h-44-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                                                <img src="../assets/images/parent-widget-icon1.png" alt="Icon">
                                            </div>
                                            <p class="fw-medium text-primary-light mb-1">Expenses Approved </p>
                                        </div>
                                        <h6 class="mb-0">{{ $managerApprovedExpenses ?? '' }}</h6>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        @endrole
    </div>
@endsection
