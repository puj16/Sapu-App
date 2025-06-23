<?php

namespace App\Http\Controllers;

use App\Models\PushNotification;
use App\Models\PushNotificationMsgs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;


class PushNotificationController extends Controller
{
    //
    public function sendNotification(Request $request)
    {
        
        $auth = [
            'VAPID' => [
                'subject' => 'https://sapu-app.test', // can be a mailto: or your website address
                'publicKey' => 'BKb-3rQok_QrP9KkjbWxRubtdVGbvXqWY2DNFN89oCLf4fB_0w4aLm2tP0QFi4T9PLQoTxdcPOG0pYEppywW-KA', // (recommended) uncompressed public key P-256 encoded in Base64-URL
                'privateKey' => 'yZXxkl-7U7U4idKFdfVNF3YH1ggK4Me-3SqguW35q1s', // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
            ],
        ];

        $webPush = new WebPush($auth);
        // $payload = '{"title":"' . $request->title . '" , "body":"' . $request->body . '" , "url":"./?id=' . $request->idOfProduct . '"}';

        // Construct the payload with the logo
        $payload = json_encode([
            'title' => 'RDKK Siap!',
            'body' => 'RDKK '." ".$request->title."/".$request->idOfProduct." ".'Komoditi'." ".$request->body." ".'siap disalurkan!',
            'url' => './?tahun=' . $request->title.'&komoditi=' . $request->body.'&periode=' . $request->idOfProduct.'#features',
        ]);

        // $msg = new PushNotificationMsgs();
        // $msg->title = $request->title;
        // $msg->body = $request->body;
        // $msg->url = $request->idOfProduct;
        // $msg->save();



        $notifications = PushNotification::all();

        foreach ($notifications as $notification) {
            $webPush->sendOneNotification(
                Subscription::create($notification['subscriptions']),
                $payload,
                ['TTL' => 5000]
            );
        }

        return response()->json(['message' => 'send successfully'], 200);
    }

    public function saveSubscription(Request $request)
    {
        // $id = Auth::user()->id;
        // $user= User::findOrFail($id);
        // $user->subs = 1;
        // $user->save();

        $items = new PushNotification();
        $items->subscriptions = json_decode($request->sub);
        $items->save();

        return response()->json(['message' => 'added successfully'], 200);
    }
}