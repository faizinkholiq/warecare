<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Company_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data["current_user"] = $this->auth_lib->current_user();
		$data["title"] = "Company";
		$data["menu_id"] = "company";
		$data["view"] = "company/index";
    $this->load->view('layouts/template', $data);
  }

  public function get_list()
  {
    $companies = $this->Company_model->get_all();
    
    $data = [];
    foreach ($companies as $company) {
      $data[] = [
        'id'          => $company['id'],
        'name'        => $company['name'],
        'location'    => $company['location'],
        'company'     => $company['company_name'],
        'created_at'  => date('Y-m-d H:i:s', strtotime($company['created_at'])),
      ];
    }

    echo json_encode(['data' => $data]);
  }

  public function get($id)
  {
    $company = $this->Company_model->get($id);
    if (!$company) show_404();

    echo json_encode($company);
  }

  public function create()
  {
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('company_id', 'Company', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['companies'] = $this->Company_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('company/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'company_id'  => $this->input->post('company_id'),
        'created_by'  => $this->session->userdata('user_id'),
      ];
      $this->Company_model->insert($data);
      redirect('company');
    }
  }

  public function edit($id)
  {
    $company = $this->Company_model->get($id);
    if (!$company) show_404();

    $this->form_validation->set_rules('name', 'Name', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['company'] = $company;
      $data['companies'] = $this->Company_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('company/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'company_id'  => $this->input->post('company_id'),
        'updated_by'  => $this->session->userdata('user_id'),
      ];
      $this->Company_model->update($id, $data);
      redirect('company');
    }
  }

  public function delete($id)
  {
    $this->Company_model->delete($id);
    redirect('company');
  }
}
