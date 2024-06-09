<?php
    $con = new Database;
    // $menuitems = getusermenuitems($con->dbh,(int)$_SESSION['userId']);
    $menuitems = getusermenuitems($con->dbh,$_SESSION['user_id']);
    $menuicons = [
        'admin' => 'shield-check',
        'master entry' => 'cog',
        'transactions' => 'workflow',
        'reports' => 'file-text',
    ];
?>
<aside class="main-sidebar bg-slate-50 text-slate-700 shadow">
    <a href="<?php echo URLROOT;?>/dashboard" class="flex justify-center items-center h-12">
        <span class="text-blue-600 font-bold">VALUE PACK</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar text-sm ">
        
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <?php foreach($menuitems as $menuitem) :?>
                    <li class="nav-item">
                        <a href="#" class="nav-link flex items-center transition-all hover:bg-gray-200/50">
                            <i data-lucide="<?php echo $menuicons[$menuitem];?>" class="size-4 text-gray-400 mr-2"></i>
                            <p class="capitalize text-xs font-medium">
                                <?php echo $menuitem ;?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview custom-font">
                            <!-- $navitems = getmodulemenuitems($con->dbh,(int)$_SESSION['userId'],$menuitem)  -->
                            <?php $navitems = getmodulemenuitems($con->dbh,$_SESSION['user_id'],$menuitem) ;?>
                            <?php foreach($navitems as $navitem) : ?>
                                <li class="nav-item transition-all hover:bg-gray-200/50 pl-4">
                                    <a href="<?php echo URLROOT;?>/<?php echo $navitem->path;?>" class="nav-link">
                                        <p class="text-xs"><?php echo ucwords($navitem->form_name);?></p>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach;?>
            </ul>
        </nav>
    </div><!-- /.sidebar -->
</aside>   