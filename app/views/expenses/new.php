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
              <li class="breadcrumb-item text-sm"><a href="<?php echo URLROOT;?>/expenses">Expenses</a></li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content bg-white p-4">
        <form action="<?php echo URLROOT;?>/expenses/create_update" method="post">
            <div class="col-md-6 mx-auto">
                <div class="alert"></div>
                <?php flash('expense_msg');?>
                <?php if(!is_null($data['errors']) && count($data['errors']) > 0) : ?>
                    <div class="alert custom-destructive">
                        <?php foreach($data['errors'] as $error) : ?>
                            <p class="text-sm font-medium text-rose-900">👉 <?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="expense_date">Expense Date</label>
                        <input type="date" name="expense_date" id="expense_date" 
                               class="form-control mandatory <?php echo invalid_setter($data['expense_date_err']);?>"
                               value="<?php echo $data['expense_date']; ?>">
                        <span class="invalid-feedback"><?php echo $data['expense_date_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account_id">Expense Account</label>
                        <select name="account_id" id="account_id" class="form-control mandatory <?php echo invalid_setter($data['account_id_err']);?>">
                            <option value="" disabled selected>Select account</option>
                            <?php foreach($data['accounts'] as $account) : ?>
                                <option value="<?php echo $account->id;?>" <?php selectdCheck($data['account_id'],$account->id) ?>><?php echo strtoupper($account->account_name);?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['account_id_err'];?></span>
                    </div>
                </div>                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="amount">Expense Amount</label>
                        <input type="text" name="amount" id="amount" 
                               class="form-control mandatory <?php echo invalid_setter($data['amount_err']);?>"
                               value="<?php echo $data['amount']; ?>">
                        <span class="invalid-feedback"><?php echo $data['amount_err'];?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks">Expense remark</label>
                        <input type="text" name="remarks" id="remarks" 
                               class="form-control"
                               value="<?php echo $data['remarks']; ?>">
                    </div>
                </div>
            </div>
            <div class="row mt-8">
                <div class="col-12">
                    <button type="submit" class="btn btn-default">Save</button>
                    <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                    <input type="hidden" name="is_edit" value="<?php echo $data['is_edit'];?>">
                </div>
            </div>
        </form>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/expenses/expense.js"></script>
</body>
</html>  