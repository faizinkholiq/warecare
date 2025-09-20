<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Report extends MY_Controller
{
    private $CATEGORY_WITH_DETAIL = [2, 3];
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
                if (preg_match_all('/\d+/', $entity["name"], $matches)) {
                    $numbers = $matches[0];
                    if (count($numbers) > 0) {
                        $data["no"] = end($numbers) . '-' . date('Ym') . '-' . str_pad($this->Report_model->get_next_id(), 4, '0', STR_PAD_LEFT);
                    }
                }
            }

            $evidence_files = $_FILES['evidence_files'] ?? [];
            $file_count = !empty($evidence_files['name'][0]) ? count($evidence_files['name']) : 0;

            if ($file_count < 1) {
                $this->form_validation->set_rules('evidence_files', 'Evidence Files', 'required', [
                    'required' => 'At least one evidence file is required.'
                ]);
            } elseif ($file_count > $this->MAX_FILE_COUNT) {
                $this->form_validation->set_rules('evidence_files', 'Evidence Files', 'max_evidence_files', [
                    'max_evidence_files' => "Maximum of {$this->MAX_FILE_COUNT} evidence files allowed."
                ]);
            }

            $this->form_validation->set_message('max_evidence_files', "Maximum of {$this->MAX_FILE_COUNT} evidence files allowed.");

            if ($file_count < 1) {
                $this->output->set_status_header(422);
                echo json_encode([
                    'success' => false,
                    'error' => 'Please upload at least one evidence image.'
                ]);
                return;
            }

            if ($file_count > $this->MAX_FILE_COUNT) {
                $this->output->set_status_header(422);
                echo json_encode([
                    'success' => false,
                    'error' => "Maximum of {$this->MAX_FILE_COUNT} evidence files allowed."
                ]);
                return;
            }

            $uploaded_evidences = $this->handle_bulk_upload_files($evidence_files, 'evidence');

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

            $details = json_decode($this->input->post('details'), true) ?? [];
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
                    ];
                    $this->Report_model->add_detail($item_data);
                }
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
        $data["report"]["evidences"] = $this->Report_model->get_evidences_by_report($id);;
        $data["report"]["works"] = $this->Report_model->get_works_by_report($id);
        $data["report"]["details"] = $this->Report_model->get_details_by_report($id);
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

        $existing_evidences = $this->Report_model->get_evidences_by_report($id);
        $existing_count = count($existing_evidences);

        $existing_works = $this->Report_model->get_works_by_report($id);
        $existing_count = count($existing_works);

        $this->form_validation->set_rules('entity_id', 'Entity', 'required');
        $this->form_validation->set_rules('project_id', 'Project', 'required');
        $this->form_validation->set_rules('company_id', 'Company', 'required');
        $this->form_validation->set_rules('warehouse_id', 'Warehouse', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');

        if (!$this->input->is_ajax_request() && $this->form_validation->run() === FALSE) {
            $data["report"] = $report;
            $data["report"]["rab"] = $this->Report_model->get_rab($id);
            $data["report"]["manager"] = $this->Report_model->get_manager($id);

            if (in_array($report['category_id'], $this->CATEGORY_WITH_DETAIL)) {
                $data["report"]["details"] = $this->Report_model->get_details_by_report($id);
            }

            $data["report"]["evidences"] = $existing_evidences;
            $data["report"]["works"] = $existing_works;

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
                'is_rab' => (bool)$this->input->post('is_rab'),
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

            $evidence_files = $_FILES['evidence_files'] ?? [];
            $evidence_count = !empty($evidence_files['name'][0]) ? count($evidence_files['name']) : 0;
            $deleted_evidences = json_decode($this->input->post('deleted_evidence_files'), true) ?? [];
            $total_evidences = $existing_count + $evidence_count - count($deleted_evidences);

            if ($total_evidences < 1) {
                $this->form_validation->set_rules('evidence_files', 'Evidence Files', 'required', [
                    'required' => 'At least one evidence file is required.'
                ]);
            } elseif ($total_evidences > $this->MAX_FILE_COUNT) {
                $this->form_validation->set_rules('evidence_files', 'Evidence Files', 'max_evidence_files', [
                    'max_evidence_files' => "Maximum of {$this->MAX_FILE_COUNT} evidence files allowed."
                ]);
            }

            $uploaded_evidences = [];
            if (!empty($evidence_files['name'][0])) {
                if ($total_evidences > $this->MAX_FILE_COUNT) {
                    $this->session->set_flashdata('error', "Maximum of {$this->MAX_FILE_COUNT} evidence files allowed.");
                    $this->output->set_status_header(400);
                    echo json_encode([
                        "success" => false,
                        "error" => "Maximum of {$this->MAX_FILE_COUNT} evidence files allowed."
                    ]);
                    return;
                }

                $uploaded_evidences = $this->handle_bulk_upload_files($evidence_files, 'evidence');
            }

            $work_files = $_FILES['work_files'] ?? [];
            $work_count = !empty($work_files['name'][0]) ? count($work_files['name']) : 0;
            $deleted_works = json_decode($this->input->post('deleted_work_files'), true) ?? [];
            $total_works = $existing_count + $work_count - count($deleted_works);

            if ($total_works < 1) {
                $this->form_validation->set_rules('work_files', 'Work Files', 'required', [
                    'required' => 'At least one work file is required.'
                ]);
            } elseif ($total_works > $this->MAX_FILE_COUNT) {
                $this->form_validation->set_rules('work_files', 'Work Files', 'max_work_files', [
                    'max_work_files' => "Maximum of $this->MAX_FILE_COUNT work files allowed."
                ]);
            }

            $uploaded_works = [];
            if (!empty($work_files['name'][0])) {
                if ($total_works > $this->MAX_FILE_COUNT) {
                    $this->session->set_flashdata('error', "Maximum of {$this->MAX_FILE_COUNT} work files allowed.");
                    $this->output->set_status_header(400);
                    echo json_encode([
                        "success" => false,
                        "error" => "Maximum of {$this->MAX_FILE_COUNT} work files allowed."
                    ]);
                    return;
                }

                $uploaded_works = $this->handle_bulk_upload_files($work_files, 'work');
            }

            if ($this->Report_model->delete_details_by_report($id)) {
                if (in_array($data['category_id'], $this->CATEGORY_WITH_DETAIL)) {
                    $details = json_decode($this->input->post('details'), true) ?? [];
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

            if (!$this->Report_model->update($id, $data)) {
                $this->session->set_flashdata('error', 'Failed to update report');
                $this->output->set_status_header(500);
                echo json_encode([
                    "success" => false,
                    "error" => "Failed to update report"
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
                        $this->handle_delete_file('./uploads/', $file['image_name']);
                        $this->Report_model->delete_evidence($file_id);
                    }
                }
            }

            if (!empty($uploaded_works)) {
                foreach ($uploaded_works as $file) {
                    $this->Report_model->add_work($id, $file['file_path'], $file['file_name']);
                }
            }

            if (!empty($deleted_works)) {
                foreach ($deleted_works as $file_id) {
                    $file = $this->Report_model->get_work($file_id);
                    if ($file) {
                        $this->handle_delete_file('./uploads/', $file['image_name']);
                        $this->Report_model->delete_work($file_id);
                    }
                }
            }

            $rab = $this->Report_model->get_rab($id);
            $delete_rab_file = $this->input->post('delete_rab_file') === 'true';
            $delete_rab_final_file = $this->input->post('delete_rab_final_file') === 'true';

            if ($rab) {
                if ($delete_rab_file && !empty($rab['file'])) {
                    $this->handle_delete_file('./uploads/', $rab['file']);
                }


                if ($delete_rab_final_file && !empty($rab['final_file'])) {
                    $this->handle_delete_file('./uploads/', $rab['final_file']);
                }
            }

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
                    $uploaded_rab = $this->handle_upload_file($rab_file, 'rab');
                    if ($uploaded_rab) {
                        $new_rab['file'] = $uploaded_rab['file_name'];
                    }
                }

                $rab_final_file = $_FILES['rab_final_file'] ?? [];
                if ($rab_final_file) {
                    $uploaded_rab = $this->handle_upload_file($rab_final_file, 'rab_final');
                    if ($uploaded_rab) {
                        $new_rab['final_file'] = $uploaded_rab['file_name'];
                    }
                }

                if ($rab) {
                    if (!empty($new_rab['file'])) {
                        $this->handle_delete_file('./uploads/', $rab['file']);
                    }

                    if (!empty($new_rab['final_file'])) {
                        $this->handle_delete_file('./uploads/', $rab['final_file']);
                    }

                    $this->Report_model->update_rab($id, $new_rab);
                } else {
                    $this->Report_model->create_rab($new_rab);
                }
            } else {
                if ($rab) {
                    $this->Report_model->delete_rab($id);
                }
            }

            if ($data['status'] === 'Approved') {
                $new_manager = [
                    'report_id' => $id,
                    'rab_budget' => (float)str_replace('.', '', $this->input->post('manager_rab_budget') ?: 0),
                    'paid_by' => $this->input->post('manager_paid_by'),
                    'bill' => (float)str_replace('.', '', $this->input->post('manager_bill') ?: 0),
                    'name' => $this->input->post('manager_name'),
                    'date' => $this->input->post('manager_date'),
                    'tax_report' => $this->input->post('manager_tax_report'),
                ];

                $delete_manager_payment_file = $this->input->post('delete_manager_payment_file') === 'true';

                $manager_payment_file = $_FILES['manager_payment_file'] ?? [];
                if ($manager_payment_file) {
                    $uploaded_manager_payment = $this->handle_upload_file($manager_payment_file, 'manager_payment');
                    if ($uploaded_manager_payment) {
                        $new_manager['payment_file'] = $uploaded_manager_payment['file_name'];
                    }
                }

                $manager = $this->Report_model->get_manager($id);
                if ($manager) {
                    if ($delete_manager_payment_file && !empty($manager['payment_file'])) {
                        $this->handle_delete_file('./uploads/', $manager['payment_file']);
                    }

                    $this->Report_model->update_manager($id, $new_manager);
                } else {
                    $this->Report_model->create_manager($new_manager);
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

        $evidences = $this->Report_model->get_evidences_by_report($id);
        foreach ($evidences as $evidence) {
            $this->handle_delete_file('./uploads/', $evidence['image_name']);
        }

        $this->Report_model->delete_evidences_by_report($id);

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

    private function handle_upload_file($files, $suffix = 'my_file')
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

        $original_name = $files['name'];
        $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);

        $custom_filename = $suffix . '_' . date('YmdHis') . '.' . $file_extension;
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
            $report["details"] = $this->Report_model->get_details_by_report($id);
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

    private function export_excel()
    {
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

        $reports = $this->Report_model->get_list_export($params);

        export_excel($reports, 'report_export_' . date('Y-m-d_H-i-s'), 'Pengaduan Export');
    }
}
