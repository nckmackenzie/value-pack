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
              <li class="breadcrumb-item text-sm">Master Entry</li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <?php DeleteModal(URLROOT .'/customers/delete','deleteModal','Are your you want to delete this customer?','id'); ?>
    <?php flash('customer_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/customers/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create New customer</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="customersDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="">Customer Name</th>
            <th scope="col" class="">Contact</th>
            <th scope="col" class="">Email</th>
            <th scope="col" class="">Status</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data['customers'] as $customer) : ?>
            <tr>
              <td class="capitalize"><?php echo $customer->customer_name;?></td>
              <td><?php echo $customer->contact;?></td>
              <td class="lowercase"><?php echo $customer->email;?></td>
              <td class=""><div class="capsule <?php echo (bool)$customer->active ? 'capsule-success' : 'capsule-destructive';?>"><?php echo $customer->active ? 'Active' : 'Inactive';?></div></td>
              <td class="flex items-center gap-2">
                <?php action_buttons("edit","customers",$customer->id);?>
                <?php action_buttons("delete","",$customer->id);?>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/customers/customer.js"></script>
</body>
</html>  