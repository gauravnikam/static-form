<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StaticFormRequest;
use Validator;
use App\Models\form_access_key;
use App\Models\form_message;
use Mail;
use App\Mail\SendData;
use App\Mail\MailConfirmation;


class StaticFormController extends Controller
{

    public function send(Request $request){

        
        //Validate request Parameters
        $validator = $this->validate_inputs($request);   
        if ($validator->fails()) {           
            $validation_error = $validator->errors()->all();            
            if($request->ajax() || $request->wantsJson()){
                return response()->json(['status'=>0,'message'=>$validation_error[0]], 422);
            }//End of if case
            return redirect()->back()->with('error',$validation_error[0]);
        }

        //Check Honepot Parameter For Spam Attack
        if($request->honeypot){
            if($request->ajax() || $request->wantsJson()){
                return response()->json(['status'=>0,'message'=>"HoneyPot Attack Detected."], 422);
            }//End of if case
            return redirect()->back()->with('error','HoneyPot Attack Detected.');
        }
         
        $validate_access_key=form_access_key::validate_access_key($request->accessKey);
        if($validate_access_key==-1){
            if($request->ajax() || $request->wantsJson()){
                return response()->json(['status'=>0,'message'=>"Invalid Access Key."], 422);
            }//End of if case
            return redirect()->back()->with('error','Invalid Access Key.');
        }//End of if case

        //Send mail
        $mail_status = $this->email($request->all());

        $insert = $this->insert($request->all(),$mail_status,$validate_access_key);

        if($mail_status){
            if($request->ajax() || $request->wantsJson()){
                return response()->json(['status'=>1,'message'=>"Info mailed Successfully"], 200);
            }//End of if case
            return redirect()->back()->with('message','Info. Successfuly mailed');
        }else{
            if($request->ajax() || $request->wantsJson()){
                return response()->json(['status'=>0,'message'=>"Failed to sent an email."], 200);
            }//End of if case
            return redirect()->back()->with('message','Failed to sent an email');
        }

    }//End of function

    public function insert($data,$mail_status,$accesskey){

        $Message = new form_message();
        $Message->access_key = $accesskey;
        $Message->email = $data['email'];
        $Message->confirmation_mail = $data['confirmationMail'];
        $Message->honeypot = $data['honeypot'];
        $Message->status = $mail_status.'';
        $Message->full_data = json_encode($data);
        
        if($Message->save()){
            return true;
        }
        return false;
    
    }//End of function


    public function email($all_data){

        try{
            Mail::to($all_data['email'])->send(new SendData($all_data) );
            $mail_status=1;
            Mail::to($all_data['confirmationMail'])->send(new MailConfirmation());
        }catch(\Exception $e){
            $mail_status=0;
        }

        return $mail_status;
    
    }//End of function


    public function validate_inputs($request){

        return Validator::make($request->all(), [
            'email' => 'required|email',
            'confirmationMail' => 'required|email',
            'honeypot' => 'nullable',
            'accessKey' => 'required',
        ])->stopOnFirstFailure(true);

    }


}//End of class
