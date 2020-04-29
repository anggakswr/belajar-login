<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('form_validation');

    // jika user blm login
    is_logged_in(); // helper buatan sendiri
  }

  public function index() {
    $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
    $data['title'] = 'My Profile';

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('user/index');
    $this->load->view('templates/footer');
  }

  public function edit() {
    $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
    $data['title'] = 'Edit Profile';

    $this->form_validation->set_rules('name','Full Name','required|trim');

    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/header', $data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('user/edit');
      $this->load->view('templates/footer');
    } else {
      $name = $this->input->post('name');
      $email = $this->input->post('email');

      // cek jika ada gambar
      $upload_image = $_FILES['image']['name'];
      if ($upload_image) {
        $config['upload_path']          = './assets/img/profile/';
        $config['allowed_types']        = 'jpeg|jpg|png';
        $config['max_size']             = '2048';
        $this->load->library('upload',$config);

        // upload image berhasil
        if ($this->upload->do_upload('image')) {
          $old_image = $data['user']['image'];
          if ($old_image != 'default.png') {
            unlink(FCPATH . 'assets/img/profile/' . $old_image);
          }

          $new_image = $this->upload->data('file_name');
          $this->db->set('image',$new_image); // set ini utk update
        } else {
          $this->session->set_flashdata('register', '
          <div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
          redirect('user');
          echo $this->upload->display_errors();
        }
      }

      $this->db->set('name',$name); // set ini utk update
      $this->db->where('email',$email);
      $this->db->update('user');

      $this->session->set_flashdata('register','
      <div class="alert alert-success" role="alert">
        Profile updated.
      </div>');
      redirect('user');
    }
  }

  public function change_password() {
    $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
    $data['title'] = 'Change Password';

    $this->form_validation->set_rules('current_password','Current Password','required|trim');
    $this->form_validation->set_rules('new_password1','New Password','required|trim|min_length[3]|matches[new_password2]');
    $this->form_validation->set_rules('new_password2','Repeat New Password','required|trim|min_length[3]|matches[new_password1]');

    if ($this->form_validation->run() == FALSE) {
      $this->load->view('templates/header',$data);
      $this->load->view('templates/sidebar');
      $this->load->view('templates/topbar');
      $this->load->view('user/change_password');
      $this->load->view('templates/footer');
    } else {
      $current_password = $this->input->post('current_password');
      $new_password = $this->input->post('new_password1');
      // jika current_password != password yg ad di db
      if (!password_verify($current_password,$data['user']['password'])) {
        $this->session->set_flashdata('register','
        <div class="alert alert-danger" role="alert">
          Current password is wrong!
        </div>');
        redirect('user/change_password');
      } else {
        // jika current_password = new_password1, ga boleh ya
        if ($current_password == $new_password) {
          $this->session->set_flashdata('register','
          <div class="alert alert-danger" role="alert">
            New password cannot be the same as current password!
          </div>');
          redirect('user/change_password');
        } else {
          // jika password sdh ok
          $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

          $this->db->set('password',$password_hash);
          $this->db->where('email',$data['user']['email']);
          $this->db->update('user');

          $this->session->set_flashdata('register','
          <div class="alert alert-success" role="alert">
            Password changed!
          </div>');
          redirect('user/change_password');
        }
      }
    }
  }

}
