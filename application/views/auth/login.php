<div class="container">

  <!-- Outer Row -->
  <div class="row justify-content-center">

    <div class="col-lg-7">

      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <!-- Nested Row within Card Body -->
          <div class="row">
            <div class="col-lg">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">Login</h1>
                </div>

                <!-- menampilkan pesan sukses register -->
                <?php echo $this->session->flashdata('register'); ?>

                <form class="user" method="post" action="<?php echo base_url('auth'); ?>">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Enter Email Address..." value="<?php echo set_value('email'); ?>">
                    <!-- set_value('name') utk mengisi input dg name="name", ini berguna utk mengembalikkan value yg hilang saat form salah isi -->
                    <?php echo form_error('email','<small class="pl-3 text-danger">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                    <?php echo form_error('password','<small class="pl-3 text-danger">','</small>'); ?>
                  </div>
                  <button type="submit" class="btn btn-primary btn-user btn-block">
                    Login
                  </button>
                </form>
                <hr>
                <div class="text-center">
                  <a class="small" href="forgot-password.html">Forgot Password?</a>
                </div>
                <div class="text-center">
                  <a class="small" href="<?php echo base_url('auth/register/') ?>">Create an Account!</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>
