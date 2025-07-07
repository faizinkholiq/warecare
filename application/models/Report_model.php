<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    private $table = 'warehouse';
    
    public function get_datatables($p)
    {
        $search = $p["search"];

        $this->db->start_cache();

        $limit = $p["length"];
		$offset = $p["start"];

        if(!empty($search["value"])){
			$col = [
                "date", 
                "currency", 
                "code", 
                "category", 
                "invoice_to",
                "address",
                "phone",
                "email",
                "description",
                "paid_by",
                "status.name",
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
            'transaction.id',
            'transaction.date',
            'transaction.currency',
            'transaction.code',
            'category.name category',
            'transaction.invoice_to',
            'transaction.address',
            'transaction.phone',
            'transaction.email',
            'transaction.description',
            'transaction.qty',
            'transaction.price',
            '(transaction.qty * transaction.price) sub_total',
            'transaction.tax',
            'transaction.other',
            'transaction.deduction',
            '(transaction.qty * transaction.price) +  ((transaction.qty * transaction.price) * transaction.tax / 100) + transaction.other - transaction.deduction total',
            'transaction.paid_by',
            'status.name status',
            'transaction.received',
        ])
        ->from('transaction')
        ->join('transaction_status status', "status.id = transaction.status", 'left')
        ->join('transaction_category category', "category.id = transaction.category", 'left')
        ->order_by('transaction.date', 'desc')
        ->group_by('transaction.id');
        
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

    public function get_all()
    {
        $this->db->select('warehouse.*, company.name AS company_name');
        $this->db->from($this->table);
        $this->db->join('company', 'company.id = warehouse.company_id');
        return $this->db->get()->result();
    }

    public function get($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}