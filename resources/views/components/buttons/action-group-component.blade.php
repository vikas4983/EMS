@if ($type == 'edit')
    <a href="{{ $url }}"
        class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
        <i class="ri-edit-2-line"></i>
        Edit
    </a>
@elseif($type == 'delete')
    <form action="{{ $url }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button onclick="return confirm('Are you sure?')"
            class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6"><i
                class="ri-delete-bin-6-line"></i>Delete</button>
    </form>
@elseif($type == 'restore')
    <form action="{{ $url }}" method="POST" class="d-inline">
        @csrf
        <button onclick="return confirm('Are you sure?')"
            class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
            <i class="ri-history-line"></i>Restore</button>
    </form>
@elseif($type == 'permanent-delete')
    <form action="{{ $url }}" method="POST" class="d-inline">
        @csrf
        <button onclick="return confirm('Are you sure?')"
            class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
            <i class="ri-delete-bin-6-line"></i>Force Delete</button>
    </form>
@elseif($type == 'approve')
    <button type="button"
        class="statusBtn dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6"
        data-bs-toggle="modal"   data-bs-target="#statusModal" data-url="{{route('expense.approve')}}" data-id="{{ $id }}" data-status="approved">
        <i class="ri-checkbox-circle-line"></i>
        Approve
    </button>
@elseif($type == 'reject')
    <button type="button"
        class="statusBtn dropdown-item rounded text-danger bg-hover-neutral-200 d-flex align-items-center gap-2 py-6"
        data-bs-toggle="modal" data-bs-target="#statusModal" data-url="{{route('expense.reject')}}" data-id="{{ $id }}" data-status="rejected">
        <i class="ri-close-circle-line"></i>
        Reject
    </button>
@endif
