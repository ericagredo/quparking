<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Aws\Sns\SnsClient;
use Aws\Credentials\CredentialProvider;


class DeviceMaster extends Model
{
    protected $table = 'device_master';
    public $timestamps = false;
    protected $fillable = ['users_id', 'user_type', 'device_token', 'gcm_key', 'gcm_arn', 'is_login'];

    public static function sendPushNotification($gcm_arn, $message = '', $data = null)
    {

        $AmazonConfigs = config('aws');

        $aws = new SnsClient([
            'region' => 'us-east-1',
            'version' => '2010-03-31',
            'credentials' => CredentialProvider::env()
        ]);

        $id = 10;

        $merged_array_apns = array_merge(array("alert" => $message, "id" => $id), $data);
        // Array For GCM
        $merged_array_gcm = $merged_array_apns;
        $merged_array_gcm['message'] = $message;

        // Make Message
        $msg = array('default' => $message,
            "APNS_SANDBOX" => json_encode(array("aps" => $merged_array_apns)),
            "APNS" => json_encode(array("aps" => $merged_array_apns)),
            "GCM" => json_encode(array("data" => $merged_array_gcm))
        );

        // Push
        $sns = $aws->publish([
            'TargetArn' => $gcm_arn,
            'Message' => json_encode($msg),
            'Subject' => 'Park It',
            'MessageStructure' => 'json',
            'MessageAttributes' => array(),
        ]);

        return $sns;

    }
}
