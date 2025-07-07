<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_model extends CI_Model {

    
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


    public function create($data)
    {
        $this->db->insert('transaction', $data);

        return ($this->db->affected_rows()>0) ? $this->db->insert_id() : false;
    }

    public function edit($data)
    {   
        $this->db->where('id', $data['id']);
        unset($data['id']);
        $this->db->update('transaction', $data);

        return ($this->db->error()["code"] == 0) ? true : false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('transaction');
        
        return ($this->db->affected_rows() > 0) ? true : false ;
    }

    public function detail($id)
    {
        return $this->db->get_where('transaction', ["id" => $id])->row_array();
    }

    public function get_status_list()
    {
        return $this->db->get('transaction_status')->result_array();
    }

    public function get_category_list()
    {
        return $this->db->get('transaction_category')->result_array();
    }

    public function get_data()
    {
        
        $q = $this->db->select([
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
            '(transaction.qty * transaction.price) - transaction.tax - transaction.other - transaction.deduction total',
            'transaction.paid_by',
            'status.name status',
            'transaction.received',
        ])
        ->from('transaction')
        ->join('transaction_status status', "status.id = transaction.status", 'left')
        ->join('transaction_category category', "category.id = transaction.category", 'left')
        ->order_by('transaction.date', 'desc')
        ->group_by('transaction.id');

        return $q->get()->result_array();
    }

}