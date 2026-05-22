<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
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
