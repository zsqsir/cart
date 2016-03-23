<?php echo $header; ?><?php echo $column_left; ?>
123456789
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_install) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_install; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> MyCnCart 官方QQ群： 473071696</div>
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $order; ?></div>
      <?php
        foreach($goodslist as $goods)
        {
          echo '<p>'.$goods;
        }
      ?>
      ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>