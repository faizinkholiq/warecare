<?php defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse_model extends CI_Model
{

    private $table = 'warehouse';

    public function get_all()
    {
        $this->db->select([
            $this->table . '.*',
            'company.name AS company_name',
        ])
            ->from($this->table)
            ->join('company', 'company.id = ' . $this->table . '.company_id')
            ->order_by($this->table . '.created_at', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_list($p)
    {
        if (!empty($p['project'])) {
            $this->db->where("company.project_id", $p['project']);
        }

        if (!empty($p['company'])) {
            $this->db->where($this->table . ".company_id", $p['company']);
        }

        if (isset($p['name']) && !empty($p['name'])) {
            $this->db->like($this->table . '.name', $p['name'], 'both');
        }

        $this->db->select([
            $this->table . '.*',
            'company.name AS company_name',
        ])
            ->from($this->table)
            ->join('company', 'company.id = ' . $this->table . '.company_id')
            ->order_by($this->table . '.created_at', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        $this->db->select([
            $this->table . '.*',
            'company.name AS company_name',
            'company.project_id AS project_id',
            'project.entity_id AS entity_id'
        ])
            ->from($this->table)
            ->join('company', 'company.id = ' . $this->table . '.company_id')
            ->join('project', 'project.id = company.project_id')
            ->where($this->table . '.id', $id);

        return $this->db->get()->row_array();
    }

    public function get_by_company($company)
    {
        $this->db->order_by('name', 'ASC');
        return $this->db->get_where($this->table, ['company_id' => $company])->result_array();
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
}
