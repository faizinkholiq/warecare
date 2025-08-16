<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
private $table = 'user';

  public function get_all()
  {
    return $this->db->get($this->table)->result();
  }

  public function get_list_datatables($p)
	{
		$search = $p["search"];

		$this->db->start_cache();

		$limit = $p["length"];
		$offset = $p["start"];

		if(!empty($search["value"])){
      $col = [
        "user.username",
				"user.first_name",
				"user.last_name",
				"user.role",
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
			'user.id',
			'user.username',
			'CONCAT_WS(" ", user.first_name, user.last_name) as name',
			'user.role',
			'user.is_active',
		])
		->from('user')
		->order_by('user.id', 'desc')
		->group_by('user.id');

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