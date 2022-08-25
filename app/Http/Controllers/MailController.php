<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\RecoveryMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MailController extends Controller
{
    public function send(Request $request)
    {


        $objDemo = new \stdClass();
        $objDemo->sender = 'Corpozulia@Adidot';
        $objDemo->receiver = 'ReceiverUserName';

        if ($request->has('type') == 'email') {
            $user = DB::table('trabajadors')->where('email', $request->value)->first();
        }
        if ($request->has('type') == 'cedula') {
            $user = DB::table('trabajadors')->where('cedula', $request->value)->first();
        }
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);

        $token = DB::table('password_resets')->where('email', $user->email)->first();
        $objDemo->receiver = $user->email;
        //url('') -> gets url from env file.
        $objDemo->link = 'http://localhost:3000/password/reset/' . $token->token . '?email=' . urlencode($user->email);

        try {

            // Mail::to($user->email)->send(new RecoveryMail($objDemo), ['token' => $token]);

            return response()->json([
                'message' => $objDemo->link
            ]);
        } catch (\PDOException $e) {
            return response()->json([
                'message' => 'Mensaje fallido'
            ], 500);
        }
    }
}
