<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
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
    $data["current_user"] = $this->auth_lib->current_user();
		$data["title"] = "Manajemen User";
		$data["menu_id"] = "user";
		$data["view"] = "user/index";
    $this->load->view('layouts/template', $data);
  }

  public function get_list_datatables()
	{
		$params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

		$products = $this->User_model->get_list_datatables($params);
		
		echo json_encode($products);
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