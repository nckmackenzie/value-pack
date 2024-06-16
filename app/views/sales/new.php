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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/sales">Sales</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content bg-white py-1">
        <form action="<?php echo URLROOT;?>/sales/create_update" method="post">
            <div class="col-md-6 mx-auto">
                <div class="alert"></div>
                <?php flash('sale_msg');?>
                <?php if(!is_null($data['error'])) : ?>
                    <div class="alert custom-destructive">
                        <p class="text-sm font-medium text-rose-900">👉 <?php echo $data['error']; ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sale_no">Sale No</label>
                        <input type="text" name="sale_no" id="sale_no" class="form-control"
                        value="<?php echo $data['sale_no'];?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sale_date">Sale Date</label>
                        <input type="date" name="sale_date" id="sale_date" 
                               class="form-control mandatory <?php echo invalid_setter($data['sale_date_err']);?>"
                               value="<?php echo $data['sale_date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['sale_date_err'];?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="customer">Customer</label>
                        <select name="customer" id="customer" class="form-control mandatory <?php echo invalid_setter($data['customer_err']);?>">
                            <option value="" disabled selected>Select customer</option>
                            <?php foreach($data['customers'] as $customer) : ?>
                                <option value="<?php echo $customer->id;?>" <?php selectdCheck($data['customer'],$customer->id) ?>><?php echo strtoupper($customer->customer_name);?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['customer_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sale_type">Sale Type</label>
                        <select name="sale_type" id="sale_type" class="form-control mandatory <?php echo invalid_setter($data['sale_type_err']);?>">
                            <option value="" disabled selected>Select sale type</option>
                            <option value="refill">Water Refill</option>
                            <option value="sale">General Sale</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['sale_type_err'];?></span>
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="rate">Selling Price</label>
                        <input type="text" name="rate" id="rate" class="form-control" value="<?php echo $data['rate'];?>" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="current_stock">Available Stock</label>
                        <input type="text" name="current_stock" id="current_stock" 
                               class="form-control" value="<?php echo $data['current_stock'];?>" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="qty">Selling Qty</label>
                        <input type="number" name="qty" id="qty" 
                               class="form-control mandatory <?php echo invalid_setter($data['qty_err']);?>" 
                               value="<?php echo $data['qty'];?>">
                        <span class="invalid-feedback"><?php echo $data['qty_err'];?></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="total_value">Total Value</label>
                        <input type="text" name="total_value" id="total_value" 
                               class="form-control" value="<?php echo $data['total_value'];?>" readonly>
                    </div>
                </div>
            </div>
            <div class="row mt-8">
                <div class="col-12">
                    <button type="submit" class="btn btn-default">Save</button>
                    <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                    <input type="hidden" name="current_store" id="current_store" value="<?php echo $_SESSION['store'];?>" >
                    <input type="hidden" name="is_edit" value="<?php echo $data['is_edit'];?>">
                </div>
            </div>
        </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/sales/sale.js"></script>
</body>
</html>  