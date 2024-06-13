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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/transfers">Transfers</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content bg-white py-1">
        <form action="<?php echo URLROOT;?>/transfers/create_update" method="post">
            <div class="col-md-6 mx-auto">
                <?php flash('transfer_msg');?>
                <?php if(!is_null($data['errors']) && count($data['errors']) > 0) : ?>
                    <div class="alert custom-destructive">
                        <?php foreach($data['errors'] as $error) : ?>
                            <p class="text-sm font-medium text-rose-900">👉 <?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="transfer_date">Transfer Date</label>
                        <input type="date" name="transfer_date" id="transfer_date" 
                               class="form-control mandatory <?php echo invalid_setter($data['transfer_date_err']);?>"
                               value="<?php echo $data['transfer_date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['transfer_date_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="store">Transfer To</label>
                        <select name="store" id="store" class="form-control mandatory <?php echo invalid_setter($data['store_err']);?>">
                            <option value="" disabled selected>Select store</option>
                            <?php foreach($data['stores'] as $store) : ?>
                                <?php if($store->id !== $_SESSION['store']) : ?>
                                    <option value="<?php echo $store->id;?>" <?php selectdCheck($data['store'],$store->id) ?>><?php echo $store->store_name;?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['store_err'];?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="transfer_no">Transfer Reference</label>
                        <input type="text" name="transfer_no" id="transfer_no" class="form-control"
                        value="<?php echo $data['transfer_no'];?>" readonly>
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
                        <label for="qty">Qty</label>
                        <input type="number" name="qty" id="qty" class="form-control">
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
                                <th class="w-[40%]">Product</th>
                                <th class="w-[40%]">Qty</th>
                                <th class="w-[20%]"></th>
                            </tr>      
                        </thead> 
                        <tbody>
                            <?php foreach($data['items'] as $item) : ?>
                                <tr>
                                    <td class="hidden"><input type="text" name="product_id[]" value="<?php echo $item['product_id'];?>"/></td>
                                    <td><input type="text" name="product[]" class="w-full uppercase" value="<?php echo $item['product_name'];?>" readonly/></td>
                                    <td><input type="number" name="qty[]" value="<?php echo $item['qty'];?>" readonly /></td>
                                    <td><button type="button" class="outline-none border-none text-rose-400 focus:outline-0">Remove</button></td>
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
                    <input type="hidden" name="current_store" id="current_store" value="<?php echo $_SESSION['store'];?>">
                    <input type="hidden" name="is_edit" value="<?php echo $data['is_edit'];?>">
                </div>
            </div>
        </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/transfers/transfer.js"></script>
</body>
</html>  