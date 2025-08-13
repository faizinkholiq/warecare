<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model([
      'Project_model'
    ]);
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data["current_user"] = $this->auth_lib->current_user();
    $data["title"] = "Project";
	  $data["view"] = "project/index";
	  $data["menu_id"] = "project";
    $this->load->view('layouts/template', $data);
  }

  public function get_list()
  {
    $projects = $this->Project_model->get_all();
    
    $data = [];
    foreach ($projects as $project) {
      $data[] = [
        'id'          => $project['id'],
        'name'        => $project['name'],
        'created_at'  => date('Y-m-d H:i:s', strtotime($project['created_at'])),
      ];
    }

    echo json_encode(['data' => $data]);
  }

  public function get($id)
  {
    $project = $this->Project_model->get($id);
	if (!$project) {
      $this->session->set_flashdata('error', 'Project not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'Project not found.',
      ]);
      return;
    }

    echo json_encode($project);
  }

  public function create()
  {
    $this->form_validation->set_rules('name', 'Name', 'required');

    $data = [
      'name'        => $this->input->post('name'),
      'created_by'  => $this->auth_lib->user_id(),
    ];

    $project_id = $this->Project_model->create($data);
    if (!$project_id) {
      $this->session->set_flashdata('error', 'Failed to create project');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to create project"
      ]);
      return;
    }

    $this->session->set_flashdata('success', 'Project created successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "Project created successfully"
    ]);
  }

  public function edit($id)
  {
    $project = $this->Project_model->get($id);
    if (!$project) {
      $this->session->set_flashdata('error', 'Project not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'Project not found.',
      ]);
      return;
    }

    $this->form_validation->set_rules('name', 'Name', 'required');

    $data = [
      'name'        => $this->input->post('name'),
      'updated_by'  => $this->auth_lib->user_id(),
    ];

    if(!$this->Project_model->update($id, $data)) {
      $this->session->set_flashdata('error', 'Failed to update project');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to update project"
      ]);
      return;
    }
    
    $this->session->set_flashdata('success', 'Project updated successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "Project updated successfully"
    ]);
  }

  public function delete($id)
  {
    $project = $this->Project_model->get($id);
    if (!$project) {
      $this->session->set_flashdata('error', 'Project not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'Project not found.',
      ]);
      return;
    }

    if(!$this->Project_model->delete($id)){
      $this->session->set_flashdata('error', 'Failed to delete croject');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to delete project"
      ]);
      return;
    }
    
    $this->session->set_flashdata('success', 'Project updated successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "Project updated successfully"
    ]);
  }
}
