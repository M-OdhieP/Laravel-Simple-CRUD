@extends('users.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Laravel 8 CRUD Example</h2>
            </div>
            <div class="pull-right my-1">
                <a class="btn btn-sm btn-success" href="{{ route('users.create') }}"><i class="bi bi-plus"></i> Create New
                    user</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($users as $user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="form-delete">

                        <a class="btn btn-sm btn-info" href="{{ route('users.show', $user->id) }}" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-title="Detail"><i class="bi bi-eye-fill"></i></a>

                        <a class="btn btn-sm btn-primary" href="{{ route('users.edit', $user->id) }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit"><i
                                class="bi bi-pencil-fill"></i></a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-title="Delete"><i class="bi bi-trash-fill"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $users->links() !!}
@endsection

@section('js')
    <script>
        $('.form-delete').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $(this).off('submit').submit();
                }
            });
        });
    </script>
@endsection
