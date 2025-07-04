<?php

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'login/login_model',
            'invoice_model',
        ]);
    }

    public function index()
    {
        $d = $this->login_model->login_check();
        $mode = !empty($this->input->get('mode')) ? $this->input->get('mode') : 'normal';
        $type = !empty($this->input->get('type')) ? $this->input->get('type') : 'invoice';
        $d["title"] = "Invoice";

        if ($mode == 'normal') {
            $d["menu_id"] = "menu_invoice";
            $d['js_files'] = array(
                base_url('assets/modules/invoice/invoice.js?2024')
            );
            $d['list']['status'] = $this->invoice_model->get_status_list();
            $d['list']['category'] = $this->invoice_model->get_category_list();
            $d['view'] = 'invoice/invoice';
            $this->load->view('template/template', $d);
        } else {
            if ($mode == 'print') {
                switch ($type) {
                    case 'invoice':
                        $this->_print_invoice($d);
                        break;
                    case 'receipt':
                        $this->_print_receipt($d);
                        break;
                }
            } else if ($mode == "export") {
                $params["search"] = $this->input->get("search");
                $params["draw"] = $this->input->get("draw");
                $params["length"] = $this->input->get("length");
                $params["start"] = $this->input->get("start");
                $params["status"] = $this->input->get("status");

                $d['data'] = $this->invoice_model->get_datatables($params);
                $d['filename'] = str_replace(' ', '_', $d['title']) . '_' . date('YmdHis');

                $this->_export($d);
            }
        }
    }

    public function datatables()
    {
        $d = $this->login_model->login_check();
        $params["search"] = $this->input->post("search");
        $params["draw"] = $this->input->post("draw");
        $params["length"] = $this->input->post("length");
        $params["start"] = $this->input->post("start");
        $params["status"] = $this->input->post("status");

        $data = $this->invoice_model->get_datatables($params);

        ob_end_clean();
        echo json_encode($data);
    }

    private function get_input()
    {
        $data["date"] = $this->input->post('date');
        $data["currency"] = $this->input->post('currency');
        $data["code"] = $this->input->post('code');
        $data["category"] = $this->input->post('category');
        $data["invoice_to"] = $this->input->post('invoice_to');
        $data["address"] = $this->input->post('address');
        $data["phone"] = $this->input->post('phone');
        $data["email"] = $this->input->post('email');
        $data["description"] = $this->input->post('description');
        $data["qty"] = $this->input->post('qty');
        $data["price"] = parseToFloat($this->input->post('price'));
        $data["tax"] = $this->input->post('tax');
        $data["other"] = parseToFloat($this->input->post('other'));
        $data["deduction"] = parseToFloat($this->input->post('deduction'));
        $data["paid_by"] = $this->input->post('paid_by');
        $data["status"] = $this->input->post('status');
        $data["received"] = $this->input->post('received');

        return $data;
    }

    public function create()
    {
        $d = $this->login_model->login_check();
        $nd = $this->get_input();
        if (!$nd) {
            $data['success'] = 0;
            $data['error'] = "Invalid Person !";
        } else {
            $pinjaman_id = $this->invoice_model->create($nd);
            if ($pinjaman_id) {
                $data['success'] = 1;
                $data['message'] = "Invoice created !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Failed create invoice !";
            }
        }

        echo json_encode($data);
    }

    public function edit($id)
    {
        $d = $this->login_model->login_check();
        $nd = $this->get_input();
        $detail = $this->invoice_model->detail($id);
        if ($detail) {
            $nd['id'] = $detail['id'];
            if ($this->invoice_model->edit($nd)) {
                $data['success'] = 1;
                $data['message'] = "Invoice updated !";
            } else {
                $data['success'] = 0;
                $data['error'] = "Failed update invoice !";
            }
        } else {
            $data['success'] = 0;
            $data['error'] = "Invalid ID !";
        }

        echo json_encode($data);
    }

    public function delete($id)
    {
        $d = $this->login_model->login_check();
        if ($this->invoice_model->delete($id)) {
            $data['success'] = 1;
            $data['message'] = "Invoice deleted !";
        } else {
            $data['success'] = 0;
            $data['error'] = "Failed delete invoice !";
        }

        echo json_encode($data);
    }

    public function detail($id)
    {
        $d = $this->login_model->login_check();
        $data = $this->invoice_model->detail($id);
        echo json_encode($data);
    }

    private function _print_invoice($d)
    {
        $d['userdata'] = $this->login_model->login_check();
        $d['filename'] = str_replace(' ', '_', $d['title']) . '_' . date('YmdHis') . '.pdf';
        $d['data'] = $this->invoice_model->get_data();
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];


        $mpdf = new Mpdf([
            'tempDir' => '/tmp',
            'fontDir' => array_merge($fontDirs, [
                FCPATH . '/assets/fonts/calibri',
            ]),
            'fontdata' => $fontData + [
                'proxima_nova' => [
                    'R' => 'calibri-regular.ttf',
                    'I' => 'calibri-italic.ttf',
                    'B' => 'calibri-bold.ttf',
                    'BI' => 'calibri-bold-italic.ttf'
                ]
            ],
            'default_font' => 'calibri',
            'default_font_size' => 14,
            'format' => [297, 275]
        ]);
        $html = $this->load->view('invoice/invoice_print', $d, true);

        $mpdf->showImageErrors = true;
        $mpdf->WriteHtml($html);
        $mpdf->Output($d['filename'], 'I');
    }

    private function _print_receipt($d)
    {
        $d['userdata'] = $this->login_model->login_check();
        $d['filename'] = str_replace(' ', '_', $d['title']) . '_Receipt_' . date('YmdHis') . '.pdf';
        $d['data'] = $this->invoice_model->get_data();

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];


        $mpdf = new Mpdf([
            'tempDir' => '/tmp',
            'fontDir' => array_merge($fontDirs, [
                FCPATH . '/assets/fonts/calibri',
            ]),
            'fontdata' => $fontData + [
                'proxima_nova' => [
                    'R' => 'calibri-regular.ttf',
                    'I' => 'calibri-italic.ttf',
                    'B' => 'calibri-bold.ttf',
                    'BI' => 'calibri-bold-italic.ttf'
                ]
            ],
            'default_font' => 'calibri',
            'default_font_size' => 14,
            'format' => [297, 242]
        ]);
        $html = $this->load->view('invoice/receipt_print', $d, true);

        $mpdf->showImageErrors = true;
        $mpdf->WriteHtml($html);
        $mpdf->Output($d['filename'], 'I');
    }

    private function _export($d)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName("Calibri")->setSize(10);
        $styleFillHeader = [
            "borders" => [
                "allBorders" => [
                    "borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    "color" => ["rgb" => "000000"]
                ]
            ],
            "fill" => [
                "fillType" => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                "color" => ["rgb" => "C1C1C1"]
            ]
        ];
        $styleBorderAll = [
            "borders" => [
                "allBorders" => [
                    "borderStyle" => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    "color" => ["rgb" => "000000"]
                ]
            ],
        ];

        $spreadsheet->getActiveSheet()->setCellValue('B2', 'PT INTERNATIONAL AKATSUKI BUSINESS');
        $spreadsheet->getActiveSheet()->getStyle('B2')->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->setCellValue('B3', 'Business Park Kebon Jeruk, Blok H 1-2');
        $spreadsheet->getActiveSheet()->setCellValue('B4', 'Jalan Raya Meruya Ilir Nomor 88');
        $spreadsheet->getActiveSheet()->setCellValue('B5', 'Desa/Kelurahan Meruya Utara');
        $spreadsheet->getActiveSheet()->setCellValue('B6', 'Kec. Kembangan, Kota Adm. Jakarta Barat');
        $spreadsheet->getActiveSheet()->setCellValue('B7', 'Provinsi DKI Jakarta, Kode Pos 11620');


        $spreadsheet->getActiveSheet()->setCellValue("B9", strtoupper($d['title'])); //set title
        $spreadsheet->getActiveSheet()->getStyle("B9")->getFont()->setBold(TRUE);

        $rowNo = 11; //start data

        // list letter alphabet
        $letters = get_alphabet_list();

        $letterCounter = 1; // $letter[1] = B
        $firstRow = $rowNo;

        $spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(3);
        $spreadsheet->getActiveSheet()->getRowDimension($rowNo)->setRowHeight(30);

        ///////////////// HEADER ///////////////////////////////////////////////////////////////////////////
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "NO");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(5);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "#ID");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(5);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "DATE");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(11);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "CURRENCY");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(11);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "CODE");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(11);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "CATEGORY");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "INVOICE TO");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "ADDRESS");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "PHONE");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "EMAIL");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "DESCRIPTION");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "QUANTITY");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "PRICE");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "SUB TOTAL");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "TAX");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "OTHER");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "DEDUCTION");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "GRAND TOTAL");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "PAID BY");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "STATUS");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);
        $letterCounter++;
        $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", "RECEIVED");
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getFont()->setBold(TRUE);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getColumnDimension($letters[$letterCounter])->setWidth(16);
        $spreadsheet->getActiveSheet()->mergeCells("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo));
        $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}:{$letters[$letterCounter]}" . ($rowNo))->applyFromArray($styleFillHeader);

        $lastLetterCount = $letterCounter;

        $rowNo++;

        $i = 1;

        if (!empty($d["data"]["data"])) {
            foreach ($d["data"]["data"] as $key => $value) {
                $letterCounter = 1;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $i);
                $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $spreadsheet->getActiveSheet()->getStyle("{$letters[$letterCounter]}{$rowNo}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['id']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['date']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['currency']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['code']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['category']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['invoice_to']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['address']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['phone']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['email']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['description']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['qty']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['price'] ? number_format($value['price']) : NULL);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['sub_total'] ? number_format($value['sub_total']) : NULL);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['tax'] ? intval($value['tax']) : NULL);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['other'] ? number_format($value['other']) : NULL);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['deduction'] ? number_format($value['deduction']) : NULL);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['total'] ? number_format($value['total']) : NULL);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['paid_by']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['status']);
                $letterCounter++;
                $spreadsheet->getActiveSheet()->setCellValue("{$letters[$letterCounter]}{$rowNo}", $value['received']);
                $letterCounter++;

                $i++;
                $rowNo++;
            }
        }

        $rowNo--;

        $spreadsheet->getActiveSheet()->getStyle("{$letters[1]}{$firstRow}:{$letters[$lastLetterCount]}{$rowNo}")->applyFromArray($styleBorderAll);

        //Redirect output to a client web browser (Xlsx)
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $d['filename'] . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        ob_end_clean(); // it's fix everything
        $writer->save('php://output'); die();
    }
}
