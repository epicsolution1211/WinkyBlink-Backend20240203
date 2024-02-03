<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Upload extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    public function index() {
        $this->load->view('upload_form', array('error' => ' ' ));
    }

    public function upload_file() {
        $config['upload_path']          = APPPATH . "../uploads/";
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['overwrite']            = true;
        // $config['max_size']             = 100;
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')){
            // $this->response->setStatusCode(400, $this->upload->display_errors());
            // $this->response($this->upload->display_errors(), REST_Controller::HTTP_BAD_REQUEST);
            echo json_encode(['success'=>false, 'message'=>$this->upload->display_errors()]);
        } else {
            echo json_encode(['success' => true, 'file' => $this->upload->data()['file_name']]);
        }
    }

    public function upload_files() {
        $files = $_FILES['files'];

        $config['upload_path']          = APPPATH . "../uploads/auctions/";
        $config['overwrite']            = true;
        $config['allowed_types']        = 'gif|jpg|png|jpeg|mov|mp4|m4v|avi';

        $this->load->library('upload', $config);

        $uploaded_files = array();
        foreach ($files['name'] as $key => $image) {
            $_FILES['files[]']['name']= $files['name'][$key];
            $_FILES['files[]']['type']= $files['type'][$key];
            $_FILES['files[]']['tmp_name']= $files['tmp_name'][$key];
            $_FILES['files[]']['error']= $files['error'][$key];
            $_FILES['files[]']['size']= $files['size'][$key];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('files[]')) {
                $uploaded = $this->upload->data();
                $uploaded_files[] = $uploaded['file_name'];
            } else {
                echo json_encode(['success' => false, 'message' => $this->upload->display_errors()]);
                exit;
            }
        }
        echo json_encode(['success' => true, 'files' => $uploaded_files]);
    }
    
}