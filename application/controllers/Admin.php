<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('form_validation');

    // jika user blm login
    is_logged_in(); // helper buatan sendiri
  }

  public function index() {
    $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
    $data['title'] = 'Dashboard';

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('admin/index');
    $this->load->view('templates/footer');
  }

  public function role() {
    $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
    $data['title'] = 'Role';
    $data['roles'] = $this->db->get('user_role')->result_array();

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('admin/role');
    $this->load->view('templates/footer');
  }

  public function role_access($role_id) {
    $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
    $data['title'] = 'Role';
    $data['roles'] = $this->db->get_where('user_role',['id' => $role_id])->row_array();
    $this->db->where('id != ', 1);
    $data['menus'] = $this->db->get('user_menu')->result_array();

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('admin/role_access');
    $this->load->view('templates/footer');
  }

  public function change_access() {
    $menu_id = $this->input->post('menuId');
    $role_id = $this->input->post('roleId');

    $data = [
      'role_id' => $role_id,
      'menu_id' => $menu_id
    ];

    $result = $this->db->get_where('user_access_menu',$data);

    if ($result->num_rows() < 1) {
      $this->db->insert('user_access_menu',$data);
    } else {
      $this->db->delete('user_access_menu',$data);
    }

    $this->session->set_flashdata('register','
    <div class="alert alert-success" role="alert">
      Access changed.
    </div>');
  }

}
