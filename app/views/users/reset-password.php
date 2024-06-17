<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-4">
    <section class="content-header px-0">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?php echo $data['title'];?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item text-sm">Admin</li>
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/users">Users</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content py-1">
        <div class="col-md-4 mx-auto bg-white p-4">        
            <form action="<?php echo URLROOT;?>/users/reset_password_act" method="post">
                <div class="col-md-6 mx-auto">
                    <?php flash('user_msg');?>
                    <?php if(!is_null($data['error']))  : ?>
                        <div class="alert custom-destructive">
                            <p class="text-sm font-medium text-rose-900">👉 <?php echo $data['error']; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row">                   
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" 
                                class="form-control mandatory <?php echo invalid_setter($data['password_err']);?>"
                                value="<?php echo $data['password']; ?>">
                            <span class="invalid-feedback"><?php echo $data['password_err'];?></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" 
                                class="form-control mandatory <?php echo invalid_setter($data['confirm_password_err']);?>"
                                value="<?php echo $data['confirm_password']; ?>">
                            <span class="invalid-feedback"><?php echo $data['confirm_password_err'];?></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <button type="submit" class="btn btn-default w-full">Save</button>
                        <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                    </div>
                </div>
            </form>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/users/user.js"></script>
</body>
</html>  