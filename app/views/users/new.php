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
    <section class="content bg-white py-1">
        <form action="<?php echo URLROOT;?>/users/create_update" method="post">
            <div class="col-md-6 mx-auto">
                <?php flash('user_msg');?>
                <?php if(!is_null($data['error']))  : ?>
                    <div class="alert custom-destructive">
                        <p class="text-sm font-medium text-rose-900">👉 <?php echo $data['error']; ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_name">User Name</label>
                        <input type="text" name="user_name" id="user_name" 
                               class="form-control <?php echo invalid_setter($data['user_name_err']);?>"
                               value="<?php echo $data['user_name'];?>" >
                        <span class="invalid-feedback"><?php echo $data['user_name_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contact">Phone No</label>
                        <input type="text" name="contact" id="contact" 
                               class="form-control mandatory <?php echo invalid_setter($data['contact_err']);?>"
                               value="<?php echo $data['contact']; ?>">
                        <span class="invalid-feedback"><?php echo $data['contact_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" 
                               class="form-control mandatory <?php echo invalid_setter($data['password_err']);?>"
                               value="<?php echo $data['password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['password_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" 
                               class="form-control mandatory <?php echo invalid_setter($data['confirm_password_err']);?>"
                               value="<?php echo $data['confirm_password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err'];?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control mandatory <?php echo invalid_setter($data['role_err']);?>">
                            <option value="" disabled selected>Select user role</option>
                            <?php foreach($data['roles'] as $role) : ?>
                                <option value="<?php echo $role->id;?>" <?php selectdCheck($data['role'],$role->id) ?>><?php echo strtoupper($role->role_name);?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['role_err'];?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="store">Store to have access to</label>
                        <select name="store[]" id="store" class="form-control mandatory" multiple>
                            <?php foreach($data['stores'] as $store) : ?>
                                <option value="<?php echo $store->id;?>" <?php echo in_array($store->id,$data['stores_allowed']) ? 'selected' : ''; ?>><?php echo $store->store_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(!empty($data['store_err'])) : ?>
                            <span class="text-sm text-rose-500"><?php echo $data['store_err']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <button type="submit" class="btn btn-default">Save</button>
                    <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                    <input type="hidden" name="current_store" id="current_store" value="<?php echo $_SESSION['store'];?>">
                    <input type="hidden" name="is_edit" value="<?php echo $data['is_edit'];?>">
                </div>
            </div>
        </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js"></script>
<script type="module" src="<?php echo URLROOT;?>/js/pages/users/user.js"></script>
</body>
</html>  