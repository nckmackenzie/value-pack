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
    <?php DeleteModal(URLROOT .'/transfers/delete','deleteModal','Are your you want to delete this transfer?','id'); ?>
    <?php flash('transfer_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/transfers/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create new transfer</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="transfersDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="text-left">Transfer Date</th>
            <th scope="col" class="text-left">Transfer No</th>
            <th scope="col" class="text-left">To Store</th>
            <th scope="col" class="text-left">Status</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data['transfers'] as $transfer) : ?>
            <tr>
              <td class="text-left"><?php echo date('d-m-Y',strtotime($transfer->transfer_date));?></td>
              <td class="uppercase text-left"><?php echo $transfer->transfer_no;?></td>
              <td class="uppercase text-left"><?php echo $transfer->store_name;?></td>
              <td class="text-left">
                <div class="uppercase capsule <?php echo (int)$transfer->rec_count === 0 ? 'capsule-destructive' : 'capsule-success';?>">
                  <?php echo (int)$transfer->rec_count === 0 ? 'pending' : 'received';?>
                </div>
              </td>
              <td class="flex items-center gap-2">
                  <?php action_buttons("edit","transfers",$transfer->id);?>
                  <?php action_buttons("delete","",$transfer->id);?>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/transfers/transfer-v1.js"></script>
</body>
</html>  