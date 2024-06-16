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
              <li class="breadcrumb-item text-sm">Admin</li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <?php DeleteModal(URLROOT .'/roles/delete','deleteModal','Are your you want to delete this role?','id'); ?>
    <?php flash('role_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/roles/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create new role</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="rolesDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="text-left">Role Name</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
           <?php foreach($data['roles'] as $role) : ?>
            <tr>
              <td class="text-left uppercase"><?php echo $role->role_name;?></td>
              <td class="flex items-center gap-2">
                <?php action_buttons("edit","roles",$role->id);?>
                <?php action_buttons("delete","",$role->id);?>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/roles/role.js"></script>
</body>
</html>  