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
        $backendItemsPerPage = Settings::getValue('backend_items_per_page', CheckValueType::cvtInteger, 20);

        $this->page    = !empty($this->requestData['page']) ? $this->requestData['page'] : '';
        $this->filterName     = !empty($this->requestData['filterName']) ? $this->requestData['filterName'] : '';

        $this->filterPublished     = isset($this->requestData['filterPublished']) ? $this->requestData['filterPublished'] : '';

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

        if ($laravelLoggerActivity === null) {
            return response()->json([
                'message'    => 'Import log # "' . $id . '" not found!',
                'laravelLoggerActivity'       => null
            ], HTTP_RESPONSE_NOT_FOUND);
        }

        return $laravelLoggerActivity;
    }

    public function destroy($laravelLoggerActivityId)
    {
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
