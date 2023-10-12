# Laravel User Roles

A package with role subsystem.

### Installation

`composer require make-it-app/laravel-user-roles:dev-main`

### Publish localization and config files
`php artisan vendor:publish --provider="MakeIT\\UserRoles\\UserRolesServiceProvider" --tag="user-roles-install"`

### Edit Migration file first!

**Migration file implies that your users table has UUID as primary key, not BigInt !**<br>

### Check/Edit migration file !

### Migrate

`php artisan migrate`

### If You are using Laravel Nova

`php artisan vendor:publish --provider="MakeIT\\UserRoles\\UserRolesServiceProvider" --tag="user-roles-nova"`

Add BelongsToMany Field to `\App\Nova\User.php` file<br>
`BelongsToMany::make( __( 'Roles' ), 'roles', NovaRoles::class ),`<br>
<br>
You are free to rename published `NovaRoles.php` file.

### User Model Modifications

```php
<?php

namespace App\Models;

use MakeIT\UserRoles\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    //....
```

### Add Roles

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
