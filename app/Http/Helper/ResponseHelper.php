<?php
namespace App\Http\Helper;

use Illuminate\Http\Response;

class ResponseHelper{
    /**
     * send response success
     * @param array $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendResponseSuccess($data=[] , int  $code = 200  , $message=null ){

        $response = self::responseData( true , $code  , $data , $message);
        return response()->json($response, $code);
    }

    /**
     *  Send response error
     * @param array $data
     * @param mixed $message
     * @param mixed $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendResponseError( $data , int  $code  , $message=null ){

        $response = self::responseData( false , $code  , $data , $message);
        return response()->json($response, $code);
    }




    private static function responseData(bool $status , int $status_code , $data , $message){

        $message = $message ? $message : Response::$statusTexts[$status_code];

        return  [
            'status' =>  $status,
            'status_code'=>$status_code,
            'data'    => $data,
            'message' =>  $message,
        ];
    }


}
