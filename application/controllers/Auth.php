<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
  }

  public function index() {

    $this->form_validation->set_rules('email','Email','trim|required|valid_email');
    $this->form_validation->set_rules('password','Password','trim|required');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = 'Login';
      $this->load->view('templates/auth_header',$data);
      $this->load->view('auth/login');
      $this->load->view('templates/auth_footer');
    } else {
      $this->_login(); // method buatan sendiri
    }

  }

  private function _login() { // method ini hanya bs diakses oleh controller ini

    // menyimpan inputan ke dlm variabel
    $email = $this->input->post('email');
    $password = $this->input->post('password');

    // mengambil data user dr database
    $user = $this->db->get_where('user',['email' => $email])->row_array();

    // jika user ada
    if ($user) {
      // jika user sdh diaktivasi emailnya
      if ($user['is_active'] == 1) {
        // jika $password, cocok dg $user['password']
        if(password_verify($password, $user['password'])) { // password_verify = dekripsi hash password, menghasilkan boolean
          $data = [
            'email' => $user['email'],
            'role_id' => $user['role_id']
          ];
          $this->session->set_userdata($data);
          // menentukan otoritas user
          if ($user['role_id'] == 1) {
            redirect('admin');
          } else {
            redirect('user');
          }
        } else {
          $this->session->set_flashdata('register','
          <div class="alert alert-danger" role="alert">
            Password is wrong.
          </div>');
          redirect('auth');
        }
      } else {
        $this->session->set_flashdata('register','
        <div class="alert alert-danger" role="alert">
          Email is not activated.
        </div>');
        redirect('auth');
      }
    } else {
      $this->session->set_flashdata('register','
      <div class="alert alert-danger" role="alert">
        Email is not registered.
      </div>');
      redirect('auth');
    }

  }

  public function logout() {
    $this->session->unset_userdata('email');
    $this->session->unset_userdata('role_id');
    $this->session->set_flashdata('register','
    <div class="alert alert-success" role="alert">
      Logout succeed.
    </div>');
    redirect('auth');
  }

  public function register() {

    $this->form_validation->set_rules('name', 'Full Name', 'required|trim'); // trim utk menghilangkan spasi spy tdk msk di db
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]',[
      'is_unique' => 'Email already taken.'
    ]); // is_unique mengecek field email di table user
    $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]',[
      'matches' => 'Password not match.',
      'min_length' => 'Password too short.'
    ]); // matches = apakah cocok dg field password2
    $this->form_validation->set_rules('password2', 'Password', 'required|trim|min_length[3]|matches[password1]'); // matches = apakah cocok dg field password1

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = 'Registration';
      $this->load->view('templates/auth_header',$data);
      $this->load->view('auth/register');
      $this->load->view('templates/auth_footer');
    } else {

      if (empty($this->input->post('image'))) { // jika tdk ada image yg dipilih
        $image = 'default.png'; // pasang image default
      } else {
        $image = $this->input->post('image');
      }

      $data = [
        'name' => htmlspecialchars($this->input->post('name'),TRUE),
        'email' => htmlspecialchars($this->input->post('email'),TRUE),
        'image' => $image,
        'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT), // enkripsi password dg algoritma terbaik yg dipilihkan php
        'role_id' => 2,
        'is_active' => 1,
        'date_created' => time()
      ];

      $this->db->insert('user',$data);
      $this->session->set_flashdata('register','
      <div class="alert alert-success" role="alert">
        Registration succeed!
      </div>');
      redirect('auth');
      redirect('auth');
    }

  }

}
