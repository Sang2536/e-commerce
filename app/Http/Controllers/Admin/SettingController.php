<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Hiển thị trang cài đặt
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = Setting::where('group', '!=', 'mail')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Cập nhật cài đặt
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                // Xử lý file upload
                $file = $request->file($key);
                $path = $file->store('settings', 'public');
                $value = $path;

                // Xóa file cũ nếu có
                $setting = Setting::where('key', $key)->first();
                if ($setting && $setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Xóa cache cài đặt
        Cache::forget('settings');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Cài đặt đã được cập nhật thành công.');
    }

    /**
     * Hiển thị trang cài đặt email
     *
     * @return \Illuminate\View\View
     */
    public function mail()
    {
        $mailSettings = Setting::where('group', 'mail')->get()->keyBy('key');
        return view('admin.settings.mail', compact('mailSettings'));
    }

    /**
     * Cập nhật cài đặt email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMail(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required_if:mail_mailer,smtp|nullable|string',
            'mail_port' => 'required_if:mail_mailer,smtp|nullable|numeric',
            'mail_username' => 'required_if:mail_mailer,smtp|nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'mail'],
                ['value' => $value]
            );
        }

        // Xóa cache cài đặt
        Cache::forget('settings');

        // Cập nhật biến môi trường nếu cần
        $this->updateEnvironmentFile($validated);

        return redirect()->route('admin.settings.mail')
            ->with('success', 'Cài đặt email đã được cập nhật thành công.');
    }

    /**
     * Cập nhật file .env
     *
     * @param  array  $data
     * @return void
     */
    private function updateEnvironmentFile($data)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $mappings = [
            'mail_mailer' => 'MAIL_MAILER',
            'mail_host' => 'MAIL_HOST',
            'mail_port' => 'MAIL_PORT',
            'mail_username' => 'MAIL_USERNAME',
            'mail_password' => 'MAIL_PASSWORD',
            'mail_encryption' => 'MAIL_ENCRYPTION',
            'mail_from_address' => 'MAIL_FROM_ADDRESS',
            'mail_from_name' => 'MAIL_FROM_NAME',
        ];

        foreach ($mappings as $key => $envKey) {
            if (isset($data[$key])) {
                $value = $data[$key];

                // Xử lý giá trị đặc biệt
                if (strpos($value, ' ') !== false || strpos($value, '#') !== false) {
                    $value = '"' . $value . '"';
                }

                // Cập nhật hoặc thêm biến môi trường
                if (strpos($str, "$envKey=") !== false) {
                    $str = preg_replace(
                        "/^{$envKey}=.*/m",
                        "{$envKey}={$value}",
                        $str
                    );
                } else {
                    $str .= "\n{$envKey}={$value}\n";
                }
            }
        }

        file_put_contents($envFile, $str);

        // Xóa cache cấu hình
        Artisan::call('config:clear');
    }

    public function general() {
        return view('admin.settings.general');
    }

    public function payment() {
        return view('admin.settings.payment');
    }

    public function shipping() {
        return view('admin.settings.shipping');
    }

    public function seo() {
        return view('admin.settings.seo');
    }
}
