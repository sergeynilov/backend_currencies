<?php

namespace App\Http\Controllers\API;

use Auth;
use DB;
use App\Http\Controllers\Controller;
use App\Models\User;

use App\Models\Settings;
use App\Models\Currency;
use App\library\CheckValueType;

class HomeController extends Controller
{
    private $requestData;

    public function __construct()
    {
        $request           = request();
        $this->requestData = $request->all();
    }

    public function app_settings()
    {

        $retArray    = ['message' => ''];
//        \Log::info(  varDump($this->requestData, ' -1 app_settings $this->requestData::') );
        foreach ($this->requestData as $nextParamName) {

            $a = preg_split("/requestKey_/", $nextParamName);
            if (count($a) == 2) {
                $retArray['requestKey'] = $a[1];
            }

            if ($nextParamName == 'currencyOrderingLabels') {
                $retArray['currencyOrderingLabels'] = Currency
                    ::orderBy('ordering', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return ['code' => $item->ordering, 'label' => /* "Before '" . $item->ordering . '=>' .  */$item->name /*. "'"*/];
                    })
                    ->all();
                $retArray['currencyOrderingLabels'][]= ['code' => -1, 'label' => ' -Last currency- ' ];
            }

            if ($nextParamName == 'activeCurrenciesList') {
                $data = Currency
                    ::getByActive(true)
                    ->orderBy('ordering', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return ['code' => $item->id, 'label' => $item->name . '( ' . $item->char_code . ' )', 'selected' => false];
                    })
                    ->all();
                $retArray['activeCurrenciesList'] = $data;
            }

            if ($nextParamName == 'baseCurrencyList') {
                $data = Currency
                    ::select('*')
                    ->orderBy('ordering', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return ['code' => $item->char_code, 'label' => $item->name . '( ' . $item->char_code . ' )', 'selected' => false];
                    })
                    ->all();
                $retArray['baseCurrencyList'] = $data;
            }

            if ($nextParamName == 'usersLabels') {
                $retArray['usersLabels'] = User
                    ::get()
                    ->map(function ($item) {
                        return ['code' => $item->id, 'label' => $item->name . ', ' . User::getUserStatusLabel($item->status) . '/' . $item->email];
                    })
                    ->all();
            }

            if ($nextParamName == 'baseCurrencyInfo') {
                $base_currency              = Settings::getValue('base_currency', CheckValueType::cvtString, '');
                $baseCurrency = Currency
                    ::getByCharCode($base_currency)
                    ->first();
                $retArray['base_currency_char_code'] = $baseCurrency->char_code ?? '';
                $retArray['base_currency_name'] = $baseCurrency->name ?? '';
            }

            if ($nextParamName == 'siteName') {
                $siteName              = Settings::getValue('site_name', CheckValueType::cvtString, '');
                $retArray['site_name'] = $siteName;
            }

            if ($nextParamName == 'siteHeading') {
                $siteHeading              = Settings::getValue('site_heading', CheckValueType::cvtString, '');
                $retArray['site_heading'] = $siteHeading;
            }

            if ($nextParamName == 'copyrightText') {
                $siteHeading                = Settings::getValue('copyright_text', CheckValueType::cvtString, '');
                $retArray['copyright_text'] = $siteHeading;
            }

            if ($nextParamName == 'rateDecimalNumbers') {
                $rateDecimalNumbers             = Settings::getValue('rate_decimal_numbers', CheckValueType::cvtInteger, 4);
                $retArray['rateDecimalNumbers'] = (int)$rateDecimalNumbers;
            }

            if ($nextParamName == 'backendItemsPerPage') {
                $backendItemsPerPage             = Settings::getValue('backend_items_per_page', CheckValueType::cvtInteger, 20);
                $retArray['backendItemsPerPage'] = (int)$backendItemsPerPage;
            }


        }
//        \Log::info(  varDump($retArray, ' -90 app_settings $retArray:') );
        return response()->json($retArray, HTTP_RESPONSE_OK);
    }

}
