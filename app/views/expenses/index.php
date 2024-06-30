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
    <?php DeleteModal(URLROOT .'/expenses/delete','deleteModal','Are your you want to delete this expense?','id'); ?>
    <?php flash('expense_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/expenses/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create new expense</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="expensesDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="">Expense Date</th>
            <th scope="col" class="text-left">Expense Account</th>
            <th scope="col" class="text-left">Expense Amount</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data['expenses'] as $expense) : ?>
            <tr>
              <td><?php echo date('d-m-Y',strtotime($expense->expense_date));?></td>
              <td class="text-left uppercase"><?php echo $expense->expense_account;?></td>
              <td class="text-left"><div class="capsule capsule-info"><?php echo number_format($expense->amount,2);?></div></td>
              <td class="flex items-center gap-2">
                <?php action_buttons("edit","expenses",$expense->id);?>
                <?php action_buttons("delete","",$expense->id);?>
              </td> 
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/expenses/expense.js"></script>
</body>
</html>  