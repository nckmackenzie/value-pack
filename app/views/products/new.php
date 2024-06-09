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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/products">Products</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
        <a href="<?php echo URLROOT;?>/products" class="btn btn-outline">
            <i data-lucide="chevron-left" class="icon mr-1.5 text-slate-600"></i><span>Go Back</span>
        </a>
        <div class="col-md-6 mx-auto">
            <?php flash('product_msg');?>
            <?php if(!is_null($data['error'])) : ?>
                <div class="alert custom-destructive"><?php echo $data['error'];?></div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-12 col-md-8 mx-auto bg-white rounded-sm p-3">
                <h2 class="text-lg font-semibold"><?php echo $data['title'];?></h2>
                <p class="text-xs text-slate-400">Fill out the details to create a product listing.</p>
                <form action="<?php echo URLROOT;?>/products/create_update" method="post" class="mt-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control mandatory <?php echo invalid_setter($data['name_err']);?>" 
                                   value="<?php echo $data['name'];?>"
                                   placeholder="eg Water bottle 500ml">
                            <span class="invalid-feedback"><?php echo $data['name_err'];?></span>
                        </div>                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Product Code</label>
                            <input type="text" name="code" id="code" class="form-control  <?php echo invalid_setter($data['code_err']);?>" 
                                   value="<?php echo $data['code'];?>"
                                   placeholder="eg 10000001">
                            <span class="invalid-feedback"><?php echo $data['code_err'];?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit">U.O.M</label>
                            <select name="unit" id="unit" class="form-control mandatory <?php echo invalid_setter($data['unit_err']);?>">
                                <option value="" disabled selected>Select unit</option>
                                <?php foreach($data['units'] as $unit) :?>
                                    <option value="<?php echo $unit->id;?>" <?php selectdCheck($data['unit'], $unit->id) ?>><?php echo $unit->unit;?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['unit_err'];?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="restock_level">Restock Level</label>
                            <input type="number" name="restock_level" id="restock_level" class="form-control" 
                                   value="<?php echo $data['restock_level'];?>"
                                   placeholder="eg 10">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="buying_price">Buying Rate</label>
                            <input type="number" name="buying_price" id="buying_price" class="form-control" 
                                   value="<?php echo $data['buying_price'];?>"
                                   placeholder="eg 100">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selling_price">Selling Rate</label>
                            <input type="number" name="selling_price" id="selling_price" 
                                   class="form-control <?php echo invalid_setter($data['selling_price_err']);?>" 
                                   value="<?php echo $data['selling_price'];?>"
                                   placeholder="eg 200">
                            <span class="invalid-feedback"><?php echo $data['selling_price_err'];?></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                          <label for="stores">Stores Allowed In</label>
                          <select id="stores" name="stores[]"class="form-control mandatory <?php echo invalid_setter($data['stores_err']);?>" multiple>
                                <?php foreach($data['stores'] as $store) :?>
                                    <option value="<?php echo $store->ID;?>" <?php echo in_array($store->ID,$data['stores_allowed']) ? 'selected' : '' ;?> ><?php echo $store->Store_Name;?></option>
                                <?php endforeach; ?>
                          </select>
                          <span class="invalid-feedback"><?php echo $data['stores_err'];?></span>
                      </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Product Description</label>
                            <textarea name="description" id="description" class="form-control"><?php echo $data['description'];?></textarea>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js"></script>
<script type="module" src="<?php echo URLROOT;?>/js/pages/products/products.js"></script>
</body>
</html>  