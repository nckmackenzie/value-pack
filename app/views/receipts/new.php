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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/receipts">Receipts</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content bg-white py-1">
        <form action="<?php echo URLROOT;?>/receipts/create_update" method="post">
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
                        <label for="receipt_no">Receipt Reference</label>
                        <input type="text" name="receipt_no" id="receipt_no" class="form-control"
                        value="<?php echo $data['receipt_no'];?>" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="receipt_date">Receipt Date</label>
                        <input type="date" name="receipt_date" id="receipt_date" 
                               class="form-control mandatory <?php echo invalid_setter($data['receipt_date_err']);?>"
                               value="<?php echo $data['receipt_date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['receipt_date_err'];?></span>
                    </div>
                </div>
                <?php if(!$data['is_edit']) : ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="store_from">Store From</label>
                            <select name="store_from" id="store_from" class="form-control mandatory <?php echo invalid_setter($data['store_from_err']);?>">
                                <option value="" disabled selected>Select store from</option>
                                <?php foreach($data['stores'] as $store) : ?>
                                    <option value="<?php echo $store->id;?>" <?php selectdCheck($data['store_from'],$store->id) ?>><?php echo strtoupper($store->store_name);?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['store_from_err'];?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transfer_no">Transfer Reference</label>
                            <select name="transfer_no" id="transfer_no" class="form-control mandatory <?php echo invalid_setter($data['transfer_no_err']);?>">
                                <option value="" disabled selected>Select transfer</option>
                                <?php foreach($data['transfers'] as $transfer) : ?>
                                    <option value="<?php echo $transfer->id;?>" <?php selectdCheck($data['transfer_no'],$transfer->id) ?>><?php echo $transfer->transfer_no;?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['transfer_no_err'];?></span>
                        </div>
                    </div>
                <?php endif; ?>
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
                                <th class="w-[40%]">Product</th>
                                <th class="w-[30%]">Transfered Qty</th>
                                <th class="w-[30%]">Received Qty</th>
                            </tr>      
                        </thead> 
                        <tbody>
                            <?php foreach($data['items'] as $item) : ?>
                                <tr>
                                    <td class="hidden"><input type="text" name="product_id[]" value="<?php echo $item['product_id'];?>"/></td>
                                    <td><input type="text" name="product[]" class="w-full uppercase" value="<?php echo $item['product_name'];?>" readonly/></td>
                                    <td><input type="number" name="transfered_qty[]" value="<?php echo $item['transfered_qty'];?>" readonly/></td>
                                    <td><input type="number" name="received_qty[]" value="<?php echo $item['received_qty'];?>" /></td>
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
<script type="module" src="<?php echo URLROOT;?>/js/pages/receipts/receipt-v1.js"></script>
</body>
</html>  