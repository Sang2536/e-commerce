<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class CreateUserCommand extends Command
{
    protected $signature = 'make:user
                            {--name= : Tên người dùng}
                            {--email= : Email người dùng}
                            {--password= : Mật khẩu người dùng}';

    protected $description = 'Tạo một user mới (ngẫu nhiên hoặc thủ công)';

    public function handle()
    {
        $faker = Faker::create();

        // Nếu không truyền option nào, sẽ tạo tự động
        $name     = $this->option('name')     ?? $faker->name;
        $email    = $this->option('email')    ?? $faker->unique()->safeEmail;
        $password = $this->option('password') ?? 'password'; // mặc định

        // Nếu email trùng thì tạo lại email ngẫu nhiên
        while (User::where('email', $email)->exists()) {
            $email = $faker->unique()->safeEmail;
        }

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("✅ User đã được tạo:");
        $this->line("👤 Name: {$user->name}");
        $this->line("📧 Email: {$user->email}");
        $this->line("🔑 Password: {$password}");

        return Command::SUCCESS;
    }
}
