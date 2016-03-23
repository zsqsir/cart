<?php

$_['heading_title'] 								= 'SQL Query Executor';

$_['heading_warning'] 								= 'BE SURE TO DO A FULL DATABASE BACKUP BEFORE RUNNING SCRIPTS HERE';
$_['heading_warning2']								= 'If you are installing 3rd-party contributions, note that you do so at your own risk.<br />OpenCart&reg; makes no warranty as to the safety of scripts supplied by 3rd-party contributors. Test before using on your live database!';
$_['heading_warning_installscripts']				= 'NOTE: OpenCart database-upgrade scripts should NOT be run from this page.<br />Please upload the new <strong>zc_install</strong> folder and run the upgrade from there instead for better reliability.';
$_['text_query_results']							= 'Query Results:';
$_['text_enter_query_string']						= 'Enter the query <br />to be executed:&nbsp;&nbsp;<br /><br />Be sure to<br />end with ;';
$_['text_query_filename']							= 'Upload file:';
$_['error_nothing_to_do']							= 'Error: Nothing to do - no query or query-file specified.';
$_['text_close_window']								= '[ close window ]';
$_['text_upload_file']								= 'Upload:';
$_['sqlpatch_help_text']							= 'The SQLPATCH tool lets you install system patches by pasting SQL code directly into the textarea '.
								  'field here, or by uploading a supplied script (.SQL) file.<br />' .
								  'When preparing scripts to be used by this tool, DO NOT include a table prefix, as this tool will ' .
								  'automatically insert the required prefix for the active database, based on settings in the store\'s ' .
								  'admin/config.php file (DB_PREFIX definition).<br /><br />' .
								  'The commands entered or uploaded may only begin with the following statements, and MUST be in UPPERCASE:'.
								  '<br /><ul><li>DROP TABLE IF EXISTS</li><li>CREATE TABLE</li><li>INSERT INTO</li><li>INSERT IGNORE INTO</li><li>ALTER TABLE</li>' .
								  '<li>UPDATE (just a single table)</li><li>UPDATE IGNORE (just a single table)</li><li>DELETE FROM</li><li>DROP INDEX</li><li>CREATE INDEX</li>' .
								  '<br /><li>SELECT </li></ul>' .
	'<h2>Advanced Methods</h2>The following methods can be used to issue more complex statements as necessary:<br />
	To run some blocks of code together so that they are treated as one command by MySQL, you need the "<code>#NEXT_X_ROWS_AS_ONE_COMMAND:xxx</code>" value set.  The parser will then treat X number of commands as one.<br />
	If you are running this file thru phpMyAdmin or equivalent, the "#NEXT..." comment is ignored, and the script will process fine.<br />
	<br /><strong>NOTE: </strong>SELECT.... FROM... and LEFT JOIN statements need the "FROM" or "LEFT JOIN" to be on a line by itself in order for the parse script to add the table prefix.<br /><br />
	<em><strong>Examples:</strong></em>
	<ul><li><code>#NEXT_X_ROWS_AS_ONE_COMMAND:4<br />
	SET @t1=0;<br />
	SELECT (@t1:=configuration_value) as t1 <br />
	FROM configuration <br />
	WHERE configuration_key = \'KEY_NAME_HERE\';<br />
	UPDATE product_type_layout SET configuration_value = @t1 WHERE configuration_key = \'KEY_NAME_TO_CHECK_HERE\';<br />
	DELETE FROM configuration WHERE configuration_key = \'KEY_NAME_HERE\';<br />&nbsp;</li>

	<li>#NEXT_X_ROWS_AS_ONE_COMMAND:1<br />
	INSERT INTO tablename <br />
	(col1, col2, col3, col4)<br />
	SELECT col_a, col_b, col_3, col_4<br />
	FROM table2;<br />&nbsp;</li>

	<li>#NEXT_X_ROWS_AS_ONE_COMMAND:1<br />
	INSERT INTO table1 <br />
	(col1, col2, col3, col4 )<br />
	SELECT p.othercol_a, p.othercol_b, po.othercol_c, pm.othercol_d<br />
	FROM table2 p, table3 pm<br />
	LEFT JOIN othercol_f po<br />
	ON p.othercol_f = po.othercol_f<br />
	WHERE p.othercol_f = pm.othercol_f;</li>
	</ul></code>';
	
$_['reason_table_already_exists']						= 'Cannot create table %s because it already exists';
$_['reason_table_doesnt_exist'] 						= 'Cannot drop table %s because it does not exist.';
$_['reason_table_not_found']							= 'Cannot execute because table %s does not exist.';
$_['reason_config_key_already_exists']					= 'Cannot insert configuration_key "%s" because it already exists';
$_['reason_column_already_exists']						= 'Cannot ADD column %s because it already exists.';
$_['reason_column_doesnt_exist_to_drop']				= 'Cannot DROP column %s because it does not exist.';
$_['reason_column_doesnt_exist_to_change']				= 'Cannot CHANGE column %s because it does not exist.';
$_['reason_product_type_layout_key_already_exists']		= 'Cannot insert prod-type-layout configuration_key "%s" because it already exists';
$_['reason_index_doesnt_exist_to_drop']					= 'Cannot drop index %s on table %s because it does not exist.';
$_['reason_primary_key_doesnt_exist_to_drop']			= 'Cannot drop primary key on table %s because it does not exist.';
$_['reason_index_already_exists']						= 'Cannot add index %s to table %s because it already exists.';
$_['reason_primary_key_already_exists']					= 'Cannot add primary key to table %s because a primary key already exists.';
$_['reason_no_privileges']								= 'User {DB_USERNAME}@{DB_HOSTNAME} does not have %s privileges to database '.DB_DATABASE.'.';

$_['entry_query_success'] = '%s statements processed.';
$_['entry_query_error']	  = 'Failed: %s';
$_['entry_query_ignored'] = 'Note: %s statements ignored. See "upgrade_exceptions" table for additional details.';
$_['entry_query_output']  = 'INFO: %s';
$_['entry_query_queries_success'] = '%s statements processed.';
$_['entry_query_queries_failed'] = 'Failed: %s';
$_['entry_empty_sql_statement'] = 'Empty SQL Statement';

$_['button_send'] 		= 'Send';
$_['button_upload'] 	= 'Upload';
?>