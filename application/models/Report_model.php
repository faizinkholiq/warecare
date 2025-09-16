<?php defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{
    private $table = 'report';

    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function get_list_datatables($p)
    {
        $columns = $p['columns'];
        $search = $p["search"];
        $order = !empty($p["order"]) ? $p["order"][0] : null;

        $limit = $p["length"];
        $offset = $p["start"];

        if (!empty($p["reported_by"])) {
            $this->db->where('report.created_by', $p['reported_by']);
        }

        if (!empty($p["rab_only"])) {
            $this->db->where('report.is_rab', true);
        }

        if (!empty($p["start_date"])) {
            $this->db->where('DATE(report.created_at) >=', $p["start_date"]);
        }

        if (!empty($p["end_date"])) {
            $this->db->where('DATE(report.created_at) <=', $p["end_date"]);
        }

        if (!empty($columns)) {
            foreach ($columns as $column) {
                if (!empty($column['search']['value'])) {
                    switch ($column['name']) {
                        case 'status':
                            $this->db->where('report.status', $column['search']['value']);
                            break;
                        case 'category':
                            $this->db->where('category_id', $column['search']['value']);
                            break;
                        default:
                            $this->db->like($column['name'], $column['search']['value']);
                    }
                }
            }
        }

        if (!empty($search["value"])) {
            $searchable_fields = [
                "report.no",
                "report.title",
                "entity.name",
                "project.name",
                "warehouse.name",
                "company.name",
                "category.name",
                "report.status",
                "report.created_at"
            ];

            $search_terms = explode(" ", $search["value"]);

            $this->db->group_start();
            foreach ($searchable_fields as $field) {
                $this->db->or_group_start();
                foreach ($search_terms as $term) {
                    $this->db->like($field, $term);
                }
                $this->db->group_end();
            }
            $this->db->group_end();
        }

        // Base query
        $this->db->select([
            'report.id',
            'report.no',
            'report.title',
            'report.is_rab',
            'entity.name as entity',
            'project.name as project',
            'warehouse.name as warehouse',
            'company.name as company',
            'category.name as category',
            'report.status',
            'report_rab.file rab_file',
            'report_rab.final_file rab_final_file',
            'CONCAT_WS(" ", created_by.first_name, created_by.last_name) created_by',
            'CONCAT_WS(" ", processed_by.first_name, processed_by.last_name) processed_by',
            'CONCAT_WS(" ", approved_by.first_name, approved_by.last_name) approved_by',
            'CONCAT_WS(" ", completed_by.first_name, completed_by.last_name) completed_by',
            'report.created_at'
        ])
            ->from('report')
            ->join('report_rab', 'report_rab.report_id = report.id', 'left')
            ->join('entity', 'entity.id = report.entity_id', 'left')
            ->join('project', 'project.id = report.project_id', 'left')
            ->join('warehouse', 'warehouse.id = report.warehouse_id', 'left')
            ->join('category', 'category.id = report.category_id', 'left')
            ->join('company', 'company.id = report.company_id', 'left')
            ->join('user created_by', 'created_by.id = report.created_by', 'left')
            ->join('user processed_by', 'processed_by.id = report.processed_by', 'left')
            ->join('user approved_by', 'approved_by.id = report.approved_by', 'left')
            ->join('user completed_by', 'completed_by.id = report.completed_by', 'left');

        // Handle ordering
        if (!empty($order)) {
            $this->db->order_by($columns[$order['column']]['data'], $order['dir']);
        } else {
            $this->db->order_by('report.id', 'desc');
        }

        $this->db->group_by('report.id');

        // Clone the current query before getting the count
        $total_records = $this->db->count_all_results('', false);

        // Get the records with limit and offset
        $this->db->limit($limit, $offset);
        $result = $this->db->get();

        $filtered_records = $total_records;
        $data = $result->result_array();

        return [
            "draw" => intval($p["draw"]),
            "recordsTotal" => $total_records,
            "recordsFiltered" => $filtered_records,
            "data" => $data
        ];
    }

    public function get($id)
    {
        $this->db->select([
            $this->table . '.*',
            'entity.name as entity',
            'project.name as project',
            'warehouse.name as warehouse',
            'company.name as company',
            'category.name as category',
        ])
            ->from($this->table)
            ->join('entity', 'entity.id = report.entity_id', 'left')
            ->join('project', 'project.id = report.project_id', 'left')
            ->join('warehouse', 'warehouse.id = report.warehouse_id', 'left')
            ->join('category', 'category.id = report.category_id', 'left')
            ->join('company', 'company.id = report.company_id', 'left')
            ->where('report.id', $id)
            ->group_by('report.id');

        return $this->db->get()->row_array();
    }

    public function get_detail($id)
    {
        $this->db->select([
            $this->table . '.id',
            $this->table . '.no',
            $this->table . '.title',
            $this->table . '.description',
            'entity.name as entity',
            'project.name as project',
            'warehouse.name as warehouse',
            'company.name as company',
            'category.id as category_id',
            'category.name as category',
            $this->table . '.status',
            'DATE_FORMAT(report.completed_at, "%Y-%m-%d %H:%i") as completed_at',
            'CONCAT_WS(" ", created_by.first_name, created_by.last_name) created_by',
            'DATE_FORMAT(report.created_at, "%Y-%m-%d %H:%i") as created_at'
        ])
            ->from($this->table)
            ->join('entity', 'entity.id = report.entity_id', 'left')
            ->join('project', 'project.id = report.project_id', 'left')
            ->join('warehouse', 'warehouse.id = report.warehouse_id', 'left')
            ->join('category', 'category.id = report.category_id', 'left')
            ->join('company', 'company.id = report.company_id', 'left')
            ->join('user created_by', 'created_by.id = report.created_by', 'left')
            ->where('report.id', $id)
            ->group_by('report.id');

        return $this->db->get()->row_array();
    }

    public function summary($user = null)
    {
        $this->db->select([
            "COUNT(*) as all_count",
            "SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count",
            "SUM(CASE WHEN status = 'On Process' THEN 1 ELSE 0 END) as on_process_count",
            "SUM(CASE WHEN status = 'Completed' OR status = 'Approved' THEN 1 ELSE 0 END) as completed_count"
        ]);

        if (!empty($user)) {
            $this->db->where('created_by', $user);
        }

        $query = $this->db->get($this->table);
        $result = $query->row_array();

        return [
            "all" => $result['all_count'] ?? 0,
            "pending" => $result['pending_count'] ?? 0,
            "on_process" => $result['on_process_count'] ?? 0,
            "completed" => $result['completed_count'] ?? 0
        ];
    }

    public function get_next_id()
    {
        $this->db->select_max('id');
        $query = $this->db->get($this->table);
        $row = $query->row_array();
        return $row['id'] + 1;
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function get_evidences_by_report($report_id)
    {
        return $this->db->get_where('report_evidences', ['report_id' => $report_id])->result_array();
    }

    public function get_evidence($id)
    {
        return $this->db->get_where('report_evidences', ['id' => $id])->row_array();
    }

    public function add_evidence($report_id, $image_path, $image_name)
    {
        $data = array(
            'report_id' => $report_id,
            'image_path' => $image_path,
            'image_name' => $image_name
        );
        $this->db->insert('report_evidences', $data);
        return $this->db->insert_id();
    }

    public function delete_evidences_by_report($report_id)
    {
        return $this->db->delete('report_evidences', ['report_id' => $report_id]);
    }

    public function delete_evidence($id)
    {
        return $this->db->delete('report_evidences', ['id' => $id]);
    }

    public function get_works_by_report($report_id)
    {
        return $this->db->get_where('report_works', ['report_id' => $report_id])->result_array();
    }

    public function get_work($id)
    {
        return $this->db->get_where('report_works', ['id' => $id])->row_array();
    }

    public function add_work($report_id, $image_path, $image_name)
    {
        $data = array(
            'report_id' => $report_id,
            'image_path' => $image_path,
            'image_name' => $image_name
        );
        $this->db->insert('report_works', $data);
        return $this->db->insert_id();
    }

    public function delete_works_by_report($report_id)
    {
        return $this->db->delete('report_works', ['report_id' => $report_id]);
    }

    public function delete_work($id)
    {
        return $this->db->delete('report_works', ['id' => $id]);
    }

    public function get_details_by_report($report_id)
    {
        return $this->db->order_by('no')->get_where('report_details', ['report_id' => $report_id])->result_array();
    }

    public function add_detail($data)
    {
        $this->db->insert('report_details', $data);
        return $this->db->insert_id();
    }

    public function delete_details_by_report($report_id)
    {
        return $this->db->delete('report_details', ['report_id' => $report_id]);
    }

    public function get_rab($report_id)
    {
        return $this->db->get_where('report_rab', ['report_id' => $report_id])->row_array();
    }

    public function create_rab($data)
    {
        $this->db->insert('report_rab', $data);
        return $this->db->insert_id();
    }

    public function update_rab($id, $data)
    {
        return $this->db->where('report_id', $id)->update('report_rab', $data);
    }

    public function delete_rab($report_id)
    {
        return $this->db->delete('report_rab', ['report_id' => $report_id]);
    }

    public function get_manager($report_id)
    {
        return $this->db->get_where('report_manager', ['report_id' => $report_id])->row_array();
    }

    public function create_manager($data)
    {
        $this->db->insert('report_manager', $data);
        return $this->db->insert_id();
    }

    public function update_manager($id, $data)
    {
        return $this->db->where('report_id', $id)->update('report_manager', $data);
    }

    public function delete_manager($report_id)
    {
        return $this->db->delete('report_manager', ['report_id' => $report_id]);
    }
}
