<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-4 flex items-center justify-center">
    <div class="max-w-xl w-full bg-slate-50 rounded-md px-6 py-4 flex flex-col space-y-6 items-center">
            <header>
                <h1 class="text-lg font-bold text-center">Not Found</h1>
                <p class="text-sm text-slate-400"><?php echo $data['message']; ?></p>
            </header>
            <img src="<?php echo URLROOT;?>/img/not-found.svg" alt="Forbidden illustrator" class="w-48 h-48">
            <a href="<?php echo URLROOT . $data['path'];?>" class="btn btn-outline"><i data-lucide="move-left" class="icon text-slate-400 mr-2"></i><span>Go back</span></a>
        </div>
    </div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
</body>
</html>  