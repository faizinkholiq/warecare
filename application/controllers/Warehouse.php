<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'Warehouse_model',
            'Entity_model',
            'Company_model',
        ]);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data["current_user"] = $this->auth_lib->current_user();
        $data["title"] = "Warehouse";
        $data["menu_id"] = "warehouse";
        $data["list_data"]["entity"] = $this->Entity_model->get_all();
        $data["list_data"]["company"] = $this->Company_model->get_all();
        $data["view"] = "warehouse/index";
        $this->load->view('layouts/template', $data);
    }

    public function get_list()
    {
        $p['project'] = $this->input->post('project');
        $p['company'] = $this->input->post('company');
        $p['name'] = $this->input->post('name');

        $warehouses = $this->Warehouse_model->get_list($p);

        $data = [];
        foreach ($warehouses as $warehouse) {
            $data[] = [
                'id'            => $warehouse['id'],
                'name'          => $warehouse['name'],
                'status'        => $warehouse['status'],
                'owned_at'      => $warehouse['owned_at'],
                'handovered_at' => $warehouse['handovered_at'],
                'company'       => $warehouse['company_name'],
                'created_at'    => date('Y-m-d H:i:s', strtotime($warehouse['created_at'])),
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function get($id)
    {
        $warehouse = $this->Warehouse_model->get($id);
        if (!$warehouse) {
            $this->session->set_flashdata('error', 'Warehouse not found');
            $this->output->set_status_header(404);
            echo json_encode([
                'success' => false,
                'error' => 'Warehouse not found.',
            ]);
            return;
        }

        echo json_encode($warehouse);
    }

    public function create()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('company_id', 'Company', 'required');

        $data = [
            'name'          => $this->input->post('name'),
            'status'        => $this->input->post('status'),
            'owned_at'      => ($this->input->post('owned_at') && $this->input->post('owned_at') != '0000-00-00') ? $this->input->post('owned_at') : null,
            'handovered_at' => ($this->input->post('handovered_at') && $this->input->post('handovered_at') != '0000-00-00') ? $this->input->post('handovered_at') : null,
            'company_id'    => $this->input->post('company_id'),
            'created_by'    => $this->auth_lib->user_id(),
        ];

        $warehouse_id = $this->Warehouse_model->create($data);
        if (!$warehouse_id) {
            $this->session->set_flashdata('error', 'Failed to create warehouse');
            $this->output->set_status_header(500);
            echo json_encode([
                "success" => false,
                "error" => "Failed to create warehouse"
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Warehouse created successfully');
        $this->output->set_status_header(200);
        echo json_encode([
            "success" => true,
            "message" => "Warehouse created successfully"
        ]);
    }

    public function edit($id)
    {
        $warehouse = $this->Warehouse_model->get($id);
        if (!$warehouse) {
            $this->session->set_flashdata('error', 'Warehouse not found');
            $this->output->set_status_header(404);
            echo json_encode([
                'success' => false,
                'error' => 'Warehouse not found.',
            ]);
            return;
        }

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('company_id', 'Company', 'required');

        $data = [
            'name'          => $this->input->post('name'),
            'status'        => $this->input->post('status'),
            'owned_at'      => ($this->input->post('owned_at') && $this->input->post('owned_at') != '0000-00-00') ? $this->input->post('owned_at') : null,
            'handovered_at' => ($this->input->post('handovered_at') && $this->input->post('handovered_at') != '0000-00-00') ? $this->input->post('handovered_at') : null,
            'company_id'    => $this->input->post('company_id'),
            'updated_by'    => $this->auth_lib->user_id(),
        ];

        if (!$this->Warehouse_model->update($id, $data)) {
            $this->session->set_flashdata('error', 'Failed to update warehouse');
            $this->output->set_status_header(500);
            echo json_encode([
                "success" => false,
                "error" => "Failed to update warehouse"
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Warehouse updated successfully');
        $this->output->set_status_header(200);
        echo json_encode([
            "success" => true,
            "message" => "Warehouse updated successfully"
        ]);
    }

    public function delete($id)
    {
        $warehouse = $this->Warehouse_model->get($id);
        if (!$warehouse) {
            $this->session->set_flashdata('error', 'Warehouse not found');
            $this->output->set_status_header(404);
            echo json_encode([
                'success' => false,
                'error' => 'Warehouse not found.',
            ]);
            return;
        }

        if (!$this->Warehouse_model->delete($id)) {
            $this->session->set_flashdata('error', 'Failed to delete warehouse');
            $this->output->set_status_header(500);
            echo json_encode([
                "success" => false,
                "error" => "Failed to delete warehouse"
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Warehouse updated successfully');
        $this->output->set_status_header(200);
        echo json_encode([
            "success" => true,
            "message" => "Warehouse updated successfully"
        ]);
    }
}
