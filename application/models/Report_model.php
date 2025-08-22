<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {
	private $table = 'report';

	public function get_all()
	{
		return $this->db->get($this->table)->result_array();
	}

	public function get_list_datatables($p)
	{
		$search = $p["search"];

		$this->db->start_cache();

		$limit = $p["length"];
		$offset = $p["start"];

		if(!empty($search["value"])){
			$col = [
				"report.name"
			];
			$src = $search["value"];
			$src_arr = explode(" ", $src);

			if ($src){
				$this->db->group_start();
				foreach($col as $key => $val){
					$this->db->or_group_start();
					foreach($src_arr as $k => $v){
						$this->db->like($val, $v, 'both'); 
					}
					$this->db->group_end();
				}
				$this->db->group_end();
			}
		}

		$this->db->select([
			'report.id',
            'report.no',
            'report.title',
            'entity.name as entity',
            'project.name as project',
            'report.created_at',
            'warehouse.name as warehouse',
            'company.name as company',
            'category.name as category',
            'report.status'
		])
		->from('report')
		->join('entity', 'entity.id = report.entity_id', 'left')
		->join('project', 'project.id = report.project_id', 'left')
		->join('warehouse', 'warehouse.id = report.warehouse_id', 'left')
		->join('category', 'category.id = report.category_id', 'left')
		->join('company', 'company.id = report.company_id', 'left')
		->order_by('report.id', 'desc')
		->group_by('report.id');

		$q = $this->db->get();
		$data["recordsTotal"] = $q->num_rows();
		$data["recordsFiltered"] = $q->num_rows();

		$this->db->stop_cache();

		$this->db->limit($limit, $offset);

		$data["data"] = $this->db->get()->result_array();
		$data["draw"] = intval($p["draw"]);

		$this->db->flush_cache();

		return $data;
	}

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
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

	public function add_evidence($report_id, $image_path, $image_name) {
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
}