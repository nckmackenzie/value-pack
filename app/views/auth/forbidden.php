<?php require APPROOT . '/views/inc/header.php';?>
<?php require APPROOT . '/views/inc/top-nav.php';?>
<?php require APPROOT . '/views/inc/side-nav.php';?>
  <div class="content-wrapper px-4 flex items-center justify-center">
    <div class="max-w-xl w-full bg-slate-50 rounded-md px-6 py-4 flex flex-col space-y-6 items-center">
            <header>
                <h1 class="text-lg font-bold text-center">No Permission</h1>
                <p class="text-sm text-slate-400">Sorry, you don&apos; have permission to access this page.</p>
            </header>
            <img src="<?php echo URLROOT;?>/img/forbidden.svg" alt="Forbidden illustrator" class="size-80">
            <a href="<?php echo URLROOT;?>/dashboard" class="btn btn-outline"><i data-lucide="home" class="icon text-slate-400 mr-2"></i><span>Go back home</span></a>
        </div>
    </div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
</body>
</html>  