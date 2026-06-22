@extends('layouts.app')
@section('title', 'Edit Category')
@section('content')
    <div class="dashboard-main-body">
        <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div class="">
                <h1 class="fw-semibold mb-4 h6 text-primary-light">Edit Category</h1>
                <div class="">
                    <a href="{{ route('dashboard') }}"
                        class="text-secondary-light hover-text-primary hover-underline">Dashboard </a>
                    <a href="{{ route('categories.index') }}"
                        class="text-secondary-light hover-text-primary hover-underline "> /
                        Category</a>
                    <span class="text-secondary-light">/ Edit Category</span>
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
        <form action="{{ route('categories.update', $category->id) }}') }}" method="POST" class="mt-24">
            @csrf
            @method('PATCH')
            <div class="row gy-3">
                <div class="col-lg-12">
                    <div class="shadow-1 radius-12 bg-base h-100 overflow-hidden">
                        <div
                            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                            <h6 class="text-lg fw-semibold mb-0">Category</h6>
                        </div>
                        <div class="card-body p-20">
                            <div class="row gy-3">
                                <div class="col-xxl-6 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label for="name"
                                            class="text-sm fw-semibold text-primary-light d-inline-block mb-8 @error('name') is-invalid @enderror">Category
                                            Name <span class="text-danger-600">* </span> </label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter category name" value="{{ $category->name }}">
                                        @error('name')
                                            <span class="text-danger-600">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xxl-6 col-xl-4 col-sm-6">
                                    <div class="">
                                        <label for="status"
                                            class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Status <span
                                                class="text-danger-600">* </span> </label>
                                        <select id="status" name="status"
                                            class="form-control form-select @error('status') is-invalid @enderror">

                                            <option value="1"
                                                {{ old('status', $category->status) == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>

                                            <option value="0"
                                                {{ old('status', $category->status) == 0 ? 'selected' : '' }}>
                                                Inactive
                                            </option>

                                        </select>
                                        @error('status')
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
                        <a href="{{ route('categories.index') }}"
                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-50 py-11 radius-8">
                            Cancel
                        </a>
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
