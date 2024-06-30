<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-4">
    <section class="content-header px-0">
      
    </section>
    <section class="content">
        <div class="col-md-6">
            <div class="alert-box"></div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control mandatory">
                    <span class="invalid-feedback"></span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control mandatory">
                    <span class="invalid-feedback"></span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="report_type">Report Type</label>
                    <select name="report_type" id="report_type" class="form-control mandatory">
                        <option value="" selected disabled>Select report type</option>
                        <option value="summary">Summary</option>
                        <option value="detailed">Detailed</option>
                    </select>
                    <span class="invalid-feedback"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <button class="btn btn-default preview">Preview</button>
            </div>
        </div>
        <div class="row mt-8">
            <div class="col-md-12">
                <div class="table-responsive" id="table-area"></div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/reports/sales-report.js"></script>
</body>
</html>  