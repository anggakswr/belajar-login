<?php

function is_logged_in() {

  $ci = get_instance(); // $this diganti jd $ci, krn helper tdk bs memakai $this

  // jika user blm login
  if (!$ci->session->userdata('email')) {
    $ci->session->set_flashdata('register','
    <div class="alert alert-danger" role="alert">
      Sorry, you are not logged in.
    </div>');
    redirect('auth');
  } else {
    $role_id = $ci->session->userdata('role_id'); // mengambil role_id dr session
    $menu = $ci->uri->segment(1); // mengambil url stlh base_url
    $queryMenu = $ci->db->get_where('user_menu',['menu' => $menu])->row_array(); //SELECT * FROM user_menu WHERE menu = $menu
    $menu_id = $queryMenu['id']; // //SELECT id FROM user_menu WHERE menu = $menu

    $userAccess = $ci->db->get_where('user_access_menu',[
      'role_id' => $role_id,
      'menu_id' => $menu_id
    ]);
    if ($userAccess->num_rows() < 1) {
      redirect('auth/blocked');
    }
  }

}

function check_access($role_id, $menu_id) {

  $ci = get_instance(); // $this diganti jd $ci, krn helper tdk bs memakai $this

  $result = $ci->db->get_where('user_access_menu',[
    'role_id' => $role_id,
    'menu_id' => $menu_id
  ]);

  if ($result->num_rows() > 0) {
    return "checked";
  }

}
