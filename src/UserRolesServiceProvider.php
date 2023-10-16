<?php

namespace MakeIT\UserRoles;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;

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
            $this->publishes([__DIR__ . '/../configonfig.php' => config_path('user_roles.php')], 'config');
            $this->publishes([__DIR__ . '/../database/migrations' => base_path('database/migrations')], 'migrations');
            $this->publishes([__DIR__ . '/../resources/lang' => lang_path('vendor/user_roles')], 'lang');
            $this->publishes([__DIR__ . '/NovaRoles.php' => app_path('/Nova/NovaRoles.php')], 'nova');
            $this->publishes([
                __DIR__ . '/../stubs/User.php' => app_path('/Models/User.php'),
                __DIR__ . '/../stubs/UserObserver.php' => app_path('/Observers/UserObserver.php'),
                __DIR__ . '/../stubs/UserPolicy.php' => app_path('/Policies/UserPolicy.php'),
            ], 'user-model');
        }
        $this->registerPolicies();
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'user_roles');
        $this->loadJsonTranslationsFrom(lang_path('vendor/user_roles'));
        /**
         * Observer
         */
        if (is_file(app_path('/Models/User.php')) && is_file(app_path('/Observers/UserObserver.php'))) {
            // priority path
            \App\Models\User::observe(\App\Observers\UserObserver::class);
        } else {
            \MakeIT\UserRoles\User::observe(\MakeIT\UserRoles\UserObserver::class);
        }
        /**
         * Policy
         */
        Gate::policy(Role::class, RolePolicy::class);
        if (is_file(app_path('/Policies/UserPolicy.php'))) {
            Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
        } else {
            Gate::policy(\MakeIT\UserRoles\User::class, \MakeIT\UserRoles\UserPolicy::class);
        }
    }

}
