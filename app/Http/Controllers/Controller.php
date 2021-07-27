<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use App\Mail\SendPartnerForm;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function sendPartnerForm(Request $request) {
        $rules = [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|not_throw_away',
            'mobile_number' => 'required',
            'captcha' => 'required|captcha',
            'address' => 'required'
        ];

        $type = !empty($request->input('type')) ? $request->input('type') : '';
        
        $messages = [ '*' => 'This field is required.'];
        switch($type){
            case 'Betting Agents':
                $rules['has-community'] = 'required';
                $rules['region'] = 'required';
                $rules['legal-members'] = 'required';
                $rules['total-members'] = 'required';
                $rules['total-bettors'] = 'required';
                $rules['agent-before'] = 'required';
                $rules['total-years-website'] = 'required';
                $rules['payment-methods'] = 'required';
                $rules['amount-transactions'] = 'required';
                $rules['can-process-247'] = 'required';
                $rules['other-businesses'] = 'required';
                $rules['profession'] = 'required';
                break;
            case 'Reseller': 
                $rules['has-capital-5k'] = 'required';
                $rules['have-friends-interested'] = 'required';
                $rules['other-businesses-reseller'] = 'required';
                $rules['full-part-time-reseller'] = 'required';
                $rules['payment-methods-reseller'] = 'required';
                $rules['has-community-reseller'] = 'required';
                $rules['region-reseller'] = 'required';
                $rules['legal-members-reseller'] = 'required';
                $rules['total-members-reseller'] = 'required';
                break;
            case 'Streamer';
                $rules['streamer-platforms'] = 'required';
                $rules['streamer-hours-in-month'] = 'required';
                $rules['streamer-page-link'] = 'required';
                $rules['streamer-followers'] = 'required';                
                $rules['streamer-games'] = 'required';
                $rules['streamer-hours-dota2'] = 'required';
                $rules['streamer-hours-csgo'] = 'required';
                $rules['cast-tournaments'] = 'required';
                $rules['cast-tournaments-dota2-hours'] = 'required';
                $rules['have-existing-betting-sponsor'] = 'required';
                $rules['earnings-from-betting-sponsor'] = 'required';
                $rules['expected-compensation'] = 'required';
                break;

            case 'Group Moderator';
                $rules['moderated-community-before'] = 'required';
                $rules['community-managed'] = 'required';
                $rules['community-members'] = 'required';
                $rules['community-how-long-managing'] = 'required';
                $rules['community-managed-link'] = 'required';
                $rules['community-management-suggestion'] = 'required';
                $rules['community-management-suggestion-detailed'] = 'required';
                $rules['number-group-moderators'] = 'required';
                $rules['group-mod-months-betting'] = 'required';
                $rules['group-mod-do-you-stream'] = 'required';
                $rules['group-mod-aspiring-streamer'] = 'required';
                $rules['group-mod-stream-and-giveaways'] = 'required';
                $rules['group-mod-work-from-home'] = 'required';
                $rules['group-mod-pc-specs'] = 'required';
                $rules['group-mod-internet-speed'] = 'required';
                $rules['group-mod-legal-age'] = 'required';
                $rules['group-mod-get-employed'] = 'required';
                break;
     


            default:
                break;
        }

        $validation = \Validator::make($request->all(), $rules, $messages);
        if ($validation->passes()) {
            $form = $request->all();
            $user = [
                'name' => $request->fname . ' ' . $request->lname,
                'email' => $request->email
            ];
            
            if( !app()->environment('prod') ){
                \Mail::to('south.2ez@gmail.com')->send(new SendPartnerForm($form));
            }else{
                \Mail::to('admin@2ez.bet')->send(new SendPartnerForm($form));
            }
           
            return ['success' => true];
        } else {
            return [
                'success' => false,
                'new_captcha_image' => captcha_img('flat'),
                'errors' => $validation->errors()
            ];
        }
    }
}
