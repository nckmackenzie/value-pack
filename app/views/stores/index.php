<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-4">
    <section class="content-header px-0">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stores</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item text-sm">Admin</li>
              <li class="breadcrumb-item text-sm active">Stores</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <?php DeleteModal(URLROOT .'/stores/delete','deleteModal','Are your you want to delete this store?','id'); ?>
    <section class="content space-y-6">
        <a href="<?php echo URLROOT;?>/stores/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
            </i><span>Create New Store</span>
        </a>
        <!-- <div class="table-container"> -->
          <table class="table-cm table-responsive" id="storesDatatable">
            <thead class="">
              <tr>
                <th scope="col" class="">
                    Store Name
                </th>
                <th scope="col" class="">
                    Status
                </th>
                <th scope="col" class="">
                    Actions
                </th>
            </tr>
            </thead>
            <tbody>
              <?php foreach($data['stores'] as $store) : ?>
                <tr class="">
                    <td class="capitalize "><?php echo $store->Store_Name;?></td>
                    <td class=""><div class="capsule <?php echo (bool)$store->Active ? 'capsule-success' : 'capsule-destructive';?>"><?php echo $store->Active ? 'Active' : 'Inactive';?></div></td>
                    <td class="flex items-center gap-2">
                      <?php action_buttons("edit","stores",$store->ID);?>
                      <?php action_buttons("delete","",$store->ID);?>
                    </td>
                </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        <!-- </div> -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>

</body>
</html>  