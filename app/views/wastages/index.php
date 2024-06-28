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
    <?php DeleteModal(URLROOT .'/wastages/delete','deleteModal','Are your you want to delete this wastage?','id'); ?>
    <?php flash('wastage_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/wastages/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create new wastage</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="wastagesDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="">Date</th>
            <th scope="col" class="text-left">Product</th>
            <th scope="col" class="">Qty</th>
            <th scope="col" class="text-left">Wastage Value</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data['wastages'] as $wastage) : ?>
            <tr>
              <!-- <td><?php echo date('d-m-Y',strtotime($wastage->wastage_date));?></td>
              <td class="text-left"><?php echo $wastage->invoice_no;?></td>
              <td class="uppercase"><?php echo $wastage->customer_name;?></td>
              <td class="text-left"><div class="capsule capsule-info"><?php echo number_format($wastage->amount,2);?></div></td>
              <td class="flex items-center gap-2">
                <?php action_buttons("edit","wastages",$wastage->id);?>
                <?php action_buttons("delete","",$wastage->id);?>
              </td>  -->
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/wastages/wastage.js"></script>
</body>
</html>  