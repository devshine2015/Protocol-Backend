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
        include (app_path("helper.php"));
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
        $this->app->bind('App\Interfaces\SendEmailInterface', 'App\Repository\SendEmailRepository');
        $this->app->bind('App\Interfaces\ShareInterface', 'App\Repository\ShareRepository');
        $this->app->singleton(SocialUserResolverInterface::class, SocialUserResolver::class);
    }
}
