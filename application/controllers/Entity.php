<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Entity extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model([
      'Entity_model'
    ]);
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data["current_user"] = $this->auth_lib->current_user();
    $data["title"] = "Entity";
	  $data["view"] = "entity/index";
	  $data["menu_id"] = "entity";
    $this->load->view('layouts/template', $data);
  }

  public function get_list()
  {
    $entitys = $this->Entity_model->get_all();
    
    $data = [];
    foreach ($entitys as $entity) {
      $data[] = [
        'id'          => $entity['id'],
        'name'        => $entity['name'],
        'created_at'  => date('Y-m-d H:i:s', strtotime($entity['created_at'])),
      ];
    }

    echo json_encode(['data' => $data]);
  }

  public function get($id)
  {
    $entity = $this->Entity_model->get($id);
	if (!$entity) {
      $this->session->set_flashdata('error', 'Entity not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'Entity not found.',
      ]);
      return;
    }

    echo json_encode($entity);
  }

  public function create()
  {
    $this->form_validation->set_rules('name', 'Name', 'required');

    $data = [
      'name'        => $this->input->post('name'),
      'created_by'  => $this->auth_lib->user_id(),
    ];

    $entity_id = $this->Entity_model->create($data);
    if (!$entity_id) {
      $this->session->set_flashdata('error', 'Failed to create entity');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to create entity"
      ]);
      return;
    }

    $this->session->set_flashdata('success', 'Entity created successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "Entity created successfully"
    ]);
  }

  public function edit($id)
  {
    $entity = $this->Entity_model->get($id);
    if (!$entity) {
      $this->session->set_flashdata('error', 'Entity not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'Entity not found.',
      ]);
      return;
    }

    $this->form_validation->set_rules('name', 'Name', 'required');

    $data = [
      'name'        => $this->input->post('name'),
      'updated_by'  => $this->auth_lib->user_id(),
    ];

    if(!$this->Entity_model->update($id, $data)) {
      $this->session->set_flashdata('error', 'Failed to update entity');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to update entity"
      ]);
      return;
    }
    
    $this->session->set_flashdata('success', 'Entity updated successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "Entity updated successfully"
    ]);
  }

  public function delete($id)
  {
    $entity = $this->Entity_model->get($id);
    if (!$entity) {
      $this->session->set_flashdata('error', 'Entity not found');
      $this->output->set_status_header(404);
      echo json_encode([
        'success' => false,
        'error' => 'Entity not found.',
      ]);
      return;
    }

    if(!$this->Entity_model->delete($id)){
      $this->session->set_flashdata('error', 'Failed to delete entity');
      $this->output->set_status_header(500);
      echo json_encode([
        "success" => false,
        "error" => "Failed to delete entity"
      ]);
      return;
    }
    
    $this->session->set_flashdata('success', 'Entity updated successfully');
    $this->output->set_status_header(200);
    echo json_encode([
      "success" => true,
      "message" => "Entity updated successfully"
    ]);
  }
}
