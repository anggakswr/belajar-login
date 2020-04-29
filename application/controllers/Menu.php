<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

  public function __construct() {
    parent::__construct();

    // jika user blm login
    is_logged_in(); // helper buatan sendiri

    $this->load->library('form_validation');
  }

  public function index() {
    $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
    $data['title'] = 'Edit Menu';
    $data['menus'] = $this->db->get('user_menu')->result_array();

    $this->form_validation->set_rules('menu','Menu','required');

    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/header',$data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('menu/index');
      $this->load->view('templates/footer');
    } else {
      $this->db->insert('user_menu',['menu' => $this->input->post('menu')]);
      $this->session->set_flashdata('register','
      <div class="alert alert-success" role="alert">
        New menu added.
      </div>');
      redirect('menu');
    }

  }

  public function submenu() {
    $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
    $data['title'] = 'Edit Submenu';
    // $data['submenus'] = $this->db->get('user_sub_menu')->result_array();
    $this->load->model('Menu_model');
    $data['submenus'] = $this->Menu_model->getSubmenu();
    $data['menus'] = $this->db->get('user_menu')->result_array();

    $this->form_validation->set_rules('title','Title','required');
    $this->form_validation->set_rules('menu_id','Menu','required');
    $this->form_validation->set_rules('url','URL','required');
    $this->form_validation->set_rules('icon','Icon','required');

    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/header',$data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('menu/submenu');
      $this->load->view('templates/footer');
    } else {
      $data = [
        'title' => $this->input->post('title'),
        'menu_id' => $this->input->post('menu_id'),
        'url' => $this->input->post('url'),
        'icon' => $this->input->post('icon'),
        'is_active' => $this->input->post('is_active')
      ];
      $this->db->insert('user_sub_menu', $data);
      $this->session->set_flashdata('register','
      <div class="alert alert-success" role="alert">
        New submenu added.
      </div>');
      redirect('menu/submenu');
    }
  }

}
