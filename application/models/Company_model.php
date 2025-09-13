<?php defined('BASEPATH') or exit('No direct script access allowed');

class Company_model extends CI_Model
{

    private $table = 'company';

    public function get_all()
    {
        $this->db->select($this->table . '.*, project.name AS project_name');
        $this->db->from($this->table);
        $this->db->join('project', 'project.id = ' . $this->table . '.project_id');
        $this->db->order_by($this->table . '.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
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
