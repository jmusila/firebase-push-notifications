<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $serverKey;
 
    public function __construct()
    {
        $this->serverKey = config('firebase.firebase_server_key');
    }

    public function saveToken (Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->device_token = $request->fcm_token;
        $user->save();

        if($user)
            return response()->json([
                'message' => 'User token updated'
            ]);

        return response()->json([
            'message' => 'Error!'
        ]);
    }

    public function sendPush (Request $request)
    {
        $user = User::find($request->id);
        $data = [
            "to" => $user->device_token,
            "notification" =>
                [
                    "title" => 'Test notification',
                    "body" => "Sample Notification",
                    "icon" => url('/firebase_logo.png')
                ],
        ];
        $dataString = json_encode($data);
  
        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json',
        ];
  
        $ch = curl_init();
  
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
  
        curl_exec($ch);

        return redirect('/home')->with('message', 'Notification sent!'); 
    }
}
