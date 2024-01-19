<?php

namespace MakeIT\UserRoles;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class UserRolesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('role', function () {
            return new Role();
        });
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'user_roles');
        Event::listen(Registered::class, UserAddDefaultRoleListener::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'user_roles');
        $this->loadJsonTranslationsFrom(lang_path('vendor/user_roles'));
        //
        $this->configurePublishing();
        $this->configureObservers();
        $this->configurePolicies();
    }

    /**
     * Configures a poblishes
     */
    protected function configurePublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AssignUserRoleCommand::class,
            ]);
            $this->publishes([
                __DIR__ . '/config.php' => config_path('user_roles.php'),
                __DIR__ . '/../database/migrations' => base_path('database/migrations'),
                __DIR__ . '/../resources/lang' => lang_path('vendor/user_roles'),
                __DIR__ . '/../stubs/User.php' => app_path('/Models/User.php'),
                __DIR__ . '/../stubs/UserObserver.php' => app_path('/Observers/UserObserver.php'),
                __DIR__ . '/../stubs/UserPolicy.php' => app_path('/Policies/UserPolicy.php'),
            ], 'install');
            $this->publishes([__DIR__ . '/../config.php' => config_path('user_roles.php')], 'config');
            $this->publishes([__DIR__ . '/../database/migrations' => base_path('database/migrations')], 'migrations');
            $this->publishes([__DIR__ . '/../resources/lang' => lang_path('vendor/user_roles')], 'lang');
            $this->publishes([
                __DIR__ . '/../stubs/User.php' => app_path('/Models/User.php'),
                __DIR__ . '/../stubs/UserObserver.php' => app_path('/Observers/UserObserver.php'),
                __DIR__ . '/../stubs/UserPolicy.php' => app_path('/Policies/UserPolicy.php'),
            ], 'user-model');
            // for Laravel Nova
            $this->publishes([__DIR__ . '/NovaRoles.php' => app_path('/Nova/NovaRoles.php')], 'nova');
        }
    }

    /**
     * Configure Policies
     */
    protected function configurePolicies(): void
    {
        if (config('user_roles.policiesToRegister', [])) {
            $this->policies = config('user_roles.policiesToRegister');
            $this->registerPolicies();
        }
    }

    /**
     * Configure Observers
     */
    protected function configureObservers(): void
    {
        foreach (config('user_roles.observersToRegister') as $Model => $Observer) {
            if (class_exists($Model) && class_exists($Observer)) {
                /** @noinspection PhpUndefinedMethodInspection */
                $Model::observe($Observer);
            }
        }
    }
}
