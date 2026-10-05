<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Header --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">
                        Edit User
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Update user account details and status.
                    </p>
                </div>

                <div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors inline-block">
                        Back to List
                    </a>
                </div>
            </div>

            {{-- Form Card --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <p class="font-medium mb-1">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">

                        {{-- Name --}}
                        <div>
                            <x-input-label for="name" value="Name" />

                            <x-text-input
                                id="name"
                                name="name"
                                type="text"
                                class="mt-1 block w-full"
                                value="{{ old('name', $user->name) }}"
                                required
                                autofocus
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" value="Email" />

                            <x-text-input
                                id="email"
                                name="email"
                                type="email"
                                class="mt-1 block w-full"
                                value="{{ old('email', $user->email) }}"
                                required
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Phone Number --}}
                        <div>
                            <x-input-label for="phone_number" value="Phone Number" />

                            <x-text-input
                                id="phone_number"
                                name="phone_number"
                                type="text"
                                class="mt-1 block w-full"
                                value="{{ old('phone_number', $user->phone_number) }}"
                                required
                            />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <x-input-label for="password" value="New Password (leave blank to keep current password)" />

                            <x-text-input
                                id="password"
                                name="password"
                                type="password"
                                class="mt-1 block w-full"
                            />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <x-input-label for="password_confirmation" value="Confirm New Password" />

                            <x-text-input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                            />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        {{-- Status --}}
                        <div>
                            <x-input-label for="status" value="Status" />

                            <select
                                id="status"
                                name="status"
                                class="mt-1 block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                                <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                                <option value="inactive" @selected(old('status', $user->status) === 'inactive')>Inactive</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-6 flex items-center justify-end gap-3 pt-4 ">
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-800 transition-colors"
                        >
                            Update User
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>