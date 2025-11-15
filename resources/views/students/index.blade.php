@extends('layouts.app')

@section('title', 'Students List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Students List</h4>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Add Student
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table id="students-table" class="table table-striped table-bordered align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th width="5%">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('#students-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('students.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
    });

    // Confirm before delete
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this student?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endsection