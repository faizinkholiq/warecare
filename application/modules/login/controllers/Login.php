<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MX_Controller
{

    public function __construct()
	{
		parent::__construct();
		$this->load->model(['login_model']);
    }

    public function index()
    {
        $d["title"] = "Login";

        if ($this->session->userdata("sess_data")) {
            redirect('home');
        } else {
            if (!empty($this->input->get("redirect"))) {
                $d["login_url"] = site_url("login") . "?redirect=" . $this->input->get("redirect");
            } else {
                $d["login_url"] = site_url("login");
            }

            if ($this->input->post('username') && $this->input->post('password')) {
                $nd['username'] = $this->input->post('username');
                $nd['password'] = $this->input->post('password');

                if($check = $this->login_model->check_user($nd)){
                    $data_session = $this->login_model->detail($check);
                    $this->session->set_userdata('sess_data', $data_session);

                    redirect('home');
                }else{
                    $this->session->set_flashdata('msg', 'Username / Password is wrong');
                    $this->load->view("login", $d);
                }
            }else{
                $this->load->view("login", $d);
            }
        }

    }

    public function logout()
    {
		if ($this->session->userdata('sess_data') == TRUE) {
            $this->session->sess_destroy();
        }

        redirect('login');
	}
}
