<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>

  <div class="row">
    <div class="col-lg-6">

      <!-- klo form berhasil, tampil ini -->
      <?php echo $this->session->flashdata('register'); ?>

      <h5>Role : <?php echo $roles['role']; ?></h5>

      <a href="<?php echo base_url('admin/role'); ?>" class="btn btn-primary mb-3">Back</a>

      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Menu</th>
            <th scope="col">Access</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php foreach ($menus as $menu): ?>
            <tr>
              <th scope="row"><?php echo $i; ?></th>
              <td><?php echo $menu['menu']; ?></td>
              <td>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" <?php echo check_access($roles['id'],$menu['id']); ?> data-role="<?php echo $roles['id']; ?>" data-menu="<?php echo $menu['id']; ?>">
                </div>
              </td>
            </tr>
            <?php $i++; ?>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
