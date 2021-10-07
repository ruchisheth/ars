<?php
namespace App;
use Collective\Html\HtmlFacade;
use App\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\User;
use Auth;

class Emailer {

	public static function SendEmail($type,$data){
		
		/* OK satus $response->http_response_code=='200' */
    //return false;

    switch ($type) {
      case "superadmin.new_admin":
      return self::send_new_admin($data);
      case "admin.new_fieldrep":
      return self::send_new_fieldrep($data); 
      case "admin.schedule_fieldrep":
      return self::send_schedule_fieldrep($data);  
      case "admin.offer_fieldrep":
      return self::send_offer_fieldrep($data);
      case "fieldrep.submit_survey":
      return self::send_fieldrep_survey_submit($data);
      case "admin.survey_partial":
      return self::send_fieldrep_survey_partial($data);
      case "admin.survey_approved":
      return self::send_fieldrep_survey_approved($data);
      case "invite_payer":
      return self::send_payer_invitation($data);
      case "invite_payee":
      return self::send_payee_invitation($data);
      case "feedback":
      return self::send_feedback($data);

    }

  }

  public static function send_feedback($data){
    //dd($data['send_to']);
    $response = Mail::send('emails.feedback', $data, function($message) use ($data) {
      $message->to($data['send_to'])->subject('You have new Feed back for Store '.$data['site_name']);
      // $message->from('support@alpharepservice.com', 'ARS');
    });
    return $response;
  }

  public static function send_new_admin($data){
    $response = Mail::send('emails.superadmin_new_client', $data, function($message) use ($data) {
    //   $message->to($data['user']->email)->subject('Welcome to ARS');
      $message->to('shethruchi62@gmail.com')->subject('Welcome to ARS');
    });
    return $response;
  }

  public static function send_payer_invitation($data){
   $data = $data->toArray();
   $user = User::find($data['user_id'])->toArray();
   $data['email'] = $user['email'];
   $response = Mail::send('emails.invite_payer', $data, function($message) use ($data) 
   {
    $message->to($data['email'])->subject('Join Submit Your Invoice');
    // $message->from('support@alpharepservice.com', 'ARS');
  });
   return $response;
 }

 public static function send_payee_invitation($data){
   $data = $data->toArray();
   $data['invitation_link'] = Setting::get(['invitaton_link'])->first()->invitaton_link;
   
   $user = User::find($data['user_id'])->toArray();
   $data['email'] = $user['email'];
   //$data['email'] = 'testwts01@gmail.com';
   
   $response = Mail::send('emails.invite_payee', $data, function($message) use ($data) 
   {
    $message->to($data['email'])->subject('Join Submit Your Invoice');
    // $message->from('support@alpharepservice.com', 'ARS');
  });
   return $response;
 }

 public static function send_new_fieldrep($data){
  $response = Mail::send('emails.admin_new_fieldrep', $data, function($message) use ($data) {
    $message->to($data['user']->email)->subject('Welcome to ARS');
    // $message->from('support@alpharepservice.com', 'ARS');
  });
  return $response;
}
public static function send_schedule_fieldrep($data){

  $response = Mail::send('emails.admin_schedule_fieldrep', $data, function($message) use ($data) {
    $message->to($data['details']['fieldrep_email'])->subject('Assignment Scheduled');
    // $message->from('testwts01@gmail.com', 'ARS');
  });
  return $response;
}
public static function send_offer_fieldrep($data){
  $response = Mail::send('emails.admin_offer_fieldrep', $data, function($message) use ($data) {
    $message->to($data['details']['fieldrep_email'])->subject('Assignment Offered');
    // $message->from('support@alpharepservice.com', 'ARS');
  });
  return $response;
}
public static function send_fieldrep_survey_submit($data){
  $response = Mail::send('emails.fieldrep_submit_survey', $data, function($message) use ($data) {
    $message->to($data['details']['client_email'])->subject('Survey Reported');
    // $message->from('support@alpharepservice.com', 'ARS');
  });
  return $response;
}
public static function send_fieldrep_survey_partial($data){
  $response = Mail::send('emails.admin_survey_partial', $data, function($message) use ($data) {
    $message->to($data['details']['fieldrep_email'])->subject('Survey Partial');
    // $message->from('support@alpharepservice.com', 'ARS');
  });
  return $response;
}
public static function send_fieldrep_survey_approved($data){
  $response = Mail::send('emails.admin_survey_approved', $data, function($message) use ($data) {
    $message->to($data['details']['fieldrep_email'])->subject('Survey Approved');
    // $message->from('support@alpharepservice.com', 'ARS');
  });
  return $response;
}
}