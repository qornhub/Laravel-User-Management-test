<x-app-layout>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Header --}}
            <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900">
                                User Management
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Manage system users and their account status.
                            </p>
                        </div>

                        <div class="flex gap-3">

                            <button type="button" onclick="window.location.href='{{ route('admin.users.export') }}'"
                                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                Export Excel
                            </button>

                            <button type="button" onclick="window.location.href='{{ route('admin.users.create') }}'"
                                class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
                                Add User
                            </button>

                        </div>

                    </div>
                </div>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Status Filter --}}
            <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="p-6">

                    <form method="GET" action="{{ route('admin.dashboard') }}">

                        <div class="flex flex-col sm:flex-row sm:items-end gap-4">

                            <div>
                                <x-input-label for="status" value="Status" />

                                <select id="status" name="status"
                                    class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All</option>

                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            <button type="submit"
                                class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800">
                                Filter
                            </button>





                            @if (request('status'))
                                <button type="button" onclick="window.location.href='{{ route('admin.dashboard') }}'"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200">
                                    Clear
                                </button>
                            @endif

                        </div>

                    </form>

                </div>
            </div>

            {{-- Users Table --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">

                {{-- Bulk Delete Form --}}
                <form id="bulk-delete-form" method="POST" action="{{ route('admin.users.bulk-destroy') }}"
                    onsubmit="return confirm('Delete selected users?')">
                    @csrf
                    @method('DELETE')
                </form>

                {{-- Table Header --}}
                <div
                    class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Users
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $users->total() }} users found.
                        </p>
                    </div>

                    <button type="submit" form="bulk-delete-form"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                        Delete Selected
                    </button>

                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">
                            <tr>

                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300">
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Name
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Email
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Phone
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                                    Actions
                                </th>

                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                            form="bulk-delete-form" class="user-checkbox rounded border-gray-300">
                                    </td>

                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->phone_number }}
                                    </td>

                                    <td class="px-6 py-4">

                                        @if ($user->status === 'active')
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-6 py-4 text-right text-sm">

                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-medium mr-4">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            class="inline" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                                Delete
                                            </button>
                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div class="p-6 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>

    <script>
        const selectAll = document.getElementById('select-all');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.user-checkbox').forEach(function(checkbox) {
                    checkbox.checked = selectAll.checked;
                });
            });
        }
    </script>

</x-app-layout>
