<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	public function index()
	{	
		$data["current_user"] = $this->auth_lib->current_user();
		$data["title"] = "Dashboard";
		$data["menu_id"] = "dashboard";
		$data["view"] = "dashboard/index";
    	$this->load->view('layouts/template', $data);
	}
}
