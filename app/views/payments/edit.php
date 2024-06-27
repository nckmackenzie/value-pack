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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/payments">Payments</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content bg-white p-4">
        <a href="<?php echo URLROOT;?>/payments" class="btn btn-info">&larr; Go Back</a>
        <form action="<?php echo URLROOT;?>/payments/update" method="post">
            <div class="col-md-6 mx-auto">
                <div class="alert"></div>
                <?php flash('payment_msg');?>
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
                        <label for="payment_date">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date" 
                               class="form-control mandatory <?php echo invalid_setter($data['payment_date_err']);?>"
                               value="<?php echo $data['payment_date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['payment_date_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
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
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control mandatory <?php echo invalid_setter($data['payment_method_err']);?>">
                            <option value="" disabled selected>Select payment method</option>
                            <option value="CASH" <?php selectdCheck($data['payment_method'],'CASH'); ?>>Cash</option>
                            <option value="MPESA" <?php selectdCheck($data['payment_method'],'MPESA'); ?>>Mpesa</option>
                            <option value="CHEQUE" <?php selectdCheck($data['payment_method'],'CHEQUE'); ?>>Cheque</option>
                            <option value="BANK" <?php selectdCheck($data['payment_method'],'BANK'); ?>>Bank</option>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['payment_method_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_reference">Payment reference</label>
                        <input type="text" name="payment_reference" id="payment_reference" 
                               class="form-control mandatory <?php echo invalid_setter($data['payment_reference_err']);?>"
                               value="<?php echo $data['payment_reference']; ?>">
                        <span class="invalid-feedback"><?php echo $data['payment_reference_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="amount_due">Amount Due</label>
                        <input type="text" name="amount_due" id="amount_due" 
                               class="form-control"
                               value="<?php echo $data['amount_due']; ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment">Payment</label>
                        <input type="number" name="payment" id="payment" 
                               class="form-control mandatory <?php echo invalid_setter($data['payment_err']);?>"
                               value="<?php echo $data['payment']; ?>">
                        <span class="invalid-feedback"><?php echo $data['payment_err'];?></span>
                    </div>
                </div>
            </div>
            <div class="row mt-8">
                <div class="col-12">
                    <button type="submit" class="btn btn-default">Save</button>
                    <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                    <input type="hidden" name="invoice_id" value="<?php echo $data['invoice_id'];?>">
                    <input type="hidden" name="payment_id" value="<?php echo $data['payment_id'];?>">
                    <input type="hidden" name="is_edit" value="<?php echo $data['is_edit'];?>">
                </div>
            </div>
        </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/payments/payment.js"></script>
</body>
</html>  