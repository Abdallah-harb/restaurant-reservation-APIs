<?php
namespace App\Http\Helper;

use App\Models\LogActivity;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
class HelperApp
{
    // public static function set_log(string $type , string $message_ar , string $message_en){
    //     $description = [
    //         "ar"=>$message_ar,
    //         "en"=>$message_en
    //     ];
    //     LogActivity::create([
    //         'type'=>$type,
    //         "description"=>$description,
    //         "admin_id"=>auth('admin')->id()
    //     ]);
    // }


    public static function send_fcm( $title , $message , $fcm_token){


        Http::acceptJson()->withToken(config('services.fcm.key'))->post(
            'https://fcm.googleapis.com/fcm/send',
            [
                'to' => $fcm_token,
                'notification' =>[
                    'title'=>$title,
                    "body"=>$message
                ],
            ]
        );

    }


}
