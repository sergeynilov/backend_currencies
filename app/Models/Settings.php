<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DB;
use App\library\CheckValueType;


class Settings extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'value',
        'updated_at',
    ];

    public static function scopeGetByName($query, $name= '')
    {
        if(!empty($name)) {
            return $query->where(with(new Settings)->getTable() . '.name', '=', $name);
        }
        return $query;
    }

    public static function getSettingsList($name= '')
    {
        $settingsValuesList = Settings
            ::orderBy('id', 'asc')
            ->getByName($name)
            ->select('id','name','value')
            ->get();

        return $settingsValuesList;
    }

    public static function getValue($name, int $checkValueType = null, $default_value = null)
    {
        $settingsValue = Settings::getByName($name)->first();
//        \Log::info(  varDump($settingsValue, ' -1getValue $settingsValue::') );
        if (empty($settingsValue->value)) {
            return $default_value;
        }

        if ($checkValueType == CheckValueType::cvtInteger and ! isValidInteger($settingsValue->value) and ! empty
            ($default_value)) {
            return $default_value;
        }
        if ($checkValueType == CheckValueType::cvtFloat and ! isValidFloat($settingsValue->value) and ! empty($default_value)) {
            return $default_value;
        }
        if ($checkValueType == CheckValueType::cvtBool and ! isValidBool($settingsValue->value) and ! empty($default_value)) {
            return $default_value;
        }

        return $settingsValue->value;
    }


    public static function getValidationRulesArray(): array
    {
        $returnValidationRules = [
            'items_per_page'         => 'required|integer',
            'backend_items_per_page' => 'required|integer',
            'base_currency'          => 'required',
        ];

        return $returnValidationRules;
    }

}
