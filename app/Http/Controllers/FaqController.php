<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Faq;

class FaqController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin', 
            [
                'except' => [
                    'faqPage',
                    'getExistingFaqs',
                    'termsandconditions',
                    'viewTOSContent'
                ]
            ]
        );
    }

    public function faqPage()
    {
        return view('others.faqpage');
    }

    public function getExistingFaqs(){
        $faq = Faq::select('id', 'question', 'answer', 'created_at')->get();
        return ['faq' => $faq];
    }

    public function addNewQuestion(Request $request){
        $rules = [
            'question' => 'required',
            'answer' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()){
            $faq = new Faq;
            $faq->question = $request->question;
            $faq->answer = $request->answer;
            $faq->added_by = \Auth::user()->id;
            $faq->save();

            return ['success' => true];
        }
        else{
            return ['success' => false, 'errors' => $validator->errors()];
        }  
    }

    public function updateQuestion(Request $request){
        $rules = [
            'question' => 'required',
            'answer' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->passes()){
            $faq = Faq::find($request->id);
            $faq->question = $request->question;
            $faq->answer = $request->answer;
            $faq->save();

            return ['success' => true];
        }
        else{
            return ['success' => false, 'errors' => $validator->errors()];
        }  
    }
    
    public function updateTOS(Request $request) {
        $rules = [
            'content' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $tos = \App\LegalDocument::where('type', 'tos')->first();
            if ($tos) {
                $tos->contents = $request->content;
                $tos->editor_id = \Auth::user()->id;
                $tos->save();

                return ['success' => true];
            } else
                return ['success' => false];
        } else {
            return ['success' => false, 'errors' => $validator->errors()];
        }
    }

    public function viewTOSContent() {
        $tos = \App\LegalDocument::where('type', 'tos')->first();
        return $tos ? $tos->contents : '';
    }

    public function deleteQuestions(Request $request){
        $input = $request->all();
        if(isset($input['list'])){
            $list = collect($input['list']);
            foreach ($list as $id) {
                $faq = Faq::where('id', $id)->first();
                $faq->delete();
            }
            return ['success' => true, 'message' => $list->count() > 1 ? $list->count().' FAQs have been deleted.': 'An FAQ has been deleted.'];
        }
        else{
            return ['success' => false, 'message' => 'Select at least one FAQ to be deleted.'];
        }
    }
}
