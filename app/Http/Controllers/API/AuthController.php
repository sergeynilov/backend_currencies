<?php

namespace App\Http\Controllers\API;

use App\library\CheckValueType;
use App\Models\Settings;
use Auth;
use Config;
use DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;

//use App\Http\Resources\UserCollection;
//use App\Notifications\SignupActivate;
use Storage;

class AuthController extends Controller
{

    protected $maxAttempts = 1; // Default is 5
    protected $decayMinutes = 1; // Default is 1

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'register', 'activate']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

//        sleep(3);
//        \Log::info('LOGIN +1 $credentials ::');
//        \Log::info(print_r($credentials, true));

        if ($token = $this->guard('api')->attempt($credentials /*,['exp' => \Carbon\Carbon::now()->addHours(4)->timestamp]*/)) {
            $loggedUser = $this->guard('api')->user();

//            \Log::info('LOGIN +2 $loggedUser ::');
//            \Log::info($loggedUser);
//
//            \Log::info('LOGIN +3 ::');
//            \Log::info('$ $token');
//            \Log::info(print_r($token, true));

            return $this->respondWithToken($token);
        }

//        \Log::info('LOGIN -3 ::');

        return response()->json(['error' => 'Unauthorized'], HTTP_RESPONSE_UNAUTHORIZED);
    }


    public function logout_()
    {
        $this->guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    protected function respondWithToken($token)
    {
        $loggedUser = $this->guard()->user();

        return response()->json([
            'access_token'     => $token,
            'user'             => $loggedUser,
            'token_type'       => 'bearer',
            'expires_in'       => $this->guard('api')->factory()->getTTL() * 999360 // TOFIX
        ]);
    }

    public function guard()
    {
        return \Auth::Guard('api');
    }

    public function register(Request $request)
    {
        $request     = request();
        $requestData = $request->all();
//        \Log::info('00 register $requestData::');
//        \Log::info(print_r($requestData, true));


        $validator = Validator::make($requestData, [
            'name'                  => 'required|unique:users,name',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], HTTP_RESPONSE_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            $app_url = $requestData['app_url'];

            $newUser           = new User();
            $newUser->name     = $requestData['name'];
            $newUser->password = bcrypt($requestData['password']);
            $newUser->email    = $requestData['email'];

            $token = Str::random(60);

            // status_on_registration

//            $registerWithoutActivation = Config::get('app.REGISTER_WITHOUT_ACTIVATION');
//            \Log::info('$registerWithoutActivation ::');
//            \Log::info(print_r($registerWithoutActivation, true));
//

            $status_on_registration = Settings::getValue('status_on_registration', CheckValueType::cvtString, 'N');

            if ($status_on_registration == 'A') { // 'Active - Can login at once '
                $newUser->status = 'A'; //
            } else {
                $newUser->status           = 'N'; //  New - Waiting activation
                $newUser->activation_token = $token;
            }
            $newUser->save();



            $site_name = Settings::getValue('site_name', CheckValueType::cvtString, '');

            $avatar_dest_image_path = 'public/' . User::getUserAvatarPath($newUser->id, $newUser->avatar);

            $avatar = Avatar::create($newUser->name)->getImageObject()->encode('png');
            Storage::put($avatar_dest_image_path . '/avatar' . '.png', (string)$avatar);

            DB::commit();
            if ($status_on_registration != 'A') { // NOT Active ( Can login at once )
                $newUser->notify(new SignupActivate($newUser, $site_name, $app_url));
            }

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'newUser' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['success' => ['user' => $newUser]], HTTP_RESPONSE_OK_RESOURCE_CREATED);
    }


    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if ( ! $user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], HTTP_RESPONSE_NOT_FOUND);
        }
        $user->status           = 'A';
        $user->activation_token = '';
        $user->updated_at       = Carbon::now(config('app.timezone'));
        $user->save();

        return new $user;
    } // public function activate($token)


    // USER AVATAR BLOCK START
    public function upload_user(Request $request)
    {
        $requestData = $request->all();

        $user = User::find($requestData['id']);
        if ($user === null) {
            return response()->json([
                'message' => 'user # "' . $requestData['id'] . '" not found!',
                'user'    => null
            ], HTTP_RESPONSE_NOT_FOUND);
        }

        $userUserUploadedFile = $request->file('user');

        $uploaded_file_max_mib = (float)\Config::get('app.uploaded_file_max_mib', 1);
        $max_size              = 1024 * $uploaded_file_max_mib;
        $rules                 = array(
            'user' => 'max:' . $max_size,
        );
        $validator             = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Size of uploaded file is bigger permitted ' . getNiceFileSize((1024 * $max_size)),
            ], HTTP_RESPONSE_BAD_REQUEST);
        }

        $usersExtensions = config('app.users_extensions');

        $user_user = checkValidImgName
        ($requestData['user_filename'], with(new User)->getUserFilenameMaxLength(), true);

        $filename_extension = getFilenameExtension($user_user);
        if ( ! in_array($filename_extension, $usersExtensions)) {
            return response()->json([
                'message' => 'Extension ' . $filename_extension . ' is not permitted !',
            ], HTTP_RESPONSE_BAD_REQUEST);
        }

        try {
            DB::beginTransaction();

            if ( ! empty($userUserUploadedFile)) {
                $user_file_path   = $userUserUploadedFile->getPathName();
                $user->user       = $user_user;
                $user->updated_at = Carbon::now(config('app.timezone'));
            }

            $user->save();

            if ( ! empty($user_user)) {
                $dest_user = 'public/' . User::getUserUserPath($user->id, $user_user);
                Storage::disk('local')->put($dest_user, File::get($user_file_path));
                UserOptimizer::optimize(storage_path() . '/app/' . $dest_user, null);
            } // if ( !empty($user_user) ) {

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage(), 'user' => null],
                HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }
        $filenameData = User::setUserUserProps($user->id, $user->user, true);
        if ( ! empty($filenameData)) {
            $user->filenameData = $filenameData;
        }

        return response()->json(['message' => '', 'user' => $user], HTTP_RESPONSE_OK);
    } // public function upload_user(CreateCustomerRequest $request)

    public function delete_user($user_id)
    {
        $user = User::find($user_id);
        if ($user === null) {
            return response()->json(['message' => 'User # "' . $user_id . '" not found!'],
                HTTP_RESPONSE_NOT_FOUND);
        }

        try {
            DB::beginTransaction();
            $user->user       = null;
            $user->updated_at = Carbon::now(config('app.timezone'));
            $user->save();

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], HTTP_RESPONSE_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, HTTP_RESPONSE_OK_RESOURCE_DELETED);
    }

    // USER AVATAR BLOCK END

}
