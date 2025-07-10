<?php

// application/controllers/Warehouses.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Warehouse_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data["current_user"] = $this->auth_lib->current_user();
		$data["title"] = "Warehouse";
		$data["view"] = "warehouse/index";
    $this->load->view('layouts/template', $data);
  }

  public function get_list()
  {
    $warehouses = $this->Warehouse_model->get_all();
    
    $data = [];
    foreach ($warehouses as $warehouse) {
      $data[] = [
        'id'          => $warehouse['id'],
        'name'        => $warehouse['name'],
        'location'    => $warehouse['location'],
        'company'     => $warehouse['company_name'],
        'created_at'  => date('Y-m-d H:i:s', strtotime($warehouse['created_at'])),
      ];
    }

    echo json_encode(['data' => $data]);
  }

  public function get($id)
  {
    $warehouse = $this->Warehouse_model->get($id);
    if (!$warehouse) show_404();

    echo json_encode($warehouse);
  }

  public function create()
  {
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('company_id', 'Company', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['companies'] = $this->Warehouse_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('warehouses/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'company_id'  => $this->input->post('company_id'),
        'created_by'  => $this->session->userdata('user_id'),
      ];
      $this->Warehouse_model->insert($data);
      redirect('warehouse');
    }
  }

  public function edit($id)
  {
    $warehouse = $this->Warehouse_model->get($id);
    if (!$warehouse) show_404();

    $this->form_validation->set_rules('name', 'Name', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['warehouse'] = $warehouse;
      $data['companies'] = $this->Warehouse_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('warehouses/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'company_id'  => $this->input->post('company_id'),
        'updated_by'  => $this->session->userdata('user_id'),
      ];
      $this->Warehouse_model->update($id, $data);
      redirect('warehouses');
    }
  }

  public function delete($id)
  {
    $this->Warehouse_model->delete($id);
    redirect('warehouses');
  }
}
