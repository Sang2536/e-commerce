<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang bảng điều khiển
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $countRecord = [
            'user' => User::count(),
            'product' => Product::count(),
            'category' => DB::table('categories')->count(),
            'order' => Order::count(),
        ];

        $currentMonthRevenue = Order::whereMonth('created_at', now()->month)->sum('total');
        $lastMonthRevenue = Order::whereMonth('created_at', now()->subMonth()->month)->sum('total');
        $todayRevenue = Order::whereDate('created_at', today())->sum('total');
        $newOrders = Order::whereDate('created_at', today())->count();
        $newUsers = User::whereDate('created_at', today())->count();
        $lowStockProducts = Product::where('stock', '<', 5)->count();

        $recentUsers = User::latest()->take(5)->get();
        $recentOrders = Order::latest()->with('user')->take(5)->get();

        $orderStatusData = [
            'labels' => ['Chờ xử lý', 'Đang xử lý', 'Đang giao hàng', 'Hoàn thành', 'Đã hủy'],
            'data' => [
                Order::where('status', 'pending')->count(),
                Order::where('status', 'processing')->count(),
                Order::where('status', 'shipping')->count(),
                Order::where('status', 'completed')->count(),
                Order::where('status', 'cancelled')->count(),
            ],
            'colors' => ['#facc15', '#3b82f6', '#6366f1', '#22c55e', '#ef4444'],
        ];

        $revenueData = [
            'labels' => collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('d/m'))->toArray(),
            'data' => collect(range(6, 0))->map(fn($i) => Order::whereDate('created_at', now()->subDays($i))->sum('total')
            )->toArray(),
        ];

        $topProducts = Product::withCount('orderItems')->orderByDesc('order_items_count')->take(5)->get();

        $orderStatusColors = [
            'pending' => 'warning',
            'processing' => 'primary',
            'shipping' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];

        $orderStatusLabels = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return view('admin.dashboard-other', compact(
            'countRecord', 'currentMonthRevenue', 'lastMonthRevenue',
            'todayRevenue', 'newOrders', 'newUsers', 'lowStockProducts',
            'recentUsers', 'recentOrders', 'orderStatusData',
            'revenueData', 'topProducts', 'orderStatusColors', 'orderStatusLabels'
        ));
    }

    /**
     * Lấy dữ liệu doanh thu 7 ngày qua
     *
     * @return array
     */
    private function getRevenueData()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $revenues = Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('d/m');
            $data[] = $revenues[$date] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Lấy dữ liệu đơn hàng theo trạng thái
     *
     * @return array
     */
    private function getOrderStatusData()
    {
        $statuses = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $statusLabels = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Đã hoàn tiền',
        ];

        $statusColors = [
            'pending' => '#ffc107',
            'processing' => '#17a2b8',
            'shipped' => '#007bff',
            'delivered' => '#28a745',
            'cancelled' => '#dc3545',
            'refunded' => '#6c757d',
        ];

        $labels = [];
        $data = [];
        $colors = [];

        foreach ($statusLabels as $status => $label) {
            if (isset($statuses[$status]) && $statuses[$status] > 0) {
                $labels[] = $label;
                $data[] = $statuses[$status];
                $colors[] = $statusColors[$status];
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
        ];
    }
}
