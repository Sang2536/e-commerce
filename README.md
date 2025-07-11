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

## ⚙️ Công nghệ sử dụng

- Laravel 10+
- MySQL / MariaDB
- Laravel Breeze / Jetstream
- Blade (hoặc VueJS nếu có)
- TailwindCSS
- Spatie Laravel Permission (RBAC)
- Laravel Excel (tuỳ chọn)


## ✨ Tính năng chính
- ✅ Quản lý người dùng & quyền hạn (Admin, Role, Permission)
- ✅ Quản lý sản phẩm & danh mục (Product, Inventory, Category, Tag)
- ✅ Quản lý giá, khuyến mãi & thuế (Discount, Promotion, Voucher, TaxRate)
- ✅ Quản lý đơn hàng & giao dịch (shipping, payment, order, transaction)
- ✅ Quản lý đánh giá & tương tác (Review, Card, Wishlist)
- ✅ Quản lý thống kê & theo dõi (Analytic, Log, Notification)
- ✅ Quản lý nội dung & marketing (Ads, Blog, Post, Subscriber, Faq, Subscriber, Mail)
- ✅ Quản lý ngôn ngữ & địa lý (Language, Country, Translate)
- ✅ Quản lý thiết lập & cài đặt (System, Setting, Api, Social)

- ✅ Dashboard thống kê
- ✅ Thống kê nâng cao (charts)
- ✅ Import & Export (Excel)
- ✅ Hệ thống thông báo (Notification, Mail)
- ✅ Hệ thống bật / tắt các package


## Model
- Nhóm người dùng & quyền hạn
  - User	            ->  Quản trị viên (admin, staff, moderator...)
  - Customer	        ->	Người mua hàng
  - Address		        ->  Địa chỉ giao hàng của khách hàng
  - Role / Permission	->  Phân quyền RBAC

- Nhóm xác thực & bảo mật
  - LoginLog	        ->  Nhật ký đăng nhập
  - PasswordReset	    ->  Reset mật khẩu
  - ApiToken	        ->  Token bảo mật khi dùng API

- Nhóm sản phẩm & danh mục
  - Category            ->  Danh mục sản phẩm (có thể lồng nhau - parent/child)
  - Product             ->  Những thông tin chính của sản phẩm
  - ProductVariant      ->  Phiên bản sản phẩm (màu sắc, size, chất liệu, thương hiệu...)
  - ProductImage        ->  Hình ảnh sản phẩm
  - ProductInventory    ->  Quản lý tồn kho cho từng biến thể sản phẩm
  - Brand               ->  Thương hiệu sản phẩm
  - Tag                 ->  Thẻ cho nhóm sản phẩm, danh mục, ...

- Nhóm giá, khuyến mãi & thuế
  - Discount	        ->  Giảm giá (phần trăm, theo sản phẩm, đơn hàng...)
  - Promotion	        ->  Chiến dịch khuyến mãi lớn
  - TaxRate	            ->  Thuế áp dụng theo khu vực, sản phẩm...
  - Voucher / Coupon	->  Mã giảm giá có thể nhập khi thanh toán

- Nhóm đơn hàng & giao dịch
  - Order	            ->  Đơn hàng của khách hàng
  - OrderItem	        ->  Sản phẩm cụ thể trong đơn hàng
  - OrderStatus	        ->  Trạng thái đơn hàng (mới, xử lý, giao, huỷ...)
  - Payment	            ->  Thông tin thanh toán
  - PaymentMethod	    ->  Phương thức thanh toán (COD, VNPay, Momo...)
  - Shipping	        ->  Thông tin giao hàng
  - ShippingMethod	    ->  Hình thức giao hàng (GHTK, GHN, Tự giao...)
  - Invoice	            ->  Hoá đơn xuất cho đơn hàng

- Nhóm đánh giá & tương tác
  - Review	            ->  Đánh giá sản phẩm, sao, bình luận, ...
  - Wishlist	        ->  Danh sách yêu thích của khách
  - Cart	            ->  Giỏ hàng
  - CartItem	        ->  Mỗi sản phẩm trong giỏ hàng

- Nhóm thống kê & theo dõi
  - Analytics	        ->  Thống kê lượt xem, mua hàng...
  - ActivityLog	        ->  Lịch sử hoạt động (log action)
  - Notification	    ->  Thông báo hệ thống
  - Visitor	            ->  Theo dõi người dùng ẩn danh, cookie...

- Nhóm nội dung & marketing
  - Ads	                ->  Quảng cáo
  - BlogPost	        ->  Bài viết, tin tức
  - Page	            ->  Trang tĩnh (About Us, Provision, Service, Contact, ...)
  - Faq	                ->  Hỏi đáp thường gặp
  - Subscriber	        ->  Người đăng ký (nhận email, thông báo web, ...)
  - MailTemplate	    ->  Mẫu email gửi đi

- Nhóm đa ngôn ngữ & địa lý & tiền tệ
  - Language	        ->  Ngôn ngữ được hỗ trợ
  - Translate	        ->  Bản dịch cho sản phẩm, trang, danh mục...
  - Country 	        ->  Dữ liệu địa lý phục vụ shipping/tax
  - Currencie           ->  Dữ liệu tiền tệ

- Nhóm cài đặt & thiết lập
  - System              ->  Thiết lập hệ thống
  - Setting             ->  Cài đặt cho các Model
  - ApiSetting          ->  Cài đặt kết nối Api
  - SocialSetting       ->  Cài đặt kết nối mạng xã hội
