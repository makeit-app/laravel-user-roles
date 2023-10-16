# Laravel User Roles

A package with role subsystem.

## This package should be installed first from the list of third-party packages.

### Installation

`composer require make-it-app/laravel-user-roles:dev-main`

### Publish localization and config files

- `php artisan vendor:publish --provider="MakeIT\\UserRoles\\UserRolesServiceProvider" --tag="install"` - all necessary files in one command<br>
**OR**<br>
- `php artisan vendor:publish --provider="MakeIT\\UserRoles\\UserRolesServiceProvider" --tag="config"` - if you plan modify config
- `php artisan vendor:publish --provider="MakeIT\\UserRoles\\UserRolesServiceProvider" --tag="migrations" --force` - if you plan to modify migrations
- `php artisan vendor:publish --provider="MakeIT\\UserRoles\\UserRolesServiceProvider" --tag="lang"` - if you plan modyfy localization files
- `php artisan vendor:publish --provider="MakeIT\\UserRoles\\UserRolesServiceProvider" --tag="user-model" --force`<br>User model and observer. be careful! this command will overwrite existing files!

### Edit Migration file first!

**Migration file implies that your `users` table has UUID as primary key, not BigInt !**<br>

### Again. Check/Edit the migration file to match the user model

### If You are using Laravel Nova

`php artisan vendor:publish --provider="MakeIT\\UserRoles\\UserRolesServiceProvider" --tag="nova"`

Add BelongsToMany Field to `\App\Nova\User.php` file<br>
`BelongsToMany::make( __( 'Roles' ), 'roles', NovaRoles::class ),`<br>
<br>
You are free to rename published `NovaRoles.php` file.

### Migrate

`php artisan migrate`

### Added Roles

by default roles added during migration<br>
<br>
| name    | label               | comment | is_protected |
|---------|---------------------|---------|--------------|
| super   | Super Administrator | ?string | true         |
| admin   | Administrator       | ?string | true         |
| support | Support Stuff       | ?string | true         |
| user    | Regular User        | ?string | false        |

Labels is translateable (see `resources/lang` files)<br>
Name must be static as shown in table, because of used at Policy to restrict access non admin-users<br>

### Notes

Package implements an Auto assign a `user` role via event `Illuminate\Auth\Events\Registered` (see Service Provider file)

# License MIT
