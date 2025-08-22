<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends MY_Controller {
	public function __construct() {
		parent::__construct();
        $this->load->helper('download');
	}

    public function download($filename) {
        $file_path = './uploads/' . urldecode($filename);
        
        if (file_exists($file_path)) {            
            force_download($file_path, NULL);
        } else {
            $this->session->set_flashdata('error', 'File not found: '. $filename);
        }
    }
}