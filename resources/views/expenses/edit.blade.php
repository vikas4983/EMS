@extends('layouts.app')
@section('title', 'Edit Expense')
@section('content')
    <div class="dashboard-main-body">
        <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div class="">
                <h1 class="fw-semibold mb-4 h6 text-primary-light">Edit Expense</h1>
                <div class="">
                    <a href="{{ route('dashboard') }}"
                        class="text-secondary-light hover-text-primary hover-underline">Dashboard </a>
                    <a href="{{ route('categories.index') }}"
                        class="text-secondary-light hover-text-primary hover-underline "> /
                        Expense</a>
                    <span class="text-secondary-light">/ Edit Expense</span>
                </div>
            </div>
            <a href="add-new-student.html" class="btn btn-primary-600 d-flex align-items-center gap-6 d-none">
                <span class="d-flex text-md">
                    <i class="ri-add-large-line"></i>
                </span>
                Edit Student
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
                            <h6 class="text-lg fw-semibold mb-0">Expense</h6>
                        </div>
                        <div class="card-body p-20">
                            <div class="row gy-3">
                                <div class="col-xxl-3 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label for="title"
                                            class="text-sm fw-semibold text-primary-light d-inline-block mb-8 @error('title') is-invalid @enderror">
                                            Title <span class="text-danger-600">* </span> </label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            placeholder="Enter title" value="{{ old('title', $expense?->title) }}" required>
                                        @error('title')
                                            <span class="text-danger-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label for="amount"
                                            class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Amount <span
                                                class="text-danger-600">* </span> </label>
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            min="1" placeholder="Enter amount"
                                            value="{{ old('amount', $expense?->amount) }}" required>
                                        @error('amount')
                                            <span class="text-danger-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label for="expense_date"
                                            class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Expense Date
                                            <span class="text-danger-600">* </span> </label>

                                        <input type="date" class="form-control" id="expense_date" name="expense_date"
                                            placeholder="Enter expense_date"
                                            value="{{ old('expense_date', $expense->expense_date?->format('Y-m-d')) }}"
                                            required>
                                        @error('expense_date')
                                            <span class="text-danger-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-4 col-sm-6">
                                    <label for="category_id"
                                        class="text-sm fw-semibold text-primary-light d-inline-block mb-8 @error('category_id') is-invalid @enderror">Expense
                                        Category
                                        <span class="text-danger-600">* </span></label>

                                    <select name="category_id" class="form-control">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $expense->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger-600">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-xxl-12 col-xl-4 col-sm-6">
                                <div class="">
                                    <label for="description"
                                        class="text-sm fw-semibold text-primary-light d-inline-block mb-8 @error('description') is-invalid @enderror">
                                        Description </label>
                                    <input type="text" class="form-control" id="description" name="description"
                                        placeholder="Enter expense description"
                                        value="{{ old('description', $expense?->description) }}">
                                    @error('description')
                                        <span class="text-danger-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex align-items-center justify-content-center gap-3 mt-8">
                    <button type="submit"
                        class="btn btn-primary-600 border border-primary-600 text-md px-28 py-12 radius-8">
                        Submit
                    </button>
                </div>
            </div>
    </div>
    </form>
    </div>
@endsection
