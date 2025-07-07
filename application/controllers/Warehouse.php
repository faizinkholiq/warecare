<?php

// application/controllers/Warehouses.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Warehouse_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data['warehouses'] = $this->Warehouse_model->get_all();
    print_r($data);
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
