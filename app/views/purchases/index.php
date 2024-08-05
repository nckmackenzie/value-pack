<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="px-4 content-wrapper">
    <section class="px-0 content-header">
      <div class="container-fluid">
        <div class="mb-2 row">
          <div class="col-sm-6">
            <h1><?php echo $data['title'];?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="text-sm breadcrumb-item">Transactions</li>
              <li class="text-sm breadcrumb-item active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <?php DeleteModal(URLROOT .'/purchases/delete','deleteModal','Are your you want to delete this purchase?','id'); ?>
    <?php flash('purchase_msg'); ?>
    <section class="space-y-6 content">
      <a href="<?php echo URLROOT;?>/purchases/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create New purchase</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="purchasesDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="">Purchase Date</th>
            <th scope="col" class="text-left">Reference</th>
            <th scope="col" class="">Vendor</th>
            <th scope="col" class="text-left">Purchase Value</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data['purchases'] as $purchase) : ?>
            <tr>
              <td ><?php echo date('d-m-Y',strtotime($purchase->purchase_date));?></td>
              <td class="text-left uppercase"><?php echo $purchase->reference;?></td>
              <td class="capitalize"><?php echo $purchase->supplier_name;?></td>
              <td class="text-left"><?php echo number_format($purchase->total,2);?></td>
              <td class="flex items-center gap-2">
                <?php action_buttons("edit","purchases",$purchase->id);?>
                <?php action_buttons("delete","",$purchase->id);?>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/purchases/purchase-v1.js"></script>
</body>
</html>  