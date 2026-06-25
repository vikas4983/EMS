@extends('layouts.app')
@section('title', 'Categories')
@section('content')

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
                                        <span
                                            class="
        {{ $category->status_label == 'Active' ? 'bg-success-100 text-success-600' : 'bg-danger-100 text-danger-600' }}
        px-24 py-4 radius-4 fw-medium text-sm">
                                            {{ $category->status_label }}
                                        </span>
                                    </td>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="text-primary-light text-xl"
                                                data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                                <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                                                @if ($category->trashed())
                                                    <x-buttons.action-group-component type="restore" :url="route('categories.restore', $category->id)" />
                                                    <x-buttons.action-group-component type="permanent-delete"
                                                        :url="route('categories.forceDelete', $category->id)" />
                                                @else
                                                    <li>
                                                        <x-buttons.action-group-component type="edit"
                                                            :url="route('categories.edit', $category->id)" />
                                                    </li>
                                                    <li>
                                                        <x-buttons.action-group-component type="delete"
                                                            :url="route('categories.destroy', $category->id)" />
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No Data Found!</td>
                                </tr>
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
