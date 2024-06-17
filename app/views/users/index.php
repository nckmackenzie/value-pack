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
    <?php DeleteModal(URLROOT .'/users/delete','deleteModal','Are your you want to delete this user?','id'); ?>
    <?php flash('user_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/users/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create new user</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="usersDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="text-left">User Name</th>
            <th scope="col" class="text-left">Contact</th>
            <th scope="col" class="text-left">Role</th>
            <th scope="col" class="text-left">Status</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
           <?php foreach($data['users'] as $user) : ?>
            <tr>
              <td class="text-left uppercase"><?php echo $user->user_name;?></td>
              <td class="uppercase text-left"><?php echo $user->contact;?></td>
              <td class="uppercase text-left"><?php echo $user->role_name;?></td>
              <td class="text-left">
                <div class="uppercase capsule <?php echo !(bool)$user->active ? 'capsule-destructive' : 'capsule-success';?>">
                  <?php echo (bool)$user->active ? 'Active' : 'Deactivated';?>
                </div>
              </td>
              <td class="flex items-center gap-2">
                <?php action_buttons("edit","users",$user->id);?>
                <?php if((bool)$user->active) : ?>
                  <a href="<?php echo URLROOT;?>/users/reset-password/<?php echo $user->id;?>" class='group'>
                      <span class='text-sky-400 transition-colors font-medium text-xs group-hover:text-sky-300'>Reset Password</span>
                  </a>
                <?php endif; ?>
                <?php action_buttons("delete","",$user->id);?>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/users/user.js"></script>
</body>
</html>  