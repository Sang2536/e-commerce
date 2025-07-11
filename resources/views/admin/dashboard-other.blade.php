<x-admin-layout>
    <x-slot name="header">
        {{ __('Bảng điều khiển') }}
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-dashboard.summary-card title="Người dùng" :value="number_format($countRecord['user'])" color="bg-indigo-500"
                                  :icon="view('icons.icon')"/>
        <x-dashboard.summary-card title="Sản phẩm" :value="number_format($countRecord['product'])" color="bg-green-500"
                                  :icon="view('icons.icon')"/>
        <x-dashboard.summary-card title="Danh mục" :value="number_format($countRecord['category'])"
                                  color="bg-yellow-500" :icon="view('icons.icon')"/>
        <x-dashboard.summary-card title="Đơn hàng" :value="number_format($countRecord['order'])" color="bg-red-500"
                                  :icon="view('icons.icon')"/>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-dashboard.revenue-summary
            :current="$currentMonthRevenue"
            :last="$lastMonthRevenue"
        />

        <x-dashboard.recent-users :users="$recentUsers"/>
    </div>

    <x-dashboard.recent-orders :orders="$recentOrders"/>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <x-charts.line :data="$revenueData"/>
        <x-charts.doughnut :data="$orderStatusData"/>
    </div>

    <x-dashboard.top-products :products="$topProducts"/>
</x-admin-layout>
