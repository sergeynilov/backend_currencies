<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\Admin\CurrencyController as AdminCurrencyController;
use App\Http\Controllers\API\Admin\LaravelLoggerActivityController;
use App\Http\Controllers\API\Admin\SettingsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',  [AuthController::class, 'login']);

//Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::post('logout', 'API\AuthController@logout');
Route::post('refresh', 'API\AuthController@refresh');
Route::post('me', 'API\AuthController@me');
Route::get('activate/{token}', 'API\AuthController@activate');

Route::get('test', 'API\HomeController@test');
Route::post('app_settings', [HomeController::class, 'app_settings'] /*'API\HomeController@app_settings'*/);

Route::post('top_currencies', [CurrencyController::class, 'top_currencies'] );
Route::get('get_currency_history/{currencyId}', [CurrencyController::class, 'get_currency_history']);
//                 axios.get(apiUrl + '/get_currency_history/' + currencyId, {}, credentials)

Route::group(['middleware' => 'jwt.auth',  'prefix' => 'admin', 'as' => 'admin.'], function ($router) {

    Route::post('currencies-filter', [AdminCurrencyController::class, 'filter']);
    Route::resource('currencies', AdminCurrencyController::class);

    Route::get('get-settings', [SettingsController::class, 'index']);
    Route::put('update-settings', [SettingsController::class, 'update']);
    Route::get('clear_rates_history', [SettingsController::class, 'clear_rates_history']);
    Route::get('run_currency_rates_import_manually', [SettingsController::class, 'run_currency_rates_import_manually']);

    /* use App\Http\Controllers\Admin\PersonalAccessTokenController;


    Route::group(['prefix' => 'users'], function ($router) {

        Route::resource('/{user_id}/user_permissions', UserPermissionController::class);
        Route::resource('/{user_id}/personal_access_tokens', PersonalAccessTokenController::class);
    }); // Route::group(['prefix' => 'users'], function ($router) {

 */

    // CATEGORIES CRUD OPERATIONS BLOCK START
//    Route::post('laravel-logger-activities', [LaravelLoggerActivityController::class, 'index']);
    //                 axios.post(apiUrl + '/admin/laravel-logger-activities-filter', filters, credentials)

    Route::post('laravel-logger-activities-filter', [LaravelLoggerActivityController::class, 'filter']);
    Route::resource('laravel-logger-activities', LaravelLoggerActivityController::class);
    /* VM5394:1 DELETE http://local-backend-currencies.com/api/admin/laravel-logger-activities/5  */
//    Route::resource('laravel-logger-activities', 'API\Admin\LaravelLoggerActivityController');

    // CATEGORIES CRUD OPERATIONS BLOCK END

}); // Route::group(['middleware' => 'jwt.auth',  'prefix' => 'admin', 'as' => 'admin.'], function ($router) {


//http://local-backend-currencies.com/api/top_currencies

/*
 *
php artisan config:cache
php artisan route:cache

 */

