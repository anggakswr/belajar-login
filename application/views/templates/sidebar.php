<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Belajar Login</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Query Menu -->
  <?php
    $role_id = $this->session->userdata('role_id'); // mengambil role_id dr user yg sedang login
    $this->db->select('user_menu.id, menu');
    $this->db->from('user_menu');
    $this->db->join('user_access_menu', 'user_menu.id = user_access_menu.menu_id'); // parameter kedua menyamakan id (primary & foreign key (id dr user_menu & menu_id dr user_access_menu))
    $this->db->where('user_access_menu.role_id = ' . $role_id);
    $this->db->order_by('user_access_menu.menu_id','ASC');
    $menus = $this->db->get()->result_array();
  ?>

  <!-- Looping Menu -->
  <?php foreach ($menus as $menu): ?>
    <div class="sidebar-heading">
      <?php echo $menu['menu']; ?>
    </div>

  <!-- Siapkan Submenu Sesuai Menu -->
  <?php
    // $this->db->select('*');
    $menu_id = $menu['id']; // mengambil id dr query di atas
    $this->db->from('user_sub_menu');
    $this->db->where('menu_id = ' . $menu_id);
    $this->db->where('is_active = 1');
    $this->db->order_by('menu_id','ASC');
    $subMenus = $this->db->get()->result_array();
  ?>

    <?php foreach ($subMenus as $subMenu): ?>
      <?php if ($title == $subMenu['title']): ?>
        <li class="nav-item active">
      <?php else: ?>
        <li class="nav-item">
      <?php endif; ?>
      <!-- Nav Item - Dashboard -->
        <a class="nav-link" href="<?php echo base_url($subMenu['url']); ?>">
          <i class="<?php echo $subMenu['icon']; ?>"></i>
          <span><?php echo $subMenu['title']; ?></span>
        </a>
      </li>
    <?php endforeach; ?>

    <!-- Divider -->
    <hr class="sidebar-divider">

  <?php endforeach; ?>

  <li class="nav-item">
    <a class="nav-link" href="<?php echo base_url('auth/logout'); ?>">
      <i class="fas fa-fw fa-sign-out-alt"></i>
      <span>Logout</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->
