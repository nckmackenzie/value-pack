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
    <?php DeleteModal(URLROOT .'/receipts/delete','deleteModal','Are your you want to delete this receipt?','id'); ?>
    <?php flash('receipt_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/receipts/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create new receipt</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="receiptsDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="text-left">Receipt Date</th>
            <th scope="col" class="text-left">Receipt No</th>
            <th scope="col" class="text-left">Received From</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
           <?php foreach($data['receipts'] as $receipt) : ?>
            <tr>
              <td class="text-left"><?php echo date('d-m-Y',strtotime($receipt->receipt_date));?></td>
              <td class="uppercase text-left"><?php echo $receipt->receipt_no;?></td>
              <td class="uppercase text-left"><?php echo $receipt->store_name;?></td>
              <td class="flex items-center gap-2">
                <?php action_buttons("edit","receipts",$receipt->id);?>
                <?php action_buttons("delete","",$receipt->id);?>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/receipts/receipt.js"></script>
</body>
</html>  