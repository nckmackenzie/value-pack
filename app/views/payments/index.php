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
    <?php DeleteModal(URLROOT .'/payments/delete','deleteModal','Are your you want to delete this payment?','id'); ?>
    <?php flash('payment_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/payments/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create new payment</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="paymentsDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="">Payment Date</th>
            <th scope="col" class="text-left">Invoice No</th>
            <th scope="col" class="">Customer</th>
            <th scope="col" class="text-left">Payment Value</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data['payments'] as $payment) : ?>
            <tr>
              <td><?php echo date('d-m-Y',strtotime($payment->payment_date));?></td>
              <td class="text-left"><?php echo $payment->invoice_no;?></td>
              <td class="uppercase"><?php echo $payment->customer_name;?></td>
              <td class="text-left"><div class="capsule capsule-info"><?php echo number_format($payment->amount,2);?></div></td>
              <td class="flex items-center gap-2">
                <?php action_buttons("edit","payments",$payment->id);?>
                <?php action_buttons("delete","",$payment->id);?>
              </td> 
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/payments/payment.js"></script>
</body>
</html>  