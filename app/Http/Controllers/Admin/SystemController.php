<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    /**
     * Hiển thị logs hệ thống
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function logs(Request $request)
    {
        $logFiles = collect(Storage::disk('logs')->files())
            ->filter(function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'log';
            })
            ->map(function ($file) {
                return [
                    'name' => $file,
                    'size' => Storage::disk('logs')->size($file),
                    'modified' => Storage::disk('logs')->lastModified($file),
                ];
            })
            ->sortByDesc('modified')
            ->values()
            ->all();

        $selectedLog = $request->input('file', $logFiles[0]['name'] ?? null);
        $logContent = null;

        if ($selectedLog) {
            try {
                $logContent = Storage::disk('logs')->get($selectedLog);

                // Lọc nội dung log theo level nếu có yêu cầu
                if ($request->has('level') && $request->level !== 'all') {
                    $level = strtoupper($request->level);
                    $filteredContent = '';
                    $lines = explode(PHP_EOL, $logContent);

                    foreach ($lines as $line) {
                        if (strpos($line, $level) !== false) {
                            $filteredContent .= $line . PHP_EOL;
                        }
                    }

                    $logContent = $filteredContent;
                }

                // Giới hạn số dòng hiển thị nếu có yêu cầu
                if ($request->has('lines') && is_numeric($request->lines)) {
                    $lines = explode(PHP_EOL, $logContent);
                    $lines = array_slice($lines, -intval($request->lines));
                    $logContent = implode(PHP_EOL, $lines);
                }
            } catch (\Exception $e) {
                $logContent = 'Không thể đọc tệp log: ' . $e->getMessage();
            }
        }

        return view('admin.system.logs', compact('logFiles', 'selectedLog', 'logContent'));
    }

    /**
     * Hiển thị thông tin sức khỏe hệ thống
     *
     * @return \Illuminate\View\View
     */
    public function health()
    {
        // Thông tin PHP
        $phpInfo = [
            'version' => PHP_VERSION,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];

        // Thông tin database
        try {
            $dbInfo = [
                'connection' => config('database.default'),
                'database' => config('database.connections.' . config('database.default') . '.database'),
                'version' => DB::select('SELECT VERSION() as version')[0]->version,
                'size' => $this->getDatabaseSize(),
            ];
        } catch (\Exception $e) {
            $dbInfo = [
                'error' => $e->getMessage(),
            ];
        }

        // Thông tin ứng dụng
        $appInfo = [
            'name' => config('app.name'),
            'environment' => config('app.env'),
            'debug' => config('app.debug') ? 'Enabled' : 'Disabled',
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'laravel_version' => app()->version(),
        ];

        // Thông tin cache và session
        $cacheInfo = [
            'driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
        ];

        // Kiểm tra dung lượng đĩa
        $diskInfo = [
            'free_space' => $this->formatBytes(disk_free_space('/')),
            'total_space' => $this->formatBytes(disk_total_space('/')),
        ];

        // Kiểm tra trạng thái dịch vụ
        $services = [
            'database' => $this->checkDatabaseConnection(),
            'cache' => $this->checkCacheConnection(),
            'storage' => $this->checkStorageWritable(),
            'queue' => $this->checkQueueConnection(),
            'mail' => $this->checkMailConfig(),
        ];

        // Kiểm tra các thư mục quan trọng
        $directories = [
            'storage' => [
                'path' => storage_path(),
                'writable' => is_writable(storage_path()),
            ],
            'bootstrap/cache' => [
                'path' => base_path('bootstrap/cache'),
                'writable' => is_writable(base_path('bootstrap/cache')),
            ],
            'public' => [
                'path' => public_path(),
                'writable' => is_writable(public_path()),
            ],
        ];

        return view('admin.system.health', compact(
            'phpInfo',
            'dbInfo',
            'appInfo',
            'cacheInfo',
            'diskInfo',
            'services',
            'directories'
        ));
    }

    /**
     * Lấy kích thước cơ sở dữ liệu
     *
     * @return string
     */
    private function getDatabaseSize()
    {
        try {
            $connection = config('database.default');

            if ($connection === 'mysql') {
                $database = config('database.connections.mysql.database');
                $result = DB::select("SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema = '{$database}'");
                return $this->formatBytes($result[0]->size);
            }

            return 'Không hỗ trợ loại cơ sở dữ liệu này';
        } catch (\Exception $e) {
            return 'Không thể xác định';
        }
    }

    /**
     * Định dạng kích thước byte thành đơn vị dễ đọc
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $bytes = max($bytes, 0);
        $pow = floor(log($bytes) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Kiểm tra kết nối cơ sở dữ liệu
     *
     * @return bool
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Kiểm tra kết nối cache
     *
     * @return bool
     */
    private function checkCacheConnection()
    {
        try {
            $time = time();
            cache()->put('health-check', $time, 10);
            $value = cache()->get('health-check');
            return $value === $time;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Kiểm tra khả năng ghi vào storage
     *
     * @return bool
     */
    private function checkStorageWritable()
    {
        try {
            $filename = 'health-check-' . time() . '.txt';
            Storage::put($filename, 'Health check');
            $result = Storage::exists($filename);
            Storage::delete($filename);
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Kiểm tra kết nối queue
     *
     * @return bool
     */
    private function checkQueueConnection()
    {
        try {
            // Đơn giản chỉ kiểm tra cấu hình queue
            // Trong thực tế, bạn có thể đẩy một job vào queue và kiểm tra nó có được thực hiện không
            return config('queue.default') !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Kiểm tra cấu hình mail
     *
     * @return bool
     */
    private function checkMailConfig()
    {
        try {
            // Đơn giản chỉ kiểm tra cấu hình mail
            // Trong thực tế, bạn có thể gửi một email thử và kiểm tra nó có được gửi không
            return !empty(config('mail.mailers.' . config('mail.default')));
        } catch (\Exception $e) {
            return false;
        }
    }
}
