<?php

namespace App\Http\Controllers\API\Admin;

use Auth;
use Carbon\Carbon;
use DB;
use Validator;

use App\library\CheckValueType;
use App\Models\Settings;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;


class CurrencyController extends Controller
{

    private $requestData;

    public function __construct()
    {
        $request           = request();
        $this->requestData = $request->all();
    }

    // currency CRUD BLOCK START
    public function filter()
    {
        $backendItemsPerPage = Settings::getValue('backend_items_per_page', CheckValueType::cvtInteger, 20);

        $this->page    = !empty($this->requestData['page']) ? $this->requestData['page'] : '';
        $this->filterIsTop     = !empty($this->requestData['filterIsTop']) ? $this->requestData['filterIsTop'] : '';

        $this->filterActive     = isset($this->requestData['filterActive']) ? $this->requestData['filterActive'] : '';

        $this->filterName     = isset($this->requestData['filterName']) ? $this->requestData['filterName'] : '';

        $this->orderBy        = !empty($this->requestData['orderBy']) ? $this->requestData['orderBy'] : 'ordering';
        $this->orderDirection = !empty($this->requestData['orderDirection']) ? $this->requestData['orderDirection'] : 'desc';

        $currencies = Currency
            ::getByIsTop($this->filterIsTop)
            ->withCount('currencyHistories')
            ->getByName($this->filterName)
            ->getByActive($this->filterActive)
            ->orderBy($this->orderBy, $this->orderDirection)
            ->paginate($backendItemsPerPage, null, null, $this->page)
            ->onEachSide($backendItemsPerPage / 2);

        return $currencies;
    } // public function filter()


    public function store(CurrencyRequest $request)
    {
        $currency =  new Currency;

        $currency->name = $this->requestData['name'];
        $currency->num_code = $this->requestData['num_code'];
        $currency->char_code = $this->requestData['char_code'];
        $currency->is_top = $this->requestData['is_top'];
        $currency->active = $this->requestData['active'];
        $currency->updated_at  = Carbon::now(config('app.timezone'));

        if (empty($currency->ordering)) {
            $currency->ordering= Currency::max('ordering') + 1;
        }

        $currencies = Currency
            ::orderBy('id', 'asc')
            ->get();

        try {
            DB::beginTransaction();

            foreach ($currencies as $nextCurrency) {
                if($nextCurrency->ordering >= $currency->ordering) {
                    $nextCurrency->ordering= $nextCurrency->ordering + 1;
                    $nextCurrency->save();
                }
            }

            $currency->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(), 'currency' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        $currency = Currency::find($currency->id);
        return $currency;
    }


    public function show($id)
    {
        $currency = Currency
            ::getById($id)
            ->withCount('currencyHistories')
            ->first();

        if ($currency === null) {
            return response()->json([
                'message'    => 'Currency # "' . $id . '" not found!',
                'currency'       => null
            ], HTTP_RESPONSE_NOT_FOUND);
        }
//        $currency->name = '';
//        $currency->num_code = '';
//        $currency->ordering = '';
//        $currency->active = '';
//        \Log::info(  varDump($currency, ' -3 CurrencyController show $currency::') );
        return $currency;
    }

    public function update(CurrencyRequest $request, $id)
    {
        $currency = Currency::find($request->id);
        if ($currency === null) {
            return response()->json([
                'message'    => 'Currency # "' . $id . '" not found!',
                'currency'       => null
            ], HTTP_RESPONSE_NOT_FOUND);
        }

        $currency->name = $this->requestData['name'];
        $currency->num_code = $this->requestData['num_code'];
        $currency->char_code = $this->requestData['char_code'];
        $currency->is_top = $this->requestData['is_top'];
        $currency->active = $this->requestData['active'];
        $currency->updated_at  = Carbon::now(config('app.timezone'));
        try {
            DB::beginTransaction();
            $currency->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'currency' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return $currency;
    }

    public function destroy($currencyId)
    {
        $currency = Currency::find($currencyId);
        if ($currency === null) {
            return response()->json([
                'message'    => 'Currency # "' . $currencyId . '" not found!',
                'currency'       => null
            ], HTTP_RESPONSE_NOT_FOUND);
        }

        try {
            DB::beginTransaction();
            $currency->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'currency' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, HTTP_RESPONSE_OK_RESOURCE_DELETED);
    }

    // currency CRUD BLOCK END

}
