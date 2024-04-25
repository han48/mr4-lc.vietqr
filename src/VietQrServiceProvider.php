<?php

namespace Mr4Lc\VietQr;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Mr4Lc\VietQr\Http\Controllers\VietQrController;

class VietQrServiceProvider extends ServiceProvider
{

    public $lang = __DIR__ . '/../resources/lang';
    public $assets = __DIR__ . '/../resources/assets';
    public $views = __DIR__ . '/../resources/views';
    public $database = __DIR__ . '/../database/migrations';
    public $config = __DIR__ . '/../config';

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->app->runningInConsole() && $config = $this->config) {
            $this->publishes(
                [$config => config_path('')],
                'mr4-lc-vietqr'
            );
        }

        if ($this->app->runningInConsole() && $assets = $this->assets) {
            $this->publishes(
                [$assets => public_path('vendor/mr4-lc/vietqr')],
                'mr4-lc-vietqr'
            );
        }

        if ($this->app->runningInConsole() && $lang = $this->lang) {
            $this->publishes(
                [$lang => resource_path('lang')],
                'mr4-lc-vietqr'
            );
        }

        if ($this->app->runningInConsole() && $views = $this->views) {
            $this->publishes(
                [$views => resource_path('views/components/mr4-lc')],
                'mr4-lc-vietqr'
            );
        }

        if ($this->app->runningInConsole() && $database = $this->database) {
            $this->publishes(
                [$database => database_path('migrations')],
                'mr4-lc-vietqr'
            );
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Mr4Lc\VietQr\Console\Commands\SeedCommand::class,
                \Mr4Lc\VietQr\Console\Commands\UpdateBanksCommand::class,
                \Mr4Lc\VietQr\Console\Commands\UpdateServiceCodesCommand::class,
            ]);
        }

        Route::post('api/vietqr', [VietQrController::class, 'generateVietQr'])->name('mr4.lc.vietqr.generate');
        Route::post('api/vietqr_encode', [VietQrController::class, 'generateVietQrEncode'])->name('mr4.lc.vietqr.generate');
        Route::post('api/vietqr_decode', [VietQrController::class, 'generateVietQrDecode'])->name('mr4.lc.vietqr.consumer_account_information.decode');
        Route::post('api/vietqr_detech', [VietQrController::class, 'generateVietQrDetech'])->name('mr4.lc.vietqr.consumer_account_information.detech');
    }
}
