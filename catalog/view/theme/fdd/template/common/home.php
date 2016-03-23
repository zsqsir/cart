<?php
echo $header;
echo "<a target=\"_blank\" href=\"index.php?route=common/home2\" name=\"mydd_7\" dd_name=\"星星自出版\">星星吕自出版</a>";
exit;
?>

<div class="container">
  1324564789xxxxx
  afagaghah
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?><?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>