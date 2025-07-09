# 🛠️ Admin Dashboard - E-commerce Management

Trang quản trị dành cho hệ thống thương mại điện tử. Dùng để quản lý sản phẩm, đơn hàng, người dùng và nhiều tính năng khác.

Tài khoản mẫu
    -   Email: admin.ecommerce@example.com
    -   Password: password

## 🚀 Cài đặt dự án

```bash
git clone https://github.com/your-username/admin-ecommerce.git
cd admin-ecommerce
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

## ✨ Tính năng chính

- ✅ Quản lý sản phẩm (CRUD)
- ✅ Quản lý kho hàng & xuất nhập
- ✅ Quản lý đơn hàng (chi tiết, trạng thái, thanh toán)
- ✅ Quản lý danh mục, tag, coupon

- ✅ Dashboard thống kê
- ✅ Thống kê nâng cao (charts)
- ✅ Hệ thống thông báo (Notification)
- ✅ Cài đặt hệ thống

- ✅ Quản lý người dùng & phân quyền
- ✅ Quản lý nhân viên, log hoạt động
- 🔐 Phân quyền theo vai trò (Role & Permission)


## ⚙️ Công nghệ sử dụng

- Laravel 10+
- MySQL / MariaDB
- Laravel Breeze / Jetstream
- Blade (hoặc VueJS nếu có)
- TailwindCSS
- Spatie Laravel Permission (RBAC)
- Laravel Excel (tuỳ chọn)
