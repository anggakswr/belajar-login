<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>

  <div class="row">
    <div class="col-lg">

      <!-- klo form error tampil ini -->
      <?php if (validation_errors()): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo validation_errors(); ?>
        </div>
      <?php endif; ?>

      <!-- klo form berhasil tampil ini -->
      <?php echo $this->session->flashdata('register'); ?>

      <a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addSubmenu">Add Submenu</a>

      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Menu</th>
            <th scope="col">Url</th>
            <th scope="col">Icon</th>
            <th scope="col">Active</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php foreach ($submenus as $submenu): ?>
            <tr>
              <th scope="row"><?php echo $i; ?></th>
              <td><?php echo $submenu['title']; ?></td>
              <td><?php echo $submenu['menu']; ?></td>
              <td><?php echo $submenu['url']; ?></td>
              <td><?php echo $submenu['icon']; ?></td>
              <td><?php echo $submenu['is_active']; ?></td>
              <td>
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
<div class="modal fade" id="addSubmenu" tabindex="-1" role="dialog" aria-labelledby="addSubmenuLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSubmenuLabel">Add Submenu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo base_url('menu/submenu'); ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <input type="text" class="form-control" id="title" name="title" placeholder="Submenu title">
          </div>
          <div class="form-group">
            <select class="form-control" id="menu_id" name="menu_id">
              <option value="">-- Select menu --</option>
              <?php foreach ($menus as $menu): ?>
                <option value="<?php echo $menu['id']; ?>"><?php echo $menu['id']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="url" name="url" placeholder="Submenu url">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu icon">
          </div>
          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
              <label class="form-check-label" for="is_active">
                Active?
              </label>
            </div>
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
