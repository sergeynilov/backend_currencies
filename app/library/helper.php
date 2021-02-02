<?php

use App\Settings;
use App\UserGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;
//use ImageOptimizer;



if ( ! function_exists('varDump')) {
    function varDump($var, $descr= '', bool $return_string= true) {
//        \Log::info( '00 varDump $var ::' . print_r( $var, true  ) );
//        \Log::info( '000 varDump gettype($var) ::' . print_r( gettype($var), true  ) );

        if( is_null($var) ) {
            $output_str= 'NULL :' . ( !empty($descr) ? $descr .' : ' : '' ) . 'NULL' ;
            if($return_string) return $output_str;
            \Log::info($output_str );
            return;
        }
        if( is_scalar($var) ) {
            $output_str= 'scalar => ('.gettype($var).') :' . ( !empty($descr) ? $descr .' : ' : '' ) . $var ;
            if($return_string) return $output_str;
            \Log::info($output_str );
            return;
        }
//        \Log::info( -1);
        if( is_array($var) ) {
//            \Log::info( -2);
            $output_str= '[]';
            if( isset($var[0]) ) {
//                \Log::info( -22);
                if (is_subclass_of($var[0], 'Illuminate\Database\Eloquent\Model')) {
//                    \Log::info( -23);
                    $collectionClassBasename = class_basename($var[0]);
                    $output_str = ' Array('.count(collect($var)->toArray()).' of '.$collectionClassBasename.') :' . ( !empty($descr) ? $descr .' : ' : '' ). print_r( collect($var)->toArray(), true);
                } else {
//                    \Log::info( -24);
                    $output_str = 'Array(' . count($var) . ') :' . (! empty($descr) ? $descr . ' : ' : '') . print_r($var,true);
                }
            }
            else {
//                \Log::info( -41);
                $output_str = 'Array(' . count($var) . ') :' . (! empty($descr) ? $descr . ' : ' : '') . print_r($var,true);
            }

//            \Log::info( -3);
            if($return_string) return $output_str;
//            \Log::info($output_str );
            return;
        }

//        \Log::info( -4);
//        \Log::info( '-0 varDump class_basename($var) ::' . print_r( class_basename($var), true  ) );
        if( class_basename($var) === 'Request' or class_basename($var) === 'LoginRequest'  ) {
            $request = request();
            $requestData = $request->all();
            $output_str  = 'Request:' . ( !empty($descr) ? $descr .' : ' : '' ). print_r( $requestData, true);
            if($return_string) return $output_str;
            \Log::info( $output_str  );
            return;
        }

        if( class_basename($var) === 'LengthAwarePaginator' or class_basename($var) === 'Collection' ) {
            $collectionClassBasename= '';
            if( isset($var[0])) {
                $collectionClassBasename= class_basename($var[0]);
            }
            $output_str = ' Collection('.count($var->toArray()).' of '.$collectionClassBasename.') :' . ( !empty($descr) ? $descr .' : ' : '' ). print_r( $var->toArray(), true);
            if($return_string) return $output_str;
            \Log::info( $output_str  );
            return;
        }

        /*        if (!is_subclass_of($model, 'Illuminate\Database\Eloquent\Model')) {
                }*/
        if( gettype($var) === 'object'  ) {
            if (is_subclass_of($var, 'Illuminate\Database\Eloquent\Model')) {
//            if ( get_parent_class($var) == 'Illuminate\Database\Eloquent\Model' ) {
                $output_str = ' (Model Object of '.get_class($var).') :' . ( !empty($descr) ? $descr .' : ' : '' ). print_r( $var/*->getAttributes()*/  ->toArray(), true);
                if($return_string) return $output_str;
                \Log::info( $output_str  );
                return;
            }
            $output_str = ' (Object of '.get_class($var).') :' . ( !empty($descr) ? $descr .' : ' : '' ). print_r( (array)$var, true);
            if($return_string) return $output_str;
            \Log::info( $output_str );
            return;
        }
        //        \Log::info( '-2 varDump $var ::' . print_r( $var, true  ) );
        //        \Log::info( '-3 varDump gettype($var) ::' . print_r( gettype($var), true  ) );
    }
} // if ( ! function_exists('varDump')) {


if ( !function_exists('objectIntoArray')) {
    function objectIntoArray($obj): array
    {
        $array = json_decode(json_encode($obj), true);
        \Log::info(  varDump($array, ' -1 objectIntoArray $array::') );
        return $array;
    }
} // if ( !function_exists('objectIntoArray')) {



if ( !function_exists('deleteFileByPath')) {
    function deleteFileByPath(string $filename_path, $delete_empty_directory = false): bool
    {
        Storage::delete($filename_path);
        $directory_path = pathinfo($filename_path);

//        $file_exists = Storage::disk('local')->exists( 'public/'.$filename_path);
        Storage::disk('local')->delete('public/' . $filename_path);

        if ( ! empty($directory_path['dirname'])) {
            $files = Storage::files('public/' . $directory_path['dirname']);
            if (empty($files)) {
                Storage::deleteDirectory('public/' . $directory_path['dirname']);

                return true;
            }
        }
        return false;
    }
} // if ( !function_exists('deleteFileByPath')) {



if ( !function_exists('getValueLabelKeys')) {
    function getValueLabelKeys(array $arr): string
    {
        $keys    = array_keys($arr);
        $ret_str = '';
        foreach ($keys as $next_key) {
            $ret_str .= $next_key . ',';
        }

        return trimRightSubString($ret_str, ',');
    }
} // if ( !function_exists('getValueLabelKeys')) {


if ( !function_exists('checkUserGroup')) {
    function checkUserGroup(array $checkRoles)
    {
        $loggedUser      = Auth::guard('api')->user();
        \Log::info(  varDump($loggedUser, ' -1 $loggedUser::') );
          \Log::info('checkUserGroup $loggedUser->id::');
            \Log::info(print_r(  (!empty($loggedUser->id)??'user not defined'), true  ));

        if(empty($loggedUser)) {
            \Log::info('$loggedUser NOT DEFINED');
            return false;
        }
        $userGroupsCount = UserGroup
            ::getByUserId($loggedUser->id)
            ->getByGroupId($checkRoles)
            ->count();
        \Log::info(  varDump($userGroupsCount, ' -$userGroupsCount $::') );
        if ($userGroupsCount == 0) {
            return false;
        }

        return true;
    }
} // if ( ! function_exists('checkUserGroup')) {

if ( ! function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
//        echo '<pre>==$randomString::'.print_r($randomString,true).'</pre>';
        return $randomString;
    }
} // if ( ! function_exists('generateRandomString')) {


if ( ! function_exists('checkValidImgName')) {
    function checkValidImgName(string $filename, int $max_length=0, bool $check_valid_chars= false) : string
    {
        $ret_str= $filename;
        if ( !empty($max_length) and isPositiveNumeric($max_length)) {
            if ( strlen($filename) > $max_length ) {
                $basename= getFilenameBasename($filename);
                $extension= getFilenameExtension($filename);
                $index= $max_length - strlen('.'.$extension);
                $ret_str= substr($basename,0,$index) . '.'.$extension;
            }
        }
        if ( $check_valid_chars ) {
            $ret_str= str_replace(' ','_',$ret_str);
        }
        return $ret_str;
    }

} // if ( ! function_exists('checkValidImgName')) {


if ( ! function_exists('prepareEncodeArray')) {
    function prepareEncodeArray($sourceArray)
    {
        return str_replace('"', "'", json_encode($sourceArray));
    }
} // if ( ! function_exists('prepareEncodeArray')) {



if ( ! function_exists('prefixHttpProtocol')) {
    function prefixHttpProtocol($url)
    {
        return $url;
//        }
        if ( ! (strpos('http://', $url) === false) or ! (strpos('https://', $url) === false)) {
            return $url;
        }
        $request = request();
        if ($request->secure()) {
            return 'https://' . $url;
        }

        return 'http://' . $url;
    }
} // if ( ! function_exists('prefixHttpProtocol')) {


if ( ! function_exists('addWatermarkToImage')) {
    function addWatermarkToImage(string $source_file_path): bool
    {
        \Log::info( '-1 addWatermarkToImage $source_file_path::' . print_r(  $source_file_path, true  ) );

        $watermark_path= public_path('/images/hostels_brand_small.jpg');
        if(!file_exists($watermark_path)) {
            \Log::info( '-1 $ ::' . print_r(  'WATERMARK FILE NOT FOUND!', true  ) );
            return false;
        }

        $watermark =  \Image::make( $watermark_path );
        $watermark->opacity(50);
        \Log::info( '-2 $ ::' . print_r(  -2, true  ) );

//        $src_image_path= storage_path('app/images/ancient-roads.jpeg');
//        $src_image_path= storage_path('app/images/bata.png');
//        $src_image_path= storage_path('app/images/computer-mouse.webp');
//        $source_file_path= 'app/images/test1WW.jpg';
//        $source_file_exists = Storage::disk('local')->exists( /*'public/' . */$source_file_path );
//        $file_exists = ( ! empty($image) and Storage::disk('local')->exists('public/' . $file_full_path));

//        \Log::info( '-0 $source_file_exists ::' . print_r( $source_file_exists, true  ) );
        // [2020-08-02 16:42:51] local.INFO: -1 $src_image_path ::/mnt/_work_sdb8/wwwroot/lar/hostels3/storage/app/images/test1.jpg

        $src_image_path= storage_path($source_file_path);
        \Log::info( '-1 $src_image_path ::' . print_r(  $src_image_path, true  ) );
        if(!file_exists($src_image_path)) {
            \Log::info( '-1 $ ::' . print_r(  'SOURCE FILE NOT FOUND!', true  ) );
            return false;
        }

        $src_image_path_ext= getFilenameExtension($src_image_path);

        $srcImageObject = \Image::make($src_image_path);
        \Log::info( '-3 $ :: SOURCE FILE NOT FOUND! ' . print_r(  -3, true  ) );

        $srcImageObject->insert($watermark, 'bottom-right', 10, 10);
        if(strtolower($src_image_path_ext) == 'jpg' or strtolower($src_image_path_ext) == 'jpeg') {
            $srcImageObject->encode('jpg', 100);
        }
        if(strtolower($src_image_path_ext) == 'png') {
            $srcImageObject->encode('png', 100);
        }
        if(strtolower($src_image_path_ext) == 'webp') {
            $srcImageObject->encode('webp', 100);
        }
//        $srcImageObject->encode('png');

        $dest_file_path= storage_path('app/images/ResultingImage.'.$src_image_path_ext);
        $srcImageObject->save($dest_file_path);

        ImageOptimizer::optimize( $dest_file_path, null );
        return true;
    }
} // if (! function_exists('addWatermarkToImage')) {

if ( ! function_exists('clearValidationError')) {
    function clearValidationError(string $str, array $clearArray): string
    {
        foreach ($clearArray as $next_key => $next_value) {
            $str = str_replace($next_key, $next_value, $str);
        }

        return $str;
    }
} // if (! function_exists('clearValidationError')) {

if ( ! function_exists('getConcatStrMaxLength')) {
    function getConcatStrMaxLength(): int
    {
        return 50;
    }
} // if (! function_exists('getConcatStrMaxLength')) {


if ( ! function_exists('getFormattedCurrency')) {
    function getFormattedCurrency($val): string
    {
        $currencyConfig = getCurrency();
        $s              = number_format($val, 2);

        return (! empty($currencyConfig['currency_left']) ? $currencyConfig['currency_short'] : '') . $s . (empty($currencyConfig['currency_left']) ? $currencyConfig['currency_short'] : '');
    }
} // if (!function_exists('getFormattedCurrency')) {

if ( ! function_exists('getCurrency')) {
    function getCurrency(string $key = null)
    {
        /*     'currency'                   => 'AU$',
    'currency_short'             => 'AU$',
    'currency_left'              => true,
 */
        /*        if (strtolower($key) == 'sign') {
                    return config('app.currency_sign');
                }*/
        if (strtolower($key) == 'currency_left') {
            return config('app.currency_left');
        }
        /*        if (strtolower($key) == 'code') {
                    return config('app.currency_code');
                }*/
        if (strtolower($key) == 'stripe_code') {
            return config('app.currency_stripe_code');
        }
        if (strtolower($key) == 'currency_short') {
            return config('app.currency_short');
        }

        return [
//            'sign'                => config('app.currency_sign'),
            'currency_left'  => config('app.currency_left'),
//            'code'                => config('app.currency_code'),
            'currency_short' => config('app.currency_short')
        ];
    }
} // if (!function_exists('getCurrency')) {


if ( ! function_exists('safeFilename')) {
    function safeFilename(string $filename): string
    {
        return preg_replace("/[^A-Za-z ]/", '', $filename);
    }
} // if (! function_exists('safeFilename')) {

if ( ! function_exists('addAppMetaKeywords')) {
    function addAppMetaKeywords(array $arr): array
    {
        $arr[] = Settings::getValue('site_name');
        $arr[] = Settings::getValue('site_heading');
        $arr[] = Settings::getValue('site_subheading');

        return $arr;
    }
} // if (! function_exists('addAppMetaKeywords')) {

if ( ! function_exists('isValidBool')) {
    function isValidBool($val): bool
    {
        if (in_array($val, ["Y", "N"])) {
            return true;
        } else {
            return false;
        }
    }
} // if (! function_exists('isValidBool')) {

if ( ! function_exists('isValidInteger')) {
    function isValidInteger($val): bool
    {
        if (preg_match('/^[1-9][0-9]*$/', $val)) {
            return true;
        } else {
            return false;
        }
    }
} // if (! function_exists('isValidInteger')) {

if ( ! function_exists('isValidFloat')) {
    function isValidFloat($val): bool
    {
        if (preg_match('/^[+-]?([0-9]*[.])?[0-9]+$/', $val)) {
            return true;
        } else {
            return false;
        }
    }
} // if (! function_exists('isValidFloat')) {

if ( ! function_exists('getQuizQualityOptions')) {

    function getQuizQualityOptions(bool $return_key_value = false): array
    {
        $settings = Settings::getByName('showQuizQualityOptions')->first();
        if ($settings !== null) {
            $settings_quizQualityOptions_str = $settings->value;
            $arr                             = splitStrIntoArray($settings_quizQualityOptions_str, ';');
            $settingsQuizQualityOptions      = [];
            foreach ($arr as $next_key => $next_value) {
                if ($return_key_value) {
                    $settingsQuizQualityOptions[$next_key] = $next_value;
                } else {
                    $settingsQuizQualityOptions[] = [
                        'quiz_quality_id'    => $next_key,
                        'quiz_quality_label' => $next_value
                    ];
                }
            }

            return $settingsQuizQualityOptions;
        }

        return [];

    }
} // if (! function_exists('getQuizQualityOptions')) {

if ( ! function_exists('getFileExtensionsImageUrl')) {
    function getFileExtensionsImageUrl(string $filename): string
    {
        $fileExtensionsImages = config('app.fileExtensionsImages');
        $filename_extension   = getFilenameExtension($filename);
        foreach ($fileExtensionsImages as $next_extension => $next_extension_file) {
            if (strtolower($next_extension) == $filename_extension) {
                $extension_filename = with(new Settings)->getFilesExtentionDir() . $next_extension_file;

                return $extension_filename;
            }
        }

        return '';
    }
} // if (! function_exists('getFileExtensionsImageUrl')) {

if ( ! function_exists('getFilenameBasename')) {
    function getFilenameBasename($file)
    {
        return File::name($file);
    }
} // if (! function_exists('getFilenameBasename')) {

if ( ! function_exists('getFilenameExtension')) {
    function getFilenameExtension($file)
    {
        return File::extension($file);
    }
} // if (! function_exists('getFilenameExtension')) {

if ( ! function_exists('splitStrIntoArray')) {
    function splitStrIntoArray($str, $splitter_1, $splitter_2 = '=', $output_format = 'array')
    {
//        echo '<pre>splitStrIntoArray  $str::'.print_r($str,true).'</pre>';
//        echo '<pre>splitStrIntoArray  $splitter_1::'.print_r($splitter_1,true).'</pre>';
//        echo '<pre>splitStrIntoArray  $splitter_2::'.print_r($splitter_2,true).'</pre>';
        $retArray = array();
        $A        = preg_split('/' . $splitter_1 . '/', $str);
        foreach ($A as $key => $val) {
            if (empty($splitter_2)) {
                $retArray[] = $val;
            } else {
//                echo '<pre>$splitter_2;'.print_r($splitter_2,true).';</pre>';
                $A_2 = preg_split('/' . $splitter_2 . '/', $val);
//                echo '<pre>$A_2::'.print_r($A_2,true).'</pre>';
                if (count($A_2) == 2) {
                    $retArray[$A_2[0]] = $A_2[1];
                }
                if (count($A_2) > 2) {
                    $A_2_text = '';
                    for ($i = 1; $i < count($A_2); $i++) {
                        $A_2_text .= $A_2[$i] . ($i < count($A_2) - 1 ? $splitter_2 : "");
                    }
//                    $retArray[$A_2[0]] = $A_2[1];
                    $retArray[$A_2[0]] = $A_2_text;
                }
            }
        }
        if ($output_format == 'array') {
            return $retArray;
        }
//        echo '<pre>$retArray::'.print_r($retArray,true).'</pre>';
        if ($output_format == 'string_2_array') {
//            return ' \'{  "S:61" : \'801\' ,  "S:63" : \'840\'  }';
//            return '{"a": 1, "b": {"c": "d", "e": true}}';
            $ret_str = '{ ';
            foreach ($retArray as $next_key => $next_value) {
//                $ret_str.= ' { "'.$next_key.'":'. "'" . $next_value."' }, ";
                $ret_str .= ' "' . $next_key . '" : ' . '"' . $next_value . '" , ';
            }
            $ret_str = trimRightSubString(trim($ret_str), ',');
            $ret_str .= ' }';

//            $ret_str.= ' }:jsonb ';

            return $ret_str;
        }
//        if ( $output_format == 'string_2_array' ) {
//            if (empty($retArray) or !is_array($retArray)) {
//                return "ARRAY []::varchar(255)[] ";
//            }
//            $ret_str= "ARRAY [";
//            $i= 1;
//            foreach( $retArray as $next_key=>$next_value ) {
//                $ret_str.= " ARRAY[ '".trim($next_key)."','".trim($next_value)."' ]".($i< count($retArray) ? "," :"")." ";
//                $i++;
//            }
//            $ret_str.= ' ]';
////            $ret_str.= ' ]::varchar(255)[][]';
//            return $ret_str;
//        }
        /* SELECT reduce_dim(array[array[1, 2], array[2, 3], array[4,5], array[9,10]]);
         reduce_dim */
    }
} // if (! function_exists('splitStrIntoArray')) {

if ( ! function_exists('trimRightSubString')) {
    function trimRightSubString(
        string $s,
        string $substr
    ): string {
        $res = preg_match('/(.*?)(' . preg_quote($substr, "/") . ')$/si', $s, $A);
        if ( ! empty($A[1])) {
            return $A[1];
        }

        return $s;
    }

} // if (! function_exists('trimRightSubString')) {

if ( ! function_exists('isFakeEmail')) {
    function isFakeEmail(string $email): string
    {
        $settingsArray = Settings::getSettingsList(['site_name']);
        $site_name     = ! empty($settingsArray['site_name']) ? $settingsArray['site_name'] : '';

        $has_fake_text = false;
        $pos           = strpos($email, 'fake_');
        if ( ! ($pos === false)) {
            $has_fake_text = true;
        }

        $has_site_name_text = false;
        $pos                = strpos($email, $site_name);
        if ( ! ($pos === false)) {
            $has_site_name_text = true;
        }

        return $has_fake_text and $has_site_name_text;
    }
} // if (! function_exists('isFakeEmail')) {

if ( ! function_exists('makeAddHttpPrefix')) {
    function makeAddHttpPrefix(string $url): string
    {
        if (empty($url)) {
            return '';
        }
        $url = trim($url);
        $ret = checkRegexpHttpPrefix($url);
        if ( ! $ret) {
            return 'http://' . $url;
        }

        return $url;
    }
} // if (! function_exists('makeAddHttpPrefix')) {

if ( ! function_exists('checkRegexpHttpPrefix')) {
    function checkRegexpHttpPrefix($str)
    {
        $pattern = "~^http(s)?:\/\/~i";
        $res     = preg_match($pattern, $str);

        return $res;
    }
} // if (! function_exists('checkRegexpHttpPrefix')) {

if ( ! function_exists('capitalize')) {
    function capitalize($str)
    {
        return ucfirst($str);
    }

} // if (! function_exists('capitalize')) {


if ( ! function_exists('getNiceFileSize')) {
    function getNiceFileSize(
        $bytes,
        $binaryPrefix = true
    ) {
        if ($binaryPrefix) {
            $unit = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
            if ($bytes == 0) {
                return '0 ' . $unit[0];
            }

            return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 2) . ' ' . (isset($unit[$i]) ? $unit[$i] : 'B');
        } else {
            $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
            if ($bytes == 0) {
                return '0 ' . $unit[0];
            }

            return @round($bytes / pow(1000, ($i = floor(log($bytes, 1000)))), 2) . ' ' . (isset($unit[$i]) ? $unit[$i] : 'B');
        }
    }

} // if (! function_exists('getNiceFileSize')) {


if ( ! function_exists('getElasticsearchInfo')) {
    function getElasticsearchInfo(
        $detail = null
    ) {

        try {
            $elasticsearch_url = config('app.elasticsearch_url');
            if (empty($elasticsearch_url)) {
                return false;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $elasticsearch_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, []);

            $output = curl_exec($ch);
            $output = json_decode($output);
            curl_close($ch);
            if (strtolower($detail) == 'version') {
                $version = ! empty($output->version->number) ? $output->version->number : false;

                return $version;
            }
        } catch (\Exception $e) {
            return '';
        }

        return $output;
    }

} // if (! function_exists('getElasticsearchInfo')) {


if ( ! function_exists('concatStr')) {
    function concatStr(string $str, int $max_length = 0, string $add_str = ' ...', $show_help = false, $strip_tags = true, $additive_code = ''): string
    {
        if ($strip_tags) {
            $str = strip_tags($str);
        }
        $ret_html = limitChars($str, (! empty($max_length) ? $max_length : getConcatStrMaxLength()), $add_str);
        if ($show_help and strlen($str) > $max_length) {
            $ret_html .= '<i class=" a_link fa bars" style="font-size:larger;" hidden ' . $additive_code . ' ></i>';
        }

        return $ret_html;
    }
} // if (! function_exists('concatStr')) {


if ( ! function_exists('limitChars')) {
    function limitChars(
        $str,
        $limit = 100,
        $end_char = null,
        $preserve_words = false
    ) {
        $end_char = ($end_char === null) ? '&#8230;' : $end_char;

        $limit = (int)$limit;

        if (trim($str) === '' OR strlen($str) <= $limit) {
            return $str;
        }

        if ($limit <= 0) {
            return $end_char;
        }

        if ($preserve_words == false) {
            return rtrim(substr($str, 0, $limit)) . $end_char;
        }
        // TO FIX AND DELETE SPACE BELOW
        preg_match('/^.{' . ($limit - 1) . '}\S* /us', $str, $matches);

        return rtrim($matches[0]) . (strlen($matches[0]) == strlen($str) ? '' : $end_char);
    }

} // if (! function_exists('limitChars')) {


if ( ! function_exists('getBackendTemplateName')) {
    function getBackendTemplateName()
    {
        return 'defaultBS41Backend';
    }
} // if (! function_exists('getBackendTemplateName')) {


if ( ! function_exists('getFrontendTemplateName')) {
    function getFrontendTemplateName()
    {
        $loggedUser = Auth::user();
        if ( ! empty($loggedUser->template_id) and $loggedUser->template_id == 2) {
            return 'FlexyFrontend';
        }

        return 'cardsBS41Frontend';
    }
} // if (! function_exists('getFrontendTemplateName')) {


if ( ! function_exists('limitWords')) {
    /**
     * Limits a phrase to a given number of words.
     *
     * @param string   phrase to limit words of
     * @param integer  number of words to limit to
     * @param string   end character or entity
     *
     * @return  string
     */
    function limitWords(
        $str,
        $limit = 100,
        $end_char = null
    ) {
        $limit    = (int)$limit;
        $end_char = ($end_char === null) ? '&#8230;' : $end_char;

        if (trim($str) === '') {
            return $str;
        }

        if ($limit <= 0) {
            return $end_char;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,' . $limit . '}/u', $str, $matches);

        // Only attach the end character if the matched string is shorter
        // than the starting string.
        return rtrim($matches[0]) . (strlen($matches[0]) === strlen($str) ? '' : $end_char);
    }
} // if (! function_exists('limitWords')) {

if ( ! function_exists('isRunningUnderDocker')) {
    function isRunningUnderDocker()  :bool
    {
        if (empty($_SERVER['HTTP_HOST'])) {
            return false;
        }
        $docker_host = '127.0.0.1:8084';
//        echo '<pre>$_SERVER::'.print_r($_SERVER,true).'</pre>';
//        $mystring = 'abc';
        $pos = strpos($_SERVER['HTTP_HOST'], $docker_host);
        if ($pos === false) {
            return false;
        } else {
            return true;
        }
    }
} // if (! function_exists('isRunningUnderDocker')) {


if ( ! function_exists('isCliCommand')) {
    function isCliCommand()
    {
        if (strpos(php_sapi_name(), 'cli') !== false) {
            return true;
        }

        return false;
    }
} // if (! function_exists('isCliCommand')) {


if ( ! function_exists('isHttpsProtocol')) {
    function isHttpsProtocol()
    {
        if (empty($_SERVER['HTTP_HOST'])) {
            return false;
        }
        if ( ! (strpos($_SERVER['HTTP_HOST'], 'votes.my-demo-apps')) === false) {
            return true;
        }

        return false;
    }
} // if (! function_exists('isHttpsProtocol')) {

if ( ! function_exists('isDeveloperComp')) {
    function isDeveloperComp($check_debug = false)
    {
        if ( ! empty($_SERVER['HTTP_HOST'])) {
            $pos = strpos($_SERVER['HTTP_HOST'], 'local-hostels3.com');
            if ( ! ($pos === false)) {
                return true;
            }
        }
        if (isRunningUnderDocker()) {
            return true;
        }
        $app_developers_mode = Session::get('app_developers_mode', '');

        return ! empty($app_developers_mode);
    }
} // if (! function_exists('isDeveloperComp')) {

if ( ! function_exists('getSpatieTagLocaledValue')) {
    function getSpatieTagLocaledValue($value)
    {
        $spatie_tag_locale = config('app.spatie_tag_locale', 'en');
        $decodedValue      = (array)json_decode($value);
        if ( ! empty($decodedValue[$spatie_tag_locale])) {
            return $decodedValue[$spatie_tag_locale];
        }

        return '';
    }
} // if (! function_exists('getSpatieTagLocaledValue')) {

if ( ! function_exists('clearEmptyArrayItems')) {
    function clearEmptyArrayItems($arr): array
    {
        if (empty($arr)) {
            return [];
        }
        foreach ($arr as $next_key => $next_value) {
            if (empty($next_value)) {
                unset($arr[$next_key]);
            }
        }

        return $arr;
    }
} // if (! function_exists('clearEmptyArrayItems')) {

if ( ! function_exists('concatArray')) {
    function concatArray(
        $arr,
        $splitter = ',',
        $skip_empty = true,
        $skip_last_delimiter = true
    ) {
        $ret_str = '';

        if ( ! is_array($arr) or empty($arr)) {
            return '';
        }
        $l              = count($arr);
        $nonempty_array = array();
        for ($i = 0; $i < $l; $i++) {
            $next_value = trim($arr[$i]);
            if (empty($next_value) and $skip_empty) {
                continue;
            }
            $nonempty_array[] = removeMore1Space($next_value);
        }

        $l = count($nonempty_array);
        for ($i = 0; $i < $l; $i++) {
            $next_value = trim($nonempty_array[$i]);
            $ret_str    .= $next_value . (($skip_last_delimiter and $i == $l - 1) ? '' : $splitter);
        }

        return $ret_str;
    }
} // if (! function_exists('concatArray')) {

if ( ! function_exists('concatConditionalValues')) {
    function concatConditionalValues(
        $valuesArray,
        $splitter = '',
        $default_value = ''
    ) {
        $ret         = '';
        $have_values = false;
//        echo '<pre>$valuesArray::'.print_r($valuesArray,true).'</pre>';
        foreach ($valuesArray as $next_key => $next_value) {
            if ($next_value['condition']) {
                $have_values = true;
                $ret         .= $next_value['value'] . $splitter;
            }
        }
        if (empty($have_values)) {
            $ret = $default_value;
        }
        $ret = trimRightSubString($ret, $splitter);

        return $ret;
    }
} // if (! function_exists('concatConditionalValues')) {

if ( ! function_exists('removeMore1Space')) {
    function removeMore1Space($str)
    {
        $res = preg_replace('/\s\s+/', ' ', $str);

        return $res;
    }
} // if (! function_exists('removeMore1Space')) {

if ( ! function_exists('getRightSubstring')) {
    function getRightSubstring(string $S, $count): string
    {
        return substr($S, strlen($S) - $count, $count);
    }
} // if (! function_exists('getRightSubstring')) {


if ( ! function_exists('setArrayHeader')) {
    //        $newsPublishedValueArray   = SetArrayHeader(['key'=> '', 'label' => ' -Select published- '], News::getNewsPublishedValueArray(true));
    function setArrayHeader(array $headersArray, array $dataArray): array
    {
//        \Log::info( '-1 setArrayHeader $headersArray ::' . print_r(  $headersArray, true  ) );

        if (empty($headersArray) or ! is_array(($headersArray))) {
//            \Log::info( '-2 ' . print_r(  -2, true  ) );
            return $dataArray;
        }
        $retArray = [];
//        \Log::info( '-3 ' . print_r(  -3, true  ) );
        foreach ($headersArray as $next_header_key => $next_header_text) {
//            \Log::info( '-4 $next_header_key' . print_r(  $next_header_key, true  ) );
//            \Log::info( '-5 $next_header_text' . print_r(  $next_header_text, true  ) );
            $retArray[] = $next_header_text;
//            \Log::info( '-51 $retArray' . print_r(  $retArray, true  ) );
//            $retArray[$next_header_text['key']] = $next_header_text['label'];
        }
//        \Log::info( '-52 $retArray' . print_r(  $retArray, true  ) );
        if (is_array($dataArray)) {
            foreach ($dataArray as $next_data_key => $next_data_text) {
//                \Log::info( '-6 $next_data_key' . print_r(  $next_data_key, true  ) );
//                \Log::info( '-7 $next_data_text' . print_r(  $next_data_text, true  ) );
//                $retArray[$next_data_key] = $next_data_text;
                $retArray[] = $next_data_text;
            }
        }

        return $retArray;
    }
} // if (! function_exists('setArrayHeader')) {


if ( ! function_exists('setFlashMessage')) {
    function setFlashMessage(string $message_text, string $action_status = 'success', string $action_header = '')
    {
        \Session::flash('action_text', $message_text);
        \Session::flash('action_status', $action_status);
        if ( ! empty($action_header)) {
            \Session::flash('action_header', $action_header);
        }
    }
} // if (! function_exists('setFlashMessage')) {


if ( ! function_exists('getCFPriceFormat')) {
    function getCFPriceFormat($value)
    {
        return number_format($value, 2, ',', '.');
    }
} // if (! function_exists('getCFPriceFormat')) {


if ( ! function_exists('getCFFormattedDate')) {
    function getCFFormattedDate($date, $date_format = 'mysql', $output_format = ''): string
    {
        if (empty($date)) {
            return '';
        }
        $date_carbon_format = config('app.date_carbon_format');
        if ($date_format == 'mysql' /*and ! isValidTimeStamp($date)*/) {
            $date_format = getDateFormat("astext");
            $date        = Carbon::createFromTimestamp(strtotime($date))->format($date_format);

            return $date;
        }


        if (isCFValidTimeStamp($date)) {
            if (strtolower($output_format) == 'astext') {
                $date_carbon_format_as_text = config('app.date_carbon_format_as_text', '%d %B, %Y');

                return Carbon::createFromTimestamp($date, Config::get('app.timezone'))->formatLocalized($date_carbon_format_as_text);
            }
            if (strtolower($output_format) == 'pickdate') {
                $date_carbon_format_as_pickdate = config('app.pickdate_format_submit');

                return Carbon::createFromTimestamp($date, Config::get('app.timezone'))->format($date_carbon_format_as_pickdate);
            }

            return Carbon::createFromTimestamp($date, Config::get('app.timezone'))->format($date_carbon_format);
        }
        $A = preg_split("/ /", $date);
        if (count($A) == 2) {
            $date = $A[0];
        }
        $a = Carbon::createFromFormat($date_carbon_format, $date);
        $b = $a->format(getCFDateFormat("astext"));

        return $a->format(getCFDateFormat("astext"));
    }
} // if (! function_exists('getCFFormattedDate')) {


if ( ! function_exists('getDateFormat')) {
    function getDateFormat($format = '')
    {
        if (strtolower($format) == "numbers") {
            return 'Y-m-d';
        }
        if (strtolower($format) == "astext") {
            return 'j F, Y';
        }

        return 'Y-m-d';
    }


} // if (! function_exists('getDateFormat')) {


if ( ! function_exists('getCFDateTimeFormat')) {
    function getCFDateTimeFormat($format = '')
    {
        if (strtolower($format) == "numbers") {
            return 'Y-m-d H:i';
        }
        if (strtolower($format) == "astext") {
            return 'j F, Y g:i A';
        }

        return 'Y-m-d H:i';
    }
} // if (! function_exists('getCFDateTimeFormat')) {

if ( ! function_exists('isCFValidTimeStamp')) {
    function isCFValidTimeStamp($timestamp)
    {
        if (gettype($timestamp) == "object") {
            return false;
        }

        return ((string)(int)$timestamp === (string)$timestamp)
               && ($timestamp <= PHP_INT_MAX)
               && ($timestamp >= ~PHP_INT_MAX);
    }
} // if (! function_exists('isCFValidTimeStamp')) {

if ( ! function_exists('getCFFormattedDateTime')) {
    function getCFFormattedDateTime($datetime, $datetime_format = 'mysql', $output_format = ''): string
    {
        if (empty($datetime)) {
            return '';
        }
//        echo '<pre>$this->isValidTimeStamp($datetime)::'.print_r($this->isValidTimeStamp($datetime),true).'</pre><br>';
//        echo '<pre>$output_format::'.print_r($output_format,true).'</pre><br>';
//        echo '<pre>$datetime_format::'.print_r($datetime_format,true).'</pre><br>';
//        echo '<pre>$datetime::'.print_r($datetime,true).'</pre><br>';
//        if ($datetime_format == 'mysql' and ! $this->isValidTimeStamp($datetime_format)) {
        if (( $datetime_format == 'mysql' or empty($datetime_format)) and ! isValidTimeStamp($datetime)) {
//            echo '<pre>-10 $output_format ::'.print_r($output_format,true).'</pre><br>';
            if ($output_format == 'only_time') {
                $time_format = 'H:i';//$this->getDateTimeFormat("astext");
                $ret         = Carbon::createFromTimestamp(strtotime($datetime))->format($time_format);

//                echo '<pre>== $ret::'.print_r($ret,true).'</pre>';
                return $ret;
//                return Carbon::createFromTimestamp(strtotime($datetime))->diffForHumans();
            }
            if ($output_format == 'ago_format') {
//                echo '<pre>-2::'.print_r(-2,true).'</pre><br>';
//                return $datetime->diffForHumans();
//        die("-1 XXZ=====");
                return Carbon::createFromTimestamp(strtotime($datetime))->diffForHumans();
            }
            $datetime_format = getDateTimeFormat("astext");
            $ret             = Carbon::createFromTimestamp(strtotime($datetime))->format($datetime_format);

            return $ret;
        }

//        if ($this->isValidTimeStamp($datetime_format)) {
        if (isValidTimeStamp($datetime)) {
//            echo '<pre>-3::'.print_r(-3,true).'</pre><br>';
            $datetime_format = getDateTimeFormat("astext");
            $ret             = Carbon::createFromTimestamp($datetime)->format($datetime_format);

            return $ret;
        }

        return (string)$datetime;
    }

} // if (! function_exists('getCFFormattedDateTime')) {


if ( ! function_exists('isValidTimeStamp')) {
    function isValidTimeStamp($timestamp)
    {
        return ((string)(int)$timestamp === (string)$timestamp)
               && ($timestamp <= PHP_INT_MAX)
               && ($timestamp >= ~PHP_INT_MAX);
    }
} // if (! function_exists('isValidTimeStamp')) {


if ( ! function_exists('getDateTimeFormat')) {
    function getDateTimeFormat($format = '')
    {
        if (strtolower($format) == "numbers") {
            return 'Y-m-d H:i';
        }
        if (strtolower($format) == "astext") {
            return 'j F, Y g:i A';
        }

        return 'Y-m-d H:i';
    }
} // if (! function_exists('getDateTimeFormat')) {

if ( ! function_exists('getCFDateFormat')) {
    function getCFDateFormat($format = '')
    {
        if (strtolower($format) == "numbers") {
            return 'Y-m-d';
        }
        if (strtolower($format) == "astext") {
            return 'j F, Y';
        }

        return 'Y-m-d';
    }
} // if (! function_exists('getCFDateFormat')) {


if ( ! function_exists('cFCreateDir')) {
    function cFCreateDir(array $directoriesList = [], $mode = 0777)
    {
        foreach ($directoriesList as $dir) {
            if ( ! file_exists($dir)) {
                mkdir($dir, $mode);
            }
        }
    }
} // if (! function_exists('cFCreateDir')) {

if ( ! function_exists('cFWriteArrayToCsvFile')) {
    function cFWriteArrayToCsvFile(array $dataArray, string $filename, array $directoriesArray): int
    {
        cFCreateDir($directoriesArray);
        $path = $directoriesArray[count($directoriesArray) - 1];
        \Excel::create($filename, function ($excel) use ($dataArray) {
            $excel->sheet('file', function ($sheet) use ($dataArray) {
                $sheet->fromArray($dataArray);
            });
        })->store('csv', $path);

        return 1;
    }
} // if (! function_exists('cFWriteArrayToCsvFile')) {

if ( ! function_exists('getCFImageShowSize')) {
    function getCFImageShowSize(string $image_filename, int $orig_width, int $orig_height)
    {
        if ( ! file_exists($image_filename) or empty($image_filename) or is_dir($image_filename)) {
            return;
        }
        try {
            $height = \Image::make($image_filename)->height();
            $width  = \Image::make($image_filename)->width();
        } catch (Exception $e) {
            return false;
        }
        $retArray                    = array('width' => 0, 'height' => 0, 'original_width' => 0, 'original_height' => 0);
        $retArray['original_width']  = $width;
        $retArray['original_height'] = $height;
        $retArray['width']           = $width;
        $retArray['height']          = $height;

        $ratio = round($width / $height, 3);

        if ($width > $orig_width) {
            $retArray['width']  = (int)($orig_width);
            $retArray['height'] = (int)($orig_width / $ratio);
            if ($retArray['width'] <= (int)$orig_width and $retArray['height'] <= (int)$orig_height) {
                return $retArray;
            }
            $width  = $retArray['width'];
            $height = $retArray['height'];
        }
        if ($height > $orig_height and ((int)($orig_height / $ratio)) <= $orig_width) {
            $retArray['width']  = (int)($orig_height * $ratio);
            $retArray['height'] = (int)($orig_height);

            return $retArray;
        }
        if ($height > $orig_height and ((int)($orig_height / $ratio)) > $orig_width) {
            $retArray['width']  = (int)($orig_height * $ratio);
            $retArray['height'] = (int)($retArray['width'] / $ratio);

            return $retArray;
        }

        return $retArray;
    }
} // if (! function_exists('getCFImageShowSize')) {


if ( ! function_exists('getCFImageProps')) {
    function getCFImageProps(string $image_path, array $imagePropsArray = []): array
    {
        if ( ! file_exists($image_path)) {
            return [];
        }
        $imagesExtensionsArray = \Config::get('app.images_extensions', []);
        $extension             = getFilenameExtension($image_path);
        $file_width            = null;
        $file_height           = null;
        if (in_array($extension, $imagesExtensionsArray)) {
            $file_width  = Image::make($image_path)->width();
            $file_height = Image::make($image_path)->height();
            $file_size   = Image::make($image_path)->filesize();
        } else {
            $file_size = File::size($image_path);
        }
        $file_size_label       = getCFFileSizeAsString($file_size);
        $retArray              = [];
        $retArray['file_info'] = '<b>' . basename($image_path) . '</b>, ' . $file_size_label;


        foreach ($imagePropsArray as $nextImageProp => $nextImagePropValue) {
            $retArray[$nextImageProp] = $nextImagePropValue;
        }
        $retArray['file_size']       = $file_size;
        $retArray['file_size_label'] = $file_size_label;
        if (isset($file_width)) {
            $retArray['file_width'] = $file_width;
        }
        if (isset($file_height)) {
            $retArray['file_height'] = $file_height;
        }
        if ( ! empty($retArray['file_width']) and ! empty($retArray['file_height'])) {
            $retArray['file_info'] .= ', ' . $retArray['file_width'] . 'x' . $retArray['file_height'];
        }

        return $retArray;
    }
} // if (! function_exists('getCFImageProps')) {


if ( ! function_exists('getCFFileSizeAsString')) {
    function getCFFileSizeAsString(string $file_size): string
    {
        if ((int)$file_size < 1024) {
            return $file_size . 'b';
        }
        if ((int)$file_size < 1024 * 1024) {
            return floor($file_size / 1024) . 'kb';
        }

        return floor($file_size / (1024 * 1024)) . 'mb';
    }
} // if (! function_exists('getCFFileSizeAsString')) {


if ( ! function_exists('getSystemInfo')) {
    function getSystemInfo()
    {
        $DB_CONNECTION = config('database.default');
        $connections   = config('database.connections');
        $database_name = ! empty($connections[$DB_CONNECTION]['database']) ? $connections[$DB_CONNECTION]['database'] : '';

        $pdo           = DB::connection()->getPdo();
        $db_version    = $pdo->query('select version()')->fetchColumn();
        $tables_prefix = DB::getTablePrefix();

        $elasticsearch_version = getElasticsearchInfo('version');
        $newsLetterApiArray    = (array)\Newsletter::getApi();
        $mail_chimp_api_text   = '';
        foreach ($newsLetterApiArray as $next_key => $next_value) {
            if (strpos($next_key, 'api_endpoint') > 0) {
                $mail_chimp_api_text = 'Mail Chimp API : <strong>' . $next_value . '</strong>';
                break;
            }
        }

        ob_start();
        phpinfo();
        $phpinfo_str = ob_get_contents() . '<hr><pre>' . print_r($_SERVER, true) . '</pre>';
        ob_end_clean();
        $server_info = '<hr><pre>' . print_r($_SERVER, true) . '</pre>';

        $app_version = '';
        if (file_exists(public_path('app_version.txt'))) {
            $app_version = File::get('app_version.txt');
            if ( ! empty($app_version)) {
                $app_version = ' app_version : <b> ' . $app_version . '</b><br>';
            }
        }

        $is_running_under_docker_text = '';
        if (isRunningUnderDocker()) {
            $is_running_under_docker_text = '<b>Running Under Docker</b><br>';
        }

        $runningUnderDocker = (isRunningUnderDocker() ? '<strong>UnderDocker</strong>' : 'No Docker');
        $string             = ' Laravel:<b>' . app()::VERSION . '</b><br>' .
                              'PHP:<b>' . phpversion() . '</b><br>' .
                              'DEBUG:<b>' . config('app.debug') . '</b><br>' .
                              'PHP SAPI NAME:<b>' . php_sapi_name() . '</b><br>' .
                              'ENV:<b>' . config('app.env') . '</b><br>' .
                              'Elasticsearch version:<b> ' . $elasticsearch_version . '</b><br>' .
                              'DB CONNECTION:<b> ' . $DB_CONNECTION . ' </b><br>' .
                              'DB VERSION:<b> ' . $db_version . '</b><br>' .
                              'DB DATABASE:<b> ' . $database_name . '</b><br>' .
                              'TABLES PREFIX:<b> ' . $tables_prefix . '</b><br>' .

                              '<hr>' .
                              'base_path:<b>' . base_path() . '</b><br>' .
                              'app_path:<b>' . app_path() . '</b><br>' .
                              'public_path:<b>' . public_path() . '</b><br>' .
                              'storage_path:<b>' . storage_path() . '</b><br>' .
                              'Path to the \'storage/app\' folder:<b>' . storage_path('app') . '</b><br>' .
                              $app_version .
                              $is_running_under_docker_text .
                              '<hr>' .

                              $mail_chimp_api_text . '</b><br>' .
                              '<hr><div> <div style="overflow-x:scroll; overflow-y:scroll; max-height:300px; max-width:600px;">' . $phpinfo_str . '</div></div>' .
                              '<hr><div>' . $runningUnderDocker . '</div>' .
                              '<hr><div> <div style="overflow-x:scroll; overflow-y:scroll; max-height:300px; max-width:600px;">' . $server_info . '</div></div>';

        return $string;
    }
} // if (! function_exists('getSystemInfo')) {

if ( ! function_exists('isPositiveNumeric')) {
    function isPositiveNumeric(int $str): bool
    {
        if (empty($str)) {
            return false;
        }

        return (is_numeric($str) && $str > 0 && $str == round($str));
    }
} // if (! function_exists('isPositiveNumeric')) {

if ( ! function_exists('replaceSpaces')) {
    function replaceSpaces($S)
    {
        $Pattern = '/([\s])/xsi';
        $S       = preg_replace($Pattern, '&nbsp;', $S);

        return $S;
    }
} // if (! function_exists('replaceSpaces')) {

if ( ! function_exists('createDir')) {
    function createDir(array $directoriesList = [], $mode = 0777)
    {
        foreach ($directoriesList as $dir) {
            if ( ! file_exists($dir)) {
                mkdir($dir, $mode);
            }
        }
    }

} // if (! function_exists('createDir')) {

if ( ! function_exists('clearDirectoryByPeriod')) {
    function clearDirectoryByPeriod(string $directory_name, $hours)
    {

        $is_debug                  = true;
        $deleted_files_count       = 0;
        $deleted_directories_count = 0;
        $exists                    = Storage::disk('local')->exists('public/' . $directory_name);
        if ($is_debug) {
            echo '<pre>$exists::' . print_r($exists, true) . '</pre>';
            echo '<pre>$directory_name::' . print_r($directory_name, true) . '</pre>';
        }
        if ( ! $exists) {
            return false;
        }

        $files = Storage::disk('local')->allFiles('public/' . $directory_name);
        if ($is_debug) {
            echo '<pre>$files::' . print_r($files, true) . '</pre>';
        }

        $now_dt = Carbon::now();
        foreach ($files as $next_file) {
            $next_file_time = Storage::disk('local')->lastModified($next_file);
            if ($is_debug) {
                echo '<pre>$next_file::' . print_r($next_file, true) . '</pre>';
                echo '<pre>??$next_file_time::' . print_r($next_file_time, true) . '</pre>';
                echo '<pre>222$next_file_time::' . print_r(getCFFormattedDateTime($next_file_time), true) . '</pre>';
            }


            $next_file_dt = Carbon::createFromTimestamp($next_file_time);
            $next_file_dt->addHours($hours);

            if ($now_dt->greaterThan($next_file_dt)) {    // the file is absolute
                $deleted_files_count++;
                if ($is_debug) {
                    echo '<pre>DELETE $next_file::' . print_r($next_file, true) . '</pre>';
                }
                Storage::disk('local')->delete($next_file);
            }
            echo 'next file <hr><hr>';
        }

        $directories = Storage::disk('local')->allDirectories('public/' . $directory_name);
        $is_debug    = true;
        foreach ($directories as $next_directory) {
            $next_directory_time = Storage::disk('local')->lastModified($next_directory);
            if ($is_debug) {
                echo '<pre>$next_directory::' . print_r($next_directory, true) . '</pre>';
                echo '<pre>??$next_directory_time::' . print_r($next_directory_time, true) . '</pre>';
                echo '<pre>222$next_directory_time::' . print_r(getCFFormattedDateTime($next_directory_time), true) . '</pre>';
            }
            $files                 = Storage::disk('local')->allFiles($next_directory);
            $directory_files_count = count($files);
            echo '<pre>$directory_files_count::' . print_r($directory_files_count, true) . '</pre>';


            $next_directory_dt = Carbon::createFromTimestamp($next_directory_time);
            $next_directory_dt->addHours($hours);

//            die("-1 XXZ");
//            if( $next_directory_dt->greaterThan($now_dt) and $directory_files_count == 0) {    // the directory is absolute and empty
            if ($now_dt->greaterThan($next_directory_dt) and $directory_files_count == 0) {    // the directory is absolute and empty
                $deleted_directories_count++;
                if ($is_debug) {
                    echo '<pre>DELETE $next_directory_time::' . print_r($next_directory_time, true) . '</pre>';
                }
                Storage::disk('local')->deleteDirectory($next_directory);
            }

            echo 'next dir <hr><hr>';
        }

        return ['deleted_files_count' => $deleted_files_count, 'deleted_directories_count' => $deleted_directories_count];
    }
} // if (! function_exists('clearDirectoryByPeriod')) {

if ( ! function_exists('deleteEmptyDirectory')) {
    function deleteEmptyDirectory(string $directory_name)
    {
        if ( ! file_exists($directory_name) or ! is_dir($directory_name)) {
            return true;
        }
        $H = OpenDir($directory_name);
        while ($nextFile = readdir($H)) { // All files in dir
            if ($nextFile == "." or $nextFile == "..") {
                continue;
            }
            closedir($H);

            return false; // if there are files can not delete files
        }
        closedir($H);

        return rmdir($directory_name);
    }

} // if (! function_exists('deleteEmptyDirectory')) {

if ( ! function_exists('deleteDirectory')) {
    function deleteDirectory(
        string $directory_name
    ) {
        if ( ! file_exists($directory_name) or ! is_dir($directory_name)) {
            return true;
        }

        $H = OpenDir($directory_name);
        while ($nextFile = readdir($H)) { // All files in dir
            if ($nextFile == "." or $nextFile == "..") {
                continue;
            }
            unlink($directory_name . DIRECTORY_SEPARATOR . $nextFile);
        }
        closedir($H);

        return rmdir($directory_name);
    }


} // if (! function_exists('deleteDirectory')) {

if ( ! function_exists('pregSplit')) {
    function pregSplit(
        string $splitter,
        string $string_items,
        bool $skip_empty = true,
        $to_lower = false
    ): array {
        $retArray = [];
        $a        = preg_split(($splitter), $string_items);
        foreach ($a as $next_key => $next_value) {
            if ($skip_empty and ( ! isset($next_value) or empty($next_value))) {
                continue;
            }
            $retArray[] = ($to_lower ? strtolower(trim($next_value)) : trim($next_value));
        }

        return $retArray;
    }

} // if (! function_exists('pregSplit')) {


if ( ! function_exists('makeStripTags')) {
    function makeStripTags(string $str)
    {
        return strip_tags($str);
    }
} // if (! function_exists('makeStripTags')) {


if ( ! function_exists('myGetType')) {
    function myGetType($var)
    {
        if (is_array($var)) {
            return "array";
        }
        if (is_bool($var)) {
            return "boolean";
        }
        if (is_float($var)) {
            return "float";
        }
        if (is_int($var)) {
            return "integer";
        }
        if (is_null($var)) {
            return "NULL";
        }
        if (is_numeric($var)) {
            return "numeric";
        }
        if (is_object($var)) {
            return "object";
        }
        if (is_resource($var)) {
            return "resource";
        }
        if (is_string($var)) {
            return "string";
        }

        return "unknown type";
    }
} // if (! function_exists('myGetType')) {


if ( ! function_exists('makeClearDoubledSpaces')) {
    function makeClearDoubledSpaces(string $str): string
    {
        return preg_replace("/(\s{2,})/ms", " ", $str);
    }
} // if (! function_exists('makeClearDoubledSpaces')) {


if ( ! function_exists('makeStripslashes')) {
    function makeStripslashes(string $str): string
    {
        return stripslashes($str);
    }
} // if (! function_exists('makeStripslashes')) {


if ( ! function_exists('workTextString')) {
    function workTextString($str, $skip_strip_tags = false)
    {
        if (is_string($str) and ! $skip_strip_tags) {
            $str = makeStripTags($str);
        }
        if (is_string($str)) {
            $str = makeStripslashes($str);
        }
        if (is_string($str)) {
            $str = makeClearDoubledSpaces($str);
        }

        return is_string($str) ? trim($str) : '';
    }

} // if (! function_exists('workTextString')) {


if ( ! function_exists('sendSMSMessageByTwilio')) {
    function sendSMSMessageByTwilio(string $message_str, bool $show_site_name = true)
    {
//        $loggedUser = Auth::user();

        return;

        $message = $message_str;
        if ($show_site_name) {
            $site_name = Settings::getValue('site_name', '');
            $message   = $site_name . " : '" . $message_str;
        }
        // +1 309-518-1423
        $default_phone = '+38 095-9180286'; //+1 309-518-1423

        /*        $newDebugging             = new Debugging();
                $newDebugging->user_id    = $loggedUser->id;

                $newDebugging->info       = $message;
                $newDebugging->type       = 'SMS';
                $newDebugging->save();*/
//        return;  // UNCOMMENT FOR REAL WORK

//        $current_error_reporting = error_reporting(E_ALL ^ E_WARNING);
        Twilio::message($default_phone, $message);
//        error_reporting($current_error_reporting);
//        error_reporting	22527
    } // public function sendSMSMessageByTwilio(string $message_str, bool $show_site_name= true )

} // if (! function_exists('sendSMSMessageByTwilio')) {


if ( ! function_exists('getImageShowSize')) {
    function getImageShowSize(
        string $image_filename,
        int $orig_width,
        int $orig_height
    ): array {
        $retArray  = array('width' => 0, 'height' => 0, 'original_width' => 0, 'original_height' => 0);
        $fileArray = @getimagesize($image_filename);
        if (empty($fileArray)) {
            return $retArray;
        }

        $width  = (int)$fileArray[0];
        $height = (int)$fileArray[1];

        //appUtils::deb($image_filename,'$image_filename::');
        //appUtils::deb($orig_width, '$orig_width::');
        //appUtils::deb($orig_height,'$orig_height::');
        $retArray = array('width' => 0, 'height' => 0, 'original_width' => 0, 'original_height' => 0);
        //Util::deb(sfConfig::get('sf_root_dir'). Util::getSiteRootDir(). $image_filename,'sfConfig::get(sf_root_dir). Util::getSiteRootDir(). $image_filename');

        $fileArray = @getimagesize($image_filename);
        if (empty($fileArray)) {
            return $retArray;
        }

        $width                       = (int)$fileArray[0];
        $height                      = (int)$fileArray[1];
        $retArray['original_width']  = $width;
        $retArray['original_height'] = $height;
        $retArray['width']           = $width;
        $retArray['height']          = $height;
        //appUtils::deb($retArray,'-3 0000:');

        $ratio = round($width / $height, 3);
        //appUtils::deb( $ratio,'-4 $ratio::');

        if ($width > $orig_width) {
            $retArray['width']  = (int)($orig_width);
            $retArray['height'] = (int)($orig_width / $ratio);
            //appUtils::deb($retArray,'-1 $retArray::');
            if ($retArray['width'] <= (int)$orig_width and $retArray['height'] <= (int)$orig_height) {
                //appUtils::deb(-1);
                return $retArray;
            }
            //appUtils::deb(-2);
            $width  = $retArray['width'];
            $height = $retArray['height'];
            //appUtils::deb($retArray,'-2  $retArray::');
        }
        if ($height > $orig_height and ((int)($orig_height / $ratio)) <= $orig_width) {
            //appUtils::deb( $ratio, '-3 $ratio::' );
            $retArray['width']  = (int)($orig_height * $ratio);
            $retArray['height'] = (int)($orig_height);

            //appUtils::deb($retArray,'-3  $retArray::');
            return $retArray;
        }
        if ($height > $orig_height and ((int)($orig_height / $ratio)) > $orig_width) {
            //appUtils::deb( $ratio, '-4  $ratio::' );
            $retArray['width']  = (int)($orig_height * $ratio);
            $retArray['height'] = (int)($retArray['width'] / $ratio);

            //appUtils::deb($retArray,'-4  $retArray::');
            return $retArray;
        }

        return $retArray;

    }

} // if (! function_exists('getImageShowSize')) {

