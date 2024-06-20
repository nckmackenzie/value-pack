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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/roles">Roles</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content py-1">
        <div class="col-md-8 mx-auto bg-white ">
            <form action="<?php echo URLROOT;?>/roles/create_update" method="post">
                <div class="col-md-6 mx-auto">
                    <div class="alert"></div>
                    <?php flash('role_msg');?>
                    <?php if(!is_null($data['error'])) : ?>
                        <div class="alert custom-destructive">
                            <p class="text-sm font-medium text-rose-900">👉 <?php echo $data['error']; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="role_name">Role Name</label>
                            <input type="text" name="role_name" id="role_name" 
                                   class="form-control <?php echo invalid_setter($data['role_name_err']); ?>"
                                   value="<?php echo $data['role_name'];?>" placeholder="eg sales">
                            <span class="invalid-feedback"><?php echo $data['role_name_err']; ?></span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 px-2 my-2">In the table below, Check the forms you want the defined role to have access to.</p>
                    <div class="col-12">
                        <table class="table-cm">
                            <thead>
                                <tr>
                                    <th class="w-10">Check</th>
                                    <th class="hidden">form-id</th>
                                    <th>Module</th>
                                    <th>Form</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['forms'] as $form) : ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="state[]" class="table-input state" value="<?php echo (bool)$form['checked'] ? 'true' : 'false';?>">
                                            <input type="checkbox" name="form_checkbox[]" class="table-input checkbox" <?php echo (bool)$form['checked'] ? 'checked' : '' ;?>>
                                        </td>
                                        <td class="hidden"><input type="text" name="form_id[]" value="<?php echo $form['form_id'];?>" class="table-input"></td>
                                        <td><input type="text" name="module[]" value="<?php echo $form['module'];?>" class="table-input uppercase"></td>
                                        <td><input type="text" name="form_name[]" value="<?php echo $form['form_name'];?>" class="table-input uppercase"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-default">Save</button>
                        <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                        <input type="hidden" name="current_store" id="current_store" value="<?php echo $_SESSION['store'];?>" >
                        <input type="hidden" name="is_edit" value="<?php echo $data['is_edit'];?>">
                    </div>
                </div>
            </form>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/roles/role.js"></script>
</body>
</html>  