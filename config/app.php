<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    */

    'name' => env('APP_NAME', '领师'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Shanghai',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'zh-CN',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'daily'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */
        Laravel\Tinker\TinkerServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,

    ],
    'sys_app_key' => "123",
    'app_key' => [
        '123' => []
    ],
    'mail_username' => env('MAIL_USERNAME', 'fengjin1@staff.weibo.com'),
    'pay' => [
        'wechat' => [
            'wechat' => [
                'appid' => 'wx2421b1c4370ec43b',             // 公众号APPID
                'mch_id' => '10000100',             // 微信商户号
                'notify_url' => 'http://lingshi.weibo.com/pay/notify/wechat',         // 支付成功后异步回调服务端接口
                'key' => '0CB01533B8C1EF103065174F50BCA001',                // 微信支付签名秘钥
                'cert_client' => '',        // 客户端证书路径，退款时需要用到
                'cert_key' => '',           // 客户端秘钥路径，退款时需要用到
            ]
        ],
        'alipay' => [
            'alipay' => [
                'appid' => '2018032302431797',             // 支付宝提供的 APP_ID
                'notify_url' => 'http://lingshi.weibo.com/pay/notify/ali',         // 支付成功后异步回调服务端接口
                'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAypj5nLQuc7muxCkVIJeDp/HizDtSlTy/gESkkTvfXpgwH7gab9nBr3XOtPLFch4pIO1PR80Ir/z4oaV/jdzPfU17pD+AdMzkPrEmA7PDVcauK1EP7zkjS5EThjaDmMRY19S4wGmW74ptpig82tvC2VUeAlrjGQeaRdfDs+bPts+QpHm1ZnyN5X+UoTrffhsNXu9lE/OlEqsVzzNhFblqQp+I0lw8fg8kOJKUPCN4ROyLCBhszFb7unmiBxk1cWgydxSSDfod0lsHuYfIzOOuqevYCSk4rrkm6zyjgd0UxnGhuokd0b4C8Zm/PG9QKgpoPIcXFvO5880HCUbuzNYN1QIDAQAB',     // 支付宝公钥，1行填写
                'private_key' => 'MIIEowIBAAKCAQEAypj5nLQuc7muxCkVIJeDp/HizDtSlTy/gESkkTvfXpgwH7gab9nBr3XOtPLFch4pIO1PR80Ir/z4oaV/jdzPfU17pD+AdMzkPrEmA7PDVcauK1EP7zkjS5EThjaDmMRY19S4wGmW74ptpig82tvC2VUeAlrjGQeaRdfDs+bPts+QpHm1ZnyN5X+UoTrffhsNXu9lE/OlEqsVzzNhFblqQp+I0lw8fg8kOJKUPCN4ROyLCBhszFb7unmiBxk1cWgydxSSDfod0lsHuYfIzOOuqevYCSk4rrkm6zyjgd0UxnGhuokd0b4C8Zm/PG9QKgpoPIcXFvO5880HCUbuzNYN1QIDAQABAoIBAA5Jx6DjnDsRJ2AyPYk05lYb2xDoRiS8Sg0zyh9sB47WUN6Lz2GADAbh2hgs3vvzYJcv5V18+lXfE3HjCCHrJr8BjezBhb+3C4nYWIP+U5JjFrl7WBJZB0I3ExduFM3bWyCtofIAAGMYci87uz78LLvDIwCrhESpopm2Y9j2OEP+M2XsM0IVIRlrew32MN1tfyOq0ep13aZzn5Ul5G90TmLQm3st11D12aiYHg/0P2CtypQP7DI84F407fk5lVFt9RgK/R1hg0XDPApCNODXVk4g/HeoiMlkfBEU3r6+bHzmZ0Mxy0Qlbuw/Y/PrPHtw58sN/wtCv1Bkwo2EnqLIFSkCgYEA6J0eNzWgmxPK8k8SmiYdT5Cu4Ornso+tQv96PgvJtN721KEOTH0e8xekwrB0QEtj7pt1/jl0ct0qSOa1h0a2TNHzIsJCprFL7oHGYaorQoaP45ZWvf9U06hNK2y/ZkU+JGAy4Lc8j5XhSCHjwNEkVoHaQgaXNCLKL1Y9HFAnUzMCgYEA3vdRPHxiznRmu7gcmACuSN/Bs9VQLaXQoCUMdmChu2t4DrUNjNDog6GM9xghIcS9fdvTDDJznaFPHdqD4rqFOdVsecyDQRx9OCbnckQNNlTNzE/7xgQQY1kJ19AmQPxxoAwuqqmyy/7fx3QYjcJHe4Vne2CvPU6Du1Nf/w00GtcCgYAT+rLGsS97QmbzCwGhBdcMp3Ot099Uwexyzbi1LZQEmgX/W1n8Dd8jqAs5wagqgY8yxl2LGWo8F2zzWAWNefBchsfoW2EYKjBIaxMb/l661w3y0U3gAddKWrFOIogKA7aDr1OPY42rE4eHB4olXJFPcNXLR/+itb5B9JlJHkVSawKBgCVFPCwbMr0GEiIw7X6vJnMCDDQOZT+sJwqOBt5G2uIkXcY/l4tBZIyUVab8PpWReIdwVoAEcvUXgM0huMgOm7SGK4LUn+Ajbf8T6b5dB8RcOqZ7fD+mcELbIF0V6z8Ts76oKqjWgw+8hBWoH26a2i4Yp6qlB8X8uOJ4VmShBWOzAoGBAIedqe3/KE3cOaWLnybgUfZDOkbrzP155AdXqOd+rTTbGmB02Gt9GEGBCdrT7nCLrBbkdF9A77dwG8uuxAlL5vtgXyRQvqYEBklEC6IJw1t+8r2tWXAs4j+XHTiLJsdD7c5cmHI2foa0wnNRuouhduMwbRlOqNJpdhNrcifDTqyW',        // 自己的私钥，1行填写
            ]
        ]
    ],
    'qrcode' => [
        'sign' => 'http://lingshi.weibo.com/api/signin/code',
        'usersign' => 'http://lingshi.weibo.com/api/signin/code',
        'path' => storage_path('qrcodes/'),
    ],
    'wy' => [
        'appkey' => env('Wy_App_Key', ''),
        'secret' => env('Wy_Secret', '')
    ],
    'enroll' => [
        'url' => 'http://lingshi.weibo.com/api/meet/signup'
    ],


    'sex' => [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
        '3' => '中性',
    ],

    'stop_tel' => env('STOP_TEL', '15510249632'),

    'roles' => [
        '1' => '超级管理员',
        '2' => '教师',
        '3' => '教研员',
        '4' => '管理员',
        '5' => '会议管理员'
    ]

];
