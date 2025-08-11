<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Entity extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Entity_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data["current_user"] = $this->auth_lib->current_user();
		$data["title"] = "Entity";
		$data["menu_id"] = "entity";
		$data["view"] = "entity/index";
    $this->load->view('layouts/template', $data);
  }

  public function get_list()
  {
    $entities = $this->Entity_model->get_all();
    
    $data = [];
    foreach ($entities as $entity) {
      $data[] = [
        'id'          => $entity['id'],
        'name'        => $entity['name'],
        'location'    => $entity['location'],
        'entity'     => $entity['entity_name'],
        'created_at'  => date('Y-m-d H:i:s', strtotime($entity['created_at'])),
      ];
    }

    echo json_encode(['data' => $data]);
  }

  public function get($id)
  {
    $entity = $this->Entity_model->get($id);
    if (!$entity) show_404();

    echo json_encode($entity);
  }

  public function create()
  {
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('entity_id', 'Entity', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['entities'] = $this->Entity_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('entity/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'entity_id'  => $this->input->post('entity_id'),
        'created_by'  => $this->session->userdata('user_id'),
      ];
      $this->Entity_model->insert($data);
      redirect('entity');
    }
  }

  public function edit($id)
  {
    $entity = $this->Entity_model->get($id);
    if (!$entity) show_404();

    $this->form_validation->set_rules('name', 'Name', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['entity'] = $entity;
      $data['entities'] = $this->Entity_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('entity/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'entity_id'  => $this->input->post('entity_id'),
        'updated_by'  => $this->session->userdata('user_id'),
      ];
      $this->Entity_model->update($id, $data);
      redirect('entity');
    }
  }

  public function delete($id)
  {
    $this->Entity_model->delete($id);
    redirect('entity');
  }
}
