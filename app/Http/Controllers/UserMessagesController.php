<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class UserMessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request)
    {
        $user_id = Auth()->user()->id;
        $hasMessage = false;
        $messages = [];

        if(!empty($user_id)){
            $hasMessage = \App\UserMessages::where([
                'user_id' => $user_id,
                'status' => 0
            ])->exists();

            if($hasMessage){
                $getMessages =  \App\UserMessages::where([
                    'user_id' => $user_id,
                    'status' => 0
                ])->get();
                    
                foreach($getMessages as $msg){
                    $msg->date_sent = $msg->created_at->diffForHumans();
                    $messages[] = $msg;
                }
            }
        }
            
        return json_encode([
            'hasMessage' => $hasMessage,
            'messages' => $messages
        ]);
    }
    
    public function create(Request $request)
    {
        $currentUser = Auth()->user();
        
        if(!$currentUser->isAdmin()){
            return abort(403);
        }

        $from_user_id = !empty($currentUser) ? $currentUser->id : 0;
        $message = $request->input('message');
        $user_id = $request->input('id');
        
        $newMessage = \App\UserMessages::create([
            'from_user_id' => $from_user_id,
            'user_id' => $user_id,
            'message' => $message
        ]);

        if(!empty($newMessage)){
            return json_encode([
                'success' => true,
                'message' => 'Message sent successfully.'
            ]);
        }else{
            return json_encode([
                'success' => false,
                'message' => 'Failed to send new message. Try again.'
            ]);
        }
        
    }

    public function update(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }

    public function read(Request $request)
    {
        $user_id = Auth()->user()->id;
        $markRead = \App\UserMessages::where('user_id', $user_id)->update(['status' => 1]);

        return json_encode([
            'success' => $markRead,
            'message' => 'Mark messages as read.'
        ]);
    }

}
