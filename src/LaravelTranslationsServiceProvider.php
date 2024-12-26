<?php

namespace Vershub\LaravelTranslations;

use Illuminate\Support\ServiceProvider;

class LaravelTranslationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/laraveltranslations.php' => config_path('laraveltranslations.php'),
        ]);
    }
}