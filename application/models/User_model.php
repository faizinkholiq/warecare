<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
private $table = 'user';

  public function get_all()
  {
    return $this->db->get($this->table)->result();
  }

  public function get_by_username($username) {
    return $this->db->get_where($this->table, ['username' => $username])->row_array();
  }

  public function get_by_id($id) {
    return $this->db->get_where($this->table, ['id' => $id])->row_array();
  }

  public function insert($data)
  {
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    return $this->db->insert($this->table, $data);
  }

  public function update($id, $data)
  {
    if (!empty($data['password'])) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    } else {
      unset($data['password']);
    }

    return $this->db->where('id', $id)->update($this->table, $data);
  }

  public function delete($id)
  {
    return $this->db->delete($this->table, ['id' => $id]);
  }
}