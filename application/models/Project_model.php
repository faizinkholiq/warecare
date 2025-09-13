<?php defined('BASEPATH') or exit('No direct script access allowed');

class Project_model extends CI_Model
{

    private $table = 'project';

    public function get_all()
    {
        $this->db->select($this->table . '.*, entity.name AS entity_name');
        $this->db->from($this->table);
        $this->db->join('entity', 'entity.id = ' . $this->table . '.entity_id');
        $this->db->order_by($this->table . '.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function get_by_entity($entity)
    {
        $this->db->order_by($this->table . '.name', 'ASC');
        return $this->db->get_where($this->table, ['entity_id' => $entity])->result_array();
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
