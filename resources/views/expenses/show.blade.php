@extends('layouts.app')
@section('title', 'Show Expense')
@section('content')
    <div class="dashboard-main-body">
        <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div class="">
                <h1 class="fw-semibold mb-4 h6 text-primary-light">Show Expense</h1>
                <div class="">
                    <a href="{{ route('dashboard') }}"
                        class="text-secondary-light hover-text-primary hover-underline">Dashboard </a>
                    <a href="{{ route('categories.index') }}"
                        class="text-secondary-light hover-text-primary hover-underline "> /
                        Expense</a>
                    <span class="text-secondary-light">/ Show Expense</span>
                </div>
            </div>
            <a href="add-new-student.html" class="btn btn-primary-600 d-flex align-items-center gap-6 d-none">
                <span class="d-flex text-md">
                    <i class="ri-add-large-line"></i>
                </span>
                Show Student
            </a>
        </div>
        @include('alerts.alert')
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="mt-24"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="row gy-3">
                <div class="col-lg-12">
                    <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
                        <div
                            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                            <h6 class="text-lg fw-semibold mb-0">Expense Details</h6>
                            <span
                                class="status-badge {{ $expense->status_badge_class }} px-24 py-4 radius-4 fw-medium text-sm">
                                {{ ucfirst($expense->status) }}
                            </span>
                        </div>
                        <div class="card-body p-20">
                            <div class="row gy-3">
                                <div class="col-xxl-3 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                            Title
                                        </label>
                                        <p class="form-control bg-light">{{ $expense->title }}</p>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                            Amount
                                        </label>
                                        <p class="form-control bg-light">₹{{ number_format($expense->amount, 2) }}</p>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                            Expense Date
                                        </label>
                                        <p class="form-control bg-light">{{ $expense->expense_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                            Category
                                        </label>
                                        <p class="form-control bg-light">{{ $expense->category->name ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                            Submitted By
                                        </label>
                                        <p class="form-control bg-light">{{ $expense->user->name ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                            Manager Comment
                                        </label>
                                        <p class="form-control bg-light">{{ $expense->manager_comment ?? 'No comment' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-xxl-12">
                                    <div class="">
                                        <label class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                                            Description
                                        </label>
                                        <p class="form-control bg-light" style="min-height: 60px;">
                                            {{ $expense->description ?? 'No description' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </form>
    </div>
@endsection
