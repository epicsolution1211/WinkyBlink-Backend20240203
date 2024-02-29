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
        echo phpinfo();
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

        $verification_code = $this->generate_verification_code();
        $this->send_verification_code($phone, $verification_code);

        // $verification_code = "0925";
        $this->response(['success' => true, 'verification_code' => $verification_code], REST_Controller::HTTP_OK);
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
                if ($input['introduction_video_clip'] == '') {
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
            if ($input['introduction_video_clip'] == '') {
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

    public function load_swipeable_users_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $users_preferences = $this->user_preference_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $user->id]]);
        if ($users_preferences === false) {
            $this->response(['success' => true, 'users' => []], REST_Controller::HTTP_OK);
        } else {
            $query = "SELECT u.*, up.photo FROM users u LEFT JOIN users_photos up ON up.user_id = u.id WHERE u.id <> '{$user->id}' AND u.id NOT IN (SELECT opponent_id AS id FROM swipes WHERE user_id = '{$user->id}')";

            if ($users_preferences['looking_for'] !== "Both") {
                $query = $query." AND u.gender = '{$users_preferences['looking_for']}'";
            }
            $query = $query." AND u.height >= {$users_preferences['height_min']} AND u.height <= {$users_preferences['height_max']}";

            $date_of_birth_min = strtotime("-{$users_preferences['age_min']} year", time());
            $date_of_birth_min = date('Y-m-d', $date_of_birth_min);

            $date_of_birth_max = strtotime("-{$users_preferences['age_max']} year", time());
            $date_of_birth_max = date('Y-m-d', $date_of_birth_max);

            $query = $query." AND u.date_of_birth <= '{$date_of_birth_min}' AND u.date_of_birth >= '{$date_of_birth_max}'";

            $query = $query." GROUP BY u.id";

            $users = $this->global_model->query($query);
            $this->response(['success' => true, 'users' => $users, 'query' => $query], REST_Controller::HTTP_OK);
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

    public function swipe_user_post() {
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
        
        $insert = $this->swipe_model->insert($input);
        if($insert) {
            if ($input['type'] == 'Wink') {
                $swipe = $this->swipe_model->getRows(['returnType' => 'single', 'conditions' => ['user_id' => $input['opponent_id'], 'opponent_id' => $input['user_id'], 'type' => 'Wink']]);
                if ($swipe) {
                    $this->match_model->insert(['user_id' => $input['opponent_id'], 'opponent_id' => $input['user_id']]);
                    $this->match_model->insert(['user_id' => $input['user_id'], 'opponent_id' => $input['opponent_id']]);
                }
            }            
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function undo_swipe_delete($id) {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }
        
        $delete = $this->swipe_model->delete($id);
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
        
        $insert = $this->blast_model->insert($input);
        if($insert) {
            $input['type'] = "Wink";
            $this->swipe_model->insert($input);
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function load_blasts_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $blasts = $this->global_model->query("SELECT b.*, u.name AS user_name, u.address AS user_address, up.photo AS user_photo FROM blasts b LEFT JOIN users u ON u.id = b.opponent_id LEFT JOIN users_photos up ON up.user_id = b.user_id WHERE b.user_id = '{$user->id}'", "multiple");
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

    public function load_matches_get() {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $matches = $this->global_model->query("SELECT m.*, u.name AS user_name, u.address AS user_address, up.photo AS user_photo FROM matches m LEFT JOIN users u ON u.id = m.opponent_id LEFT JOIN users_photos up ON up.user_id = m.user_id WHERE m.user_id = '{$user->id}'", "multiple");
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
        
        $insert = $this->report_model->insert($input);
        if($insert) {
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
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
        
        $insert = $this->block_model->insert($input);
        if($insert) {
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
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
        $insert = $this->virtual_date_model->insert($input);
        if($insert) {
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function update_virtual_date_put($id) {
        // Verify the token
        $token = $this->input->get_request_header('Auth-Token');
        if (!$this->verify_token($token)) {
            $this->response("You are not autorized to use the app.", REST_Controller::HTTP_BAD_REQUEST);
        }

        // Retrieve the user record from the token
        $decoded_token = $this->decode_token($token);
        $user = $decoded_token['user'];

        $input = $this->put();
        $update = $this->virtual_date_model->update($input, $id);
        if($update) {
            $this->response(['success' => true], REST_Controller::HTTP_OK);
        } else {
            $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }

}