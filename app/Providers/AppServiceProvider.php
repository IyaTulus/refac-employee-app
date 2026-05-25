<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use jeemce\models\File;

class AppServiceProvider extends ServiceProvider
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
        $vendorThemeViews = base_path('vendor/jeemce/laravel-theme-admin-v5/views');
        if (is_dir($vendorThemeViews)) {
            View::getFinder()->prependLocation($vendorThemeViews);
        }

        File::creating(function (File $file) {
            if (!empty($file->size)) {
                return;
            }

            $uploadedFile = Request::file('upload_' . $file->parent_field);

            if ($uploadedFile) {
                $file->size = (string) $uploadedFile->getSize();
                return;
            }

            $file->size = '0';
        });
    }
}
