<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-6">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>
          
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Transactions</a></li>
              <li class="breadcrumb-item active">Stock Report</li>
            </ol>
          </div> -->
        </div>
      </div>
    </section>
    <section class="content">
      <?php if(ENVIRONMENT === 'DEVELOPMENT') : ?>
        <div class="col-md-6 mx-auto">
          <div class="alert custom-warning ">
            <h2 class="text-base font-bold uppercase mb-2">⚠️ notice</h2>
            <p class="text-sm text-slate-600">For testing purposes ONLY</p>
          </div>
        </div>
      <?php endif; ?>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
</body>
</html>  