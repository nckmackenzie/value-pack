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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/purchases">Purchases</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content bg-white py-1">
        <form action="<?php echo URLROOT;?>/purchases/create_update" method="post">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Purchase Date</label>
                        <input type="date" name="date" id="date" 
                               class="form-control mandatory <?php echo invalid_setter($data['date_err']);?>">
                        <span class="invalid-feedback"><?php echo $data['date_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vendor">Vendor</label>
                        <select name="vendor" id="vendor" class="form-control mandatory">
                            <option value="" disabled selected>Select vendor</option>
                            <?php foreach($data['suppliers'] as $supplier) : ?>
                                <option value="<?php echo $supplier->id;?>"><?php echo $supplier->supplier_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['date_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="reference">Purchase Reference</label>
                        <input type="text" name="reference" id="reference"   class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vat_type">Vendor</label>
                        <select name="vat_type" id="vat_type" class="form-control mandatory">
                            <option value="" disabled selected>Select vat type</option>
                            <option value="no-vat">No Vat</option>
                            <option value="inclusive">Inclusive</option>
                            <option value="exclusive">Exclusive</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['vat_type_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vat">Vendor</label>
                        <select name="vat" id="vat" class="form-control" disabled>
                            <option value="" disabled selected>Select vat</option>
                            <option value="16">Vat 16%</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['vat_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="store">Receiving Store</label>
                        <select name="store" id="store" class="form-control mandatory <?php invalid_setter($data['store_err']);?>">
                            <option value="" disabled selected>Select store</option>
                            <?php foreach($data['stores'] as $store) : ?>
                                <option value="<?php echo $store->id;?>"><?php echo $store->store_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['vat_err'];?></span>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row mt-1">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product">Product</label>
                        <select name="product" id="product" class="form-control">
                            <option value="" disabled selected>Select product</option>
                            <?php foreach($data['products'] as $product) : ?>
                                <option value="<?php echo $product->id;?>"><?php echo $product->product_name;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="qty">Qty</label>
                        <input type="number" name="qty" id="qty" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="rate">Rate</label>
                        <input type="number" name="rate" id="rate" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="value">Value</label>
                        <input type="text" name="value" id="value" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <button class="btn btn-success" id="add">Add</button>
                </div>
            </div>
            <hr>
            <br>
            <div class="row">
                <div class="col-12">
                    <table class="table-cm" id="items_table">
                        <thead>
                            <tr>
                                <th class="w-[30%]">Product</th>
                                <th class="w-[20%]">Qty</th>
                                <th class="w-[20%]">Rate</th>
                                <th class="w-[20%]">Value</th>
                                <th class="w-[10%]"></th>
                            </tr>      
                        </thead> 
                        <tbody></tbody>     
                    </table>
                </div>
            </div>
        </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/purchases/purchase.js"></script>
</body>
</html>  