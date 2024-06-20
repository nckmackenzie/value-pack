<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-4">
    <section class="content-header px-0">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6 flex items-center gap-2">
            <button class="btn btn-default flex items-center gap-2" id="print">
              <i data-lucide="printer" class="icon"></i>
              <span>Print Invoice</span>
            </button>
            <button class="btn btn-info flex items-center gap-2" id="print-dnote">
              <i data-lucide="printer" class="icon"></i>
              <span>Print Delivery Note</span>
            </button>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="border rounded-sm max-w-7xl py-3 px-4" id="print_area">
        <header class="py-2 flex items-center justify-between">
            <h1 class="text-xl font-bold text-sky-400">VALUE PACK</h1>
            <div class="text-3xl font-bold uppercase">Invoice</div>
        </header>
        <div class="flex items-center justify-between my-8">
          <div class="space-y-2">
            <h1 class="font-bold text-lg">Invoice To</h1>
            <p class="text-slate-600 capitalize"><?php echo $data['header']->customer_name;?></p>
            <p class="text-slate-600 capitalize"><?php echo $data['header']->contact; ?></p>
            <p class="text-slate-600 uppercase"><?php echo $data['header']->pin; ?></p>
          </div>
          <div class="w-56 space-y-1">
            <div class="grid grid-cols-2 items-center gap-4">
                <p class="font-semibold">Invoice No:</p>
                <p><?php echo $data['header']->invoice_no; ?></p>
            </div>
            <div class="grid grid-cols-2 items-center gap-4">
                <p class="font-semibold">Invoice Date:</p>
                <p><?php echo $data['header']->invoice_date; ?></p>
            </div>
          </div>
        </div>
        <table class="table-cm">
          <thead>
            <tr>
              <th>Product</th>
              <th class="w-32">Qty</th>
              <th class="w-40 text-center">Unit Price</th>
              <th class="w-40 text-center">Line Total</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['items'] as $item) : ?>
              <tr>
                <td><?php echo strtoupper($item->product_name); ?></td>
                <td><?php echo $item->qty; ?></td>
                <td class="text-center"><?php echo number_format($item->rate,2); ?></td>
                <td class="text-center"><?php echo number_format($item->gross,2); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="mt-10 flex flex-col gap-4 items-end">
          <p class="text-slate-600 text-lg font-bold">Sub Total: <span class="ml-8"><?php echo number_format($data['header']->exclusive_amount,2); ?></span></p>
          <p class="text-slate-600 text-lg font-bold">V.A.T: <span class="ml-8"><?php echo number_format($data['header']->vat_amount,2); ?></span></p>
          <p class="text-slate-600 text-lg font-bold">Grand Total: <span class="ml-8"><?php echo number_format($data['header']->inclusive_amount,2); ?></span></p>
        </div>      
      </div>
      <div class="border rounded-sm max-w-7xl py-3 px-4 d-none" id="delivery">
        <header class="py-2 flex items-center justify-between">
            <h1 class="text-xl font-bold text-sky-400">VALUE PACK</h1>
            <div class="text-3xl font-bold uppercase">Delivery Note</div>
        </header>
        <div class="flex items-center justify-between my-8">
          <div class="space-y-2">
            <h1 class="font-bold text-lg">Customer</h1>
            <p class="text-slate-600 capitalize"><?php echo $data['header']->customer_name;?></p>
            <p class="text-slate-600 capitalize"><?php echo $data['header']->contact; ?></p>
            <p class="text-slate-600 uppercase"><?php echo $data['header']->pin; ?></p>
          </div>
          <div class="w-56 space-y-1">
            <div class="grid grid-cols-2 items-center gap-4">
                <p class="font-semibold">Invoice No:</p>
                <p><?php echo $data['header']->invoice_no; ?></p>
            </div>
            <div class="grid grid-cols-2 items-center gap-4">
                <p class="font-semibold">Invoice Date:</p>
                <p><?php echo $data['header']->invoice_date; ?></p>
            </div>
          </div>
        </div>
        <table class="table-cm">
          <thead>
            <tr>
              <th>Product</th>
              <th class="w-32">Qty</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['items'] as $item) : ?>
              <tr>
                <td><?php echo strtoupper($item->product_name); ?></td>
                <td><?php echo $item->qty; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script>
    document.querySelector('#print').addEventListener('click', function() {
      let printContent = document.getElementById('print_area').innerHTML;
      print(printContent)
    });
    document.querySelector('#print-dnote').addEventListener('click', function() {
      let printContent = document.getElementById('delivery').innerHTML;
      print(printContent)

    });
    function print(printContent){
      let printWindow = window.open('', '', 'height=600,width=800');
      printWindow.document.write('<html><head><title>&space;</title>');
      printWindow.document.write('<link href="<?php echo URLROOT;?>/css/output.css" rel="stylesheet">'); 
      printWindow.document.write('</head><body >');
      printWindow.document.write(printContent);
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
    }
  </script>
</body>
</html>  