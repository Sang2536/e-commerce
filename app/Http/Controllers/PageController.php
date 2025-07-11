<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    /**
     * Hiển thị trang giới thiệu
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Hiển thị trang câu hỏi thường gặp
     *
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'Làm thế nào để đặt hàng?',
                'answer' => 'Bạn có thể dễ dàng đặt hàng bằng cách thêm sản phẩm vào giỏ hàng, sau đó tiến hành thanh toán.'
            ],
            [
                'question' => 'Phương thức thanh toán nào được chấp nhận?',
                'answer' => 'Chúng tôi chấp nhận thanh toán qua thẻ tín dụng, thẻ ghi nợ, và các phương thức thanh toán trực tuyến phổ biến.'
            ],
            [
                'question' => 'Thời gian giao hàng là bao lâu?',
                'answer' => 'Thời gian giao hàng thông thường là 2-5 ngày làm việc tùy thuộc vào vị trí của bạn.'
            ],
            [
                'question' => 'Chính sách đổi trả như thế nào?',
                'answer' => 'Chúng tôi chấp nhận đổi trả trong vòng 14 ngày kể từ ngày nhận hàng nếu sản phẩm còn nguyên vẹn.'
            ],
            [
                'question' => 'Làm cách nào để theo dõi đơn hàng?',
                'answer' => 'Bạn có thể theo dõi đơn hàng bằng cách đăng nhập vào tài khoản và xem trạng thái đơn hàng trong mục "Đơn hàng của tôi".'
            ],
        ];

        return view('pages.faq', ['faqs' => $faqs]);
    }

    /**
     * Hiển thị trang điều khoản sử dụng
     *
     * @return \Illuminate\View\View
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Hiển thị trang chính sách bảo mật
     *
     * @return \Illuminate\View\View
     */
    public function privacy()
    {
        return view('pages.privacy');
    }
}
