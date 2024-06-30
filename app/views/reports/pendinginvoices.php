<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-4">
    <section class="content-header px-0">
      
    </section>
    <section class="content">
        <div class="row mt-8" id="table-area">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="pending-invoices-table" class="table table-sm table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Invoice Date</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>Invoice Value</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['invoices'] as $invoice) : ?>
                                <tr>
                                    <td><?php echo date('d-m-Y',strtotime($invoice->invoice_date));?></td>
                                    <td><?php echo $invoice->invoice_no;?></td>
                                    <td><?php echo strtoupper($invoice->customer_name);?></td>
                                    <td><?php echo number_format($invoice->invoice_amount,2);?></td>
                                    <td><?php echo number_format($invoice->balance,2);?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/reports/pending-invoices-report.js"></script>
</body>
</html>  