<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Project_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data["current_user"] = $this->auth_lib->current_user();
		$data["title"] = "Project";
		$data["view"] = "project/index";
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
        'location'    => $project['location'],
        'project'     => $project['project_name'],
        'created_at'  => date('Y-m-d H:i:s', strtotime($project['created_at'])),
      ];
    }

    echo json_encode(['data' => $data]);
  }

  public function get($id)
  {
    $project = $this->Project_model->get($id);
    if (!$project) show_404();

    echo json_encode($project);
  }

  public function create()
  {
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('project_id', 'Project', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['projects'] = $this->Project_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('project/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'project_id'  => $this->input->post('project_id'),
        'created_by'  => $this->session->userdata('user_id'),
      ];
      $this->Project_model->insert($data);
      redirect('project');
    }
  }

  public function edit($id)
  {
    $project = $this->Project_model->get($id);
    if (!$project) show_404();

    $this->form_validation->set_rules('name', 'Name', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['project'] = $project;
      $data['projects'] = $this->Project_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('project/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'project_id'  => $this->input->post('project_id'),
        'updated_by'  => $this->session->userdata('user_id'),
      ];
      $this->Project_model->update($id, $data);
      redirect('project');
    }
  }

  public function delete($id)
  {
    $this->Project_model->delete($id);
    redirect('project');
  }
}
