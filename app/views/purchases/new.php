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
            <div class="col-md-6 mx-auto">
                <?php flash('purchase_msg');?>
                <?php if(!is_null($data['error'])) : ?>
                    <div class="alert custom-destructive"><?php echo $data['error'];?></div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Purchase Date</label>
                        <input type="date" name="date" id="date" 
                               class="form-control mandatory <?php echo invalid_setter($data['date_err']);?>"
                               value="<?php echo $data['date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['date_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vendor">Vendor</label>
                        <select name="vendor" id="vendor" class="form-control mandatory <?php echo invalid_setter($data['vendor_err']);?>">
                            <option value="" disabled selected>Select vendor</option>
                            <?php foreach($data['suppliers'] as $supplier) : ?>
                                <option value="<?php echo $supplier->id;?>" <?php selectdCheck($data['vendor'],$supplier->id) ?>><?php echo $supplier->supplier_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['vendor_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="reference">Purchase Reference</label>
                        <input type="text" name="reference" id="reference" class="form-control"
                        value="<?php echo $data['reference'];?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vat_type">Vat Type</label>
                        <select name="vat_type" id="vat_type" class="form-control mandatory <?php echo invalid_setter($data['vendor_err']);?>">
                            <option value="" disabled selected>Select vat type</option>
                            <option value="no-vat" <?php selectdCheck($data['vat_type'],'no-vat') ?>>No Vat</option>
                            <option value="inclusive" <?php selectdCheck($data['vat_type'],'inclusive') ?>>Inclusive</option>
                            <option value="exclusive" <?php selectdCheck($data['vat_type'],'exclusive') ?>>Exclusive</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['vat_type_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vat">Vat</label>
                        <select name="vat" id="vat" class="form-control <?php echo invalid_setter($data['vat_err']);?>" disabled>
                            <option value="" disabled selected>Select vat</option>
                            <option value="16" <?php selectdCheck($data['vat'],'16') ?>>Vat 16%</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['vat_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="store">Receiving Store</label>
                        <select name="store" id="store" class="form-control mandatory <?php echo invalid_setter($data['store_err']);?>">
                            <option value="" disabled selected>Select store</option>
                            <?php foreach($data['stores'] as $store) : ?>
                                <option value="<?php echo $store->id;?>" <?php selectdCheck($data['store'],$store->id) ?>><?php echo $store->store_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['store_err'];?></span>
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
                    <button type="button" class="btn btn-success" id="add">Add</button>
                </div>
            </div>
            <hr>
            <br>
            <div class="row">
                <div class="col-12">
                    <table class="table-cm" id="items_table">
                        <thead>
                            <tr>
                                <th class="hidden">Product</th>
                                <th class="w-[30%]">Product</th>
                                <th class="w-[20%]">Qty</th>
                                <th class="w-[20%]">Rate</th>
                                <th class="w-[20%]">Value</th>
                                <th class="w-[10%]"></th>
                            </tr>      
                        </thead> 
                        <tbody>
                            <?php foreach($data['items'] as $item) : ?>
                                <tr>
                                    <td class="hidden"><input type="text" name="product_id[]" value="<?php echo $item['product_id'];?>"/></td>
                                    <td class="capitalize"><input type="text" name="product[]" class="w-full" value="<?php echo $item['product_name'];?>"/></td>
                                    <td><input type="number" name="qty[]" value="<?php echo $item['qty'];?>" /></td>
                                    <td><input type="number" name="rate[]" value="<?php echo $item['rate'];?>" /></td>
                                    <td><input type="text" name="value[]" value="<?php echo $item['value'];?>" /></td>
                                    <td><button type="button" class="outline-none border-none text-rose-400 focus:outline-0">Remove</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>     
                    </table>
                </div>
            </div>
            <div class="my-4">
               <div class="flex justify-end">
                <div class="w-1/3">
                    <label for="total">Total</label>
                   <input type="text" name="total" id="total" class="form-control" value="<?php echo $data['total'];?>" readonly>
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
<script type="module" src="<?php echo URLROOT;?>/js/pages/purchases/purchase.js"></script>
</body>
</html>  