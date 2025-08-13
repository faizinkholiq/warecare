<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Category_model');
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data["current_user"] = $this->auth_lib->current_user();
		$data["title"] = "Category";
		$data["menu_id"] = "category";
		$data["view"] = "category/index";
    $this->load->view('layouts/template', $data);
  }

  public function get_list()
  {
    $categories = $this->Category_model->get_all();
    
    $data = [];
    foreach ($categories as $category) {
      $data[] = [
        'id'          => $category['id'],
        'name'        => $category['name'],
        'location'    => $category['location'],
        'category'     => $category['company_name'],
        'created_at'  => date('Y-m-d H:i:s', strtotime($category['created_at'])),
      ];
    }

    echo json_encode(['data' => $data]);
  }

  public function get($id)
  {
    $category = $this->Category_model->get($id);
    if (!$category) show_404();

    echo json_encode($category);
  }

  public function create()
  {
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('category_id', 'Category', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['categories'] = $this->Category_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('category/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'category_id'  => $this->input->post('category_id'),
        'created_by'  => $this->auth_lib->user_id(),
      ];
      $this->Category_model->insert($data);
      redirect('category');
    }
  }

  public function edit($id)
  {
    $category = $this->Category_model->get($id);
    if (!$category) show_404();

    $this->form_validation->set_rules('name', 'Name', 'required');

    if ($this->form_validation->run() === FALSE) {
      $data['category'] = $category;
      $data['categories'] = $this->Category_model->get_companies();
      $this->load->view('layouts/header');
      $this->load->view('category/form', $data);
      $this->load->view('layouts/footer');
    } else {
      $data = [
        'name'        => $this->input->post('name'),
        'location'    => $this->input->post('location'),
        'category_id'  => $this->input->post('category_id'),
        'updated_by'  => $this->auth_lib->user_id(),
      ];
      $this->Category_model->update($id, $data);
      redirect('category');
    }
  }

  public function delete($id)
  {
    $this->Category_model->delete($id);
    redirect('category');
  }
}
