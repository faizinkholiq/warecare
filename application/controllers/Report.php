<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Report extends MY_Controller
{
    private $CATEGORY_WITH_DETAIL = [1, 2, 3, 4, 5];
    private $MAX_FILE_COUNT = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'Report_model',
            'Entity_model',
            'Project_model',
            'Company_model',
            'Warehouse_model',
            'Category_model',
        ]);
        $this->load->library('pdf');
        $this->load->helper('excel');
    }

    public function index()
    {
        $mode = $this->input->get('mode') ?? '';
        if ($mode == 'excel') {
            $this->export_excel();
            return;
        }

        $data["title"] = "Pengaduan";
        $data["menu_id"] = "report";

        $data["current_user"] = $this->auth_lib->current_user();
        $data["list_data"]["category"] = $this->Category_model->get_all();

        $data["view"] = "report/index";

        $this->load->view('layouts/template', $data);
    }

    public function get_list_datatables()
    {
        $params["columns"] = $this->input->post("columns");
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");
        $params['rab_only'] = false;
        $params["start_date"] = $this->input->post("start_date");
        $params["end_date"] = $this->input->post("end_date");

        if (!empty($params["start_date"])) {
            $params["start_date"] = date('Y-m-d', strtotime($params["start_date"]));
        }

        if (!empty($params["end_date"])) {
            $params["end_date"] = date('Y-m-d', strtotime($params["end_date"]));
        }

        if ($this->auth_lib->role() === 'pelapor') {
            $params["reported_by"] = $this->auth_lib->user_id();
        } else if ($this->auth_lib->role() === 'rab') {
            $params['rab_only'] = true;
        }

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

        if (!$this->input->is_ajax_request() && $this->form_validation->run() === FALSE) {
            $data["list_data"]["entity"] = $this->Entity_model->get_all();
            $data["list_data"]["project"] = $this->Project_model->get_all();
            $data["list_data"]["company"] = $this->Company_model->get_all();
            $data["list_data"]["warehouse"] = $this->Warehouse_model->get_all();
            $data["list_data"]["category"] = $this->Category_model->get_all();
            $data['category_with_detail'] = $this->CATEGORY_WITH_DETAIL;

            $data["view"] = "report/form";

            $this->load->view('layouts/template', $data);
        } else {
            $data = [
                'entity_id' => $this->input->post('entity_id'),
                'project_id' => $this->input->post('project_id'),
                'company_id' => $this->input->post('company_id'),
                'warehouse_id' => $this->input->post('warehouse_id'),
                'category_id' => $this->input->post('category_id'),
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'status' => 'Pending',
                'is_rab' => (bool)$this->input->post('is_rab'),
                'created_by'  => $this->auth_lib->user_id()
            ];

            $entity = $this->Entity_model->get($data['entity_id']);
            if ($entity) {
                $prefix_no = $entity["id"];
                if (preg_match_all('/\d+/', $entity["name"], $matches)) {
                    $numbers = $matches[0];
                    if (count($numbers) > 0) {
                        $prefix_no = end($numbers);
                    }
                }

                $data["no"] = $prefix_no . '-' . date('Ym') . '-' . str_pad($this->Report_model->get_next_id(), 4, '0', STR_PAD_LEFT);
            }

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

            $details = !empty($this->input->post('details')) ? json_decode($this->input->post('details'), true) : [];
            if (!empty($details)) {
                foreach ($details as $item) {
                    $item_data = [
                        'report_id' => $report_id,
                        'level' => $item['level'],
                        'parent_id' => $item['parent_id'] ?? null,
                        'no' => $item['no'],
                        'description' => $item['description'],
                        'status' => $item['level'] == 2 ? $item['status'] : null,
                        'condition' => $item['level'] == 2 ?  $item['condition'] : null,
                        'information' => $item['level'] == 2 ? $item['information'] : null,
                        'is_show' => 0,
                    ];
                    $this->Report_model->add_detail($item_data);
                }
            }

            $work_data = !empty($this->input->post('work_data')) ? json_decode($this->input->post('work_data'), true) : [];
            if (!empty($work_data)) {
                foreach ($work_data as $index => $work_item) {
                    $work_record = [
                        'report_id' => $report_id,
                        'description_before' => $work_item['description_before'],
                    ];

                    if (isset($_FILES['work_image_before']['name'][$index]) && !empty($_FILES['work_image_before']['name'][$index])) {
                        $file_before = [
                            'name' => $_FILES['work_image_before']['name'][$index],
                            'type' => $_FILES['work_image_before']['type'][$index],
                            'tmp_name' => $_FILES['work_image_before']['tmp_name'][$index],
                            'error' => $_FILES['work_image_before']['error'][$index],
                            'size' => $_FILES['work_image_before']['size'][$index]
                        ];

                        $uploaded_before = $this->handle_upload_file($file_before, 'work_before');
                        if ($uploaded_before) {
                            $work_record['image_path_before'] = $uploaded_before['file_path'];
                            $work_record['image_name_before'] = $uploaded_before['file_name'];
                        }
                    }

                    $this->Report_model->add_work($work_record);
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

    public function detail($id)
    {
        $report = $this->Report_model->get($id);
        if (!$report) {
            $this->session->set_flashdata('error', 'Report not found');
            if ($this->input->is_ajax_request()) {
                $this->output->set_status_header(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Report not found.',
                ]);
                return;
            } else {
                redirect('report');
            }
        }

        $data["title"] = "Pengaduan";
        $data["menu_id"] = "report";
        $data["current_user"] = $this->auth_lib->current_user();
        $data["mode"] = "detail";

        $data["report"] = $report;
        $data["report"]["rab"] = $this->Report_model->get_rab($id);
        $data["report"]["manager"] = $this->Report_model->get_manager($id);
        $data["report"]["details"] = $this->Report_model->get_details_by_report($id);
        $data["report"]["works"] = $this->Report_model->get_works_by_report($id);

        $data["list_data"]["entity"] = $this->Entity_model->get_all();
        $data["list_data"]["project"] = $this->Project_model->get_all();
        $data["list_data"]["company"] = $this->Company_model->get_all();
        $data["list_data"]["warehouse"] = $this->Warehouse_model->get_all();
        $data["list_data"]["category"] = $this->Category_model->get_all();
        $data['category_with_detail'] = $this->CATEGORY_WITH_DETAIL;

        $data["view"] = "report/form";

        $this->load->view('layouts/template', $data);
    }

    public function edit($id)
    {
        $report = $this->Report_model->get($id);
        if (!$report) {
            $this->session->set_flashdata('error', 'Report not found');
            if ($this->input->is_ajax_request()) {
                $this->output->set_status_header(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Report not found.',
                ]);
                return;
            } else {
                redirect('report');
            }
        }

        $data["title"] = "Pengaduan";
        $data["menu_id"] = "report";
        $data["current_user"] = $this->auth_lib->current_user();
        $data["mode"] = "edit";

        $this->form_validation->set_rules('entity_id', 'Entity', 'required');
        $this->form_validation->set_rules('project_id', 'Project', 'required');
        $this->form_validation->set_rules('company_id', 'Company', 'required');
        $this->form_validation->set_rules('warehouse_id', 'Warehouse', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');

        if (!$this->input->is_ajax_request() && $this->form_validation->run() === FALSE) {
            $data["report"] = $report;
            $data["report"]["rab"] = $this->Report_model->get_rab($id);
            $data["report"]["manager"] = $this->Report_model->get_manager($id);
            $data["report"]["details"] = $this->Report_model->get_details_by_report($id);
            $data["report"]["works"] = $this->Report_model->get_works_by_report($id);


            $data["list_data"]["entity"] = $this->Entity_model->get_all();
            $data["list_data"]["project"] = $this->Project_model->get_all();
            $data["list_data"]["company"] = $this->Company_model->get_all();
            $data["list_data"]["warehouse"] = $this->Warehouse_model->get_all();
            $data["list_data"]["category"] = $this->Category_model->get_all();
            $data['category_with_detail'] = $this->CATEGORY_WITH_DETAIL;

            $data["view"] = "report/form";

            $this->load->view('layouts/template', $data);
        } else {

            $data = [
                'status' => $this->input->post('status') ?: 'Pending',
                'updated_by'  => $this->auth_lib->user_id()
            ];

            switch ($data['status']) {
                case 'On Process':
                    $data['processed_by'] = $this->auth_lib->user_id();
                    $data['processed_at'] = date('Y-m-d H:i:s');
                    break;

                case 'Approved':
                    $data['approved_by'] = $this->auth_lib->user_id();
                    $data['approved_at'] = date('Y-m-d H:i:s');
                    break;

                case 'Completed':
                    $data['completed_by'] = $this->auth_lib->user_id();
                    $data['completed_at'] = date('Y-m-d H:i:s');
                    break;
            }

            switch ($this->auth_lib->role()) {
                case 'pelapor':
                    $data['entity_id'] = $this->input->post('entity_id');
                    $data['project_id'] = $this->input->post('project_id');
                    $data['company_id'] = $this->input->post('company_id');
                    $data['warehouse_id'] = $this->input->post('warehouse_id');
                    $data['category_id'] = $this->input->post('category_id');
                    $data['title'] = $this->input->post('title');
                    $data['description'] = $this->input->post('description');

                    if ($this->Report_model->delete_details_by_report($id)) {
                        if (in_array($data['category_id'], $this->CATEGORY_WITH_DETAIL)) {
                            $details = !empty($this->input->post('details')) ? json_decode($this->input->post('details'), true) : [];
                            if (!empty($details)) {
                                foreach ($details as $item) {
                                    $item_data = [
                                        'report_id' => $id,
                                        'level' => $item['level'],
                                        'parent_id' => $item['parent_id'] ?? null,
                                        'no' => $item['no'],
                                        'description' => $item['description'],
                                        'status' => $item['level'] == 2 ? $item['status'] : null,
                                        'condition' => $item['level'] == 2 ?  $item['condition'] : null,
                                        'information' => $item['level'] == 2 ? $item['information'] : null,
                                    ];
                                    $this->Report_model->add_detail($item_data);
                                }
                            }
                        }
                    }

                    if ($data['status'] === 'Completed') {
                        $work_data = !empty($this->input->post('work_data')) ? json_decode($this->input->post('work_data'), true) : [];

                        if (!empty($work_data)) {
                            foreach ($work_data as $index => $work_item) {
                                $work_record = [
                                    'description_after' => $work_item['description_after'],
                                ];

                                // Handle after image
                                if (isset($_FILES['work_image_after']['name'][$index]) && !empty($_FILES['work_image_after']['name'][$index])) {
                                    $file_after = [
                                        'name' => $_FILES['work_image_after']['name'][$index],
                                        'type' => $_FILES['work_image_after']['type'][$index],
                                        'tmp_name' => $_FILES['work_image_after']['tmp_name'][$index],
                                        'error' => $_FILES['work_image_after']['error'][$index],
                                        'size' => $_FILES['work_image_after']['size'][$index]
                                    ];

                                    $uploaded_after = $this->handle_upload_file($file_after, 'work_after');
                                    if ($uploaded_after) {
                                        $work_record['image_path_after'] = $uploaded_after['file_path'];
                                        $work_record['image_name_after'] = $uploaded_after['file_name'];
                                    }
                                }

                                $this->Report_model->update_work($work_item['id'], $work_record);
                            }
                        }
                    }

                    break;
                case 'kontraktor':
                    $data['is_rab'] = (bool)$this->input->post('is_rab');
                    if ($data['is_rab']) {
                        $new_rab = [
                            'report_id' => $id,
                            'no' => $this->input->post('rab_no'),
                            'name' => $this->input->post('rab_name'),
                            'budget' => (float)str_replace('.', '', $this->input->post('rab_budget') ?: 0),
                            'description' => $this->input->post('rab_description'),
                        ];

                        $rab_file = $_FILES['rab_file'] ?? [];
                        if ($rab_file) {
                            $uploaded_rab = $this->handle_upload_file($rab_file);
                            if ($uploaded_rab) {
                                $new_rab['file'] = $uploaded_rab['file_name'];
                            }
                        }

                        $this->Report_model->create_rab($new_rab);
                    }

                    break;
                case 'rab':
                    $rab = $this->Report_model->get_rab($id);
                    if ($rab) {
                        $delete_rab_file = $this->input->post('delete_rab_file') === 'true';
                        $delete_rab_final_file = $this->input->post('delete_rab_final_file') === 'true';

                        $new_rab = [
                            'final_budget' => (float)str_replace('.', '', $this->input->post('rab_final_budget') ?: 0)
                        ];

                        if ($delete_rab_file && !empty($rab['file'])) {
                            $this->handle_delete_file('./uploads/', $rab['file']);
                            $new_rab['file'] = null;
                        }

                        if ($delete_rab_final_file && !empty($rab['final_file'])) {
                            $this->handle_delete_file('./uploads/', $rab['final_file']);
                            $new_rab['final_file'] = null;
                        }

                        $rab_final_file = $_FILES['rab_final_file'] ?? [];
                        if ($rab_final_file) {
                            $uploaded_rab = $this->handle_upload_file($rab_final_file);
                            if ($uploaded_rab) {
                                $new_rab['final_file'] = $uploaded_rab['file_name'];
                            }
                        }

                        $this->Report_model->update_rab($id, $new_rab);
                    }

                    break;
                case 'manager':
                    if ($data['status'] === 'Approved') {
                        $manager = $this->Report_model->get_manager($id);
                        if ($manager) {
                            $delete_manager_payment_file = $this->input->post('delete_manager_payment_file') === 'true';

                            $new_manager = [];

                            if ($delete_manager_payment_file && !empty($manager['payment_file'])) {
                                $this->handle_delete_file('./uploads/', $manager['payment_file']);
                            }

                            $manager_payment_file = $_FILES['manager_payment_file'] ?? [];
                            if ($manager_payment_file) {
                                $uploaded_manager_payment = $this->handle_upload_file($manager_payment_file, 'manager_payment');
                                if ($uploaded_manager_payment) {
                                    $new_manager['payment_file'] = $uploaded_manager_payment['file_name'];
                                }
                            }

                            if ($new_manager) {
                                $this->Report_model->update_manager($id, $new_manager);
                            }
                        } else {
                            $new_manager = [
                                'report_id' => $id,
                                'paid_by' => $this->input->post('manager_paid_by'),
                                'bill' => (float)str_replace('.', '', $this->input->post('manager_bill') ?: 0),
                                'name' => $this->input->post('manager_name'),
                                'date' => $this->input->post('manager_date'),
                                'tax_report' => $this->input->post('manager_tax_report'),
                            ];

                            $manager_payment_file = $_FILES['manager_payment_file'] ?? [];
                            if ($manager_payment_file) {
                                $uploaded_manager_payment = $this->handle_upload_file($manager_payment_file, 'manager_payment');
                                if ($uploaded_manager_payment) {
                                    $new_manager['payment_file'] = $uploaded_manager_payment['file_name'];
                                }
                            }

                            $this->Report_model->create_manager($new_manager);
                        }

                        if ($this->Report_model->delete_details_by_report($id)) {
                            $details = !empty($this->input->post('details')) ? json_decode($this->input->post('details'), true) : [];
                            if (!empty($details)) {
                                foreach ($details as $item) {
                                    $item_data = [
                                        'report_id' => $id,
                                        'level' => $item['level'],
                                        'parent_id' => $item['parent_id'] ?? null,
                                        'no' => $item['no'],
                                        'description' => $item['description'],
                                        'status' => $item['level'] == 2 ? $item['status'] : null,
                                        'condition' => $item['level'] == 2 ?  $item['condition'] : null,
                                        'information' => $item['level'] == 2 ? $item['information'] : null,
                                        'is_show' => !empty($item['is_show']) ? (int)$item['is_show'] : 0,
                                    ];
                                    $this->Report_model->add_detail($item_data);
                                }
                            }
                        }
                    }

                    break;
            }

            if (!$this->Report_model->update($id, $data)) {
                $this->session->set_flashdata('error', 'Failed to update report');
                $this->output->set_status_header(500);
                echo json_encode([
                    "success" => false,
                    "error" => "Failed to update report"
                ]);
                return;
            }

            $this->session->set_flashdata('success', 'Report updated successfully');
            $this->output->set_status_header(200);
            echo json_encode([
                "success" => true,
                "message" => "Report updated successfully"
            ]);
        }
    }

    public function reject($id)
    {
        $report = $this->Report_model->get($id);
        if (!$report) {
            $this->session->set_flashdata('error', 'Report not found');
            if ($this->input->is_ajax_request()) {
                $this->output->set_status_header(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Report not found.',
                ]);
                return;
            } else {
                redirect('report');
            }
        }

        $data = [
            'status' => 'Rejected',
            'rejected_by' => $this->auth_lib->user_id(),
            'rejected_at' => date('Y-m-d H:i:s'),
            'rejected_reason' => $this->input->post('reason')
        ];

        if (!$this->Report_model->update($id, $data)) {
            $this->session->set_flashdata('error', 'Failed to update report');
            $this->output->set_status_header(500);
            echo json_encode([
                "success" => false,
                "error" => "Failed to update report"
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Report rejected successfully');
        $this->output->set_status_header(200);
        echo json_encode([
            "success" => true,
            "message" => "Report rejected successfully"
        ]);
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

        if (!$this->Report_model->delete($id)) {
            $this->session->set_flashdata('success', 'Delete report failed');
            $this->output->set_status_header(500);
            echo json_encode([
                'success' => false,
                'message' => 'Delete report failed.',
            ]);
            return;
        }

        $this->Report_model->delete_rab($id);

        $this->Report_model->delete_manager($id);

        $works = $this->Report_model->get_works_by_report($id);
        foreach ($works as $work) {
            $this->handle_delete_file('./uploads/', $work['image_name']);
        }

        $this->Report_model->delete_works_by_report($id);

        $this->session->set_flashdata('success', 'Report deleted successfully');
        $this->output->set_status_header(200);
        echo json_encode([
            'success' => true,
            'message' => 'Report deleted successfully.',
        ]);
    }

    private function handle_bulk_upload_files($files, $suffix = 'my_file')
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
        $config['max_size'] = '10240'; // 10MB
        $config['file_ext_tolower'] = TRUE;
        $config['mimes'] = array(
            // Images
            'jpg' => array('image/jpeg', 'image/pjpeg'),
            'jpeg' => array('image/jpeg', 'image/pjpeg'),
            'png' => 'image/png',
            'gif' => 'image/gif',

            // Documents
            'pdf' => array('application/pdf', 'application/x-pdf'),
            'doc' => 'application/msword',
            'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'),
            'xls' => array('application/vnd.ms-excel', 'application/excel'),
            'xlsx' => array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip')
        );

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);
        $upload_data = [];

        for ($i = 0; $i < count($files['name']); $i++) {
            $original_name = $files['name'][$i];
            $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);

            $custom_filename = $suffix . '_' . date('YmdHis') . '.' . $file_extension;
            $config['file_name'] = $custom_filename;

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

    private function handle_upload_file($files, $suffix = NULL)
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
        $config['max_size'] = '10240'; // 10MB
        $config['file_ext_tolower'] = TRUE;
        $config['mimes'] = array(
            // Images
            'jpg' => array('image/jpeg', 'image/pjpeg'),
            'jpeg' => array('image/jpeg', 'image/pjpeg'),
            'png' => 'image/png',
            'gif' => 'image/gif',

            // Documents
            'pdf' => array('application/pdf', 'application/x-pdf'),
            'doc' => 'application/msword',
            'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'),
            'xls' => array('application/vnd.ms-excel', 'application/excel'),
            'xlsx' => array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip')
        );

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);
        $upload_data = [];

        $original_filename = pathinfo($files['name'], PATHINFO_FILENAME);
        $file_extension = pathinfo($files['name'], PATHINFO_EXTENSION);

        $custom_filename = ($suffix ? $suffix : $original_filename) . '_' . date('YmdHis') . '.' . $file_extension;
        $config['file_name'] = $custom_filename;

        $_FILES['image']['name'] = $files['name'];
        $_FILES['image']['type'] = $files['type'];
        $_FILES['image']['tmp_name'] = $files['tmp_name'];
        $_FILES['image']['error'] = $files['error'];
        $_FILES['image']['size'] = $files['size'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
        }

        return $upload_data;
    }

    private function handle_delete_file($filepath, $filename)
    {
        if (empty($filename)) return;

        $filename = urldecode($filename);
        $filepath = $filepath . $this->security->sanitize_filename($filename);

        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    public function memo($id)
    {
        $report = $this->Report_model->get_detail($id);
        if (!$report) {
            $this->session->set_flashdata('error', 'The memo cannot be printed because the report not found.');
            redirect('report');
            return;
        }

        if ($report['status'] === 'Pending') {
            $this->session->set_flashdata('error', 'The memo cannot be printed because it has not been processed.');
            redirect('report');
            return;
        }

        if (in_array($report['category_id'], $this->CATEGORY_WITH_DETAIL)) {
            $report["details"] = $this->Report_model->get_details_by_report($id, true);
        }

        $pdf = new Pdf();

        $pdf->SetCreator('Waringin Group');
        $pdf->SetAuthor('Waringin Group');
        $pdf->SetTitle('Memo Pengaduan #' . $report['id']);

        $data['title'] = 'PEMBERITAHUAN PEKERJAAN KURANG/TAMBAH';
        $data['report'] = $report;
        $data['category_with_detail'] = $this->CATEGORY_WITH_DETAIL;

        $pdf->generate_from_view('report/memo', $data, date('Ymd_his') . '_memo.pdf', false);
    }

    public function memo_bulk()
    {
        $ids = $this->input->get('ids');
        if (empty($ids)) {
            $this->session->set_flashdata('error', 'No report IDs provided.');
            redirect('report');
            return;
        }

        // Convert comma-separated string to array
        $ids = explode(',', $ids);
        $ids = array_filter($ids); // Remove empty values
        $ids = array_map('intval', $ids); // Convert to integers

        $reports = $this->Report_model->get_list_detail($ids);
        if (empty($reports)) {
            $this->session->set_flashdata('error', 'No reports found.');
            redirect('report');
            return;
        }

        foreach ($reports as $key => $report) {
            if (in_array($report['category_id'], $this->CATEGORY_WITH_DETAIL)) {
                $reports[$key]["details"] = $this->Report_model->get_details_by_report($report['id'], true);
            }
        }

        $pdf = new Pdf();

        $pdf->SetCreator('Waringin Group');
        $pdf->SetAuthor('Waringin Group');
        $pdf->SetTitle('Memo Pengaduan');

        $data['title'] = 'PEMBERITAHUAN PEKERJAAN KURANG/TAMBAH';
        $data['reports'] = $reports;
        $data['category_with_detail'] = $this->CATEGORY_WITH_DETAIL;

        $pdf->generate_from_view('report/memo_bulk', $data, date('Ymd_his') . '_memo.pdf', false);
    }

    public function evidence($id)
    {
        $report = $this->Report_model->get_detail($id);
        if (!$report) {
            $this->session->set_flashdata('error', 'The evidence report cannot be printed because the report not found.');
            redirect('report');
            return;
        }

        if ($report['status'] !== 'Completed') {
            $this->session->set_flashdata('error', 'The evidence report cannot be printed because it has not been completed yet.');
            redirect('report');
            return;
        }

        $pdf = new Pdf();

        $pdf->SetCreator('Waringin Group');
        $pdf->SetAuthor('Waringin Group');
        $pdf->SetTitle('Bukti Pekerjaan #' . $report['id']);

        $data['title'] = 'BUKTI PEKERJAAN';
        $data['report'] = $report;
        $data['evidence_works'] = $this->Report_model->get_works_pairs_by_report($id);

        $pdf->generate_from_view('report/evidence', $data, date('Ymd_his') . '_work_evidence.pdf', false, false);
    }

    private function export_excel()
    {
        $filename = 'report_export_' . date('Ymd_His');;
        $title = 'Laporan Pengaduan';

        $params["columns"] = $this->input->get("columns");
        $params["search"] = $this->input->get("search");
        $params["draw"] = $this->input->get("draw");
        $params["length"] = $this->input->get("length");
        $params["start"] = $this->input->get("start");
        $params['rab_only'] = false;
        $params["start_date"] = $this->input->get("start_date");
        $params["end_date"] = $this->input->get("end_date");

        if (!empty($params["start_date"])) {
            $params["start_date"] = date('Y-m-d', strtotime($params["start_date"]));
        }

        if (!empty($params["end_date"])) {
            $params["end_date"] = date('Y-m-d', strtotime($params["end_date"]));
        }

        if ($this->auth_lib->role() === 'pelapor') {
            $params["reported_by"] = $this->auth_lib->user_id();
        } else if ($this->auth_lib->role() === 'rab') {
            $params['rab_only'] = true;
        }

        $data = $this->Report_model->get_list_export($params);
        if (empty($data)) {
            $this->session->set_flashdata('error', 'No data available for export.');
            redirect('report');
        }

        if (empty($data) || !is_array($data)) {
            return;
        }

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Determine visible RAB columns based on user role
        $role = $this->auth_lib->role();
        $show_rab = in_array($role, ['manager', 'rab', 'kontraktor']);
        $show_rab_final = in_array($role, ['manager', 'rab']);
        $show_rab_customer = ($role === 'manager');

        // Set title if provided
        if (!empty($title)) {
            $columnCount = 11;
            if ($show_rab) $columnCount++;
            if ($show_rab_final) $columnCount++;
            if ($show_rab_customer) $columnCount++;

            $sheet->setCellValue('A1', $title);
            $sheet->mergeCells('A1:' . getColumnLetter($columnCount) . '1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $startRow = 3;
        } else {
            $startRow = 1;
        }

        // Add headers
        if (!empty($data)) {
            $column = 'A';

            $headers = [
                'No. Pengaduan',
                'Entity',
                'Project',
                'Tgl. Pengaduan',
                'No Gudang',
                'Nama Perusahaan',
                'Kategori Pengaduan',
                'Uraian',
                'Sub Uraian'
            ];

            if ($show_rab) {
                $headers[] = 'RAB Kontraktor';
            }
            if ($show_rab_final) {
                $headers[] = 'RAB Final';
            }
            if ($show_rab_customer) {
                $headers[] = 'RAB Customer';
            }

            $headers = array_merge($headers, [
                'Status Pengajuan',
                'Pelapor'
            ]);

            $columnWidths = [
                'A' => 20, // No. Pengaduan
                'B' => 35, // Entity
                'C' => 20, // Project
                'D' => 20, // Tgl. Pengaduan
                'E' => 18, // No Gudang
                'F' => 35, // Nama Perusahaan
                'G' => 30, // Kategori Pengaduan
                'H' => 30, // Uraian
                'I' => 30, // Sub Uraian
            ];

            $rabColumn = 'J';
            $rabFinalColumn = 'J';
            $rabCustomerColumn = 'J';
            $statusColumn = 'J';
            $pelapor_column = 'K';

            if ($show_rab) {
                $columnWidths['J'] = 18;
                $rabColumn = 'J';
                $statusColumn = chr(ord($rabColumn) + 1);
                $pelapor_column = chr(ord($statusColumn) + 1);
            }

            if ($show_rab_final) {
                $rabFinalColumn = chr(ord($rabColumn) + 1);
                $statusColumn = chr(ord($rabFinalColumn) + 1);
                $pelapor_column = chr(ord($statusColumn) + 1);
                $columnWidths[$rabFinalColumn] = 18;
            }

            if ($show_rab_customer) {
                $rabCustomerColumn = chr(ord($rabFinalColumn) + 1);
                $statusColumn = chr(ord($rabCustomerColumn) + 1);
                $pelapor_column = chr(ord($statusColumn) + 1);
                $columnWidths[$rabCustomerColumn] = 18;
            }

            $columnWidths[$statusColumn] = 30;
            $columnWidths[$pelapor_column] = 20;

            foreach ($headers as $header) {
                $sheet->setCellValue($column . $startRow, ucwords(str_replace('_', ' ', $header)));
                $sheet->getStyle($column . $startRow)->getFont()->setBold(true);
                $sheet->getStyle($column . $startRow)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFE0E0E0');
                $sheet->getStyle($column . $startRow)->getBorders()
                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle($column . $startRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Set custom column width
                if (isset($columnWidths[$column])) {
                    $sheet->getColumnDimension($column)->setWidth($columnWidths[$column]);
                }

                $column++;
            }

            // Add data rows
            $row = $startRow + 1;

            foreach ($data as $item) {
                $rowStart = $row;

                $details = $this->Report_model->get_parent_detail_by_report($item['id']);
                if (!empty($details)) {
                    foreach ($details as $detail_item) {
                        $detail_row_start = $row;
                        $sub_details = $this->Report_model->get_sub_detail_by_parent($detail_item['id']);
                        if (!empty($sub_details)) {
                            foreach ($sub_details as $sub_detail_item) {
                                $column = 'A';

                                $sheet->setCellValue($column . $row, $item['no']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $column++;
                                $sheet->setCellValue($column . $row, $item['entity']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $column++;
                                $sheet->setCellValue($column . $row, $item['project']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $column++;
                                $sheet->setCellValue($column . $row, $item['created_at']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $column++;
                                $sheet->setCellValue($column . $row, $item['warehouse']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $column++;
                                $sheet->setCellValue($column . $row, $item['company']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $column++;
                                $sheet->setCellValue($column . $row, $item['category']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $column++;
                                $sheet->setCellValue($column . $row, $detail_item['description']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $column++;
                                $sheet->setCellValue($column . $row, $sub_detail_item['description']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $column++;

                                if ($show_rab) {
                                    $sheet->setCellValue($column . $row, number_format($item['rab'], 0, ',', '.'));
                                    $sheet->getStyle($column . $row)->getBorders()
                                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                    $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                                    $column++;
                                }
                                if ($show_rab_final) {
                                    $sheet->setCellValue($column . $row, number_format($item['rab_final'], 0, ',', '.'));
                                    $sheet->getStyle($column . $row)->getBorders()
                                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                    $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                                    $column++;
                                }
                                if ($show_rab_customer) {
                                    $sheet->setCellValue($column . $row, number_format($item['rab_customer'], 0, ',', '.'));
                                    $sheet->getStyle($column . $row)->getBorders()
                                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                    $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                                    $column++;
                                }

                                $sheet->setCellValue($column . $row, $item['status']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                $column++;
                                $sheet->setCellValue($column . $row, $item['created_by']);
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                $column++;

                                $row++;
                            }

                            $detail_row_end = $row - 1;
                            if ($detail_row_end > $detail_row_start) {
                                $sheet->mergeCells('H' . $detail_row_start . ':H' . $detail_row_end);
                                $sheet->getStyle('H' . $detail_row_start . ':H' . $detail_row_end)->getAlignment()
                                    ->setVertical(Alignment::VERTICAL_CENTER)
                                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            }
                        } else {
                            $column = 'A';

                            $sheet->setCellValue($column . $row, $item['no']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $column++;
                            $sheet->setCellValue($column . $row, $item['entity']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $column++;
                            $sheet->setCellValue($column . $row, $item['project']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $column++;
                            $sheet->setCellValue($column . $row, $item['created_at']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $column++;
                            $sheet->setCellValue($column . $row, $item['warehouse']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $column++;
                            $sheet->setCellValue($column . $row, $item['company']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $column++;
                            $sheet->setCellValue($column . $row, $item['category']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $column++;
                            $sheet->setCellValue($column . $row, $detail_item['description']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $column++;
                            $sheet->setCellValue($column . $row, '');
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $column++;

                            if ($show_rab) {
                                $sheet->setCellValue($column . $row, number_format($item['rab'], 0, ',', '.'));
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                                $column++;
                            }
                            if ($show_rab_final) {
                                $sheet->setCellValue($column . $row, number_format($item['rab_final'], 0, ',', '.'));
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                                $column++;
                            }
                            if ($show_rab_customer) {
                                $sheet->setCellValue($column . $row, number_format($item['rab_customer'], 0, ',', '.'));
                                $sheet->getStyle($column . $row)->getBorders()
                                    ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                                $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                                $column++;
                            }

                            $sheet->setCellValue($column . $row, $item['status']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $column++;
                            $sheet->setCellValue($column . $row, $item['created_by']);
                            $sheet->getStyle($column . $row)->getBorders()
                                ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                            $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $column++;

                            $row++;
                        }
                    }
                } else {
                    $column = 'A';

                    $sheet->setCellValue($column . $row, $item['no']);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;
                    $sheet->setCellValue($column . $row, $item['entity']);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;
                    $sheet->setCellValue($column . $row, $item['project']);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;
                    $sheet->setCellValue($column . $row, $item['created_at']);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;
                    $sheet->setCellValue($column . $row, $item['warehouse']);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;
                    $sheet->setCellValue($column . $row, $item['company']);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;
                    $sheet->setCellValue($column . $row, $item['category']);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;
                    $sheet->setCellValue($column . $row, '');
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;
                    $sheet->setCellValue($column . $row, '');
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $column++;

                    if ($show_rab) {
                        $sheet->setCellValue($column . $row, number_format($item['rab'], 0, ',', '.'));
                        $sheet->getStyle($column . $row)->getBorders()
                            ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                        $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $column++;
                    }
                    if ($show_rab_final) {
                        $sheet->setCellValue($column . $row, number_format($item['rab_final'], 0, ',', '.'));
                        $sheet->getStyle($column . $row)->getBorders()
                            ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                        $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $column++;
                    }
                    if ($show_rab_customer) {
                        $sheet->setCellValue($column . $row, number_format($item['rab_customer'], 0, ',', '.'));
                        $sheet->getStyle($column . $row)->getBorders()
                            ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                        $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $column++;
                    }

                    $sheet->setCellValue($column . $row, $item['status']);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $column++;
                    $sheet->setCellValue($column . $row, $item['created_by']);
                    $sheet->getStyle($column . $row)->getBorders()
                        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle($column . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $column++;

                    $row++;
                }

                $rowEnd = $row - 1;
                if ($rowEnd > $rowStart) {
                    $sheet->mergeCells('A' . $rowStart . ':A' . $rowEnd);
                    $sheet->getStyle('A' . $rowStart . ':A' . $rowEnd)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_TOP)
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    $sheet->mergeCells('B' . $rowStart . ':B' . $rowEnd);
                    $sheet->getStyle('B' . $rowStart . ':B' . $rowEnd)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_TOP)
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    $sheet->mergeCells('C' . $rowStart . ':C' . $rowEnd);
                    $sheet->getStyle('C' . $rowStart . ':C' . $rowEnd)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_TOP)
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    $sheet->mergeCells('D' . $rowStart . ':D' . $rowEnd);
                    $sheet->getStyle('D' . $rowStart . ':D' . $rowEnd)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_TOP)
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    $sheet->mergeCells('E' . $rowStart . ':E' . $rowEnd);
                    $sheet->getStyle('E' . $rowStart . ':E' . $rowEnd)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_TOP)
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    $sheet->mergeCells('F' . $rowStart . ':F' . $rowEnd);
                    $sheet->getStyle('F' . $rowStart . ':F' . $rowEnd)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_TOP)
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    $sheet->mergeCells('G' . $rowStart . ':G' . $rowEnd);
                    $sheet->getStyle('G' . $rowStart . ':G' . $rowEnd)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_TOP)
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                    // Merge RAB columns based on visibility
                    $colIndex = 10; // Starting from column J (10th column)
                    if ($show_rab) {
                        $col = getColumnLetter($colIndex);
                        $sheet->mergeCells($col . $rowStart . ':' . $col . $rowEnd);
                        $sheet->getStyle($col . $rowStart . ':' . $col . $rowEnd)->getAlignment()
                            ->setVertical(Alignment::VERTICAL_TOP)
                            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $colIndex++;
                    }
                    if ($show_rab_final) {
                        $col = getColumnLetter($colIndex);
                        $sheet->mergeCells($col . $rowStart . ':' . $col . $rowEnd);
                        $sheet->getStyle($col . $rowStart . ':' . $col . $rowEnd)->getAlignment()
                            ->setVertical(Alignment::VERTICAL_TOP)
                            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $colIndex++;
                    }
                    if ($show_rab_customer) {
                        $col = getColumnLetter($colIndex);
                        $sheet->mergeCells($col . $rowStart . ':' . $col . $rowEnd);
                        $sheet->getStyle($col . $rowStart . ':' . $col . $rowEnd)->getAlignment()
                            ->setVertical(Alignment::VERTICAL_TOP)
                            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $colIndex++;
                    }

                    // Merge Status and Created By
                    $col = getColumnLetter($colIndex);
                    $sheet->mergeCells($col . $rowStart . ':' . $col . $rowEnd);
                    $sheet->getStyle($col . $rowStart . ':' . $col . $rowEnd)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_TOP)
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $col = getColumnLetter($colIndex + 1);
                    $sheet->mergeCells($col . $rowStart . ':' . $col . $rowEnd);
                    $sheet->getStyle($col . $rowStart . ':' . $col . $rowEnd)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_TOP)
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            }

            // Auto size columns
            // $lastColumn = $sheet->getHighestColumn();
            // for ($col = 'A'; $col <= $lastColumn; $col++) {
            //     $sheet->getColumnDimension($col)->setAutoSize(true);
            // }
        }

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Create writer and output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
