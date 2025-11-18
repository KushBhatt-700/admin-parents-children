@extends('layouts.app')

@section('title', 'Parents List')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Parents</h1>
        <a href="{{ route('parents.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
           + Add New Parent
        </a>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search Box --}}
    <form method="GET" class="mb-4">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search parents..."
               class="w-1/3 border rounded px-3 py-2"
        >
        <button class="bg-gray-700 text-black px-4 py-2 rounded hover:bg-gray-800">
            Search  
        </button>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($parents as $parent)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3">{{ $parent->name }}</td>
                    <td class="px-4 py-3">{{ $parent->email }}</td>
                    <td class="px-4 py-3">{{ $parent->phone }}</td>

                    <td class="px-4 py-3 text-right flex gap-2 justify-end">

                        <a href="{{ route('parents.show', $parent->id) }}"
                           class="text-blue-600 hover:underline">
                           View
                        </a>

                        <a href="{{ route('parents.edit', $parent->id) }}"
                           class="text-yellow-600 hover:underline">
                           Edit
                        </a>

                        <form action="{{ route('parents.destroy', $parent->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                        No records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $parents->links() }}
    </div>

</div>
@endsection
