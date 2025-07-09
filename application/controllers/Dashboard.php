<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	public function index()
	{
		$data["view"] = "dashboard/index";
    	$this->load->view('layouts/template', $data);
	}
}
