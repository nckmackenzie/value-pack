<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-4">
    <section class="content-header px-0">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Expense Accounts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item text-sm">Master Entry</li>
              <li class="breadcrumb-item text-sm active">Expense Accounts</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <?php DeleteModal(URLROOT .'/expenseaccounts/delete','deleteModal','Are your you want to delete this expenseaccount?','id'); ?>
    <section class="content space-y-6">
        <a href="<?php echo URLROOT;?>/expenseaccounts/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
            </i><span>Create New expense account</span>
        </a>
        <!-- <div class="table-container"> -->
          <table class="display" style="width:100%" id="expenseaccountsDatatable">
            <thead class="">
              <tr>
                <th scope="col" class="">
                    Expense Account Name
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
              <?php foreach($data['accounts'] as $account) : ?>
                <tr class="">
                    <td class="capitalize "><?php echo $account->account_name;?></td>
                    <td class=""><div class="capsule <?php echo (bool)$account->active ? 'capsule-success' : 'capsule-destructive';?>"><?php echo $account->active ? 'Active' : 'Inactive';?></div></td>
                    <td class="flex items-center gap-2">
                      <?php action_buttons("edit","expenseaccounts",$account->id);?>
                      <?php action_buttons("delete","",$account->id);?>
                    </td>
                </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        <!-- </div> -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/expense-accounts/accounts.js"></script>
</body>
</html>  