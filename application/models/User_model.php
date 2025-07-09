<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
private $table = 'user';

  public function get_by_username($username) {
    return $this->db->get_where($this->table, ['username' => $username])->row();
  }

  public function create($data) {
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    return $this->db->insert($this->table, $data);
  }

  public function get_by_id($id) {
    return $this->db->get_where($this->table, ['id' => $id])->row();
  }
}