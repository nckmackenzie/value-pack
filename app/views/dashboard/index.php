<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-6">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stock Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Transactions</a></li>
              <li class="breadcrumb-item active">Stock Report</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
        <div class="card col-md-8 mx-auto">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">As Of</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Store</label>
                            <select name="" id="" class="form-control">
                                <option value="">Main Store</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-stripped table-sm">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Opening Bal</th>
                            <th>Receipts</th>
                            <th>Sales</th>
                            <th>Transfers</th>
                            <th>Wastages</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>500Ml Water Bottle</td>
                        <td>10</td>
                        <td>-</td>
                        <td>8</td>
                        <td>-</td>
                        <td>-</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>Value Pack 500Ml</td>
                        <td>126</td>
                        <td>-</td>
                        <td>36</td>
                        <td>41</td>
                        <td>-</td>
                        <td>49</td>
                    </tr>
                </tbody>
                <tfoot>
                <tr>
                        <td>Total</td>
                        <td>136</td>
                        <td>-</td>
                        <td>44</td>
                        <td>41</td>
                        <td>-</td>
                        <td>51</td>
                    </tr>
                </tfoot>
                </table>
                
            </div>                    
            </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
</body>
</html>  