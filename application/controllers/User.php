<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->library('form_validation');

    $this->form_validation->set_message('required', '{field} harus diisi');
    $this->form_validation->set_message('is_unique', '{field} sudah digunakan, silakan pilih yang lain');

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

		$users = $this->User_model->get_list_datatables($params);
		
		echo json_encode($users);
	}

  public function create()
	{
    $data["title"] = "Manajemen User";
		$data["current_user"] = $this->auth_lib->current_user();
    $data["menu_id"] = "user";
    $data["mode"] = "create";
		
    $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
		$this->form_validation->set_rules('password', 'Password', 'required');
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
    $this->form_validation->set_rules('role', 'Role', 'required');
		
		if (!$this->input->is_ajax_request() && $this->form_validation->run() === FALSE) {
			$data["view"] = "user/form";
			$this->load->view('layouts/template', $data);
		} else {
      if ($this->form_validation->run() === FALSE) {
        $this->output->set_status_header(422);
        echo json_encode([
          'success' => false,
          'errors' => [
            'username' => form_error('username', '', ''),
            'password' => form_error('password', '', ''),
            'first_name' => form_error('first_name', '', ''),
            'last_name' => form_error('last_name', '', ''),
            'role' => form_error('role', '', '')
          ]
        ]);
        return;
      }

			$data = [
			  'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'role' => $this->input->post('role'),
        'is_active' => 1,
				'created_by'  => $this->auth_lib->user_id()
			];

			$user_id = $this->User_model->create($data);
			if (!$user_id) {
				$this->session->set_flashdata('error', 'Failed to create user');
				if ($this->input->is_ajax_request()) {
					$this->output->set_status_header(500);
					echo json_encode([
						"success" => false,
						"error" => "Failed to create user"
					]);
					return;
				}else{
					redirect('user/create');
				}
			}

			$this->session->set_flashdata('success', 'User created successfully');
			if ($this->input->is_ajax_request()) {
				$this->output->set_status_header(200);
				echo json_encode([
					"success" => true,
					"message" => "User created successfully"
				]);
				return;
			}else{
				redirect('user');
			}
		}
	}

	public function edit($id)
	{
		$data["title"] = "Manajemen User";
		$data["current_user"] = $this->auth_lib->current_user();
    $data["menu_id"] = "user";
    $data["mode"] = "edit";

		$user = $this->User_model->get_by_id($id);
		if (!$user) {
			$this->session->set_flashdata('error', 'User not found');
			if ($this->input->is_ajax_request()){
				$this->output->set_status_header(404);
				echo json_encode([
					'success' => false,
					'message' => 'User not found.',
				]);
				return;
			}else{
				redirect('user');
			}
		}

    $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
    $this->form_validation->set_rules('role', 'Role', 'required');

		if (!$this->input->is_ajax_request() && $this->form_validation->run() === FALSE) {
			$data["user"] = $user;
			$data["view"] = "user/form";
			$this->load->view('layouts/template', $data);
		} else {
      if ($this->form_validation->run() === FALSE) {
        $this->output->set_status_header(422);
        echo json_encode([
          'success' => false,
          'errors' => [
            'username' => form_error('username', '', ''),
            'first_name' => form_error('first_name', '', ''),
            'last_name' => form_error('last_name', '', ''),
            'role' => form_error('role', '', '')
          ]
        ]);
        return;
      }

			$data = [
        'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'role' => $this->input->post('role'),
        'is_active' => 1,
				'updated_by'  => $this->auth_lib->user_id()
			];
			
      $this->session->set_flashdata('success', 'User updated successfully');
			if(!$this->User_model->update($id, $data)){
				$this->session->set_flashdata('error', 'Failed to update user');
				if ($this->input->is_ajax_request()) {
					$this->output->set_status_header(500);
					echo json_encode([
						"success" => false,
						"error" => "Failed to update user"
					]);
					return;
				}else{
					redirect('user/edit/'.$id);
				}
			}

			$this->session->set_flashdata('success', 'User updated successfully');
			if ($this->input->is_ajax_request()) {
				$this->output->set_status_header(200);
				echo json_encode([
					"success" => true,
					"message" => "User updated successfully"
				]);
				return;
			}else{
				redirect('user/edit/'.$id);
			}
		}
	}

  public function delete($id)
  {
    $user = $this->User_model->get_by_id($id);
    if (!$user) {
      $this->session->set_flashdata('error', 'User not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'User not found.',
      ]);
      return;
    }

    if(!$this->User_model->delete($id)){
      $this->session->set_flashdata('error', 'Failed to delete user');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to delete user"
      ]);
      return;
    }
    
    $this->session->set_flashdata('success', 'User updated successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "User updated successfully"
    ]);
  }

  public function set_status($id) {
		$user = $this->User_model->get_by_id($id);
        
		if (!$user) {
			$this->session->set_flashdata('error', 'User not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'message' => 'User not found.',
      ]);
      return;
    }
        
		if(!$this->User_model->update($id, [
			'is_active'	 => $user['is_active'] ? 0 : 1,
			'updated_by' => $this->auth_lib->user_id()
		])){
			$this->session->set_flashdata('success', 'Failed to set status user');
			$this->output->set_status_header(500);
      echo json_encode([
        'success' => false,
        'message' => 'Failed to set status user.',
      ]);
      return;
		}
        
    $this->session->set_flashdata('success', 'Set status user successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      'success' => true,
      'message' => 'Set status user successfully.',
    ]);
  }

}