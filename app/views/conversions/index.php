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
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content bg-white py-1">
        <form action="<?php echo URLROOT;?>/conversions/create_update" method="post">
            <div class="col-md-6 mx-auto">
                <?php flash('receipt_msg');?>
                <?php if(!is_null($data['errors']) && count($data['errors']) > 0) : ?>
                    <div class="alert custom-destructive">
                        <?php foreach($data['errors'] as $error) : ?>
                            <p class="text-sm font-medium text-rose-900">👉 <?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Conversion Date</label>
                        <input type="date" name="date" id="date" 
                               class="form-control mandatory <?php echo invalid_setter($data['date_err']);?>"
                               value="<?php echo $data['date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['date_err'];?></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="final_product">Final Product</label>
                        <select name="final_product" id="final_product" class="form-control mandatory <?php echo invalid_setter($data['final_product_err']);?>">
                            <option value="" disabled selected>Select final product</option>
                            <?php foreach($data['products'] as $product) : ?>
                                <option value="<?php echo $product->id;?>" <?php selectdCheck($data['final_product'],$product->id) ?>><?php echo strtoupper($product->product_name);?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['final_product_err'];?></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="converted_qty">Converted Qty</label>
                        <input type="text" name="converted_qty" id="converted_qty" 
                               class="form-control mandatory <?php echo invalid_setter($data['converted_qty_err']);?>"
                               value="<?php echo $data['converted_qty']; ?>">
                        <span class="invalid-feedback"><?php echo $data['converted_qty_err'];?></span>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product">Product</label>
                        <select name="product" id="product" class="form-control">
                            <option value="" disabled selected>Select product</option>
                            <?php foreach($data['products'] as $product) : ?>
                                <option value="<?php echo $product->id;?>"><?php echo strtoupper($product->product_name);?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="current_stock">Current Stock</label>
                        <input type="text" name="current_stock" id="current_stock" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="qty">Qty To Convert</label>
                        <input type="number" name="qty" id="qty" class="form-control">
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <button type="button" class="btn btn-success" id="add">Add</button>
                </div>
            </div>
            <hr/>
            <hr />
            <br>
            <div class="row">
                <div class="col-12">
                    <table class="table-cm" id="items_table">
                        <thead>
                            <tr>
                                <th class="hidden">Product</th>
                                <th>Product</th>
                                <th class="w-[30%]">Converted Qty</th>
                            </tr>      
                        </thead> 
                        <tbody>
                            <?php foreach($data['items'] as $item) : ?>
                                <tr>
                                    <td class="hidden"><input type="text" name="product_id[]" value="<?php echo $item['product_id'];?>"/></td>
                                    <td><input type="text" name="product[]" class="w-full uppercase" value="<?php echo $item['product_name'];?>" readonly/></td>
                                    <td><input type="number" name="converted_qty[]" value="<?php echo $item['converted_qty'];?>" /></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>     
                    </table>
                </div>
            </div>
            <div class="row mt-8">
                <div class="col-12">
                    <button type="submit" class="btn btn-default">Save</button>
                    <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                    <input type="hidden" name="is_edit" value="<?php echo $data['is_edit'];?>">
                    <input type="hidden" name="current_store" id="current_store" value="<?php echo $_SESSION['store'];?>">
                </div>
            </div>
        </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/conversions/conversion.js"></script>
</body>
</html>  