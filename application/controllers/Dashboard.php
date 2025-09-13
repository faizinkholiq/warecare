<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'Report_model',
        ]);
        $this->load->library('pdf');
    }

    public function index()
    {
        $data["current_user"] = $this->auth_lib->current_user();
        $data["title"] = "Dashboard";
        $data["menu_id"] = "dashboard";

        if ($this->auth_lib->role() === 'administrator') {
            $data["summary"] = $this->Report_model->summary();
        } else {
            $data["summary"] = $this->Report_model->summary($this->auth_lib->user_id());
        }

        $data["view"] = "dashboard/index";

        $this->load->view('layouts/template', $data);
    }
}
