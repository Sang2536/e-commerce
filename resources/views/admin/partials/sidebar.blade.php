<div class="w-64 bg-indigo-800 text-white min-h-screen flex flex-col" x-data="{ isMobileMenuOpen: false }">
    <!-- Mobile menu button -->
    <div class="lg:hidden p-4 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center">
            <span class="text-xl font-semibold">{{ config('app.name') }}</span>
        </a>
        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" x-show="!isMobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" x-show="isMobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Sidebar content -->
    <div class="flex-1 flex flex-col overflow-y-auto" :class="{'hidden': !isMobileMenuOpen, 'block': isMobileMenuOpen}" :class="{'lg:block': true}">
        <!-- Logo -->
        <div class="hidden lg:flex items-center h-16 px-4 border-b border-indigo-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-300" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
                </svg>
                <span class="ml-2 text-lg font-bold">Admin</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="mt-5 px-2 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-6 w-6 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-300' : 'text-indigo-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Bảng điều khiển
            </a>

            <div x-data="{ open: {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" class="w-full group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Sản phẩm
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': open, 'rotate-0': !open}" class="ml-auto h-5 w-5 transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="space-y-1 pl-8" style="display: none;">
                    <a href="{{ route('admin.products.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.products.index') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Tất cả sản phẩm
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.products.create') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Thêm sản phẩm
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.categories.index') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Danh mục
                    </a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" class="w-full group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Đơn hàng
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': open, 'rotate-0': !open}" class="ml-auto h-5 w-5 transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="space-y-1 pl-8" style="display: none;">
                    <a href="{{ route('admin.orders.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.index') && !request()->has('status') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Tất cả đơn hàng
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.index') && request()->get('status') == 'pending' ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Đơn chờ xử lý
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.index') && request()->get('status') == 'processing' ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Đang xử lý
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.index') && request()->get('status') == 'completed' ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Đã hoàn thành
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.index') && request()->get('status') == 'cancelled' ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Đã hủy
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.customers.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.customers.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Khách hàng
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.reviews.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Đánh giá
            </a>

            <div x-data="{ open: {{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" class="w-full group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Cài đặt
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': open, 'rotate-0': !open}" class="ml-auto h-5 w-5 transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" class="space-y-1 pl-8" style="display: none;">
                    <a href="{{ route('admin.settings.general') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.general') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Cài đặt chung
                    </a>
                    <a href="{{ route('admin.settings.payment') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.payment') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Thanh toán
                    </a>
                    <a href="{{ route('admin.settings.shipping') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.shipping') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        Vận chuyển
                    </a>
                    <a href="{{ route('admin.settings.seo') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings.seo') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                        SEO
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.users.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-700' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-6 w-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Người dùng
            </a>
        </nav>
    </div>
</div>
