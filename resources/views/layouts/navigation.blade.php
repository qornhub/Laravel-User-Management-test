<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo / Application Name --}}
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-900 text-white font-bold">
                        TN
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-gray-900">
                            Technical Test Nelson
                        </div>
                        <div class="text-xs text-gray-500">
                            Admin Panel
                        </div>
                    </div>
                </a>
            </div>

            {{-- Right Side --}}
            <div class="flex items-center gap-4">

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="hidden sm:inline-flex items-center px-3 py-2 text-sm font-medium
                          {{ request()->routeIs('admin.dashboard')
                              ? 'text-gray-900'
                              : 'text-gray-500 hover:text-gray-900' }}">
                    Dashboard
                </a>

                {{-- User --}}
                <div class="hidden sm:flex items-center gap-3 pl-4 border-l border-gray-200">
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">
                            {{ Auth::user()->name }}
                        </div>

                        <div class="text-xs text-gray-500">
                            Administrator
                        </div>
                    </div>

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-900 text-sm font-semibold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="text-sm text-gray-500 hover:text-gray-900 transition">
                            Logout
                        </button>
                    </form>
                </div>

                {{-- Mobile --}}
                <div class="sm:hidden">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="text-sm font-medium text-gray-600 hover:text-gray-900">
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</nav>