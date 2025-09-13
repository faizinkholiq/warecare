<?php defined('BASEPATH') or exit('No direct script access allowed');

class Entity_model extends CI_Model
{

    private $table = 'entity';

    public function get_all()
    {
        return $this->db->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result_array();
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
}
