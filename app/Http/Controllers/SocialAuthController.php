<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Chuyển hướng đến trang đăng nhập của nhà cung cấp
     *
     * @param  string  $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($provider)
    {
        if (!in_array($provider, ['google', 'facebook', 'github'])) {
            return redirect()->route('login')
                ->with('error', 'Phương thức đăng nhập không hợp lệ.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Xử lý callback từ nhà cung cấp
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Tìm user dựa trên provider_id
            $user = User::where('provider', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            // Nếu không tìm thấy, kiểm tra xem email đã tồn tại chưa
            if (!$user) {
                $existingUser = User::where('email', $socialUser->getEmail())->first();

                if ($existingUser) {
                    // Nếu tài khoản email đã tồn tại và chưa kết nối với mạng xã hội
                    if (!$existingUser->provider) {
                        // Cập nhật thông tin provider
                        $existingUser->update([
                            'provider' => $provider,
                            'provider_id' => $socialUser->getId(),
                        ]);
                        $user = $existingUser;
                    } else {
                        // Nếu đã kết nối với provider khác, thông báo lỗi
                        return redirect()->route('login')
                            ->with('error', 'Email này đã được liên kết với phương thức đăng nhập khác.');
                    }
                } else {
                    // Tạo tài khoản mới
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(uniqid()), // Tạo mật khẩu ngẫu nhiên
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'email_verified_at' => now(),
                    ]);
                }
            }

            // Đăng nhập
            Auth::login($user);

            return redirect()->intended('/')
                ->with('success', 'Đăng nhập thành công!');

        } catch (Exception $e) {
            Log::error('Social login error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Đã xảy ra lỗi khi đăng nhập. Vui lòng thử lại sau.');
        }
    }
}
