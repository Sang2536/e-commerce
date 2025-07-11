@extends('resources.views.layouts.app')

@section('title', 'Giới thiệu về chúng tôi')

@section('content')
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Giới thiệu về chúng tôi</h1>

            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Câu chuyện của chúng tôi</h2>
                <p class="text-gray-600 mb-6">Cửa hàng của chúng tôi được thành lập vào năm 2020 với mục tiêu cung cấp
                    các sản phẩm chất lượng cao với giá cả phải chăng. Chúng tôi bắt đầu từ một cửa hàng nhỏ tại Hà Nội
                    và dần phát triển thành một trong những cửa hàng trực tuyến uy tín nhất Việt Nam.</p>

                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Sứ mệnh</h2>
                <p class="text-gray-600 mb-6">Sứ mệnh của chúng tôi là mang đến cho khách hàng những sản phẩm chất lượng
                    cao, đa dạng về mẫu mã và phong cách, đồng thời cung cấp dịch vụ khách hàng xuất sắc. Chúng tôi cam
                    kết không ngừng cải tiến để đáp ứng nhu cầu ngày càng cao của khách hàng.</p>

                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Giá trị cốt lõi</h2>
                <ul class="list-disc pl-6 text-gray-600 mb-6">
                    <li class="mb-2">Chất lượng: Chúng tôi cam kết cung cấp những sản phẩm chất lượng cao nhất.</li>
                    <li class="mb-2">Trung thực: Minh bạch trong mọi giao dịch và thông tin sản phẩm.</li>
                    <li class="mb-2">Tôn trọng: Đối xử với khách hàng, đối tác và nhân viên bằng sự tôn trọng tối đa.
                    </li>
                    <li class="mb-2">Đổi mới: Không ngừng cải tiến sản phẩm và dịch vụ.</li>
                    <li>Trách nhiệm: Có trách nhiệm với cộng đồng và môi trường.</li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Đội ngũ của chúng tôi</h2>
                <p class="text-gray-600 mb-6">Đội ngũ của chúng tôi bao gồm những chuyên gia trong ngành với nhiều năm
                    kinh nghiệm. Chúng tôi làm việc với niềm đam mê và sự tận tâm để mang đến trải nghiệm mua sắm tốt
                    nhất cho khách hàng.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-32 h-32 rounded-full bg-gray-300 mx-auto mb-4 overflow-hidden">
                            <img src="/images/team/member1.jpg" alt="Nguyễn Văn A" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-semibold text-lg">Nguyễn Văn A</h3>
                        <p class="text-gray-500">Giám đốc điều hành</p>
                    </div>

                    <div class="text-center">
                        <div class="w-32 h-32 rounded-full bg-gray-300 mx-auto mb-4 overflow-hidden">
                            <img src="/images/team/member2.jpg" alt="Trần Thị B" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-semibold text-lg">Trần Thị B</h3>
                        <p class="text-gray-500">Giám đốc sản phẩm</p>
                    </div>

                    <div class="text-center">
                        <div class="w-32 h-32 rounded-full bg-gray-300 mx-auto mb-4 overflow-hidden">
                            <img src="/images/team/member3.jpg" alt="Lê Văn C" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-semibold text-lg">Lê Văn C</h3>
                        <p class="text-gray-500">Giám đốc dịch vụ khách hàng</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Liên hệ với chúng tôi</h2>
                <p class="text-gray-600 mb-6">Chúng tôi luôn sẵn sàng lắng nghe ý kiến và phản hồi từ khách hàng. Vui
                    lòng liên hệ với chúng tôi qua:</p>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1">
                        <h3 class="font-semibold mb-2">Địa chỉ</h3>
                        <p class="text-gray-600 mb-4">123 Đường ABC, Quận 1, TP. Hồ Chí Minh</p>

                        <h3 class="font-semibold mb-2">Email</h3>
                        <p class="text-gray-600 mb-4">contact@example.com</p>

                        <h3 class="font-semibold mb-2">Điện thoại</h3>
                        <p class="text-gray-600">(+84) 123 456 789</p>
                    </div>

                    <div class="flex-1">
                        <h3 class="font-semibold mb-2">Giờ làm việc</h3>
                        <p class="text-gray-600 mb-1">Thứ Hai - Thứ Sáu: 8:00 - 18:00</p>
                        <p class="text-gray-600 mb-1">Thứ Bảy: 8:00 - 12:00</p>
                        <p class="text-gray-600">Chủ Nhật: Nghỉ</p>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('contact.index') }}"
                       class="inline-block px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-300">Liên
                        hệ ngay</a>
                </div>
            </div>
        </div>
    </div>
@endsection
