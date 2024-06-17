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
              <li class="breadcrumb-item text-sm">Master Entry</li>
              <li class="breadcrumb-item text-sm active"><?php echo $data['title'];?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <?php DeleteModal(URLROOT .'/products/delete','deleteModal','Are your you want to delete this product?','id'); ?>
    <?php flash('product_msg'); ?>
    <section class="content space-y-6">
      <a href="<?php echo URLROOT;?>/products/new" class="btn btn-info"><i data-lucide="plus" class="icon mr-1.5 text-slate-600">
        </i><span>Create New Product</span>
      </a>
      <div class="col-12 table-responsive">
      <table class="display" style="width:100%" id="productsDatatable">
        <thead class="">
          <tr>
            <th scope="col" class="">Product Name</th>
            <th scope="col" class="">Product Code</th>
            <th scope="col" class="">Selling Price</th>
            <th scope="col" class="">Available Stock</th>
            <th scope="col" class="">Status</th>
            <th scope="col" class="">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data['products'] as $product) : ?>
            <tr class="">
              <td class="capitalize"><?php echo $product->product_name;?></td>
              <td class="uppercase"><?php echo $product->product_code;?></td>
              <td><?php echo number_format($product->selling_price,2);?></td>
              <td>
                <?php if((bool)$product->is_stock_item === false) : ?>
                  <div class="capsule capsule-info">Non stock item</div>
                <?php else : ?>
                  <div class="capsule <?php echo floatval($product->current_stock) >= floatval($product->reorder_level) ? 'capsule-success' : 'capsule-destructive' ;?>"><?php echo number_format($product->current_stock,2);?></div>
                <?php endif; ?>
              </td>
              <td class=""><div class="capsule <?php echo (bool)$product->active ? 'capsule-success' : 'capsule-destructive';?>"><?php echo $product->active ? 'Active' : 'Inactive';?></div></td>
              <td class="flex items-center gap-2">
                <?php action_buttons("edit","products",$product->id);?>
                <?php action_buttons("delete","",$product->id);?>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php require APPROOT . '/views/inc/footer.php'?>
<script type="module" src="<?php echo URLROOT;?>/js/pages/products/products.js"></script>
</body>
</html>  