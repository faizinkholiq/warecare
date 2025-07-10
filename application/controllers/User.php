<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->library('form_validation');

    if (!$this->auth_lib->is_admin()) {
      show_error('Access denied. Admins only.', 403);
    }
  }

  public function index()
  {
    $data['users'] = $this->User_model->get_all();
    $this->load->view('layouts/header');
    $this->load->view('users/index', $data);
    $this->load->view('layouts/footer');
  }

  public function create()
  {
    $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
    $this->form_validation->set_rules('role', 'Role', 'required');

    if ($this->form_validation->run() === FALSE) {
      $this->load->view('layouts/header');
      $this->load->view('users/form');
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'username'    => $this->input->post('username'),
        'password'    => $this->input->post('password'),
        'role'        => $this->input->post('role'),
        'created_by'  => $this->auth_lib->user_id(),
      ];
      $this->User_model->insert($data);
      redirect('users');
    }
  }

  public function edit($id)
  {
    $user = $this->User_model->get_by_id($id);
    if (!$user) show_404();

    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('role', 'Role', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['user'] = $user;
      $this->load->view('layouts/header');
      $this->load->view('users/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'username'    => $this->input->post('username'),
        'role'        => $this->input->post('role'),
        'updated_by'  => $this->auth_lib->user_id(),
      ];

      if ($this->input->post('password')) {
        $data['password'] = $this->input->post('password');
      }

      $this->User_model->update($id, $data);
      redirect('users');
    }
  }

  public function delete($id)
  {
    $this->User_model->delete($id);
    redirect('users');
  }
}