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
            class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6"
            ><i
                class="ri-delete-bin-6-line"></i>Delete</button>
    </form>
@endif
