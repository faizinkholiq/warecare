<?php defined('BASEPATH') or exit('No direct script access allowed');

class Company_model extends CI_Model
{

    private $table = 'company';

    public function get_all($p = null)
    {
        if (isset($p['project']) && !empty($p['project'])) {
            $this->db->where('project_id', $p['project']);
        }

        if (isset($p['name']) && !empty($p['name'])) {
            $this->db->like($this->table . '.name', $p['name'], 'both');
        }

        $this->db->select([
            $this->table . '.*',
            'project.name AS project_name'
        ])
            ->from($this->table)
            ->join('project', 'project.id = ' . $this->table . '.project_id')
            ->order_by($this->table . '.created_at', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get($id)
    {

        $this->db->select([
            $this->table . '.*',
            'project.name AS project_name',
            'project.entity_id'
        ])
            ->from($this->table)
            ->join('project', 'project.id = ' . $this->table . '.project_id')
            ->where($this->table . '.id', $id);

        return $this->db->get()->row_array();
    }

    public function get_by_project($project)
    {
        $this->db->order_by('name', 'ASC');
        return $this->db->get_where($this->table, ['project_id' => $project])->result_array();
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
