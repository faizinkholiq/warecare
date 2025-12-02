<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_lib
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->model('User_model');
    }

    public function is_logged_in()
    {
        return $this->CI->session->userdata('logged_in') === TRUE;
    }

    public function user_id()
    {
        return $this->CI->session->userdata('id');
    }

    public function current_user()
    {
        $id = $this->user_id();
        if (!$id) return null;
        return $this->CI->User_model->get($id);
    }

    public function role()
    {
        return $this->CI->session->userdata('role');
    }

    public function is_admin()
    {
        return $this->role() === 'administrator';
    }

    public function has_role($roles)
    {
        if (is_string($roles)) $roles = [$roles];
        return in_array($this->role(), $roles);
    }
}
