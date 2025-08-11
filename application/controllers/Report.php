<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'Report_model',
		]);
	}

	public function index()
	{
		$data["current_user"] = $this->auth_lib->current_user();
		$data["title"] = "Pengaduan";
		$data["menu_id"] = "report";
		$data["view"] = "report/form";
    	$this->load->view('layouts/template', $data);
	}

	public function get_list()
	{
		$reports = $this->Report_model->get_all();
		
		$data = [];
		foreach ($reports as $product) {
		$data[] = [
			'id'          => $product['id'],
			'name'        => $product['name'],
			'location'    => $product['location'],
			'company'     => $product['company_name'],
			'created_at'  => date('Y-m-d H:i:s', strtotime($product['created_at'])),
		];
		}

		echo json_encode(['data' => $data]);
	}
	
	public function get_list_datatables()
	{
		$params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

		$products = $this->Report_model->get_list_datatables($params);
		
		echo json_encode($products);
	}

	public function get($id)
	{
		$product = $this->Report_model->get($id);
		if (!$product) show_404();

		echo json_encode($product);
	}

	public function create()
	{
		$data["title"] = "Product Management";
		$data["sub_title"] = "Add New";
		$data["current_user"] = $this->auth_lib->current_user();
		
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('price', 'Price', 'required|numeric');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|integer');
		
		if (!$this->input->is_ajax_request() && $this->form_validation->run() === FALSE) {
			$data["menu_id"] = "add_product";
			$data["mode"] = "create";
			$data["list_data"]["category"] = $this->Category_model->get_all();
			$data["view"] = "seller/product/form";

			$this->load->view('seller/layouts/template', $data);
		} else {
			
			$data = [
				'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'sku' => $this->input->post('sku'),
                'price' => (float)str_replace('.', '', $this->input->post('price')),
                'quantity' => $this->input->post('stock'),
                'category_id' => $this->input->post('category_id'),
                'is_active' => $this->input->post('status') ? 1 : 0,
				'created_by'  => $this->auth_lib->user_id()
			];

			$product_id = $this->Report_model->create($data);
			if (!$product_id) {
				$this->session->set_flashdata('error', 'Failed to create product');
				if ($this->input->is_ajax_request()) {
					$this->output->set_status_header(500);
					echo json_encode([
						"success" => false,
						"error" => "Failed to create product"
					]);
					return;
				}else{
					redirect('seller/product/create');
				}
			}

            if (!empty($upload_data)) {
                foreach ($upload_data as $file) {
                    $this->Report_model->add_image($product_id, $file['file_path'], $file['file_name']);
                }
            }
            
			$this->session->set_flashdata('success', 'Product created successfully');
			if ($this->input->is_ajax_request()) {
				$this->output->set_status_header(200);
				echo json_encode([
					"success" => true,
					"message" => "Product created successfully"
				]);
				return;
			}else{
				redirect('seller/product');
			}
		}
	}

	public function edit($id)
	{
		$data["title"] = "Product Management";
		$data["sub_title"] = "Edit";
		$data["current_user"] = $this->auth_lib->current_user();

		$product = $this->Report_model->get_by_id($id);
		if (!$product) {
			$this->session->set_flashdata('error', 'Product not found');
			if ($this->input->is_ajax_request()){
				$this->output->set_status_header(404);
				echo json_encode([
					'success' => false,
					'message' => 'Product not found.',
				]);
				return;
			}else{
				redirect('seller/product');
			}
		}

		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('price', 'Price', 'required|numeric');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|integer');

		if (!$this->input->is_ajax_request() && $this->form_validation->run() === FALSE) {
			$data["menu_id"] = "my_products";
			$data["mode"] = "edit";
			$data["product"] = $this->Report_model->get_details($id);
			$data["list_data"]["category"] = $this->Category_model->get_all();
			$data["view"] = "seller/product/form";

			$this->load->view('seller/layouts/template', $data);
		} else {
			$data = [
				'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'sku' => $this->input->post('sku'),
                'price' => (float)str_replace('.', '', $this->input->post('price')),
                'quantity' => $this->input->post('stock'),
                'category_id' => $this->input->post('category_id'),
                'is_active' => $this->input->post('status') ? 1 : 0,
				'updated_by'  => $this->auth_lib->user_id()
			];
			
            $this->session->set_flashdata('success', 'Product updated successfully');
			if(!$this->Report_model->update($id, $data)){
				$this->session->set_flashdata('error', 'Failed to update product');
				if ($this->input->is_ajax_request()) {
					$this->output->set_status_header(500);
					echo json_encode([
						"success" => false,
						"error" => "Failed to update product"
					]);
					return;
				}else{
					redirect('seller/product/edit/'.$id);
				}
			}

			if (!empty($upload_data)) {
                foreach ($upload_data as $file) {
                    $this->Report_model->add_image($id, $file['file_path'], $file['file_name']);
                }
            }

			$this->session->set_flashdata('success', 'Product updated successfully');
			if ($this->input->is_ajax_request()) {
				$this->output->set_status_header(200);
				echo json_encode([
					"success" => true,
					"message" => "Product updated successfully"
				]);
				return;
			}else{
				redirect('seller/product/edit/'.$id);
			}
		}
	}

	public function delete($id)
	{
		if (!$this->Report_model->get_by_id($id)) {
			$this->session->set_flashdata('error', 'Product not found');
			if ($this->input->is_ajax_request()){
				$this->output->set_status_header(404);
				echo json_encode([
					'success' => false,
					'message' => 'Product not found.',
				]);
				return;
			}else{
				redirect('seller/product');
			}
        }

		if(!$this->Report_model->delete($id)){
			$this->session->set_flashdata('success', 'Delete product failed');
			if ($this->input->is_ajax_request()){
				$this->output->set_status_header(500);
				echo json_encode([
					'success' => false,
					'message' => 'Delete product failed.',
				]);
				return;
			}else{
				redirect('seller/product');
			}
		}

		$this->session->set_flashdata('success', 'Product deleted successfully');
		if ($this->input->is_ajax_request()){
			$this->output->set_status_header(200);
			echo json_encode([
				'success' => true,
				'message' => 'Product deleted successfully.',
			]);
			return;
		}else{
			redirect('seller/product');
		}
	}
}
