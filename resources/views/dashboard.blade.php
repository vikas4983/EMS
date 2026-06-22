@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="dashboard-main-body">
        <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div class="">
                <h6 class="fw-semibold mb-0">Dashboard</h6>
                <p class="text-neutral-600 mt-4 mb-0">School -> Manage your school, track attendance, expense, and
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
                                            <img src="../assets/images/dashboard-icon1.png" alt="Icon">
                                        </div>
                                        <p class="fw-medium text-primary-light mb-1">Total Employees</p>
                                    </div>
                                    <h6 class="mb-0">20,000</h6>
                                    <p
                                        class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                                        <span
                                            class="d-inline-flex align-items-center gap-1 text-primary-600 text-sm fw-semibold">
                                            10%
                                            <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                                        </span>
                                        +5 This Month
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-sm-6">
                            <div class="card shadow-1 radius-8 gradient-bg-end-2 h-100">
                                <div class="card-body p-20">
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                        <div
                                            class="w-44-px h-44-px bg-blue-600 rounded-circle d-flex justify-content-center align-items-center">
                                            <img src="../assets/images/dashboard-icon2.png" alt="Icon">
                                        </div>
                                        <p class="fw-medium text-primary-light mb-1">Total Expenses</p>
                                    </div>
                                    <h6 class="mb-0">20,000</h6>
                                    <p
                                        class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                                        <span
                                            class="d-inline-flex align-items-center gap-1 text-primary-600 text-sm fw-semibold">
                                            10%
                                            <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                                        </span>
                                        +5 This Month
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-sm-6">
                            <div class="card shadow-1 radius-8 gradient-bg-end-3 h-100">
                                <div class="card-body p-20">
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-16">
                                        <div
                                            class="w-44-px h-44-px bg-purple-600 rounded-circle d-flex justify-content-center align-items-center">
                                            <img src="../assets/images/dashboard-icon3.png" alt="Icon">
                                        </div>
                                        <p class="fw-medium text-primary-light mb-1">Total Categories</p>
                                    </div>
                                    <h6 class="mb-0">20,000</h6>
                                    <p
                                        class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                                        <span
                                            class="d-inline-flex align-items-center gap-1 text-primary-600 text-sm fw-semibold">
                                            10%
                                            <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                                        </span>
                                        +5 This Month
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
