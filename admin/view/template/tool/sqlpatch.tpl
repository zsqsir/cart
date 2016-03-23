<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/backup.png" alt="" /> <?php echo $heading_title; ?></h1>      
    </div>
    <div class="content">
      <form name="getquery" action="<?php echo $execute; ?>" method="post" id="getquery">
        <table class="form">		  
			<tr>
				<td><?php echo $heading_warning; ?></td>
			</tr>
		    <tr>
				<td class="alert"><strong><?php echo $heading_warning2; ?></strong></td>
			</tr>
	    </table>
	    <table class="form">          
		  <tr>
			  <td><?php echo $text_enter_query_string; ?></td>
			  <td><textarea name="query_string" wrap="soft" cols="80%" rows="10" id="sqlpatchKeyedQuery" class="sqlpatchKeyedQuery"></textarea></td>
		  </tr>
		  <tr>
			  <td colspan="2" align="right"><input type="submit" name="submit" value="<?php echo $button_send; ?>" class="button" /></td>			  
		  </tr>
        </table>
      </form>	  
    </div>
  </div>
</div>
<?php echo $footer; ?>