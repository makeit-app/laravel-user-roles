<?php

namespace MakeIT\UserRoles;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class UserRolesServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class => RolePolicy::class,
    ];

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
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'user_roles');
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
                __DIR__ . '/../config/config.php' => config_path('user_roles.php'),
                __DIR__ . '/../resources/lang' => lang_path('vendor/user_roles'),
                __DIR__ . '/../database/migrations/2022_05_02_123129_create_roles_table.php' => base_path('database/migrations/2022_05_02_123129_create_roles_table.php'),

            ], 'user-roles-install');
            $this->publishes([
                __DIR__ . '/NovaRoles.php' => app_path('/Nova/NovaRoles.php'),
            ], 'user-roles-nova');
        }
        $this->registerPolicies();
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'user_roles');
        $this->loadJsonTranslationsFrom(lang_path('vendor/user_roles'));
    }

}
