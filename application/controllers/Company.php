<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model([
      'Company_model'
    ]);
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data["current_user"] = $this->auth_lib->current_user();
    $data["title"] = "Company";
	  $data["view"] = "company/index";
	  $data["menu_id"] = "company";
    $this->load->view('layouts/template', $data);
  }

  public function get_list()
  {
    $companys = $this->Company_model->get_all();
    
    $data = [];
    foreach ($companys as $company) {
      $data[] = [
        'id'          => $company['id'],
        'name'        => $company['name'],
        'company'     => $company['company_name'],
        'created_at'  => date('Y-m-d H:i:s', strtotime($company['created_at'])),
      ];
    }

    echo json_encode(['data' => $data]);
  }

  public function get($id)
  {
    $company = $this->Company_model->get($id);
	if (!$company) {
      $this->session->set_flashdata('error', 'Company not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'Company not found.',
      ]);
      return;
    }

    echo json_encode($company);
  }

  public function create()
  {
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('company_id', 'Company', 'required');

    $data = [
      'name'        => $this->input->post('name'),
      'company_id'  => $this->input->post('company_id'),
      'created_by'  => $this->session->userdata('user_id'),
    ];

    $company_id = $this->Company_model->create($data);
    if (!$company_id) {
      $this->session->set_flashdata('error', 'Failed to create company');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to create company"
      ]);
      return;
    }

    $this->session->set_flashdata('success', 'Company created successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "Company created successfully"
    ]);
  }

  public function edit($id)
  {
    $company = $this->Company_model->get($id);
    if (!$company) {
      $this->session->set_flashdata('error', 'Company not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'Company not found.',
      ]);
      return;
    }

    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('company_id', 'Company', 'required');

    $data = [
      'name'        => $this->input->post('name'),
      'company_id'  => $this->input->post('company_id'),
      'updated_by'  => $this->session->userdata('user_id'),
    ];

    if(!$this->Company_model->update($id, $data)) {
      $this->session->set_flashdata('error', 'Failed to update company');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to update company"
      ]);
      return;
    }
    
    $this->session->set_flashdata('success', 'Company updated successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "Company updated successfully"
    ]);
  }

  public function delete($id)
  {
    $company = $this->Company_model->get($id);
    if (!$company) {
      $this->session->set_flashdata('error', 'Company not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'Company not found.',
      ]);
      return;
    }

    if(!$this->Company_model->delete($id)){
      $this->session->set_flashdata('error', 'Failed to delete company');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to delete company"
      ]);
      return;
    }
    
    $this->session->set_flashdata('success', 'Company updated successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "Company updated successfully"
    ]);
  }
}
