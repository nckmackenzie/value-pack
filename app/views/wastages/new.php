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
              <li class="breadcrumb-item text-sm">Transactions</li>
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/wastages">Wastages</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content bg-white p-4">
        <a href="<?php echo URLROOT;?>/wastages" class="btn btn-info">&larr; Go Back</a>
        <form action="<?php echo URLROOT;?>/wastages/create_update" method="post" enctype="multipart/form-data">
            <div class="col-md-6 mx-auto">
                <div class="alert"></div>
                <?php flash('wastage_msg');?>
                <?php if(!is_null($data['errors']) && count($data['errors']) > 0) : ?>
                    <div class="alert custom-destructive">
                        <?php foreach($data['errors'] as $error) : ?>
                            <p class="text-sm font-medium text-rose-900">👉 <?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" 
                               class="form-control mandatory <?php echo invalid_setter($data['date_err']);?>"
                               value="<?php echo $data['date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['date_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product">Product</label>
                        <select name="product" id="product" class="form-control mandatory <?php echo invalid_setter($data['product_err']);?>">
                            <option value="" disabled selected>Select product</option>
                            <?php foreach($data['products'] as $product) : ?>
                                <option value="<?php echo $product->id;?>" <?php selectdCheck($data['product'],$product->id) ?>><?php echo strtoupper($product->product_name);?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['product_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="qty_wasted">Qty Wasted</label>
                        <input type="text" name="qty_wasted" id="qty_wasted" 
                               class="form-control mandatory <?php echo invalid_setter($data['qty_wasted_err']);?>"
                               value="<?php echo $data['qty_wasted']; ?>">
                        <span class="invalid-feedback"><?php echo $data['qty_wasted_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cost">Product/Item Rate</label>
                        <input type="text" name="cost" id="cost" 
                               class="form-control mandatory <?php echo invalid_setter($data['cost_err']);?>"
                               value="<?php echo $data['cost']; ?>">
                        <span class="invalid-feedback"><?php echo $data['cost_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="wastage_value">Wastage Value</label>
                        <input type="text" name="wastage_value" id="wastage_value" 
                               class="form-control"
                               value="<?php echo $data['wastage_value']; ?>" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <input type="text" name="remarks" id="remarks" 
                               class="form-control mandatory <?php echo invalid_setter($data['remarks_err']);?>"
                               value="<?php echo $data['remarks']; ?>">
                        <span class="invalid-feedback"><?php echo $data['remarks_err'];?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="remarks">Supporting image</label>
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="file" name="file">
                        <label class="custom-file-label" for="image">Choose file...</label>
                        <div class="invalid-feedback">Select image</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-default">Save</button>
                    <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                    <input type="hidden" name="is_edit" value="<?php echo $data['is_edit'];?>">
                </div>
            </div>
        </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/wastages/wastage.js"></script>
</body>
</html>  