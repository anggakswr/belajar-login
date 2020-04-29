<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>

  <!-- jika form berhasil muncul ini -->
  <?php echo $this->session->flashdata('register'); ?>

  <form action="<?php echo base_url('user/change_password'); ?>" method="post">

    <div class="form-group">
      <label for="current_password">Current Password</label>
      <input type="password" class="form-control" id="current_password" name="current_password">
      <?php echo form_error('current_password','<small class="pl-3 text-danger">','</small>'); ?>
    </div>

    <div class="form-group">
      <label for="new_password1">New Password</label>
      <input type="password" class="form-control" id="new_password1" name="new_password1">
      <?php echo form_error('new_password1','<small class="pl-3 text-danger">','</small>'); ?>
    </div>

    <div class="form-group">
      <label for="new_password2">Repeat New Password</label>
      <input type="password" class="form-control" id="new_password2" name="new_password2">
      <?php echo form_error('new_password2','<small class="pl-3 text-danger">','</small>'); ?>
    </div>

    <button type="submit" class="btn btn-primary">Change Password</button>

  </form>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
