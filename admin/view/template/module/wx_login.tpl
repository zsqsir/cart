<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-wx-login" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error['error_warning'])) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error_warning']; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-wx-login" class="form-horizontal">
        
          <div class="alert alert-danger"><i class="fa fa-info-circle"></i> <?php echo $text_weixin_open_signup; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
                
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="entry-appid"><?php echo $entry_appid; ?></label>
            <div class="col-sm-10">
              <input type="text" name="wx_login_appid" value="<?php echo $wx_login_appid; ?>" placeholder="<?php echo $entry_appid; ?>" id="entry-appid" class="form-control"/>
              <?php if ($error_appid) { ?>
              <div class="text-danger"><?php echo $error_appid; ?></div>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="entry-appsecret"><?php echo $entry_appsecret; ?></label>
            <div class="col-sm-10">
              <input type="text" name="wx_login_appsecret" value="<?php echo $wx_login_appsecret; ?>" placeholder="<?php echo $entry_appsecret; ?>" id="entry-appsecret" class="form-control"/>
              <?php if ($error_appsecret) { ?>
              <div class="text-danger"><?php echo $error_appsecret; ?></div>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="wx_login_status" id="input-status" class="form-control">
                <?php if ($wx_login_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>