<?php

namespace App\Http\Controllers\API\Admin;

use Auth;
use Carbon\Carbon;
use DB;
use Validator;

use App\Models\Settings;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsRequest;
use App\Models\CurrencyHistory;
use App\Models\Activity;

class SettingsController extends Controller
{

    private $requestData;

    public function __construct()
    {
        $request           = request();
        $this->requestData = $request->all();
    }

    public function index()
    {
        $settings = Settings
            ::select('*')
            ->get()
            ->pluck('value', 'name')
            ->toArray();
        return $settings;
    } // public function index()


    public function clear_rates_history()
    {
        try {
            DB::beginTransaction();

            Activity::truncate();
            CurrencyHistory::truncate();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'settings' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
    }

    public function run_currency_rates_import_manually()
    {
        \Log::info( '-1 run_currency_rates_import_manually ::' . print_r( -9, true  ) );

        $exitCode = \Artisan::call('command:currencyRatesImport', []);
        return response()->json(['error_code' => 1, 'message' => "", 'exitCode' => $exitCode], HTTP_RESPONSE_OK);

    }

    public function update(SettingsRequest $request)
    {
        try {
            DB::beginTransaction();
            foreach( $this->requestData as $nextSettingsKey => $nextSettingsValue ) {
                $nextSettings = Settings::getSettingsList($nextSettingsKey);

                $nextSettingsToUpdate = $nextSettings[0] ?? null;
                if( empty($nextSettingsToUpdate) ) {
                    $nextSettingsToUpdate             = new Settings;
                    $nextSettingsToUpdate->name       = $nextSettingsKey;
                } else {
                    $nextSettingsToUpdate->updated_at = Carbon::now(config('app.timezone'));
                }
                $nextSettingsToUpdate->value          = $nextSettingsValue;
                $nextSettingsToUpdate->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'settings' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

    }

}
