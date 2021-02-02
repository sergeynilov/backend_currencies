<?php

namespace App\Models;

use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable  implements JWTSubject
{
    use Notifiable;

    protected static $password_length = 8;

    protected $fillable = [ 'name', 'email' ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function scopeGetById($query, int $id= null)
    {
        if (!empty($id)) {
            if ( is_array($id) ) {
                $query->whereIn(with(new User)->getTable().'.id', $id);
            } else {
                $query->where(with(new User)->getTable().'.id', $id);
            }
        }
        return $query;
    }

    public static function getUserValidationRulesArray($user_id= null, array $skipFieldsArray= []) : array
    {
        $validationRulesArray = [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique(with(new User)->getTable())->ignore($user_id),
            ],

            'email' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new User)->getTable())->ignore($user_id),
            ],
            'password'        => 'required|min:6|max:15',
            'password_confirmation'      => 'required|min:6|max:15|same:password',
        ];

        foreach( $skipFieldsArray as $next_field ) {
            if(!empty($validationRulesArray[$next_field])) {
                unset($validationRulesArray[$next_field]);
            }
        }
        return $validationRulesArray;
    } // public static function getValidationRulesArray($user_id) : array

    public static function generatePassword()	{
        return Str::random(self::$password_length);
    }


}
