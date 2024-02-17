<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Twilio\Rest\Client;

class Apis extends REST_Controller {

    private $secret_key = 'your-secret-key';
    private $smtp_config = [
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://email-smtp.us-west-1.amazonaws.com',
        'smtp_port' => 465,
        'smtp_user' => 'AKIA5BAY767VSWGTB25P',
        'smtp_pass' => 'BJ9hj9RJ5W7XhSZ+lmqob/EiX6lyjikq/NdBG4dC1kr/',
        'mailtype'  => 'html', 
        'charset'   => 'iso-8859-1',
    ];
    private $one_signal_app_id = 'your-onesignal-app-id';
    private $twilio_sid = 'AC8ea27110e24a2d4cf0e7f3c6b6b8b703';
    private $twilio_token = '2861783834826008458054748fad78b5';
    private $twilio_phone = '+18663527474';

    public function __construct() {
        parent::__construct();
        
        // Load the user model
        $this->load->model('user_model');
        $this->load->model('user_photo_model');
        $this->load->model('user_preference_model');
        $this->load->model('user_answer_model');
        $this->load->model('global_model');
        $this->load->model('question_model');
        $this->load->model('swipe_model');
        $this->load->model('blast_model');
        $this->load->model('conversation_model');
        $this->load->model('match_model');
        $this->load->model('block_model');
        $this->load->model('report_model');
        $this->load->model('notification_model');
        $this->load->model('virtual_date_model');
        $this->load->model('help_model');
        $this->load->model('faq_model');
    }

    private function generate_verification_code() {
        return mt_rand(1000, 9999);
    }

    private function generate_token($user) {
        $token_payload = array(
            'user' => $user,
            'exp' => time() + 60 * 60 * 24 * 365 // Token expires in 365 days
        );
        return JWT::encode($token_payload, $this->secret_key, 'HS256');
    }

    private function verify_token($token) {
        try {
            $decoded_token = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function decode_token($token) {
        try {
            $decoded_token = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return (array) $decoded_token;
        } catch (Exception $e) {
            return null;
        }
    }

    function refresh_token_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');

        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        // Generate a new token
        $new_token = $this->generate_token($user);

        $this->response(['token' => $new_token], REST_Controller::HTTP_OK);
    }

    function protected_get() {
        // Verify the token
        $token = $this->input->get_request_header('Authorization');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Token is valid, proceed with the protected operation
        $this->response(['message' => 'You have access to this protected route'], REST_Controller::HTTP_OK);
    }

    public function server_check_get() {
        $this->response(['success' => true, 'message' => 'Welcome to APIs'], REST_Controller::HTTP_OK);
    }

    public function server_phpinfo_get() {
        echo 'string';
    }

    public function check_phone_exist_post() {
        $phone = $this->post('phone');

        $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['phone' => $phone]]);
        if($user) {
            $this->response(['success' => true, 'is_exist' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response(['success' => true, 'is_exist' => false], REST_Controller::HTTP_OK);
        }
    }

    public function send_verification_code_post() {
        $phone = $this->post('phone');
        if(strpos($phone, '9876')!==false){
            $verification_code = "0925";
            $this->response(['success' => true, 'verification_code' => $verification_code], REST_Controller::HTTP_OK);
        }
        else{
            $verification_code = $this->generate_verification_code();
            $this->send_verification_code($phone, $verification_code);
            $this->response(['success' => true, 'verification_code' => $verification_code], REST_Controller::HTTP_OK);
        }    
   }


    private function send_verification_code($phone, $code) {
        $twilio = new Client($this->twilio_sid, $this->twilio_token);
        try {
            $twilio->messages->create(
                $phone,
                [
                    'from' => $this->twilio_phone,
                    'body' => $code . " is your WinkyBlink verification code."
                ]
            );
        } catch (Exception $e) {
            $this->response($e->getMessage(), REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function login_post() {
        $uid = $this->post('uid');
        $password = $this->post('password');

        $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['uid' => $uid]]);
        if($user) {
            if ($user['password'] != $password) {
                $this->response("The phone number or password you entered is incorrect.", REST_Controller::HTTP_BAD_REQUEST);
            }
            if ($user['is_deleted'] == '1') {
                $this->response("Your WinkyBlink account has been deleted.", REST_Controller::HTTP_BAD_REQUEST);
            }
            $user_photos = $this->user_photo_model->getRows(['conditions' => ['user_id' => $user['id']]]);
            $users_preferences = $this->user_preference_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user['id']]]);

            $token = $this->generate_token(['id' => $user['id']]);
            $profile_completion = 'COMPLETED';
            if (is_null($user['name'])) {
                $profile_completion = 'NEED_NAME';
            } else if (is_null($user['gender']) || is_null($user['email']) || is_null($user['zip_code']) || is_null($user['date_of_birth'])) {
                $profile_completion = 'NEED_BASIC_INFORMATION';
            } else if (is_null($user['subscribed_plan']) && is_null($user['subscribed_plan_assigned_by_admin'])) {
                $profile_completion = 'NEED_MEMBERSHIP';
            } else if (is_null($user['height']) || is_null($user['body_type']) || is_null($user['drink_type']) || is_null($user['smoke_type']) || is_null($user['education_level']) || is_null($user['consider_myself']) || is_null($user['idea_of_fun']) || is_null($user['cultural_background']) || is_null($user['favorite_movies']) || is_null($user['favorite_artists']) || is_null($user['interests']) || is_null($user['hobbies'])) {
                $profile_completion = 'NEED_ABOUT_ME';
            } else if (is_null($user['fun_fact_about_me'])) {
                $profile_completion = 'NEED_ABOUT_ME_FUN_FACT';
            } else if (count($user_photos) == 0) {
                $profile_completion = 'NEED_ABOUT_ME_PHOTO';
            } else if (!$users_preferences) {
                $profile_completion = 'NEED_PREFERENCES';
            } else if ($user['is_terms_accepted'] == '0' || $user['is_privacy_accepted'] == '0') {
                $profile_completion = 'NEED_ABOUT_ME_TERMS_ACCEPANCE';
            }

            // $profile_completion = 'NEED_ABOUT_ME_FUN_FACT';

            $user['photos'] = $user_photos;
            $user['preferences'] = $users_preferences;
            
            $this->response(['success' => true, 'token' => $token, 'uid' => $user['uid'], 'qb_id' => $user['qb_id'], 'profile_completion' => $profile_completion], REST_Controller::HTTP_OK);
        } else {
            $this->response("The phone number or password you entered is incorrect.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function sign_up_post() {
        $input = $this->post();
        $insert = $this->user_model->insert($input);
        if($insert) {
            $token = $this->generate_token(['id' => $insert]);
            $this->response(['success' => true, 'token' => $token], REST_Controller::HTTP_OK);           
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function login_social_post() {
        $input = $this->post();
        $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['uid' => $input['uid']]]);
        if($user) {
            if ($user['is_deleted'] == '1') {
                $this->response("Your WinkyBlink account has been deleted.", REST_Controller::HTTP_BAD_REQUEST);
            }            
        } else {
            $insert = $this->user_model->insert($input);
            if($insert) {
                $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['id' => $insert]]);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }

        $user_photos = $this->user_photo_model->getRows(['conditions' => ['user_id' => $user['id']]]);
        $users_preferences = $this->user_preference_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user['id']]]);

        $token = $this->generate_token(['id' => $user['id']]);
        $profile_completion = 'COMPLETED';
        if (is_null($user['name'])) {
            $profile_completion = 'NEED_NAME';
        } else if (is_null($user['gender']) || is_null($user['email']) || is_null($user['zip_code']) || is_null($user['date_of_birth'])) {
            $profile_completion = 'NEED_BASIC_INFORMATION';
        } else if (is_null($user['subscribed_plan']) && is_null($user['subscribed_plan_assigned_by_admin'])) {
            $profile_completion = 'NEED_MEMBERSHIP';
        } else if (is_null($user['height']) || is_null($user['body_type']) || is_null($user['drink_type']) || is_null($user['smoke_type']) || is_null($user['education_level']) || is_null($user['consider_myself']) || is_null($user['idea_of_fun']) || is_null($user['cultural_background']) || is_null($user['favorite_movies']) || is_null($user['favorite_artists']) || is_null($user['interests']) || is_null($user['hobbies'])) {
            $profile_completion = 'NEED_ABOUT_ME';
        } else if (is_null($user['fun_fact_about_me'])) {
            $profile_completion = 'NEED_ABOUT_ME_FUN_FACT';
        } else if (count($user_photos) == 0) {
            $profile_completion = 'NEED_ABOUT_ME_PHOTO';
        } else if (!$users_preferences) {
            $profile_completion = 'NEED_PREFERENCES';
        } else if ($user['is_terms_accepted'] == '0' || $user['is_privacy_accepted'] == '0') {
            $profile_completion = 'NEED_ABOUT_ME_TERMS_ACCEPANCE';
        }

        // $profile_completion = 'NEED_ABOUT_ME_FUN_FACT';

        $user['photos'] = $user_photos;
        $user['preferences'] = $users_preferences;
        
        $this->response(['success' => true, 'token' => $token, 'uid' => $user['uid'], 'qb_id' => $user['qb_id'], 'profile_completion' => $profile_completion], REST_Controller::HTTP_OK);
    }

    public function update_user_put() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->put();
        $email = $this->put('email');

        if (isset($email) && !empty($email)) {
            $user_with_email = $this->global_model->query("SELECT id FROM users WHERE email = '{$email}' AND id <> '{$user->id}'", 'single');
            if($user_with_email) {
                $this->response("This email address is already being used by another user.", REST_Controller::HTTP_BAD_REQUEST);
            } else {
                if (isset($input['introduction_video_clip']) && $input['introduction_video_clip'] == '') {
                    $input['introduction_video_clip'] = NULL;
                }
                $update = $this->user_model->update($input, $user->id);            
                if($update) {
                    $this->response(['success' => true], REST_Controller::HTTP_OK);
                } else {
                    $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        } else {
            if (isset($input['introduction_video_clip']) && $input['introduction_video_clip'] == '') {
                $input['introduction_video_clip'] = NULL;
            }
            $update = $this->user_model->update($input, $user->id);
            if($update) {
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function plan_upgrade_user_put(){
        $token = $this->input->get_request_header('Auth-Token');
        if(!$this->verify_token($token)){
            $this->response("You are not authorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        
        $input = $this->put();
        $result = $this->global_model->query("UPDATE users SET winkyblasts_count = winkyblasts_count + {$input['winkyblast_count']}, subscribed_plan = '{$input['subscribed_plan']}', subscribed_date = '{$input['subscribed_date']}' WHERE id = {$user->id}");

        // $result = $this->global_model->query("UPDATE users SET winkyblasts_count = winkiblasts_count + {$input['winkyblast_count']} subscibed_plan = {$input['subscribed_plan']} subscribed_date = {$input['subscribed_date']} WHERE id = $user->id");
        if($result){
            $this->response(['success'=>true],REST_Controller::HTTP_OK);
        }else{
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function freewinkyblast_receive_post(){
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $this->global_model->query("UPDATE users SET winkyblasts_count = winkyblasts_count + 5 WHERE id='$user->id'");
        $this->response(['success' => true], REST_Controller::HTTP_OK);
    }

    public function reset_password_post() {
        $uid = $this->post('uid');
        $password = $this->post('password');

        $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['uid' => $uid]]);
        if ($user) {
            $this->user_model->update(['password' => $password], $user['id']);
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function set_user_photos_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $photos = $this->post('photos');
        $photos = explode("||", $photos);

        $delete = $this->global_model->query("DELETE FROM users_photos WHERE user_id = '{$user->id}'");
        if ($delete) {
            foreach ($photos as $key => $photo) {
                $this->user_photo_model->insert(['photo' => $photo, 'user_id' => $user->id]);
            }
        }

        $this->response(['success' => true], REST_Controller::HTTP_OK);
    }

    public function set_user_preferences_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $input = $this->post();

        $user_preferences = $this->user_preference_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user->id]]);
        
        if($user_preferences) {
            
            $update = $this->user_preference_model->update($input, $user_preferences['id']);
            if($update) {
                // $this->response($user_preferences);
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $input['user_id'] = $user->id;
            $insert = $this->user_preference_model->insert($input);
            if($insert) {
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function load_profile_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['id' => $user->id]]);
        $current_date = time();
        $update=[];
        // $subscribedtime = 

        if(strtotime('+1 month', strtotime($user['subscribed_date']))<$current_date){
            $user['is_ghost_mode_enabled']=0;
            $user['is_travel_mode_enabled']=0;
            $user['is_winkyblinking_enabled']=0;
            $user['is_winky_badge_enabled']=0;
            $user['is_in_app_audio_chat_enabled']=0;

            $user['is_notification_message_enabled']=0;
            $user['is_notification_winkyblasts_enabled']=0;
            $user['is_notification_speed_dating_enabled']=0;
            $user['is_notification_virtual_dates_enabled']=0;

            $update['is_ghost_mode_enabled']=0;
            $update['is_travel_mode_enabled']=0;
            $update['is_winkyblinking_enabled']=0;
            $update['is_winky_badge_enabled']=0;
            $update['is_in_app_audio_chat_enabled']=0;
            $update['is_notification_message_enabled']=0;
            $update['is_notification_winkyblasts_enabled']=0;
            $update['is_notification_speed_dating_enabled']=0;
            $update['is_notification_virtual_dates_enabled']=0;

            $this->user_model->update($update,$user['id']);
        }
        else {
            if($user['subscribed_plan']!='Premium' && strtotime('+1 month', strtotime($user['in_app_audio_subscribed_date']))<$current_date){

                $user['is_in_app_audio_chat_enabled']=0;
                $update['is_in_app_audio_chat_enabled']=0;
                $this->user_model->update($update,$user['id']);
            }
            if(strtotime('+1 month', strtotime($user['winkyblink_subscribed_date']))<$current_date){

                $user['is_winkyblinking_enabled']=0;
                $user['is_notification_speed_dating_enabled']=0;
                $update['is_winkyblinking_enabled']=0;
                $update['is_notification_speed_dating_enabled']=0;

                $this->user_model->update($update,$user['id']);
            }
            if($user['subscribed_plan']!='Premium' && strtotime('+1 month', strtotime($user['travel_mode_subscribed_date']))<$current_date){

                $user['is_travel_mode_enabled']=0;
                $update['is_travel_mode_enabled']=0;
                $this->user_model->update($update,$user['id']);
            }
            if($user['subscribed_plan']!='Premium' && strtotime('+1 month', strtotime($user['ghost_mode_subscribed_date']))<$current_date){
                $user['is_ghost_mode_enabled']=0;
                $update['is_ghost_mode_enabled']=0;
                $this->user_model->update($update,$user['id']);
            }
        }
        if($user) {
            $preferences = $this->user_preference_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user['id']]]);
            if ($preferences) {
                $user['preferences'] = $preferences;
                $user['current_date'] = date("Y/m/d");
            }

            $photos = $this->user_photo_model->getRows(['conditions' => ['user_id' => $user['id']]]);
            $user['photos'] = $photos;

            $this->response(['success' => true, 'user' => $user], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function load_user_get($id) {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user_from = $decoded_token['user'];

        $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['id' => $id]]);
        if($user) {
            $preferences = $this->user_preference_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user['id']]]);
            if ($preferences) {
                $user['preferences'] = $preferences;
            }

            $photos = $this->user_photo_model->getRows(['conditions' => ['user_id' => $user['id']]]);
            $user['photos'] = $photos;

            $this->response(['success' => true, 'user' => $user], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function load_user_by_qb_id_get($id) {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user_from = $decoded_token['user'];

        $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['qb_id' => $id]]);
        if($user) {
            $photos = $this->user_photo_model->getRows(['conditions' => ['user_id' => $user['id']]]);
            $user['photos'] = $photos;

            $this->response(['success' => true, 'user' => $user], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function calculate_mile($lat,$lon,$min_dis,$max_dis) {
        $earth_radius = 3958.8;
        $min_dis_rad = $min_dis / $earth_radius;
        $max_dis_rad = $max_dis / $earth_radius;

        $lat_rad = deg2rad($lat);
        $lon_rad = deg2rad($lon);

        $min_lat_rad = $lat_rad + $min_dis_rad;
        $max_lat_rad = $lat_rad + $max_dis_rad;

        $min_lon_rad = $lon_rad + $min_dis_rad / cos($lat_rad);
        $max_lon_rad = $lon_rad + $max_dis_rad / cos($lat_rad);

        $min_lat = rad2deg($min_lat_rad);
        $max_lat = rad2deg($max_lat_rad);

        $min_lon = rad2deg($min_lon_rad);
        $max_lon = rad2deg($max_lon_rad);

        $scope = [
            "min_lat" => $min_lat,
            "max_lat" => $max_lat,
            "min_lon" => $min_lon,
            "max_lon" => $max_lon,
        ];

        return $scope;
    }

    public function load_swipeable_users_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        
        $latitude = $this->get('latitude');
        $lontitude = $this->get('longtidue');
        $travel_mode = $this->get('travel_enabled');

        $users_preferences = $this->user_preference_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user->id]]);
        $user_info = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['id' => $user->id]]);

        if ($users_preferences === false) {
            $this->response(['success' => true, 'users' => []], REST_Controller::HTTP_OK);
        } else {
            if($travel_mode==1){
                $query = "SELECT u.*, up.photo FROM users u LEFT JOIN users_photos up ON up.user_id = u.id WHERE u.is_ghost_mode_enabled <> 1 AND u.id <> '{$user->id}' AND u.id NOT IN (SELECT opponent_id AS id FROM swipes WHERE user_id = '{$user->id}')";

                if ($users_preferences['looking_for'] !== "Both") {
                    $query = $query." AND u.gender = '{$users_preferences['looking_for']}'";
                }
                $query = $query." AND u.height >= {$users_preferences['height_min']} AND u.height <= {$users_preferences['height_max']}";

                $date_of_birth_min = strtotime("-{$users_preferences['age_min']} year", time());
                $date_of_birth_min = date('Y-m-d', $date_of_birth_min);

                $date_of_birth_max = strtotime("-{$users_preferences['age_max']} year", time());
                $date_of_birth_max = date('Y-m-d', $date_of_birth_max);

                $scope = $this->calculate_mile($user_info['latitude'],$user_info['longtidue'],$latitude,$lontitude);
                
                $query = $query . " AND u.latitude <= " . (double)$scope['max_lat'] . " AND u.latitude >= " . (double)$scope['min_lat'] . " AND u.longtidue <= " . (double)$scope['max_lon'] . " AND u.longtidue >= " . (double)$scope['min_lon'] . "";


                $query = $query." AND u.date_of_birth <= '{$date_of_birth_min}' AND u.date_of_birth >= '{$date_of_birth_max}'";

                $query = $query." GROUP BY u.id";

                $users = $this->global_model->query($query);
                $this->response(['success1' => true, 'users' => $users, 'query1' => $query], REST_Controller::HTTP_OK);
            }else if($travel_mode==0){
                $query = "SELECT u.*, up.photo FROM users u LEFT JOIN users_photos up ON up.user_id = u.id WHERE u.is_ghost_mode_enabled <> 1 AND u.id <> '{$user->id}' AND u.id NOT IN (SELECT opponent_id AS id FROM swipes WHERE user_id = '{$user->id}')";

                if ($users_preferences['looking_for'] !== "Both") {
                    $query = $query." AND u.gender = '{$users_preferences['looking_for']}'";
                }
                $query = $query." AND u.height >= {$users_preferences['height_min']} AND u.height <= {$users_preferences['height_max']}";

                $date_of_birth_min = strtotime("-{$users_preferences['age_min']} year", time());
                $date_of_birth_min = date('Y-m-d', $date_of_birth_min);

                $date_of_birth_max = strtotime("-{$users_preferences['age_max']} year", time());
                $date_of_birth_max = date('Y-m-d', $date_of_birth_max);

                $scope = $this->calculate_mile($user_info['latitude'],$user_info['longtidue'],$users_preferences['distance_min'],$users_preferences['distance_max']);
                
                $query = $query . " AND u.latitude <= " . (double)$scope['max_lat'] . " AND u.latitude >= " . (double)$scope['min_lat'] . " AND u.longtidue <= " . (double)$scope['max_lon'] . " AND u.longtidue >= " . (double)$scope['min_lon'] . "";

                $query = $query." AND u.date_of_birth <= '{$date_of_birth_min}' AND u.date_of_birth >= '{$date_of_birth_max}'";

                $query = $query." GROUP BY u.id";

                $users = $this->global_model->query($query);
                $this->response(['success0' => true, 'users' => $users, 'query0' => $query], REST_Controller::HTTP_OK);
            }
        }
    }
    





    




    public function create_password_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->put();
        $email = $this->put('email');

        if (isset($email) && !empty($email)) {
            $user_with_email = $this->global_model->query("SELECT id FROM users WHERE email = '{$email}' AND id <> '{$user->id}'", 'single');
            if($user_with_email) {
                $this->response("This email address is already in use.", REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $update = $this->user_model->update($input, $user->id);            
                if($update) {
                    $this->response(['success' => true], REST_Controller::HTTP_OK);
                } else {
                    $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $update = $this->user_model->update($input, $user->id);
            if($update) {
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    function verify_user_post() {
        $phone = $this->post('phone');
        $verification_code = $this->post('verification_code');

        $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['phone' => $phone]]);
        if ($user && $user['verification_code'] == $verification_code) {
            $this->user_model->update(['is_verified' => 1, 'verification_code' => NULL], $user['id']);

            $token = $this->generate_token(['id' => $user['id']]);
            $this->response(['success' => true, 'token' => $token], REST_Controller::HTTP_OK);
        } else {
            $this->response("The verification code you entered is incorrect.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    function verify_phone_post() {
        $phone = $this->post('phone');
        $verification_code = $this->post('verification_code');

        $user = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['phone' => $phone]]);
        if ($user && $user['verification_code'] == $verification_code) {
            $this->user_model->update(['verification_code' => NULL], $user['id']);

            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("The verification code you entered is incorrect.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function change_password_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $current_password = $this->post('current_password');
        $new_password = $this->post('new_password');

        $user_with_password = $this->global_model->query("SELECT id FROM users WHERE `password` = '{$current_password}' AND id = '{$user->id}'", 'single');
        if(!$user_with_password) {
            $this->response("Your current password does not matches with the password you provided.", REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $update = $this->user_model->update(['password' => $new_password], $user->id);
            if($update) {
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }


    public function send_push_notification_post() {
        $title = $this->post('title');
        $message = $this->post('message');

        $content = array(
            "en" => $message
        );
        $heading = array(
            "en" => $title
        );
        $fields = array(
            'app_id' => $this->one_signal_app_id,
            'included_segments' => ['Subscribed Users'],
            'data' => ["data" => "1", "deal_id" => 1],
            'headings' => $heading,
            'contents' => $content,
            'ios_badgeType' => 'Increase',
            'ios_badgeCount' => 1,
            'priority' => 10
        );
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic YmE2MmNhNDUtYzM4MS00MmU0LTg0ZDMtZjM3ZTMxMmM2ZGQ0', 'Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        var_dump($response);
    }

    public function update_user_photos_put() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $photo_ids = $this->put('photo_ids');
        $photo_ids = explode("||", $photo_ids);

        $orders = $this->put('orders');
        $orders = explode("||", $orders);

        foreach ($photo_ids as $key => $photo_id) {
            $this->user_photo_model->update(['order' => $orders[$key]], $photo_id);
        }

        $this->response(['success' => true], REST_Controller::HTTP_OK);
    }

    public function delete_user_photo_delete($id) {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $delete = $this->user_photo_model->delete($id);
        if($delete) {
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    



    public function load_questions_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $questions = $this->question_model->getRows(['conditions' => ['is_active' => 1]]);
        $this->response(['success' => true, 'questions' => $questions], REST_Controller::HTTP_OK);
    }

    public function set_user_answers_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();

        $user_answers = $this->user_answer_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user->id]]);
        if($user_answers) {
            $update = $this->user_answer_model->update($input, $user_answers['id']);
            if($update) {
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $input['user_id'] = $user->id;
            $insert = $this->user_answer_model->insert($input);
            if($insert) {
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function set_user_answers_me_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();

        $user_answers = $this->user_answer_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user->id,'question_id'=> $input['question_id']]]);
        if($user_answers) {
            $update = $this->user_answer_model->update($input, $user_answers['id']);
            if($update) {
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $input['user_id'] = $user->id;
            $insert = $this->user_answer_model->insert($input);
            if($insert) {
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function swipe_wink_user_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();
        $input['user_id'] = $user->id;

        // $current_swipe = $this->swipe_model->getRows([
        //     'returnType' => 'single',
        //     'conditions' => [
        //         'user_id' =>  (int)$user->id,
        //         'opponent_id' => (int)$input['opponent_id']
        //     ]
        // ]);
        // if(!$current_swipe){
            $insert = $this->swipe_model->insert($input);
            if($insert) {
                $swipe = $this->swipe_model->getRows([
                    'returnType' => 'single',
                    'conditions' => [
                        'user_id' => (int)$input['opponent_id'],
                        'opponent_id' => (int)$user->id,
                        'type' => 'Wink'
                    ]
                ]);  
                                
                if ($swipe) {
                    $this->match_model->insert(['user_id' => $input['opponent_id'], 'opponent_id' => $input['user_id']]);
                    $this->match_model->insert(['user_id' => $input['user_id'], 'opponent_id' => $input['opponent_id']]);
                    $this->response(['success' => true], REST_Controller::HTTP_OK); 
                }
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        // } 
        // else if(count($current_swipe)>0){
        //     $insert = $this->swipe_model->update(['type'=>'Wink'],$current_swipe['id']);
        //     if($insert){
        //         $swipe = $this->swipe_model->getRows([
        //             'returnType' => 'single',
        //             'conditions' => [
        //                 'user_id' => (int)$input['opponent_id'],
        //                 'opponent_id' => (int)$user->id,
        //                 'type' => 'Wink'
        //             ]
        //         ]);  
        //         if ($swipe) {
        //             $this->match_model->insert(['user_id' => $input['opponent_id'], 'opponent_id' => $input['user_id']]);
        //             $this->match_model->insert(['user_id' => $input['user_id'], 'opponent_id' => $input['opponent_id']]);
        //             $this->response(['success' => true], REST_Controller::HTTP_OK); 
        //         }else {
        //             $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        //         }
        //     }else{
        //         $this->response("You have already swiped blink this user", REST_Controller::HTTP_OK);
        //     }
        // }
        // else {
        //     $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        // }
    }

    public function swipe_blink_user_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();
        $input['user_id'] = $user->id;

        // $current_swipe = $this->swipe_model->getRows([
        //     'returnType' => 'single',
        //     'conditions' => [
        //         'user_id' =>  (int)$user->id,
        //         'opponent_id' => (int)$input['opponent_id']
        //     ]
        // ]);
        // if(!$current_swipe){
            $insert = $this->swipe_model->insert($input);
            if($insert) {            
                $this->response(['success' => true], REST_Controller::HTTP_OK); 
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        // } else if(count($current_swipe)>0){
        //     $insert = $this->swipe_model->update(['type'=>'Blink'],$current_swipe['id']);
        //     if($insert){
        //         $this->response(['success' => true], REST_Controller::HTTP_OK); 
        //     }else{
        //         $this->response("You have already swiped blink this user", REST_Controller::HTTP_OK);
        //     }
        // }
        // else {
        //     $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        // }
    }

    // public function undo_swipe_delete($id) {
    //     // Verify the token
    //     $token = $this->input->get_request_header('Auth-Token');
    //     if (!$this->verify_token($token)) {
    //         $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
    //     }
        
    //     $delete = $this->swipe_model->delete($id);
    //     if($delete) {
    //         $this->response(['success' => true], REST_Controller::HTTP_OK);
    //     } else {
    //         $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
    //     }
    // }

    public function undo_swipe_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }
        
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();
        $input['user_id'] = $user->id;

        $swipe_count = $this->swipe_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => (int)$user->id,'opponent_id'=>(int)$input['opponent_id']]]);
        $blast_count = $this->blast_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => (int)$user->id,'opponent_id'=>(int)$input['opponent_id']]]);
        $delete = $this->global_model->query("DELETE FROM swipes WHERE user_id = ".(int)$user->id." AND opponent_id = ".(int)$input['opponent_id']);
        
        if ($delete)
            $delete = $this->global_model->query("DELETE FROM blasts WHERE user_id = ".(int)$user->id." AND opponent_id = ".(int)$input['opponent_id']);
            
        else 
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        
        if ($delete)
            $delete = $this->global_model->query("DELETE FROM matches WHERE user_id = ".(int)$user->id." AND opponent_id = ".(int)$input['opponent_id']);
        else
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        
        if ($delete)
            $delete = $this->global_model->query("DELETE FROM matches WHERE user_id = ".(int)$input['opponent_id']." AND opponent_id = ".(int)$user->id);
        else
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        
        if($blast_count){
            // $this->response(['success' => true], REST_Controller::HTTP_OK);
            $query = $this->global_model->query("UPDATE users SET winkyblasts_count = winkyblasts_count + 1 WHERE id='$user->id'");
            if($query)
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            else
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }

        if($delete) {
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function blast_user_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();
        $input['user_id'] = $user->id;

        

        $winkyblast_count = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['id' => $user->id]]);
        $wink_send = $this->swipe_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' =>(int)$input['opponent_id'], 'opponent_id'=>(int)$user->id,'type'=>'Wink']]);
        
        if((int)$winkyblast_count['winkyblasts_count']>0){
            // if(!$winkyblast_send){
                $insert = $this->blast_model->insert($input);
                $query = "UPDATE users SET winkyblasts_count = winkyblasts_count - 1 WHERE id = '$user->id'";
                $this->global_model->query($query);
                if($insert) {
                    $input['type'] = "Wink";
                    $this->swipe_model->insert($input);
                    if($wink_send){
                        $this->match_model->insert(['user_id' => $input['opponent_id'], 'opponent_id' => $input['user_id']]);
                        $this->match_model->insert(['user_id' => $input['user_id'], 'opponent_id' => $input['opponent_id']]);
                        $this->response(['success' => 'You have successfully sent your Winkyblasts.'], REST_Controller::HTTP_OK);
                    }else{
                        $this->response(['success' => 'You have successfully sent your Winkyblasts.'], REST_Controller::HTTP_OK);
                    }        
                } else {
                    $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
                }
            // }else {
            //     $this->response(['success' => 'You have already sent your Winkyblasts.'], REST_Controller::HTTP_OK);
            // }
        }else {
            $this->response(['success' => 'You have already used your all Winkyblasts.Please buy winkyblast.'], REST_Controller::HTTP_OK);
        }
    }

    public function load_blasts_get($limit) {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $limit = (int)$limit;
        $blasts = $this->global_model->query("SELECT
                                            bu.id as id,
                                            bu.name as user_name,
                                            bu.address as address,
                                            bu.latitude as latitude,
                                            bu.longtidue as longtidue,
                                            bu.user_id as user_id,
                                            bu.opponent_id as oppoenent_id, 
                                            bu.is_newly_added as is_newly_added,
                                            bu.create_date as create_date,
                                            up.photo as user_photo
                                            FROM (
                                                SELECT 
                                                b.id as id,
                                                u.name as name, 
                                                u.address as address , 
                                                u.latitude as latitude,
                                                u.longtidue as longtidue,
                                                b.user_id as user_id, 
                                                b.opponent_id as opponent_id, 
                                                b.is_newly_added as is_newly_added, 
                                                b.create_date as create_date 
                                                FROM  (SELECT
                                                    id as id,
                                                    user_id as user_id, 
                                                    opponent_id as opponent_id, 
                                                    is_newly_added as is_newly_added, 
                                                    create_date as create_date 
                                                    FROM blasts 
                                                    where opponent_id = '{$user->id}') b 
                                            LEFT JOIN users u
                                            ON u.id = b.user_id) bu
                                            LEFT JOIN (SELECT user_id as user_id_up, photo as photo FROM users_photos GROUP BY user_id ) up
                                            on bu.user_id = up.user_id_up
                                            ORDER BY bu.create_date DESC
                                            LIMIT ".$limit, "multiple"
                                            );
        $this->response(['success' => true, 'blasts' => $blasts], REST_Controller::HTTP_OK);
    }

    public function load_blasts_preview_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $blasts = $this->global_model->query("SELECT
                                            bu.id as id,
                                            bu.name as user_name,
                                            bu.address as address,
                                            bu.latitude as latitude,
                                            bu.longtidue as longtidue,
                                            bu.user_id as user_id,
                                            bu.opponent_id as oppoenent_id, 
                                            bu.is_newly_added as is_newly_added,
                                            bu.create_date as create_date,
                                            up.photo as user_photo
                                            FROM (
                                                SELECT 
                                                b.id as id,
                                                u.name as name, 
                                                u.address as address,
                                                u.latitude as latitude,
                                                u.longtidue as longtidue, 
                                                b.user_id as user_id, 
                                                b.opponent_id as opponent_id, 
                                                b.is_newly_added as is_newly_added, 
                                                b.create_date as create_date 
                                                FROM  (SELECT
                                                    id as id,
                                                    user_id as user_id, 
                                                    opponent_id as opponent_id, 
                                                    is_newly_added as is_newly_added, 
                                                    create_date as create_date 
                                                    FROM blasts 
                                                    where opponent_id = '{$user->id}') b 
                                            LEFT JOIN users u
                                            ON u.id = b.user_id) bu
                                            LEFT JOIN (SELECT user_id as user_id_up, photo as photo FROM users_photos GROUP BY user_id ) up
                                            on bu.user_id = up.user_id_up
                                            ORDER BY bu.create_date DESC
                                            LIMIT 2", "multiple"
                                            );
        $this->response(['success' => true, 'blasts' => $blasts], REST_Controller::HTTP_OK);
    }

    public function load_conversations_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $conversations = $this->global_model->query("SELECT c.*, u.name AS user_name, u.address AS user_address, up.photo AS user_photo FROM conversations c LEFT JOIN users u ON u.id = c.opponent_id LEFT JOIN users_photos up ON up.user_id = c.user_id WHERE c.user_id = '{$user->id}'", "multiple");
        $this->response(['success' => true, 'conversations' => $conversations], REST_Controller::HTTP_OK);
    }

    public function load_matches_get($limit) {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }
        $limit = (int)$limit;
        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $userdata = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['id' => (int)$user->id]]);
        $userplan = $userdata['subscribed_plan'];
        // $this->response($userplan);
        $matches = $this->global_model->query("SELECT 
        m.*, 
        u.name as user_name, 
        u.address as user_address, 
        u.latitude as latitude,
        u.longtidue as longtidue,
        up.photo as user_photo 
        FROM users u 
        left JOIN (SELECT user_id as user_id, photo as photo FROM users_photos group BY user_id) up
        on u.id = up.user_id 
        LEFT JOIN matches m 
        on up.user_id=m.opponent_id 
        WHERE m.user_id = '{$user->id}'
        ORDER BY m.create_date DESC
        LIMIT ".$limit, "multiple"
        );

        $this->response(['success' => true, 'matches' => $matches], REST_Controller::HTTP_OK);
    }

    public function load_matches_preview_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $matches = $this->global_model->query("SELECT 
        m.*, 
        u.name as user_name, 
        u.address as user_address, 
        up.photo as user_photo,
        u.latitude as latitude,
        u.longtidue as longtidue 
        FROM users u 
        left JOIN (SELECT user_id as user_id, photo as photo FROM users_photos group BY user_id) up
        on u.id = up.user_id 
        LEFT JOIN matches m 
        on up.user_id=m.opponent_id 
        WHERE m.user_id = '{$user->id}'
        ORDER BY m.create_date DESC
        limit 2
        ");

        $this->response(['success' => true, 'matches' => $matches], REST_Controller::HTTP_OK);
    }

    public function report_user_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();
        $input['user_id'] = $user->id;
        
        $report_count = $this->report_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => (int)$user->id,'opponent_id'=>(int)$input['opponent_id']]]);
        if($report_count){
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        }else{
            $insert = $this->report_model->insert($input);
            if($insert) {
                $this->response(['success' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
    }

    public function block_user_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();
        $input['user_id'] = $user->id;
        
        $block_count = $this->block_model->getRows(['returnType' => 'single','conditions' => ['user_id' => (int)$user->id,'opponent_id' => (int)$input['opponent_id']]]);

        $delete = $this->global_model->query("DELETE FROM matches WHERE user_id = ".(int)$user->id." AND opponent_id = ".(int)$input['opponent_id']);
        $delete = $this->global_model->query("DELETE FROM blasts WHERE user_id = ".(int)$user->id." AND opponent_id = ".(int)$input['opponent_id']);
        $delete = $this->global_model->query("DELETE FROM matches WHERE opponent_id = ".(int)$user->id." AND user_id = ".(int)$input['opponent_id']);
        $delete = $this->global_model->query("DELETE FROM blasts WHERE opponent_id = ".(int)$user->id." AND user_id = ".(int)$input['opponent_id']);


        if($block_count){
            $this->response(['success_exist' => true], REST_Controller::HTTP_OK);
        }else{
            $insert = $this->block_model->insert($input);
            if($insert) {
                $this->response(['success_none' => true], REST_Controller::HTTP_OK);
            } else {
                $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function load_blocked_users_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $blocks = $this->global_model->query("SELECT b.*, u.name AS user_name, u.address AS user_address, up.photo AS user_photo FROM blocks b LEFT JOIN users u ON u.id = b.opponent_id LEFT JOIN users_photos up ON up.user_id = b.user_id WHERE b.user_id = '{$user->id}'", "multiple");
        $this->response(['success' => true, 'blocks' => $blocks], REST_Controller::HTTP_OK);
    }

    public function load_help_user_get(){
         // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        // $blocks = $this->global_model->query("SELECT b.*, u.name AS user_name, u.address AS user_address, up.photo AS user_photo FROM blocks b LEFT JOIN users u ON u.id = b.opponent_id LEFT JOIN users_photos up ON up.user_id = b.user_id WHERE b.user_id = '{$user->id}'", "multiple");
        // $this->response(['success' => true, 'blocks' => $blocks], REST_Controller::HTTP_OK);
    }

    public function send_help_user_post(){
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $input = $this->post();
        $input['user_id'] = $user->id;
        $insert = $this->help_model->insert($input);
        if($insert){
            $this->response(['success' => true],REST_Controller::HTTP_OK);
        }else {
            $this->response('Some problems occured, please try again',REST_Controller::HTTP_BAD_REQUEST);
        }
    }


    public function load_notifications_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $notifications = $this->notification_model->getRows(['conditions' => ['user_id' => $user->id]]);
        $this->response(['success' => true, 'notifications' => $notifications], REST_Controller::HTTP_OK);
    }

    public function create_conversation_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();
        $input['user_id'] = $user->id;        
        $insert = $this->conversation_model->insert($input);
        if($insert) {
            $input['user_id'] = $input['opponent_id'];
            $input['opponent_id'] = $user->id;
            $this->conversation_model->insert($input);

            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function schedule_virtual_date_post() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->post();
        $input['user_id'] = $user->id;

        $insert = $this->virtual_date_model->insert($input);
        if($insert) {
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function update_virtual_date_put() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->put();
    
        // $update = $this->virtual_date_model->update($input, $date_id);
        // if($update) {
            $this->response($input);
        // } else {
        //     $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        // }
    }
    public function buy_blast_user_post(){
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $input = $this->post();
        $count = (int)$input['count'];
        $result =  $this->global_model->query("UPDATE users SET winkyblasts_count = winkyblasts_count + '$count' WHERE id='$user->id'");
        if($result){
        $this->response(['success' => true], REST_Controller::HTTP_OK);
        }else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }

    }
    public function viewd_match_user_post(){
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $input = $this->post();
        $result = $this->global_model->query("UPDATE matches SET is_newly_added = 0 WHERE user_id = '{$user->id}' AND opponent_id = '{$input['opponent_id']}'");
        if($result){
            $this->response(['success'=> true],REST_Controller::HTTP_OK);
        }else{
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function viewd_blast_user_post(){
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $input = $this->post();
        $result = $this->global_model->query("UPDATE blasts SET is_newly_added = 0 WHERE opponent_id = '{$user->id}' AND user_id = '{$input['opponent_id']}'");
        if($result){
            $this->response(['success'=> true],REST_Controller::HTTP_OK);
        }else{
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function load_faq_get($limit){
        $token = $this->input->get_request_header('Auth-Token');
        if(!$this->verify_token($token)){
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $limit = (int)$limit;
        $result = $this->global_model->query("SELECT * FROM faqs LIMIT {$limit}");
        if($result){
            $this->response(['success'=>true, 'faqs'=>$result],REST_Controller::HTTP_OK);
        }else{
            $this->response("Some problems occured, please try again.",REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function load_virtual_date_get($limit){
        $token = $this->input->get_request_header('Auth-Token');
        if(!$this->verify_token($token)){
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $limit = (int)$limit;
        $notifications = $this->notification_model->getRows(['conditions' => ['user_id' => $user->id]]);
       
        $virtualdate = $this->global_model->query("SELECT * FROM (
                                                    SELECT
                                                    bu.id as id,
                                                    bu.name as user_name,
                                                    -- bu.user_id as opponent_id,
                                                    -- bu.opponent_id as user_id, 
                                                    bu.user_id as user_id,
                                                    bu.opponent_id as opponent_id, 
                                                    bu.session_length as session_length,
                                                    bu.date_time as date_time,
                                                    bu.approval_status as approval_status,
                                                    up.photo as user_photo,
                                                    bu.create_date as create_date,
                                                    bu.qb_id
                                                    FROM (
                                                        SELECT 
                                                            b.id as id,
                                                            u.name as name, 
                                                            u.address as address, 
                                                            u.latitude as latitude,
                                                            u.longtidue as longtidue,
                                                            u.qb_id,
                                                            b.user_id as user_id, 
                                                            b.opponent_id as opponent_id, 
                                                            b.session_length as session_length, 
                                                            b.date_time as date_time,
                                                            b.approval_status as approval_status,
                                                            b.create_date as create_date
                                                        FROM (
                                                            SELECT
                                                                id as id,
                                                                user_id as user_id, 
                                                                opponent_id as opponent_id, 
                                                                session_length as session_length, 
                                                                date_time as date_time,
                                                                approval_status as approval_status,
                                                                create_date as create_date
                                                            FROM virtual_dates 
                                                            WHERE opponent_id = {$user->id} AND approval_status = 'Approved'
                                                        ) b 
                                                        LEFT JOIN users u ON u.id = b.user_id
                                                    ) bu
                                                    LEFT JOIN (
                                                        SELECT user_id as user_id_up, photo as photo
                                                        FROM users_photos
                                                        GROUP BY user_id
                                                    ) up ON bu.user_id = up.user_id_up
                                                    
                                                    UNION

                                                    SELECT
                                                        v.id as id,
                                                        u.name as user_name, 
                                                        v.opponent_id as user_id,
                                                        v.user_id as opponent_id,
                                                        v.session_length as session_length,
                                                        v.date_time as date_time,
                                                        v.approval_status as approval_status,
                                                        up.photo as user_photo,
                                                        v.create_date as create_date,
                                                        u.qb_id
                                                    FROM users u 
                                                    LEFT JOIN (
                                                        SELECT user_id as user_id, photo as photo
                                                        FROM users_photos
                                                        GROUP BY user_id
                                                    ) up ON u.id = up.user_id 
                                                    LEFT JOIN (
                                                        SELECT *
                                                        FROM virtual_dates
                                                        WHERE approval_status='Approved'
                                                    ) v ON up.user_id = v.opponent_id 
                                                    WHERE v.user_id = {$user->id}
                                                    ) as combineresult  ORDER BY create_date DESC LIMIT {$limit}");
    
        // if($virtualdate){
            $this->response(['success'=>true, 'virtual_dates'=>$virtualdate],REST_Controller::HTTP_OK);
        // }else{
        //     $this->response("Some problems occured, please try again.",REST_Controller::HTTP_BAD_REQUEST);
        // }
    }
    public function load_received_dates_get($limit){
        $token = $this->input->get_request_header('Auth-Token');
        if(!$this->verify_token($token)){
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $limit = (int)$limit;
        $notifications = $this->notification_model->getRows(['conditions' => ['user_id' => $user->id]]);
        $virtualdate = $this->global_model->query("SELECT
                                            bu.id as id,
                                            bu.name as user_name,
                                            bu.user_id as user_id,
                                            bu.opponent_id as opponent_id, 
                                            bu.session_length as session_length,
                                            bu.date_time as date_time,
                                            bu.approval_status as approval_status,
                                            up.photo as user_photo,
                                            bu.create_date as create_date
                                            FROM (
                                                SELECT 
                                                b.id as id,
                                                u.name as name, 
                                                u.address as address , 
                                                u.latitude as latitude,
                                                u.longtidue as longtidue,
                                                b.user_id as user_id, 
                                                b.opponent_id as opponent_id, 
                                                b.session_length as session_length, 
                                                b.date_time as date_time,
                                                b.approval_status as approval_status,
                                                b.create_date as create_date
                                                FROM  (SELECT
                                                    id as id,
                                                    user_id as user_id, 
                                                    opponent_id as opponent_id, 
                                                    session_length as session_length, 
                                                    date_time as date_time,
                                                    approval_status as approval_status,
                                                    create_date as create_date
                                                    FROM virtual_dates 
                                                    where opponent_id = '{$user->id}' AND approval_status = 'Pending') b 
                                            LEFT JOIN users u
                                            ON u.id = b.user_id) bu
                                            LEFT JOIN (SELECT user_id as user_id_up, photo as photo FROM users_photos GROUP BY user_id ) up
                                            on bu.user_id = up.user_id_up
                                            ORDER BY bu.create_date DESC
                                            LIMIT ".$limit, "multiple"
                                            );
        // if($virtualdate){
            $this->response(['success'=>true, 'virtual_dates_reveived'=>$virtualdate],REST_Controller::HTTP_OK);
        // }else{
        //     $this->response("Some problems occured, please try again.",REST_Controller::HTTP_BAD_REQUEST);
        // }
    }
    public function load_sent_dates_get($limit){
        $token = $this->input->get_request_header('Auth-Token');
        if(!$this->verify_token($token)){
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $limit = (int)$limit;
        $notifications = $this->notification_model->getRows(['conditions' => ['user_id' => $user->id]]);
        $virtualdate = $this->global_model->query("SELECT
                                            v.id as id,
                                            u.name as user_name, 
                                            v.user_id as user_id,
                                            v.opponent_id as opponent_id, 
                                            v.session_length as session_length,
                                            v.date_time as date_time,
                                            v.approval_status as approval_status,
                                            up.photo as user_photo,
                                            v.create_date as create_date
                                            FROM users u 
                                            left JOIN (SELECT user_id as user_id, photo as photo FROM users_photos group BY user_id) up
                                            on u.id = up.user_id 
                                            LEFT JOIN (SELECT * FROM virtual_dates WHERE approval_status='Pending') v 
                                            on up.user_id=v.opponent_id 
                                            WHERE v.user_id = '{$user->id}'
                                            ORDER BY v.create_date DESC
                                            LIMIT ".$limit, "multiple"
                                            );
        // if($virtualdate){
            $this->response(['success'=>true, 'virtual_dates_sent'=>$virtualdate],REST_Controller::HTTP_OK);
        // }else{
        //     $this->response("Some problems occured, please try again.",REST_Controller::HTTP_BAD_REQUEST);
        // }
    }


    public function load_virtualable_users_get($limit) {
        // Verify the token
        // $token = $this->input->get_request_header('Auth-Token');
        // if (!$this->verify_token($token)) {
        //     $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        // }

        // // Retrieve the user record from the token
        // $decoded_token = $this->decode_token($token);
        // $user = $decoded_token['user'];
        // // $latitude = $this->get('latitude');
        // // $lontitude = $this->get('longtidue');
        // $users_preferences = $this->user_preference_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user->id]]);
        // $user_info = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['id' => $user->id]]);

        // if ($users_preferences === false) {
        //     $this->response(['success' => true, 'users' => []], REST_Controller::HTTP_OK);
        // } else {
            
        //     // }else if($user_info['is_flex_gps_enabled']==0){
        //         $query = "SELECT u.*, up.photo FROM users u LEFT JOIN users_photos up ON up.user_id = u.id WHERE u.is_ghost_mode_enabled <> 1 AND u.id <> '{$user->id}' AND u.id NOT IN (SELECT opponent_id AS id FROM matches WHERE user_id = '{$user->id}')";

        //         if ($users_preferences['looking_for'] !== "Both") {
        //             $query = $query." AND u.gender = '{$users_preferences['looking_for']}'";
        //         }
        //         // $query = $query." AND u.height >= {$users_preferences['height_min']} AND u.height <= {$users_preferences['height_max']}";

        //         // $date_of_birth_min = strtotime("-{$users_preferences['age_min']} year", time());
        //         // $date_of_birth_min = date('Y-m-d', $date_of_birth_min);

        //         // $date_of_birth_max = strtotime("-{$users_preferences['age_max']} year", time());
        //         // $date_of_birth_max = date('Y-m-d', $date_of_birth_max);

        //         // $scope = $this->calculate_mile($user_info['latitude'],$user_info['longtidue'],$users_preferences['distance_min'],$users_preferences['distance_max']);
                
        //         // $query = $query . " AND u.latitude <= " . (double)$scope['max_lat'] . " AND u.latitude >= " . (double)$scope['min_lat'] . " AND u.longtidue <= " . (double)$scope['max_lon'] . " AND u.longtidue >= " . (double)$scope['min_lon'] . "";

        //         // // $query = $query." AND u.latitude <= '{(double)$scope['max_lat']}' AND u.latitude >= '{(double)$scope['min_lat']}' AND u.longtidue <= '{(double)$scope['max_lon']}' AND u.longtidue >= '{(double)$scope['min_lon']}'";

        //         // $query = $query." AND u.date_of_birth <= '{$date_of_birth_min}' AND u.date_of_birth >= '{$date_of_birth_max}'";

        //         $query = $query." GROUP BY u.id LIMIT {$limit}";

        //         $users = $this->global_model->query($query);
        //         $this->response(['success' => true, 'users' => $users, 'query' => $query], REST_Controller::HTTP_OK);
        //     // }
        // }


        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }
        $limit = (int)$limit;
        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        $userdata = $this->user_model->getRows(['returnType' => 'single', 'conditions' => ['id' => (int)$user->id]]);
        $userplan = $userdata['subscribed_plan'];
        // $this->response($userplan);
        $matches = $this->global_model->query("SELECT 
            m.*, 
            u.name as name, 
            u.address as address, 
            u.latitude as latitude,
            u.longtidue as longtidue,
            up.photo as photo ,
            m.opponent_id as user_id

            FROM users u 
            left JOIN (SELECT user_id as user_id, photo as photo FROM users_photos group BY user_id) up
            on u.id = up.user_id 
            LEFT JOIN matches m 
            on up.user_id=m.opponent_id 
            WHERE m.user_id = '{$user->id}'
            ORDER BY m.create_date DESC
            LIMIT ".$limit, "multiple"
        );

        $this->response(['success' => true, 'users' => $matches], REST_Controller::HTTP_OK);


            // $query = @unserialize (file_get_contents('http://ip-api.com/php/'));
            // if ($query && $query['status'] == 'success') {
            //     $this->response('Hey user from ' . $query['country'] . ', ' . $query['city'] . '!');
            //     // echo 'Hey user from ' . $query['country'] . ', ' . $query['city'] . '!';
            // }
            // // foreach ($query as $data) {
            // //     echo $data . "<br>";
            // //     $this->response()
            // // }
    }
    public function accept_virtual_date_put() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->put();
        $update = $this->global_model->query("UPDATE virtual_dates SET approval_status='Approved' WHERE id={$input['id']}");
        if($update) {
            $this->response(['success'=>true],REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function reschedule_virtual_date_put() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->put();
        $update = $this->global_model->query("UPDATE virtual_dates SET date_time='{$input['date_time']}' WHERE id={$input['id']}");
        if($update) {
            $this->response(['success'=>true],REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function cancel_virtual_date_put() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->put();
        $update = $this->global_model->query("UPDATE virtual_dates SET approval_status='Canceled' WHERE id={$input['id']}");
        if($update) {
            $this->response(['success'=>true],REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function count_penddingdate_get(){
        $token = $this->input->get_request_header('Auth-Token');
        if(!$this->verify_token($token)){
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];
        // $notifications = $this->notification_model->getRows(['conditions' => ['usezzr_id' => $user->id]]);
        $virtualdate = $this->global_model->query("SELECT
                                            v.id as id,
                                            u.name as user_name, 
                                            v.user_id as user_id,
                                            v.opponent_id as opponent_id, 
                                            v.session_length as session_length,
                                            v.date_time as date_time,
                                            v.approval_status as approval_status,
                                            up.photo as user_photo,
                                            v.create_date as create_date
                                            FROM users u 
                                            left JOIN (SELECT user_id as user_id, photo as photo FROM users_photos group BY user_id) up
                                            on u.id = up.user_id 
                                            LEFT JOIN (SELECT * FROM virtual_dates WHERE approval_status='Pending') v 
                                            on up.user_id=v.opponent_id 
                                            WHERE v.user_id = '{$user->id}'
                                            ORDER BY v.create_date DESC","count"
                                            );
        // if($virtualdate){
            $this->response(['success'=>true, 'virtual_dates_sent'=>$virtualdate],REST_Controller::HTTP_OK);
        // }else{
        //     $this->response("Some problems occured, please try again.",REST_Controller::HTTP_BAD_REQUEST);
        // }
    }
}