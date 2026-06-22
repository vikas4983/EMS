@extends('layouts.app')
@section('title', 'Categories')
@section('content')
    <style>
        .dataTables_paginate,
        .dataTables_info,
        .dataTables_length,
        .dataTables_filter {
            display: none !important;
        }

        .dropdown-submenu .excel-submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            cursor: pointer;
        }

        .dropdown-submenu:hover .excel-submenu {
            display: block;
        }
    </style>
    <div class="dashboard-main-body">
        <div class="breadcrumb d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <a href="{{ route('categories.create') }}" class="btn btn-primary-600 d-flex align-items-center gap-6 ">
                <span class="d-flex text-md">
                    <i class="ri-add-large-line"></i>
                </span>
                Add category
            </a>
        </div>
        @include('alerts.alert')
        <div class="mt-24">
            <div class="card h-100">
                <div class="card-body p-0 dataTable-wrapper">
                    <div
                        class="d-flex align-items-center justify-content-between flex-wrap gap-16 px-20 py-12 border-bottom border-neutral-200">
                        <div class="d-flex flex-wrap align-items-center gap-16">
                            <div class="dropdown">
                                <button type="button"
                                    class="px-12 py-5-px border border-neutral-300 radius-8 d-flex align-items-center gap-20 "
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="d-flex align-items-center gap-1 text-secondary-light text-sm">
                                        <i class="ri-file-upload-line text-md line-height-1"></i>
                                        Export
                                    </span>
                                    <span class="">
                                        <i class="ri-arrow-down-s-line"></i>
                                    </span>
                                </button>
                                <ul class="dropdown-menu p-12 border bg-base shadow">
                                    <li>
                                        <button type="button"
                                            class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalView">
                                            <i class="ri-file-3-line"></i>
                                            PDF
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button"
                                            class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10"
                                            data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                                            <i class="ri-file-excel-line"></i>
                                            Excel
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <form class="navbar-search dt-search m-0">
                                <input type="text" class="dt-input bg-transparent radius-4" aria-controls="dataTable"
                                    name="search" placeholder="Search...">
                                <iconify-icon icon="ion:search-outline" class="icon"><template shadowrootmode="open">
                                        <style data-style="data-style">
                                            :host {
                                                display: inline-block;
                                                vertical-align: 0
                                            }

                                            span,
                                            svg {
                                                display: block
                                            }
                                        </style><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                            viewBox="0 0 512 512">
                                            <path fill="none" stroke="currentColor" stroke-miterlimit="10"
                                                stroke-width="32"
                                                d="M221.09 64a157.09 157.09 0 1 0 157.09 157.09A157.1 157.1 0 0 0 221.09 64Z">
                                            </path>
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"></path>
                                        </svg>
                                    </template></iconify-icon>
                            </form>
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

                                    <form action="#" class="p-16 d-grid grid-cols-2 gap-16">
                                        <div class="">
                                            <label for="class"
                                                class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Class</label>
                                            <select id="class" class="form-control form-select">
                                                <option value="Select" disabled="">Select Class</option>
                                                <option value="Primary">Primary</option>
                                                <option value="SSC">SSC</option>
                                                <option value="HSC">HSC</option>
                                                <option value="Hons">Hons</option>
                                                <option value="Masters">Masters</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <label for="section"
                                                class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Section</label>
                                            <select id="section" class="form-control form-select">
                                                <option value="Select">Select Section</option>
                                                <option value="Arts">Arts</option>
                                                <option value="Science">Science</option>
                                                <option value="Commerce">Commerce</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <label for="gender"
                                                class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Gender</label>
                                            <select id="gender" class="form-control form-select">
                                                <option value="Select">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <label for="status"
                                                class="text-sm fw-semibold text-primary-light d-inline-block mb-8">Status</label>
                                            <select id="status" class="form-control form-select">
                                                <option value="Select">Select Status</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <button type="reset"
                                                class="btn btn-danger-200 text-danger-600 w-100">Reset</button>
                                        </div>
                                        <div class="">
                                            <button type="submit" class="btn btn-primary-600 w-100">Apply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <span class="successMessage  alert alert-success" style="width: 100%; display:none;">
                    </span>
                    <span class="errorMessage  alert alert-danger" style="width: 100%; display:none;">
                    </span>
                    <div class="p-0 result table-responsive">
                        <table class="table bordered-table mb-0 " id="dataTable" data-page-length='10'>
                            <thead>
                                <tr>
                                    <th scope="col">
                                        <div class="form-check style-check d-flex align-items-center">

                                            <label class="form-check-label">
                                                S.L
                                            </label>
                                        </div>
                                    </th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                                <tr class="error-row" style="display:none;">
                                    <th colspan="4" class="text-danger text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="noResult"></tbody>
                            @forelse ($categories as $count => $category)
                                <tr>
                                    <td>
                                        <div class="form-check style-check d-flex align-items-center">
                                            <label class="form-check-label">
                                                {{ $count + 1 }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($category)
                                            <a href="{{ route('categories.show', $category->id) }}" class="text-success">
                                                {{ $category->name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $category->status_label }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="text-primary-light text-xl"
                                                data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                                                <li>
                                                    <x-buttons.action-group-component type="edit" :url="route('categories.edit', $category->id)" />
                                                </li>
                                                <li>
                                                    <x-buttons.action-group-component type="delete" :url="route('categories.destroy', $category->id)" />
                                                </li>
                                            </ul>
                                        </div>

                                    </td>

                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row text-right">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
