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
    <?php DeleteModal(URLROOT .'/sales/delete','deleteModal','Are your you want to delete this sale?','id'); ?>
    <?php flash('sale_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/sales/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create new sale</span>
      </a>
      <div class="col-12 table-responsive">
        <table class="display" style="width:100%" id="salesDatatable">
          <thead class="">
            <tr>
              <th scope="col" class="text-left">Sale Date</th>
              <th scope="col" class="text-left">Sale No</th>
              <th scope="col" class="text-left">Sale Type</th>
              <th scope="col" class="text-left">Customer</th>
              <th scope="col" class="text-left">Amount</th>
              <th scope="col" class="">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['sales'] as $sale) : ?>
              <tr>
                <td class="text-left"><?php echo date('d-m-Y',strtotime($sale->sale_date));?></td>
                <td class="uppercase text-left"><?php echo $sale->sale_no;?></td>
                <td class="uppercase text-left">
                  <div class="uppercase capsule <?php echo $sale->sale_type === 'sale' ? 'capsule-success' : 'capsule-info';?>">
                    <?php echo $sale->sale_type === 'sale' ? 'General Sale' : 'Water Refill';?>
                  </div>
                </td>
                <td class="uppercase text-left"><?php echo $sale->customer_name;?></td>
                <td class="text-left"><?php echo number_format($sale->amount,2);?></td>
                <td class="flex items-center gap-2">
                  <?php action_buttons("edit","sales",$sale->id);?>
                  <?php action_buttons("delete","",$sale->id);?>
                </td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/sales/sale.js"></script>
</body>
</html>  