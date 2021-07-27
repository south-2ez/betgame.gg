<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class GiftCodesController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    

    //get list of gift codes
    public function list(Request $request)
    {
        $search = $request->input('search');
        $take = !empty($request->input('take')) ?  $request->input('take') : 15;
        $offset = !empty($request->input('offset')) ?  $request->input('offset') : 0;
        
        $maxTotal = !empty($request->input('maxTotal')) ?  $request->input('maxTotal') : 999999999;
        if($maxTotal == 999999999){
            $maxTotal = \App\GiftCode::count();
        }

        if(!empty($search)){
            $codes = \App\GiftCode::where('code','like','%' .$search. '%')
                                    ->orwhereHas('usedBy' , function($query) use ($search) {
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->with(['usedBy','generatedBy'])
                                    ->get();
        }else{
            $codes = \App\GiftCode::with(['usedBy','generatedBy'])->offset($offset)->take($take)->orderBy('created_at','DESC')->get();
            // $codes = \App\GiftCode::offset($offset)->take($take)->get();
            // $codes->load(['usedBy','generatedBy']);
        }

        $offset += $take;

        return json_encode([
            'codes' => $codes,
            'maxTotal' => $maxTotal,
            'offset' => $offset
        ]);
    }

    //get gift code
    public function get()
    {

    }

    //create or generating gift code
    public function create(Request $request)
    {
        $length = 8;
        $tokenString = str_random($request->input('quantity') * $length);
        $tokens = str_split($tokenString, $length);
        
        $amount = $request->input('amount');
        $purpose = $request->input('purpose');
        $giveTo = $request->input('give_to');
        $description = $request->input('description');
        $current_user_id = \Auth::user()->id;

        $codes = new \stdClass();
        $codes->new = [];
        $codes->exists = [];
        if(!empty($tokens)){
            foreach($tokens as $key => $token){
                $gc = \App\GiftCode::firstOrCreate([
                        'code' => "GC-" . strtoupper($token),
                    ],
                    [
                        'amount' => $amount,
                        'purpose' => $purpose,
                        'give_to' => $giveTo,
                        'status' => 2,
                        'description' => $description,
                        'generated_by' => $current_user_id
                    ]);

                if($gc->wasRecentlyCreated){
                    $codes->new[] = $gc;
                }else{
                    $codes['exists'][] = $gc;
                    $codes->exists[] = $gc;
                }
            }
        }
        return json_encode($codes);
    }

    //updating gift codes
    public function update(Request $request)
    {
        $id = $request->input('id');
        $defaults = $request->all();
        unset($defaults['id']);
        if(!empty($id)){
            $code = \App\GiftCode::find($id);

            if(!empty($code)){
                if($request->status == 2 && $code->status != 0 ){
                    return json_encode([
                        'success' => false,
                        'message' => 'Gift code already used or gifted.'
                    ]);
                }else{
                    $code->update($defaults);
                }
            }else{
                return json_encode([
                    'success' => false,
                    'message' => 'Gift code not found.'
                ]);
            }   


            
        }

        return json_encode([
            'success' => true
        ]);
    }

    //delete gift code
    public function delete(Request $request)
    {
        $id = $request->input('id');
        if(!empty($id)){
            if(is_array($id)){
                $deleted = \App\GiftCode::whereIn('id', $id)->delete();
            }else{
                $deleted = \App\GiftCode::find($id)->delete();
            }
          
        }

        return json_encode([
            'success' => !empty($deleted)
        ]);

    }
}
