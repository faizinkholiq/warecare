<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->library('session');
  }

  public function login() {
    if ($this->input->method() === 'post') {
      $username = $this->input->post('username');
      $password = $this->input->post('password');
      $user = $this->User_model->get_by_username($username);
      
      if ($user && password_verify($password, $user["password"])) {
        $user["logged_in"] = TRUE;
        $this->session->set_userdata($user);

        return redirect('dashboard');
      }

      $this->session->set_flashdata('error', 'Username or password is incorrect.');
      return redirect('auth/login');
    }

	$data["login_url"] = site_url('auth/login');
    $this->load->view('auth/login', $data);
  }

  public function logout() {
    $this->session->sess_destroy();
    redirect('auth/login');
  }
}
