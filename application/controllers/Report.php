<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'Report_model',
			'Entity_model',
			'Project_model',
			'Company_model',
			'Warehouse_model',
			'Category_model',
		]);
	}

	public function index()
	{
		$data["current_user"] = $this->auth_lib->current_user();
		$data["title"] = "Pengaduan";
		$data["menu_id"] = "report";
		$data["view"] = "report/index";
    	$this->load->view('layouts/template', $data);
	}
	
	public function get_list_datatables()
	{
		$params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");

		$reports = $this->Report_model->get_list_datatables($params);
		
		echo json_encode($reports);
	}

	public function get($id)
	{
		$report = $this->Report_model->get($id);
		if (!$report) show_404();

		echo json_encode($report);
	}

	public function create()
	{
		$data["title"] = "Pengaduan";
		$data["menu_id"] = "report";
		$data["current_user"] = $this->auth_lib->current_user();
		$data["mode"] = "create";

		$this->form_validation->set_rules('entity_id', 'Entity', 'required');
		$this->form_validation->set_rules('project_id', 'Project', 'required');
		$this->form_validation->set_rules('company_id', 'Company', 'required');
		$this->form_validation->set_rules('warehouse_id', 'Warehouse', 'required');
		$this->form_validation->set_rules('category_id', 'Category', 'required');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		
		if (!$this->input->is_ajax_request() && $this->form_validation->run() === FALSE) {
			$data["list_data"]["entity"] = $this->Entity_model->get_all();
			$data["list_data"]["project"] = $this->Project_model->get_all();
			$data["list_data"]["company"] = $this->Company_model->get_all();
			$data["list_data"]["warehouse"] = $this->Warehouse_model->get_all();
			$data["list_data"]["category"] = $this->Category_model->get_all();
			$data["view"] = "report/form";

			$this->load->view('layouts/template', $data);
		} else {
			$evidence_files = $_FILES['evidence_files'] ?? [];

			if (empty($evidence_files)) {
				$this->output->set_status_header(422);
				echo json_encode([
					'success' => false,
					'error' => 'Please upload at least one evidence image.'
				]);
				return;
			}

			$uploaded_evidences = $this->handle_upload_images($evidence_files);

			$data = [
				'entity_id' => $this->input->post('entity_id'),
                'project_id' => $this->input->post('project_id'),
                'company_id' => $this->input->post('company_id'),
                'warehouse_id' => $this->input->post('warehouse_id'),
                'category_id' => $this->input->post('category_id'),
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
				'status' => 'Pending',
				'rab' => false,
				'created_by'  => $this->auth_lib->user_id()
			];

			$report_id = $this->Report_model->create($data);
			if (!$report_id) {
				$this->session->set_flashdata('error', 'Failed to create report');
				$this->output->set_status_header(500);
				echo json_encode([
					"success" => false,
					"error" => "Failed to create report"
				]);
				return;
			}

            if (!empty($uploaded_evidences)) {
                foreach ($uploaded_evidences as $file) {
                    $this->Report_model->add_evidence($report_id, $file['file_path'], $file['file_name']);
                }
            }
            
			$this->session->set_flashdata('success', 'Report created successfully');
			$this->output->set_status_header(200);
			echo json_encode([
				"success" => true,
				"message" => "Report created successfully"
			]);
		}
	}

	public function edit($id)
	{
		$data["title"] = "Pengaduan";
		$data["menu_id"] = "report";
		$data["current_user"] = $this->auth_lib->current_user();
		$data["mode"] = "edit";

		$report = $this->Report_model->get($id);
		if (!$report) {
			$this->session->set_flashdata('error', 'Report not found');
			if ($this->input->is_ajax_request()){
				$this->output->set_status_header(404);
				echo json_encode([
					'success' => false,
					'message' => 'Report not found.',
				]);
				return;
			}else{
				redirect('report');
			}
        }

		$this->form_validation->set_rules('entity_id', 'Entity', 'required');
		$this->form_validation->set_rules('project_id', 'Project', 'required');
		$this->form_validation->set_rules('company_id', 'Company', 'required');
		$this->form_validation->set_rules('warehouse_id', 'Warehouse', 'required');
		$this->form_validation->set_rules('category_id', 'Category', 'required');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		
		if (!$this->input->is_ajax_request() && $this->form_validation->run() === FALSE) {
			$data["report"] = $report;
			$data["report"]["evidences"] = $this->Report_model->get_evidences_by_report($id);
			$data["list_data"]["entity"] = $this->Entity_model->get_all();
			$data["list_data"]["project"] = $this->Project_model->get_all();
			$data["list_data"]["company"] = $this->Company_model->get_all();
			$data["list_data"]["warehouse"] = $this->Warehouse_model->get_all();
			$data["list_data"]["category"] = $this->Category_model->get_all();
			$data["view"] = "report/form";
			$this->load->view('layouts/template', $data);
		} else {
			$evidence_files = $_FILES['evidence_files'] ?? [];
			if (!empty($evidence_files)) {
				$uploaded_evidences = $this->handle_upload_images($evidence_files);
			}

			$deleted_evidences = json_decode($this->input->post('deleted_evidence_files'), true) ?? [];

			$data = [
				'entity_id' => $this->input->post('entity_id'),
                'project_id' => $this->input->post('project_id'),
                'company_id' => $this->input->post('company_id'),
                'warehouse_id' => $this->input->post('warehouse_id'),
                'category_id' => $this->input->post('category_id'),
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
				'updated_by'  => $this->auth_lib->user_id()
			];

			if (!$this->Report_model->update($id, $data)) {
				$this->session->set_flashdata('error', 'Failed to create report');
				$this->output->set_status_header(500);
				echo json_encode([
					"success" => false,
					"error" => "Failed to create report"
				]);
				return;
			}

            if (!empty($uploaded_evidences)) {
                foreach ($uploaded_evidences as $file) {
                    $this->Report_model->add_evidence($id, $file['file_path'], $file['file_name']);
                }
            }

			if (!empty($deleted_evidences)) {
				foreach ($deleted_evidences as $file_id) {
					$file = $this->Report_model->get_evidence($file_id);
					if ($file) {
						$this->handle_delete_images('./uploads/', $file['image_name']);
						$this->Report_model->delete_evidence($file_id);
					}
				}
			}
            
			$this->session->set_flashdata('success', 'Report updated successfully');
			$this->output->set_status_header(200);
			echo json_encode([
				"success" => true,
				"message" => "Report updated successfully"
			]);
		}
	}

	public function delete($id)
	{
		if (!$this->Report_model->get($id)) {
			$this->session->set_flashdata('error', 'Report not found');
			$this->output->set_status_header(404);
			echo json_encode([
				'success' => false,
				'message' => 'Report not found.',
			]);
			return;
        }

		if(!$this->Report_model->delete($id)){
			$this->session->set_flashdata('success', 'Delete report failed');
			$this->output->set_status_header(500);
			echo json_encode([
				'success' => false,
				'message' => 'Delete report failed.',
			]);
			return;
		}

		$images = $this->Report_model->get_evidences_by_report($id);
		foreach ($images as $image) {
			$this->handle_delete_images('./uploads/', $image['image_name']);
		}

		$this->Report_model->delete_evidences_by_report($id);

		$this->session->set_flashdata('success', 'Report deleted successfully');
		$this->output->set_status_header(200);
		echo json_encode([
			'success' => true,
			'message' => 'Report deleted successfully.',
		]);
	}

	private function handle_upload_images($files) {        
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '2048'; // 2MB
        $config['encrypt_name'] = TRUE;
		$config['file_ext_tolower'] = TRUE;
		$config['mimes'] = array(
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif'
		);

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }
        
        $this->load->library('upload', $config);
        $upload_data = [];

        for ($i = 0; $i < count($files['name']); $i++) {
			$_FILES['image']['name'] = $files['name'][$i];
            $_FILES['image']['type'] = $files['type'][$i];
            $_FILES['image']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['image']['error'] = $files['error'][$i];
            $_FILES['image']['size'] = $files['size'][$i];

			$this->upload->initialize($config);

            if ($this->upload->do_upload('image')) {
                $upload_data[] = $this->upload->data();
            }
        }
	
        return $upload_data;
    }

	private function handle_delete_images($filepath, $filename) {
		$filename = urldecode($filename);
        $filepath = $filepath . $this->security->sanitize_filename($filename);

		if (file_exists($filepath)) {
			unlink($filepath);
		}
	}
}
