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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/expenseaccounts">Expense Accounts</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content space-y-4">
        <a href="<?php echo URLROOT;?>/expenseaccounts" class="btn btn-outline">
            <i data-lucide="chevron-left" class="icon mr-1.5 text-slate-600"></i><span>Go Back</span>
        </a>
        <div class="col-md-6 mx-auto">
            <?php flash('expenseaccount_msg');?>
            <?php if(!is_null($data['error'])) : ?>
                <div class="alert custom-destructive"><?php echo $data['error'];?></div>
            <?php endif; ?>
        </div>
        <div class="card col-md-6 mx-auto">            
            <div class="card-header"><?php echo $data['title'];?></div>
            <div class="card-body">
                <form action="<?php echo URLROOT;?>/expenseaccounts/create_update" method="post">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="account_name">Account Name</label>
                            <input type="text" class="form-control <?php echo invalid_setter($data['account_name_err']); ?>" value="<?php echo $data['account_name'];?>" 
                                name="account_name"
                                placeholder="eg Utilities">
                            <span class="invalid-feedback"><?php echo $data['account_name_err'];?></span>
                        </div>
                    </div>
                    <?php if($data['is_edit']) : ?>
                      <div class="col-12 flex items-baseline gap-2">
                        <label class="switch">
                          <input type="checkbox" value="<?php echo $data['active'] ? 'true' : '';?>" class="switch-input" name="active" <?php echo $data['active'] ? 'checked' : '';?>>
                          <span class="slider round"></span>
                        </label>
                        <div class="text-sm">Active</div>
                      </div>
                    <?php endif; ?>
                    <div class="col-12 action-btns">
                       <button type="submit" class="btn btn-default">Save</button>
                       <button type="reset" class="btn btn-outline">Cancel</button>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                    <input type="hidden" name="is_edit" value="<?php echo $data['is_edit'];?>">
                </form>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
</body>
</html>  