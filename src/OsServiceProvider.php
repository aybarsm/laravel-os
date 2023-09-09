<?php

namespace Aybarsm\Laravel\Os;

use Aybarsm\Laravel\Os\Contracts\OsInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class OsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/os.php',
            'os'
        );

        $this->publishes([
            __DIR__.'/../config/os.php' => config_path('os.php'),
        ], 'os');

        if (is_null($concrete = $this->getConcrete())){
            return;
        }

        $this->app->singleton(OsInterface::class, function ($app) use ($concrete) {
            return new $concrete();
        });

//        $this->app->singleton(OsInterface::class, function ($app) {
//            $concrete = $this->getConcrete();
//            return new $concrete();
//        });

        $this->app->alias(OsInterface::class, 'os');
    }

    protected function getConcrete(): ?string
    {
        $concrete = sconfig('os.concretes.family.'.Str::lower(PHP_OS_FAMILY));
        return ! is_null($concrete) && class_exists($concrete) ? $concrete : null;
    }

    public function provides(): array
    {
        return [
            OsInterface::class, 'os',
        ];
    }
}
