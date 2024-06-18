<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-4">
    <section class="content-header px-0">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>Blank Page</h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item text-sm">Master Entry</li>
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/customers">customers</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
        <a href="<?php echo URLROOT;?>/customers" class="btn btn-outline">
            <i data-lucide="chevron-left" class="icon mr-1.5 text-slate-600"></i><span>Go Back</span>
        </a>
        <div class="col-md-6 mx-auto">
            <?php flash('customer_msg');?>
            <?php if(!is_null($data['error'])) : ?>
                <div class="alert custom-destructive"><?php echo $data['error'];?></div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-12 col-md-8 mx-auto bg-white rounded-sm p-3">
                <h2 class="text-lg font-semibold"><?php echo $data['title'];?></h2>
                <p class="text-xs text-slate-400">Fill out the details to create a customer listing.</p>
                <form action="<?php echo URLROOT;?>/customers/create_update" method="post" class="mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_name">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" 
                                   class="form-control mandatory <?php echo invalid_setter($data['customer_name_err']);?>" 
                                   value="<?php echo $data['customer_name'];?>"
                                   placeholder="eg Bottle manufacturers">
                            <span class="invalid-feedback"><?php echo $data['customer_name_err'];?></span>
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact">Customer Contact</label>
                            <input type="text" name="contact" id="contact" 
                                   class="form-control mandatory  <?php echo invalid_setter($data['contact_err']);?>" 
                                   value="<?php echo $data['contact'];?>"
                                   placeholder="eg 0700000000" maxlength="10">
                            <span class="invalid-feedback"><?php echo $data['contact_err'];?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" 
                                   class="form-control <?php echo invalid_setter($data['email_err']);?>" 
                                   value="<?php echo $data['email'];?>"
                                   placeholder="eg test@example.com">
                            <span class="invalid-feedback"><?php echo $data['email_err'];?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pin">PIN</label>
                            <input type="text" name="pin" id="pin" class="form-control capitalize" 
                                   value="<?php echo $data['pin'];?>"
                                   placeholder="eg P123456789A" maxlength="10">
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
                </div>
            </form>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/customers/customer.js"></script>
</body>
</html>  