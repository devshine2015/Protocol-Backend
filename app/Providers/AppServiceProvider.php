<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Adaojunior\Passport\SocialUserResolverInterface;
use App\Helpers\SocialUserResolver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Way\Generators\GeneratorsServiceProvider::class);
            $this->app->register(\Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
        }

        $this->app->singleton(SocialUserResolverInterface::class, SocialUserResolver::class);
    }
}
