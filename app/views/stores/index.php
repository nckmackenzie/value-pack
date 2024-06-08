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
              <li class="breadcrumb-item">Admin</li>
              <li class="breadcrumb-item active">Stores</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content space-y-6">
        <a href="<?php echo URLROOT;?>/stores/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
            </i><span>Create New Store</span>
        </a>
        <div class="row">
          <div class="col-12 table-responsive">
            <table class="table-cm table-stripped table-sm">
              <thead>
                <tr>
                  <th>Store Name</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($data['stores'] as $store) : ?>
                  <tr>
                    <td class="capitalize"><?php echo $store->Store_Name;?></td>
                    <td><?php echo $store->Active;?></td>
                    <td></td>
                  </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
</body>
</html>  