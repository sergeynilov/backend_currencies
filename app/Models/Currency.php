<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Currency extends Model
{
    protected $table      = 'currency';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'name', 'num_code' , 'char_code' , 'is_top', 'active', 'ordering', 'updated_at'  ];

    public function latestCurrencyHistory()
    {
        return $this->hasOne('App\Models\CurrencyHistory')->latest();
    }

    public function currencyHistories()
    {
        return $this->hasMany('App\Models\CurrencyHistory', 'currency_id', 'id');
    }

    public function scopeGetById($query, int $id= null)
    {
        if (!empty($id)) {
            if ( is_array($id) ) {
                $query->whereIn(with(new Currency)->getTable().'.id', $id);
            } else {
                $query->where(with(new Currency)->getTable().'.id', $id);
            }
        }
        return $query;
    }

    public function scopeGetByName($query, $name = null)
    {
        if (empty($name)) {
            return $query;
        }
        return $query->where( with(new Currency)->getTable() . '.name', 'like', '%'.$name.'%');
    }



    public function scopeGetByIsTop($query, $is_top = null)
    {
        if (!isset($is_top) or strlen($is_top) == 0) {
            return $query;
        }
        return $query->where( 'is_top', $is_top );
    }

    public function scopeGetByActive($query, $active = null)
    {
        if (!isset($active) or strlen($active) == 0) {
            return $query;
        }
        return $query->where( 'active', $active );
    }

    public function scopeGetByNumCode($query, $numCode = null)
    {
        if (empty($numCode)) {
            return $query;
        }
        return $query->where(with(new Currency)->getTable() . '.num_code', $numCode);
    }


    public function scopeExcludeCharCode($query, $charCode = null)
    {
        if (empty($charCode)) {
            return $query;
        }
        return $query->where(with(new Currency)->getTable() . '.char_code', '!=' , $charCode);
    }

    public function scopeGetByCharCode($query, $charCode = null)
    {
        if (empty($charCode)) {
            return $query;
        }
        return $query->where(with(new Currency)->getTable() . '.char_code', $charCode);
    }


    public static function getCurrencyValidationRulesArray($currency_id = null, array $skipFieldsArray= []): array
    {
        $validationRulesArray = [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique(with(new Currency)->getTable())->ignore($currency_id),
            ],
            'num_code' => [
                'required',
                'string',
                'max:3',
                Rule::unique(with(new Currency)->getTable())->ignore($currency_id),
            ],
            'char_code' => [
                'required',
                'string',
                'max:3',
                Rule::unique(with(new Currency)->getTable())->ignore($currency_id),
            ],
            'is_top'     => 'required',
            'active'     => 'required',
            'ordering'      => 'integer|required',

        ];
        foreach( $skipFieldsArray as $next_field ) {
            if(!empty($validationRulesArray[$next_field])) {
                unset($validationRulesArray[$next_field]);
            }
        }
        return $validationRulesArray;
    }


}
