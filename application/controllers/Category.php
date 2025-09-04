<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Category extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'Category_model'
        ]);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["current_user"] = $this->auth_lib->current_user();
        $data["title"] = "Category";
        $data["view"] = "category/index";
        $data["menu_id"] = "category";
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
                'created_at'  => date('Y-m-d H:i:s', strtotime($category['created_at'])),
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function get($id)
    {
        $category = $this->Category_model->get($id);
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found');
            $this->output->set_status_header(404);
            echo json_encode([
                'success' => false,
                'error' => 'Category not found.',
            ]);
            return;
        }

        echo json_encode($category);
    }

    public function create()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');

        $data = [
            'name'        => $this->input->post('name'),
            'created_by'  => $this->auth_lib->user_id(),
        ];

        $category_id = $this->Category_model->create($data);
        if (!$category_id) {
            $this->session->set_flashdata('error', 'Failed to create category');
            $this->output->set_status_header(500);
            echo json_encode([
                "success" => false,
                "error" => "Failed to create category"
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Category created successfully');
        $this->output->set_status_header(200);
        echo json_encode([
            "success" => true,
            "message" => "Category created successfully"
        ]);
    }

    public function edit($id)
    {
        $category = $this->Category_model->get($id);
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found');
            $this->output->set_status_header(404);
            echo json_encode([
                'success' => false,
                'error' => 'Category not found.',
            ]);
            return;
        }

        $this->form_validation->set_rules('name', 'Name', 'required');

        $data = [
            'name'        => $this->input->post('name'),
            'updated_by'  => $this->auth_lib->user_id(),
        ];

        if (!$this->Category_model->update($id, $data)) {
            $this->session->set_flashdata('error', 'Failed to update category');
            $this->output->set_status_header(500);
            echo json_encode([
                "success" => false,
                "error" => "Failed to update category"
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Category updated successfully');
        $this->output->set_status_header(200);
        echo json_encode([
            "success" => true,
            "message" => "Category updated successfully"
        ]);
    }

    public function delete($id)
    {
        $category = $this->Category_model->get($id);
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found');
            $this->output->set_status_header(404);
            echo json_encode([
                'success' => false,
                'error' => 'Category not found.',
            ]);
            return;
        }

        if (!$this->Category_model->delete($id)) {
            $this->session->set_flashdata('error', 'Failed to delete category');
            $this->output->set_status_header(500);
            echo json_encode([
                "success" => false,
                "error" => "Failed to delete category"
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Category updated successfully');
        $this->output->set_status_header(200);
        echo json_encode([
            "success" => true,
            "message" => "Category updated successfully"
        ]);
    }
}
