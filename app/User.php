<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'second_name', 'email', 'password', 'phone', 'uuid',
        'all_price', 'price_tax_status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users')
            ->withTimestamps();
    }

    public function getFullName()
    {
        return $this->last_name .' '. $this->first_name .' '. $this->second_name;
    }

    /**
     * Checks if User has access to $permissions.
     */
    public function hasAccess(array $permissions) : bool
    {
        // check if the permission is available in any role
        foreach ($this->roles as $role) {
            if($role->hasAccess($permissions)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if the user belongs to role.
     */
    public function inRole(string $roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->count() == 1;
    }

    public function getUserListByRole(string $role){

    }



    public function clients()
    {
        return $this->hasMany('App\User','manager');
    }


    public function manager()
    {
        return $this->belongsTo('App\User','manager');
    }

    public function factPrice()
    {
        return $this->hasMany('App\FacticalPrice','user_id', 'id');
    }
}
