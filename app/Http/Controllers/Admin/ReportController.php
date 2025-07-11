<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Báo cáo doanh số
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function sales(Request $request)
    {
        // Lấy khoảng thời gian báo cáo
        $period = $request->input('period', 'month');
        $startDate = null;
        $endDate = now();
        $groupBy = '';
        $dateFormat = '';

        switch ($period) {
            case 'week':
                $startDate = now()->subDays(7);
                $groupBy = 'date';
                $dateFormat = '%Y-%m-%d';
                break;
            case 'month':
                $startDate = now()->subDays(30);
                $groupBy = 'date';
                $dateFormat = '%Y-%m-%d';
                break;
            case 'quarter':
                $startDate = now()->subMonths(3);
                $groupBy = 'week';
                $dateFormat = '%Y-%u'; // Năm-Tuần
                break;
            case 'year':
                $startDate = now()->subYear();
                $groupBy = 'month';
                $dateFormat = '%Y-%m';
                break;
            case 'custom':
                $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subDays(30);
                $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();

                // Chọn groupBy dựa trên khoảng thời gian
                $daysDiff = $startDate->diffInDays($endDate);
                if ($daysDiff <= 31) {
                    $groupBy = 'date';
                    $dateFormat = '%Y-%m-%d';
                } elseif ($daysDiff <= 92) { // ~3 tháng
                    $groupBy = 'week';
                    $dateFormat = '%Y-%u';
                } else {
                    $groupBy = 'month';
                    $dateFormat = '%Y-%m';
                }
                break;
        }

        // Doanh thu theo thời gian
        $salesData = Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as {$groupBy}"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy($groupBy)
            ->orderBy($groupBy)
            ->get();

        // Tổng quan doanh thu
        $totalRevenue = Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        $orderCount = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $averageOrderValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        // Doanh thu theo phương thức thanh toán
        $paymentMethodRevenue = Order::where('status', '!=', 'cancelled')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();

        // Sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.sales', compact(
            'salesData',
            'totalRevenue',
            'orderCount',
            'averageOrderValue',
            'paymentMethodRevenue',
            'topProducts',
            'period',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Báo cáo tồn kho
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function inventory(Request $request)
    {
        // Sản phẩm sắp hết hàng
        $lowStockThreshold = $request->input('threshold', 10);
        $lowStockProducts = Product::where('stock', '<=', $lowStockThreshold)
            ->where('stock', '>', 0)
            ->where('is_active', true)
            ->orderBy('stock')
            ->paginate(10);

        // Sản phẩm hết hàng
        $outOfStockProducts = Product::where('stock', 0)
            ->where('is_active', true)
            ->paginate(10);

        // Tổng quan tồn kho
        $totalProducts = Product::count();
        $totalActiveProducts = Product::where('is_active', true)->count();
        $totalStockValue = Product::where('is_active', true)
            ->selectRaw('SUM(stock * price) as value')
            ->first()
            ->value;

        // Phân tích tồn kho theo danh mục
        $stockByCategory = DB::table('categories')
            ->leftJoin('category_product', 'categories.id', '=', 'category_product.category_id')
            ->leftJoin('products', 'category_product.product_id', '=', 'products.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(DISTINCT products.id) as product_count'),
                DB::raw('SUM(products.stock) as total_stock'),
                DB::raw('SUM(products.stock * products.price) as stock_value')
            )
            ->where('products.is_active', true)
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('stock_value', 'desc')
            ->get();

        return view('admin.reports.inventory', compact(
            'lowStockProducts',
            'outOfStockProducts',
            'totalProducts',
            'totalActiveProducts',
            'totalStockValue',
            'stockByCategory',
            'lowStockThreshold'
        ));
    }

    /**
     * Báo cáo khách hàng
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function customers(Request $request)
    {
        // Khách hàng có giá trị đơn hàng cao nhất
        $topCustomers = User::select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.total) as total_spent')
            )
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.payment_status', 'paid')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // Phân tích khách hàng mới
        $period = $request->input('period', 'month');
        $startDate = null;
        $endDate = now();
        $groupBy = '';
        $dateFormat = '';

        switch ($period) {
            case 'week':
                $startDate = now()->subDays(7);
                $groupBy = 'date';
                $dateFormat = '%Y-%m-%d';
                break;
            case 'month':
                $startDate = now()->subDays(30);
                $groupBy = 'date';
                $dateFormat = '%Y-%m-%d';
                break;
            case 'quarter':
                $startDate = now()->subMonths(3);
                $groupBy = 'week';
                $dateFormat = '%Y-%u';
                break;
            case 'year':
                $startDate = now()->subYear();
                $groupBy = 'month';
                $dateFormat = '%Y-%m';
                break;
        }

        $newCustomers = User::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as {$groupBy}"),
                DB::raw('COUNT(*) as user_count')
            )
            ->groupBy($groupBy)
            ->orderBy($groupBy)
            ->get();

        // Thống kê chung
        $totalCustomers = User::count();
        $newCustomersCount = User::where('created_at', '>=', $startDate)->count();
        $activeCustomers = Order::where('created_at', '>=', $startDate)
            ->distinct('user_id')
            ->count('user_id');

        return view('admin.reports.customers', compact(
            'topCustomers',
            'newCustomers',
            'totalCustomers',
            'newCustomersCount',
            'activeCustomers',
            'period',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Báo cáo tiếp thị
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function marketing(Request $request)
    {
        // Hiệu quả khuyến mãi
        $promotions = DB::table('promotions')
            ->leftJoin('orders', 'promotions.id', '=', 'orders.promotion_id')
            ->select(
                'promotions.id',
                'promotions.name',
                'promotions.code',
                'promotions.start_date',
                'promotions.end_date',
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(orders.total) as total_revenue'),
                DB::raw('SUM(orders.discount) as total_discount')
            )
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('promotions.id', 'promotions.name', 'promotions.code', 'promotions.start_date', 'promotions.end_date')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Phân tích nguồn lưu lượng
        $trafficSources = DB::table('orders')
            ->select('source', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total) as total_revenue'))
            ->where('status', '!=', 'cancelled')
            ->groupBy('source')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Phân tích tỷ lệ chuyển đổi
        $conversionStats = [
            'total_visits' => 10000, // Mẫu dữ liệu giả định
            'product_views' => 5000,
            'add_to_cart' => 1000,
            'checkout_starts' => 500,
            'purchases' => Order::where('status', '!=', 'cancelled')->count(),
        ];

        // Tính tỷ lệ chuyển đổi
        $conversionRates = [
            'visit_to_product' => $conversionStats['total_visits'] > 0 ? ($conversionStats['product_views'] / $conversionStats['total_visits']) * 100 : 0,
            'product_to_cart' => $conversionStats['product_views'] > 0 ? ($conversionStats['add_to_cart'] / $conversionStats['product_views']) * 100 : 0,
            'cart_to_checkout' => $conversionStats['add_to_cart'] > 0 ? ($conversionStats['checkout_starts'] / $conversionStats['add_to_cart']) * 100 : 0,
            'checkout_to_purchase' => $conversionStats['checkout_starts'] > 0 ? ($conversionStats['purchases'] / $conversionStats['checkout_starts']) * 100 : 0,
            'overall' => $conversionStats['total_visits'] > 0 ? ($conversionStats['purchases'] / $conversionStats['total_visits']) * 100 : 0,
        ];

        return view('admin.reports.marketing', compact(
            'promotions',
            'trafficSources',
            'conversionStats',
            'conversionRates'
        ));
    }
}
