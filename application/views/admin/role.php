<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>

  <div class="row">
    <div class="col-lg-6">

      <!-- klo form error tampil ini -->
      <?php echo form_error('role','<div class="alert alert-danger" role="alert">','</div>') ?>
      <!-- klo form berhasil tampil ini -->
      <?php echo $this->session->flashdata('register'); ?>

      <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addRole">Add Role</a>

      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Role</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php foreach ($roles as $role): ?>
            <tr>
              <th scope="row"><?php echo $i; ?></th>
              <td><?php echo $role['role']; ?></td>
              <td>
                <a href="<?php echo base_url('admin/role_access/').$role['id']; ?>" class="badge badge-pill badge-info">access</a>
                <a href="#" class="badge badge-pill badge-primary">edit</a>
                <a href="#" class="badge badge-pill badge-danger">delete</a>
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

<!-- Modal -->
<div class="modal fade" id="addRole" tabindex="-1" role="dialog" aria-labelledby="addRoleLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoleLabel">Add Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo base_url('admin/role'); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <input type="text" class="form-control" id="role" name="role" placeholder="Role name">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>
