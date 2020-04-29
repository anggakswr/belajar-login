<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
  }

  // jika sdh login, msk ke halaman yg seharusnya
  public function goToDefaultPage() {
    if ($this->session->userdata('role_id') == 1) {
      redirect('admin');
    } else if ($this->session->userdata('role_id') == 2) {
      redirect('user');
    } else {
      // jika ada role_id yg lain maka tambahkan disini
    }
  }

  public function index() {

    $this->goToDefaultPage(); // jika sdh login, msk ke halaman yg seharusnya

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

    $this->goToDefaultPage(); // jika sdh login, msk ke halaman yg seharusnya

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

      $email = $this->input->post('email');
      $data = [
        'name' => htmlspecialchars($this->input->post('name'),TRUE),
        'email' => htmlspecialchars($email,TRUE),
        'image' => $image,
        'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT), // enkripsi password dg algoritma terbaik yg dipilihkan php
        'role_id' => 2,
        'is_active' => 0,
        'date_created' => time()
      ];

      // siapkan token konfirmasi
      $token = rand(10000,99999);
      $user_token = [
        'email' => $email,
        'token' => $token,
        'date_created' => time(),
      ];

      $this->db->insert('user',$data); // masukkan user baru ke db
      $this->db->insert('user_token',$user_token); // masukkan user_token ke dlm db

      $this->_send_email($email,$token,'verify'); // method bikinan sendiri

      $this->session->set_flashdata('register','
      <div class="alert alert-info" role="alert">
        Registration succeed! Please check your email.
      </div>');
      redirect('auth');
    }

  }

  private function _send_email($email,$token,$type) {
    $config = [
      'protocol' => 'smtp',
      'smtp_host' => 'ssl://smtp.googlemail.com',
      'smtp_user' => 'febifebi098@gmail.com',
      'smtp_pass' => 'zqiuJM33ihcM5yY',
      'smtp_port' => 465,
      'mailtype' => 'html',
      'charset' => 'utf-8',
      'newline' => "\r\n"
    ];

    $this->load->library('email',$config);

    $this->email->from('febifebi098@gmail.com','Belajar Login');
    $this->email->to($email);

    if ($type == 'verify') {
      $this->email->subject('Account Verification');
      $this->email->message('
      <div style="padding: 20px 300px; margin: auto; text-align: center;">

        <p>Almost done, To complete your <span style="color: #2F4F4F; font-weight: bold;">Belajar Login</span> sign up, we just need you to click this link to verify your email address:</p>

        <a href="'.base_url().'auth/verify?email='.$email.'&token='.$token.'" style="text-decoration: none; color: white; padding: 10px 40px; margin: 5px; display: inline-block; background-color: #2F4F4F; border-radius: 5px;">Active</a>

        <p>You’re receiving this email because you recently created a new <span style="color: #2F4F4F; font-weight: bold;">Belajar Login</span> account or change your password. If this wasn’t you, please ignore this email.</p>

        <a href="#" style="text-decoration: none; color: grey;"><small style="margin: 0 10px;">Belajar Login</small></a>
        <a href="#" style="text-decoration: none; color: grey;"><small style="margin: 0 10px;">About</small></a>
        <a href="#" style="text-decoration: none; color: grey;"><small style="margin: 0 10px;">Contact</small></a>

      </div>');
    } elseif ($type == 'forgot') {
      $this->email->subject('Reset Password');
      $this->email->message('
      <div style="padding: 20px 300px; margin: auto; text-align: center;">

        <p>Almost done, To reset password, we just need you to click this link to verify your email address:</p>

        <a href="'.base_url().'auth/reset_password?email='.$email.'&token='.$token.'" style="text-decoration: none; color: white; padding: 10px 40px; margin: 5px; display: inline-block; background-color: #2F4F4F; border-radius: 5px;">Reset Password</a>

        <p>You’re receiving this email because you recently clicked on reset password or change your password. If this wasn’t you, please ignore this email.</p>

        <a href="#" style="text-decoration: none; color: grey;"><small style="margin: 0 10px;">Belajar Login</small></a>
        <a href="#" style="text-decoration: none; color: grey;"><small style="margin: 0 10px;">About</small></a>
        <a href="#" style="text-decoration: none; color: grey;"><small style="margin: 0 10px;">Contact</small></a>

      </div>');
    }

    if ($this->email->send()) {
      return TRUE;
    } else {
      echo $this->email->print_debugger();
      die;
    }
  }

  // ini utk verify email
  public function verify() {
    $email = $this->input->get('email');
    $token = $this->input->get('token');
    $user = $this->db->get_where('user',['email' => $email])->row_array(); // cek apa user dg email tsb ad

    // jika user ada
    if ($user) {
      $user_token = $this->db->get_where('user_token',['token' => $token])->row_array(); // cek apa token tsb ad di db atau tdk

      // jika token ada
      if ($user_token) {
        if (time() - $user_token['date_created'] < (60*60*24)) {
          $this->db->set('is_active',1);
          $this->db->where('email',$email);
          $this->db->update('user'); // update status is_active
          $this->db->delete('user_token',['email' => $email]); // delete token yg sdh terpakai

          $this->session->set_flashdata('register','
          <div class="alert alert-success" role="alert">
            '.$email.' has been activated.
          </div>');
          redirect('auth');
        } else {
          $this->db->delete('user',['email' => $email]);
          $this->db->delete('user_token',['email' => $email]);

          $this->session->set_flashdata('register','
          <div class="alert alert-danger" role="alert">
            Account activation failed! Token expired.
          </div>');
          redirect('auth');
        }
      } else {
        $this->session->set_flashdata('register','
        <div class="alert alert-danger" role="alert">
          Account activation failed! Wrong token.
        </div>');
        redirect('auth');
      }
    } else {
      $this->session->set_flashdata('register','
      <div class="alert alert-danger" role="alert">
        Account activation failed! Wrong email.
      </div>');
      redirect('auth');
    }
  }

  public function blocked() {
    $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
    $data['title'] = 'Access Blocked!';
    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('auth/blocked');
    $this->load->view('templates/footer');
  }

  public function forgot_password() {
    $this->form_validation->set_rules('email','Email','trim|required|valid_email');
    if ($this->form_validation->run() == FALSE) {
      $data['title'] = 'Forgot Password';
      $this->load->view('templates/auth_header',$data);
      $this->load->view('auth/forgot_password');
      $this->load->view('templates/auth_footer');
    } else {
      $email = $this->input->post('email');
      $user = $this->db->get_where('user',[
        'email' => $email,
        'is_active' => 1
      ])->row_array(); // cek apa email yg ada di inputan ada di db / tdk

      if ($user) {
        $token = rand(10000,99999);
        $user_token = [
          'email' => $email,
          'token' => $token,
          'date_created' => time()
        ];

        $this->db->insert('user_token',$user_token);
        $this->_send_email($email,$token,'forgot');
        $this->session->set_flashdata('register','
        <div class="alert alert-info" role="alert">
          Please check your email to reset your password.
        </div>');
        redirect('auth/forgot_password');
      } else {
        $this->session->set_flashdata('register','
        <div class="alert alert-danger" role="alert">
          Email is not registered or not activated!
        </div>');
        redirect('auth/forgot_password');
      }
    }
  }

  public function reset_password() {
    $email = $this->input->get('email');
    $token = $this->input->get('token');
    $user = $this->db->get_where('user',['email' => $email])->row_array(); // cek apa user dg email tsb ad

    if ($user) {
      // jika user ada
      $user_token = $this->db->get_where('user_token',['token' => $token])->row_array();

      if ($user_token) {
        // jika token ada
        $this->session->set_userdata('reset_email',$email); // mengirim session yg akan digunakan di halaman change_password
        $this->change_password();

      } else {
        // jika token ga ada
        $this->session->set_flashdata('register','
        <div class="alert alert-danger" role="alert">
          Password reset failed! Wrong token.
        </div>');
        redirect('auth');
      }

    } else {
      // jika user tdk ada
      $this->session->set_flashdata('register','
      <div class="alert alert-danger" role="alert">
        Password reset failed! Wrong email.
      </div>');
      redirect('auth');
    }
  }

  public function change_password() {
    if (!$this->session->userdata('reset_email')) {
      // halaman reset_password hanya bs diakses setelah user isi form forgot_password
      $this->session->set_flashdata('register','
      <div class="alert alert-danger" role="alert">
        Please reset your password here.
      </div>');
      redirect('auth/forgot_password');
    }

    $this->form_validation->set_rules('password1','Password','trim|required|min_length[3]|matches[password2]');
    $this->form_validation->set_rules('password2','Password','trim|required|min_length[3]|matches[password1]');

    if ($this->form_validation->run() == FALSE) {
      $data['title'] = 'Change Password';
      $this->load->view('templates/auth_header',$data);
      $this->load->view('auth/change_password');
      $this->load->view('templates/auth_footer');
    } else {
      $password = password_hash($this->input->post('password1'),PASSWORD_DEFAULT);
      $email = $this->session->userdata('reset_email');

      $this->db->set('password',$password);
      $this->db->where('email',$email);
      $this->db->update('user');

      $this->session->unset_userdata('reset_email');

      $this->session->set_flashdata('register','
      <div class="alert alert-success" role="alert">
        Password reset succeed.
      </div>');
      redirect('auth');
    }
  }

}
