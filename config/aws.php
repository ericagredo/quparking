<?php

use Aws\Laravel\AwsServiceProvider;


return [
    'credentials' => [
        'key'    => 'AKIAJFPY6MBQR5AG4YBA',
        'secret' => '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',
    ],
    /*
   |--------------------------------------------------------------------------
   | Platform Application Arn For IOS
   |--------------------------------------------------------------------------
   */
    'platformApplicationArnIOS' => 'arn:aws:sns:us-east-1:959774175020:app/APNS_SANDBOX/parkit-ios-dev',

    /*
	|--------------------------------------------------------------------------
	| Platform Application Arn For Android
	|--------------------------------------------------------------------------
	*/
    /*'platformApplicationArnAndroid' => 'arn:aws:sns:us-east-1:756476222167:app/GCM/suvlas-android',*/

    /*
	|--------------------------------------------------------------------------
	| Topic Arn
	|--------------------------------------------------------------------------
	*/
    'topicArn' => 'arn:aws:sns:us-east-1:959774175020:prakit-ios-dev',

    'region' => 'us-east-1',
    'version' => '2010-03-31',

    // You can override settings for specific services
    'Sns' => [
        'region' => 'us-east-1',
    ],
];

