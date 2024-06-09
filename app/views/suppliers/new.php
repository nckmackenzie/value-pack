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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/suppliers">Suppliers</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
        <a href="<?php echo URLROOT;?>/suppliers" class="btn btn-outline">
            <i data-lucide="chevron-left" class="icon mr-1.5 text-slate-600"></i><span>Go Back</span>
        </a>
        <div class="col-md-6 mx-auto">
            <?php flash('supplier_msg');?>
            <?php if(!is_null($data['error'])) : ?>
                <div class="alert custom-destructive"><?php echo $data['error'];?></div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-12 col-md-8 mx-auto bg-white rounded-sm p-3">
                <h2 class="text-lg font-semibold"><?php echo $data['title'];?></h2>
                <p class="text-xs text-slate-400">Fill out the details to create a supplier listing.</p>
                <form action="<?php echo URLROOT;?>/suppliers/create_update" method="post" class="mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_name">Supplier Name</label>
                            <input type="text" name="supplier_name" id="supplier_name" 
                                   class="form-control mandatory <?php echo invalid_setter($data['supplier_name_err']);?>" 
                                   value="<?php echo $data['supplier_name'];?>"
                                   placeholder="eg Bottle manufacturers">
                            <span class="invalid-feedback"><?php echo $data['supplier_name_err'];?></span>
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact">Supplier Contact</label>
                            <input type="text" name="contact" id="contact" 
                                   class="form-control mandatory  <?php echo invalid_setter($data['contact_err']);?>" 
                                   value="<?php echo $data['contact'];?>"
                                   placeholder="eg 0700000000">
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
                            <label for="contact_person">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control capitalize" 
                                   value="<?php echo $data['contact_person'];?>"
                                   placeholder="eg Jane Doe">
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
<script type="module" src="<?php echo URLROOT;?>/js/pages/suppliers/supplier.js"></script>
</body>
</html>  