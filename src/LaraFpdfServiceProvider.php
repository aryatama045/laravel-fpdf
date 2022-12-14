<?php

namespace Aryatama045\LaraFpdf;

use Illuminate\Support\ServiceProvider;
use Aryatama045\LaraFpdf\LaraFpdf;

class LaraFpdfServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('lara-fpdf.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'lara-fpdf');
        $this->app->singleton(LaraFpdf::class, function ($app) {
            $config = $app['config'];

            $pdf = new LaraFpdf(
                $config->get('lara-fpdf.orientation'),
                $config->get('lara-fpdf.unit'),
                $config->get('lara-fpdf.size')
            );

            $pdf->SetFont($config->get('lara-fpdf.font-family'), $config->get('lara-fpdf.font-style'), $config->get('lara-fpdf.font-size'));
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetAuthor($config->get('lara-fpdf.author'), true);

            return $pdf;
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [LaraFpdf::class];
    }
}
