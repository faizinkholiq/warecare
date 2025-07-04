<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MX_Controller
{
    public function __construct()
	{
		parent::__construct();
		$this->load->model('login/login_model');
    }

    public function index()
    {   
        $d = $this->login_model->login_check();
        $d['menu_id'] = "menu_home";
        $d['title'] = "Home";
        $d['view'] = 'home/home';

        $this->load->view('template/template', $d);
    }
}
