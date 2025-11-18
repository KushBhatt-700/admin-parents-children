@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4 fw-bold text-dark">Admin Dashboard</h2>

    {{-- Flex row (Parents + Children Side by Side) --}}
    <div class="row">

        {{-- Parents --}}
        <div class="col-md-6 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="fw-bold text-dark">Parents</h4>

                <a href="{{ route('parents.create') }}" class="btn btn-primary btn-sm">
                    + Add Parent
                </a>
            </div>

            <div class="card shadow-sm h-100">
                <div class="card-body">

                    @if($parents->count())
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Age</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parents as $parent)
                                <tr>
                                    <td class="text-dark">{{ $parent->first_name }} {{ $parent->last_name }}</td>
                                    <td class="text-secondary">{{ $parent->email }}</td>
                                    <td class="text-dark">{{ $parent->age }}</td>
                                    <td class="d-flex gap-2">
                                        <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('parents.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this parent?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $parents->links() }}

                    @else
                        <p class="text-muted">No parents found.</p>
                    @endif

                </div>
            </div>
        </div>


        {{-- Children --}}
        <div class="col-md-6 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="fw-bold text-dark">Children</h4>

                <a href="{{ route('children.create') }}" class="btn btn-success btn-sm">
                    + Add Child
                </a>
            </div>

            <div class="card shadow-sm h-100">
                <div class="card-body">

                    @if($children->count())
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Age</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($children as $child)
                                <tr>
                                    <td class="text-dark">{{ $child->first_name }} {{ $child->last_name }}</td>
                                    <td class="text-secondary">{{ $child->email }}</td>
                                    <td class="text-dark">{{ $child->age }}</td>
                                    <td class="d-flex gap-2">
                                        <a href="{{ route('children.edit', $child->id) }}" class="btn btn-sm btn-outline-success">Edit</a>
                                        <form action="{{ route('children.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this child?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $children->links() }}

                    @else
                        <p class="text-muted">No children found.</p>
                    @endif

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
