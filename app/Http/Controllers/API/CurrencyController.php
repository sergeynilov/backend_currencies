<?php

namespace App\Http\Controllers\API;

use Auth;
use DB;
use App\Http\Controllers\Controller;
use App\Models\User;

use App\Models\Settings;
use App\Models\Currency;
use App\Models\CurrencyHistory;
use App\library\CheckValueType;

class CurrencyController extends Controller
{
    private $requestData;

    public function __construct()
    {
        $request           = request();
        $this->requestData = $request->all();
    }


    public function top_currencies() // show pages listing of currencies
    {
        $show_only_top_currencies = $this->requestData['show_only_top_currencies'] ?? false;
        $retArray                 = ['message' => ''];
        $currenciesCount          = Currency::getByActive(true)->count();
        $base_currency          = Settings::getValue('base_currency', CheckValueType::cvtString, '');

        $baseCurrency = Currency
            ::getByCharCode($base_currency)
            ->excludeCharCode($baseCurrency->char_code ?? '')
            ->first();

        $currencies        = Currency
            ::getByActive(true)
            ->getByIsTop($show_only_top_currencies)
            ->excludeCharCode($baseCurrency->char_code ?? '')
            ->withCount('currencyHistories')
            ->with('latestCurrencyHistory')
            ->orderBy('ordering', 'asc')
            ->get();

        $retArray['currencies']        = $currencies;
        $retArray['currenciesCount']       = $currenciesCount;

        return response()->json($retArray, HTTP_RESPONSE_OK);
    } // public function top_currencies($page = null)

    public function get_currency_history($currencyId)
    {
        $retArray = ['message' => ''];
        $currencyHistories     = CurrencyHistory
            ::getByCurrencyId($currencyId) // scopeGetByCurrencyId
            ->orderBy('day', 'desc')
            ->get();
        $retArray['currencyHistories']        = $currencyHistories;

        return response()->json($retArray, HTTP_RESPONSE_OK);
    } // public function get_currency_history($page = null)

}
