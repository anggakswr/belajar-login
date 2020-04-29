<div class="container">

  <!-- Outer Row -->
  <div class="row justify-content-center">

    <div class="col-lg-5">

      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900">Change your password for</h1>
                  <h5 class="mb-4"><?php echo $this->session->userdata('reset_email'); ?></h5>
                </div>

                <!-- menampilkan pesan sukses register -->
                <?php echo $this->session->flashdata('register'); ?>

                <form class="user" method="post" action="<?php echo base_url('auth/change_password'); ?>">
                  <div class="form-group">
                    <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Enter new password...">
                    <!-- set_value('name') utk mengisi input dg name="name", ini berguna utk mengembalikkan value yg hilang saat form salah isi -->
                    <?php echo form_error('password1','<small class="pl-3 text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Repeat new password...">
                    <!-- set_value('name') utk mengisi input dg name="name", ini berguna utk mengembalikkan value yg hilang saat form salah isi -->
                    <?php echo form_error('password2','<small class="pl-3 text-danger">','</small>'); ?>
                  </div>
                  <button type="submit" class="btn btn-primary btn-user btn-block">
                    Change Password
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>
