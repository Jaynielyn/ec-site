<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::registerView(function () {
            if (request()->is('admin/*')) {
                return view('admin.auth.admin_register'); // 管理者用登録ビュー
            }
            return view('auth.register'); // 利用者用登録ビュー
        });

        Fortify::loginView(function () {
            if (request()->is('admin/*')) {
                return view('admin.auth.admin_login');
            }
            return view('auth.login');
        });

        Fortify::authenticateUsing(function (Request $request) {
            // URLが`/admin/*`の場合、adminガードを使用
            if ($request->is('admin/*')) {
                Auth::shouldUse('admin');
            } else {
                Auth::shouldUse('web');
            }

            // 認証処理
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return Auth::user();
            }

            return null;
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
