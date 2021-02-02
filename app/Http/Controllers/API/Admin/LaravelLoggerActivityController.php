<?php

namespace App\Http\Controllers\API\Admin;

use Auth;
use DB;

use App\library\CheckValueType;
use App\Models\Settings;
use App\Models\Activity;
use App\Http\Controllers\Controller;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
//use vendor/jeremykenedy/laravel-logger/src/App/Models/Activity.php;
// file:///_wwwroot/lar/backend_currencies/vendor/jeremykenedy/laravel-logger/src/App/Models/Activity.php

// use App\Models\Permission;
// use App\Http\Resources\Ad as AdResource;

class LaravelLoggerActivityController extends Controller
{

    use ActivityLogger;
    private $requestData;
    protected $softDelete = true;

    public function __construct()
    {
        $request           = request();
        $this->requestData = $request->all();
    }

    public function filter()
    {
        \Log::info( '-1 LaravelLoggerActivityController.index ::' . print_r( -1, true  ) );

        $backendItemsPerPage = 2;// Settings::getValue('backend_items_per_page', CheckValueType::cvtInteger, 20);
        \Log::info(  varDump($backendItemsPerPage, ' -1 $backendItemsPerPage::') );

        $this->page    = !empty($this->requestData['page']) ? $this->requestData['page'] : '';
        \Log::info(  varDump($this->page, ' -2 $this->page::') );
        $this->filterName     = !empty($this->requestData['filterName']) ? $this->requestData['filterName'] : '';
//        \Log::info(  varDump($this->filterName, ' -1 $this->filterName::') );

        $this->filterPublished     = isset($this->requestData['filterPublished']) ? $this->requestData['filterPublished'] : '';
//        \Log::info(  varDump($this->filterPublished, ' -1 $this->filterPublished::') );

        $this->orderBy        = !empty($this->requestData['orderBy']) ? $this->requestData['orderBy'] : 'ordering';
        $this->orderDirection = !empty($this->requestData['orderDirection']) ? $this->requestData['orderDirection'] : 'asc';
        $ImportLogs = Activity
            ::select('*')
            ->orderBy('created_at', 'asc')
            ->paginate($backendItemsPerPage, null, null, $this->page)
            ->onEachSide($backendItemsPerPage / 2);
        return $ImportLogs;
    } // public function index()




    public function show($id)
    {
        $laravelLoggerActivity = Activity
            ::getById($id)
            ->with('creator')
            ->first();

        \Log::info(  varDump($laravelLoggerActivity, ' -1 LaravelLoggerActivityController show $laravelLoggerActivity::') );
        if ($laravelLoggerActivity === null) {
            return response()->json([
                'message'    => 'Import log # "' . $id . '" not found!',
                'laravelLoggerActivity'       => null
            ], HTTP_RESPONSE_NOT_FOUND);
        }

        \Log::info(  varDump($laravelLoggerActivity, ' -3 LaravelLoggerActivityController show $laravelLoggerActivity::') );
        return $laravelLoggerActivity;
    }

    public function destroy($laravelLoggerActivityId)
    {
        \Log::info(  varDump($laravelLoggerActivityId, ' -1 $laravelLoggerActivityId::') );
        $laravelLoggerActivity = Activity::find($laravelLoggerActivityId);
        if ($laravelLoggerActivity === null) {
            return response()->json([
                'message'    => 'Import log # "' . $laravelLoggerActivityId . '" not found!',
                'laravelLoggerActivity'       => null
            ], HTTP_RESPONSE_NOT_FOUND);
        }

        try {
            DB::beginTransaction();
            $laravelLoggerActivity->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'laravelLoggerActivity' => null], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, HTTP_RESPONSE_OK_RESOURCE_DELETED);
    }

}
