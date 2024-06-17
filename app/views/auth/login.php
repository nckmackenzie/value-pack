<?php require APPROOT . '/views/inc/header.php';?>
<div class="h-dvh bg-slate-50 flex items-center justify-center ">
    <div class="max-w-lg w-full p-2 mx-4 md:mx-0 space-y-6">
        <?php flash('login_msg');?>
        <?php if(!is_null($data['error'])) : ?>
            <div class="alert custom-destructive">
                <p class="text-sm font-medium"><?php echo $data['error'];?></p>
            </div>
        <?php endif; ?>
        <header class="space-y-1.5">
            <h1 class="text-3xl font-medium">Login</h1>
            <p class="text-sm text-slate-500">Hi,Welcome back👋</p>
        </header>
        <form action="<?php echo URLROOT;?>/auth/login_act" method="post">
            <div class="form-group">
                <label for="user_id">Contact</label>
                <input type="text" name="user_id" class="form-control <?php echo invalid_setter($data['user_id_err']); ?>" 
                       id="user_id" value="<?php echo $data['user_id'];?>">
                <span class="invalid-feedback"><?php echo $data['user_id_err'];?></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control <?php echo invalid_setter($data['password_err']); ?>" 
                       id="password" value="<?php echo $data['password'];?>">
                <span class="invalid-feedback"><?php echo $data['password_err'];?></span>
            </div>
            <div class="form-group">
                <label for="store">Store</label>
                <select name="store" id="store" class="form-control <?php echo invalid_setter($data['store_err']); ?>">
                    <option value="" disabled selected>Select store</option>
                    <?php foreach($data['stores'] as $store): ?>
                        <option value="<?php echo $store->id;?>" <?php selectdCheck($data['store'],$store->id)?>><?php echo $store->store_name;?></option>
                    <?php endforeach;?>
                </select>
                <span class="invalid-feedback"><?php echo $data['store_err'];?></span>
            </div>
            <!-- <div class="flex justify-end mb-4">
                <a href="" class="text-sm text-blue-600 transition-colors hover:text-blue-500">Forgot password?</a>
            </div> -->
            <button class="btn btn-default w-full">Login</button>
        </form>
    </div>
</div>
<!-- jQuery -->
<script src="<?php echo URLROOT;?>/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo URLROOT;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo URLROOT;?>/js/adminlte.min.js"></script>
</body>
</html>
