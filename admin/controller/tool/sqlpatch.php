<?php
class ControllerToolSqlpatch extends Controller {
	public function index() {
		$this->load->language('tool/sqlpatch');
    	
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] 								= $this->language->get('heading_title');		
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('tool/sqlpatch', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
			
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];			
			unset($this->session->data['success']);
			
		} else {
			$this->data['success'] = '';
		}
		
		// DON'T TOUCH THE LINEBREAK !
		$this->session->data['linebreak'] = '
		';
	
		$this->session->data['table_upgrade_exceptions'] = DB_PREFIX . 'upgrade_exceptions';		
		
		$this->session->data['heading_warning_installscripts'] 						= $this->language->get('heading_warning_installscripts');
		$this->session->data['text_query_results'] 									= $this->language->get('text_query_results');		
		$this->session->data['text_query_filename']									= $this->language->get('text_query_filename');
		$this->session->data['error_nothing_to_do']									= $this->language->get('error_nothing_to_do');
		$this->session->data['text_close_window']									= $this->language->get('text_close_window');
		$this->session->data['qlpatch_help_text'] 									= $this->language->get('sqlpatch_help_text');	  
	    $this->session->data['reason_table_already_exists']							= $this->language->get('reason_table_already_exists');
	    $this->session->data['reason_table_doesnt_exist'] 							= $this->language->get('reason_table_doesnt_exist');
	    $this->session->data['reason_table_not_found'] 								= $this->language->get('reason_table_not_found');
	    $this->session->data['reason_config_key_already_exists']					= $this->language->get('reason_config_key_already_exists');
	    $this->session->data['reason_column_already_exists']						= $this->language->get('reason_column_already_exists');
	    $this->session->data['reason_column_doesnt_exist_to_drop']					= $this->language->get('reason_column_doesnt_exist_to_drop');
	    $this->session->data['reason_column_doesnt_exist_to_change']				= $this->language->get('reason_column_doesnt_exist_to_change');
	    $this->session->data['reason_product_type_layout_key_already_exists'] 		= $this->language->get('reason_product_type_layout_key_already_exists');
	    $this->session->data['reason_index_doesnt_exist_to_drop']					= $this->language->get('reason_index_doesnt_exist_to_drop');
	    $this->session->data['reason_primary_key_doesnt_exist_to_drop']				= $this->language->get('reason_primary_key_doesnt_exist_to_drop');
	    $this->session->data['reason_index_already_exists']							= $this->language->get('reason_index_already_exists');
	    $this->session->data['reason_primary_key_already_exists']					= $this->language->get('reason_primary_key_already_exists');
	    $this->session->data['reason_no_privileges']								= str_replace(array("{DB_USERNAME}", "{DB_HOSTNAME}", "{DB_DATABASE}"), array(DB_USERNAME, DB_HOSTNAME, DB_DATABASE), $this->language->get('reason_no_privileges'));
		$this->session->data['entry_empty_sql_statement']							= $this->language->get('entry_empty_sql_statement');
		
		$this->data['text_enter_query_string']						= $this->language->get('text_enter_query_string');
		$this->data['text_upload_file']								= $this->language->get('text_upload_file');		
		
		$this->data['heading_warning']								= $this->language->get('heading_warning');
		$this->data['heading_warning2']								= $this->language->get('heading_warning2');
		
		$this->data['button_send']									= $this->language->get('button_send');
		$this->data['button_upload']								= $this->language->get('button_upload');
		
		$this->data['execute'] = $this->url->link('tool/sqlpatch/execute', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['uploadquery'] = $this->url->link('tool/sqlpatch/uploadquery', 'token=' . $this->session->data['token'], 'SSL');		
		
		$this->template = 'tool/sqlpatch.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function execute() {		
		$this->load->language('tool/sqlpatch');
		
		if (isset($this->request->post['query_string']) && $this->request->post['query_string'] != '') {
		    $query_string = $this->request->post['query_string'];
				
			if (version_compare(PHP_VERSION, 5.4, '<') && @get_magic_quotes_gpc() > 0) {
				$query_string = stripslashes($query_string);						  
			}				
		    $query_string = explode($this->session->data['linebreak'], ($query_string));
		    $query_results = $this->executeSql($query_string, DB_DATABASE, DB_PREFIX);
				
		    if ($query_results['queries'] > 0 && $query_results['queries'] != $query_results['ignored']) {
				$this->session->data['success'] = sprintf($this->language->get('entry_query_success'), $query_results['queries']);
				
			} else {				
				$this->session->data['error'] = sprintf($this->language->get('entry_query_error'), $query_results['queries']);
			}
			
			if (empty($query_results['errors'])) {
			    foreach ($query_results['errors'] as $value) {
					$this->session->data['error'][] = sprintf($this->language->get('entry_query_error'), $value);
				}
			}
			
			if ($query_results['ignored'] != 0) {
				$this->session->data['success'] = sprintf($this->language->get('entry_query_ignored'), $query_results['ignored']);				    
			}
			
			if (empty($query_results['output'])) {
			    foreach ($query_results['output'] as $value) {
			        if (empty($value)) {
						$this->session->data['error'][] = sprintf($this->language->get('entry_query_output'), $value);							
				    }
				}
			}
				  
		} else {
			$this->session->data['error'] = $this->session->data['error_nothing_to_do'];
		}
		
		$this->redirect($this->url->link('tool/sqlpatch', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function uploadquery() {
		$this->load->language('tool/sqlpatch');

		$query_string  = '';
			
		if (isset($this->request->files['sql_file']) && isset($this->request->files['sql_file']['tmp_name']) && $this->request->files['sql_file']['tmp_name'] != '') {
			$upload_query = file($this->request->files['sql_file']['tmp_name']);
			$query_string  = $upload_query;
		}
			
		if (version_compare(PHP_VERSION, 5.4, '<') && @get_magic_quotes_runtime() > 0) {
			$query_string  = $this->db->escape($upload_query);
		}
			
		if ($query_string != '') {
			$query_results = $this->executeSql($query_string, DB_DATABASE, DB_PREFIX);
			if ($query_results['queries'] > 0 && $query_results['queries'] != $query_results['ignored']) {
				$this->session->data['success'] = sprintf($this->language->get('entry_query_queries_success'), $query_results['queries']);					
				
			} else {
				$this->session->data['error'] = sprintf($this->language->get('entry_query_queries_failed'), $query_results['queries']);					
			}
			
			if (empty($query_results['errors'])) {
				foreach ($query_results['errors'] as $value) {
					$this->session->data['error'][] = sprintf($this->language->get('entry_query_error'), $value);
				}
			}
			
			if ($query_results['ignored'] != 0) {
				$this->session->data['error'] = sprintf($this->language->get('entry_query_ignored'), $query_results['ignored']);
			}
				
			if (empty($query_results['output'])) {
				foreach ($query_results['output'] as $value) {
					if (empty($value)) $this->session->data['error'][] = sprintf($this->language->get('entry_query_error'), $value);
				}
			}
				
		} else {
			$this->session->data['error'] = $this->session->data['error_nothing_to_do'];
		}
	}

 private function executeSql($lines, $database, $table_prefix = '') {
   if (version_compare(PHP_VERSION, 5.4, '>=') || !get_cfg_var('safe_mode')) {
     @set_time_limit(1200);
   }
   
   $keepslashes = (isset($this->request->get['keepslashes']) && ((int)$this->request->get['keepslashes'] == 1 || $this->request->get['keepslashes'] == 'true')) ? true : false;	  
   
   $sql_file='SQLPATCH';
   $newline = '';
   $saveline = '';
   $ignored_count=0;
   $return_output=array();
   $errors = array();
   $results = 0;
   $string = '';
   $output = '';
   $lines_to_keep_together_counter = 0;
   $ignore_line = false;

   foreach ($lines as $line) {
     $line = trim($line);
     $line = str_replace('`','',$line);
     $line = $saveline . $line;
     $keep_together = 1;	 
     
     $param=explode(" ",(substr($line,-1)==';') ? substr($line,0,strlen($line)-1) : $line);
      
      if (substr($line,0,28) == '#NEXT_X_ROWS_AS_ONE_COMMAND:') $keep_together = substr($line,28);
      if (substr($line,0,1) != '#' && substr($line,0,1) != '-' && $line != '') {

          $line_upper=strtoupper($line);
          switch (true) {
          case (substr($line_upper, 0, 21) == 'DROP TABLE IF EXISTS '):
            $line = 'DROP TABLE IF EXISTS ' . $table_prefix . substr($line, 21);
            break;
          case (substr($line_upper, 0, 11) == 'DROP TABLE ' && $param[2] != 'IF'):
            if (!$checkprivs = $this->oc_check_database_privs('DROP')) $result = sprintf($this->session->data['reason_no_privileges'], 'DROP');
            if (!$this->oc_table_exists($param[2]) || empty($result)) {
              $this->oc_write_to_upgrade_exceptions_table($line, (empty($result) ? $result : sprintf($this->session->data['reason_table_doesnt_exist'], $param[2])), $sql_file);
              $ignore_line=true;
              $result=(empty($result) ? $result : sprintf($this->session->data['reason_table_doesnt_exist'], $param[2]));
              break;
            } else {
              $line = 'DROP TABLE ' . $table_prefix . substr($line, 11);
            }
            break;
          case (substr($line_upper, 0, 13) == 'CREATE TABLE '):            
            $table = (strtoupper($param[2].' '.$param[3].' '.$param[4]) == 'IF NOT EXISTS') ? $param[5] : $param[2];
            $result = $this->oc_table_exists($table);
            if ($result==true) {
              $this->oc_write_to_upgrade_exceptions_table($line, sprintf($this->session->data['reason_table_already_exists'], $table), $sql_file);
              $ignore_line=true;
              $result=sprintf($this->session->data['reason_table_already_exists'], $table);
              break;
            } else {
              $line = (strtoupper($param[2].' '.$param[3].' '.$param[4]) == 'IF NOT EXISTS') ? 'CREATE TABLE IF NOT EXISTS ' . $table_prefix . substr($line, 27) : 'CREATE TABLE ' . $table_prefix . substr($line, 13);
            }
            break;
          case (substr($line_upper, 0, 15) == 'TRUNCATE TABLE '):            
            if (!$tbl_exists = $this->oc_table_exists($param[2])) {
              $result = sprintf($this->session->data['reason_table_not_found'], $param[2]).' CHECK PREFIXES!' . $param[2];
              $this->oc_write_to_upgrade_exceptions_table($line, $result, $sql_file);
              $ignore_line=true;
              break;
            } else {
              $line = 'TRUNCATE TABLE ' . $table_prefix . substr($line, 15);
            }
            break;
          case (substr($line_upper, 0, 13) == 'REPLACE INTO '):            
            if (!$tbl_exists = $this->oc_table_exists($param[2])) $result=sprintf($this->session->data['reason_table_not_found'], $param[2]).' CHECK PREFIXES!';            
            if (($param[2]=='setting'       && ($result = $this->oc_check_config_key($line))) or
                (!$tbl_exists)    ) {
              $this->oc_write_to_upgrade_exceptions_table($line, $result, $sql_file);
              $ignore_line=true;
              break;
            } else {
              $line = 'REPLACE INTO ' . $table_prefix . substr($line, 13);
            }
            break;
          case (substr($line_upper, 0, 12) == 'INSERT INTO '):            
            if (!$tbl_exists = $this->oc_table_exists($param[2])) $result=sprintf($this->session->data['reason_table_not_found'], $param[2]).' CHECK PREFIXES!';            
            if (($param[2]=='setting'       && ($result=$this->oc_check_config_key($line))) or
                (!$tbl_exists)    ) {
              $this->oc_write_to_upgrade_exceptions_table($line, $result, $sql_file);
              $ignore_line=true;
              break;
            } else {
              $line = 'INSERT INTO ' . $table_prefix . substr($line, 12);
            }
            break;
          case (substr($line_upper, 0, 19) == 'INSERT IGNORE INTO '):            
            if (!$tbl_exists = $this->oc_table_exists($param[3])) {
			  $result=sprintf($this->session->data['reason_table_not_found'], $param[3]).' CHECK PREFIXES!';
              $this->oc_write_to_upgrade_exceptions_table($line, $result, $sql_file);
              $ignore_line=true;
              break;
            } else {
              $line = 'INSERT IGNORE INTO ' . $table_prefix . substr($line, 19);
            }
            break;
          case (substr($line_upper, 0, 12) == 'ALTER TABLE '):            
            if ($result = $this->oc_check_alter_command($param)) {
              $this->oc_write_to_upgrade_exceptions_table($line, $result, $sql_file);
              $ignore_line=true;
              break;
            } else {
              $line = 'ALTER TABLE ' . $table_prefix . substr($line, 12);
            }
            break;
          case (substr($line_upper, 0, 13) == 'RENAME TABLE '):            
            if (DB_PREFIX == '') {
              $this->oc_write_to_upgrade_exceptions_table($line, 'RENAME TABLE command not supported by upgrader. Please use phpMyAdmin instead.', $sql_file);
              $messageStack->add('RENAME TABLE command not supported by upgrader. Please use phpMyAdmin instead.', 'caution');

              $ignore_line=true;
            }
            break;
          case (substr($line_upper, 0, 7) == 'UPDATE '):            
            if (!$tbl_exists = $this->oc_table_exists($param[1])) {
              $this->oc_write_to_upgrade_exceptions_table($line, sprintf($this->session->data['reason_table_not_found'], $param[1]).' CHECK PREFIXES!', $sql_file);
              $result=sprintf($this->session->data['reason_table_not_found'], $param[1]).' CHECK PREFIXES!';
              $ignore_line=true;
              break;
            } else {			  
              $line = 'UPDATE ' . $table_prefix . substr($line, 7);			  
            }
            break;
          case (substr($line_upper, 0, 14) == 'UPDATE IGNORE '):            
            if (!$tbl_exists = $this->oc_table_exists($param[2])) {
              $this->oc_write_to_upgrade_exceptions_table($line, sprintf($this->session->data['reason_table_not_found'], $param[2]).' CHECK PREFIXES!', $sql_file);
              $result=sprintf($this->session->data['reason_table_not_found'], $param[2]).' CHECK PREFIXES!';
              $ignore_line=true;
              break;
            } else {
              $line = 'UPDATE IGNORE ' . $table_prefix . substr($line, 14);
            }
            break;
         case (substr($line_upper, 0, 12) == 'DELETE FROM '):
            $line = 'DELETE FROM ' . $table_prefix . substr($line, 12);
            break;
          case (substr($line_upper, 0, 11) == 'DROP INDEX '):            
            if ($result = $this->oc_drop_index_command($param)) {
              $this->oc_write_to_upgrade_exceptions_table($line, $result, $sql_file);
              $ignore_line=true;
              break;
            } else {
              $line = 'DROP INDEX ' . $param[2] . ' ON ' . $table_prefix . $param[4];
            }
            break;
          case (substr($line_upper, 0, 13) == 'CREATE INDEX ' || (strtoupper($param[0])=='CREATE' && strtoupper($param[2])=='INDEX')):            
            if ($result = $this->oc_create_index_command($param)) {
              $this->oc_write_to_upgrade_exceptions_table($line, $result, $sql_file);
              $ignore_line=true;
              break;
            } else {
              if (strtoupper($param[1])=='INDEX') {
                $line = trim('CREATE INDEX ' . $param[2] .' ON '. $table_prefix . implode(' ',array($param[4],$param[5],$param[6],$param[7],$param[8],$param[9],$param[10],$param[11],$param[12],$param[13])) ).';';
              } else {
                $line = trim('CREATE '. $param[1] .' INDEX ' .$param[3]. ' ON '. $table_prefix . implode(' ',array($param[5],$param[6],$param[7],$param[8],$param[9],$param[10],$param[11],$param[12],$param[13])) );
              }
            }
            break;
          case (substr($line_upper, 0, 7) == 'SELECT ' && substr_count($line,'FROM ')>0):
            $line = str_replace('FROM ','FROM '. $table_prefix, $line);
            break;
          case (substr($line_upper, 0, 10) == 'LEFT JOIN '):
            $line = 'LEFT JOIN ' . $table_prefix . substr($line, 10);
            break;
          case (substr($line_upper, 0, 5) == 'FROM '):
            if (substr_count($line,',')>0) {
              $tbl_list = explode(',',substr($line,5));
              $line = 'FROM ';
              foreach($tbl_list as $val) {
                $line .= $table_prefix . trim($val) . ',';
              }
              if (substr($line,-1)==',') $line = substr($line,0,(strlen($line)-1));
            } else {
              $line = str_replace('FROM ', 'FROM '.$table_prefix, $line);
            }
            break;
          default:
            break;
          } 

        $newline .= $line . ' ';

        if ( substr($line,-1) ==  ';') {          
          if (substr($newline,-1)==' ') $newline = substr($newline,0,(strlen($newline)-1));
          $lines_to_keep_together_counter++;
          if ($lines_to_keep_together_counter == $keep_together) {
            $complete_line = true;
            $lines_to_keep_together_counter=0;
          } else {
            $complete_line = false;
          }
        }

        if ($complete_line) {          
          if (version_compare(PHP_VERSION, 5.4, '<') && @get_magic_quotes_runtime() > 0  && $keepslashes != true ) $newline=stripslashes($newline);
          if (trim(str_replace(';','',$newline)) != '' && !$ignore_line) $output=$this->db->query($newline);
          $results++;
          $string .= $newline.'<br />';
          $return_output[]=$output;
          if (empty($results)) $errors[]=$results;
          $newline = '';
          $keep_together=1;
          $complete_line = false;
          if ($ignore_line) $ignored_count++;
          $ignore_line=false;

        }

      }
    }
   return array('queries'=> $results, 'string'=>$string, 'output'=>$return_output, 'ignored'=>($ignored_count), 'errors'=>$errors);
  }

  private function oc_table_exists($tablename, $pre_install=false) {    
    $tables = $this->db->query("SHOW TABLES like '" . DB_PREFIX . $tablename . "'");    
    if ($tables->num_rows) {
      return true;
    } else {
      return false;
    }
  }

  private function oc_check_database_privs($priv='',$table='',$show_privs=false) {    
    return true;    
    if (isset($this->request->get['nogrants'])) return true;
    if (isset($this->request->post['nogrants'])) return true;    
    $granted_privs_list='';    
    
    $user = DB_USERNAME."@".DB_HOSTNAME;
    if ($user == 'DB_USERNAME@DB_HOSTNAME' || DB_DATABASE=='DB_DATABASE') return true;
    $sql = "show grants for ".$user;    
    $results = $this->db->query($sql);
    foreach ($results as $result) {
      $grant_syntax = $result['Grants for '.$user] . ' ';
      $granted_privs = str_replace('GRANT ','',$grant_syntax);
      $granted_privs = substr($granted_privs,0,strpos($granted_privs,' TO '));
      $granted_db = str_replace(array('`','\\'),'',substr($granted_privs,strpos($granted_privs,' ON ')+4) );
      $db_priv_ok += ($granted_db == '*.*' || $granted_db==DB_DATABASE.'.*' || $granted_db==DB_DATABASE.'.'.$table) ? true : false;      

      if ($db_priv_ok) {
        $granted_privs = substr($granted_privs,0,strpos($granted_privs,' ON '));
        $granted_privs_list .= ($granted_privs_list=='') ? $granted_privs : ', '.$granted_privs;

        $specific_priv_found = (empty($priv) && substr_count($granted_privs,$priv)==1);        

        if (($specific_priv_found && $db_priv_ok == true) || ($granted_privs == 'ALL PRIVILEGES' && $db_priv_ok==true)) {
          return true;
        }
      }
    }
    if ($show_privs) {      
      return $db_priv_ok . '|||'. $granted_privs_list;
    } else {
    return false;
    }
  }

  private function oc_drop_index_command($param) {
    if (!$checkprivs = $this->oc_check_database_privs('INDEX')) return sprintf($this->session->data['reason_no_privileges'], 'INDEX');
    
    if (!empty($param)) return $this->entry_empty_sql_statement;
    $index = $param[2];
    $sql = "show index from " . DB_PREFIX . $param[4];
    $results = $this->db->query($sql);
    foreach ($results as $result) {      
      if  ($result['Key_name'] == $index) {
        return;
      }      
    }
    
    return sprintf($this->session->data['reason_index_doesnt_exist_to_drop'],$index,$param[4]);
  }

  private function oc_create_index_command($param) {
    
    if (!$checkprivs = $this->oc_check_database_privs('INDEX')) return sprintf($this->session->data['reason_no_privileges'],'INDEX');    
    if (!empty($param)) return $this->session->data['entry_empty_sql_statement'];
    $index = (strtoupper($param[1])=='INDEX') ? $param[2] : $param[3];
    if (in_array('USING',$param)) return 'USING parameter found. Cannot validate syntax. Please run manually in phpMyAdmin.';
    $table = (strtoupper($param[2])=='INDEX' && strtoupper($param[4])=='ON') ? $param[5] : $param[4];
    $sql = "show index from " . DB_PREFIX . $table;
    $results = $this->db->query($sql);
    foreach ($results as $result) {      
      if (strtoupper($result['Key_name']) == strtoupper($index)) {
        return sprintf($this->session->data['reason_index_already_exists'],$index,$table);
      }      
    }
/*
 * @TODO: verify that individual columns exist, by parsing the index_col_name parameters list
 *        Structure is (colname(len)),
 *                  or (colname),
 */
  }

  private function oc_check_alter_command($param) {    
    if (!empty($param)) return $this->session->data['entry_empty_sql_statement'];
    if (!$checkprivs = $this->oc_check_database_privs('ALTER')) return sprintf($this->session->data['reason_no_privileges'], 'ALTER');
    switch (strtoupper($param[3])) {
      case ("ADD"):
        if (strtoupper($param[4]) == 'INDEX') {
          
          $index = $param[5];
          $sql = "show index from " . DB_PREFIX . $param[2];
          $results = $this->db->query($sql);
          foreach ($results as $result) {            
            if  ($result['Key_name'] == $index) {
              return sprintf($this->session->data['reason_index_already_exists'],$index,$param[2]);
            }            
          }
        } elseif (strtoupper($param[4])=='PRIMARY') {
          
          if ($param[5] != 'KEY') return;
          $sql = "show index from " . DB_PREFIX . $param[2];
          $results = $this->db->query($sql);
          foreach ($results as $result) {            
            if  ($result['Key_name'] == 'PRIMARY') {
              return sprintf($this->session->data['reason_primary_key_already_exists'], $param[2]);
            }            
          }

        } elseif (!in_array(strtoupper($param[4]),array('CONSTRAINT','UNIQUE','PRIMARY','FULLTEXT','FOREIGN','SPATIAL') ) ) {
        
          $colname = ($param[4]=='COLUMN') ? $param[5] : $param[4];
          $sql = "show fields from " . DB_PREFIX . $param[2];
          $results = $this->db->query($sql);
          foreach ($results as $result) {            
            if  ($result['Field'] == $colname) {
              return sprintf($this->session->data['reason_column_already_exists'], $colname);
            }            
          }

        } elseif (strtoupper($param[5])=='AFTER') {
          
          $colname = ($param[6]=='COLUMN') ? $param[7] : $param[6];
          $sql = "show fields from " . DB_PREFIX . $param[2];
          $results = $this->db->query($sql);
          foreach ($results as $result) {            
            if  ($result['Field'] == $colname) {
              return;
            }            
          }

        } elseif (strtoupper($param[6])=='AFTER') {
          
          $colname = ($param[7]=='COLUMN') ? $param[8] : $param[7];
          $sql = "show fields from " . DB_PREFIX . $param[2];
          $results = $this->db->query($sql);
          foreach ($results as $result) {            
            if  ($result['Field'] == $colname) {
              return;
            }            
          }
/*
 * @TODO -- add check for FIRST parameter, to check that the FIRST colname specified actually exists
 */
        }
        break;
      case ("DROP"):
        if (strtoupper($param[4]) == 'INDEX') {
          
          $index = $param[5];
          $sql = "show index from " . DB_PREFIX . $param[2];
          $results = $this->db->query($sql);
          foreach ($results as $result) {            
            if  ($result['Key_name'] == $index) {
              return;
            }            
          }
          
          return sprintf($this->session->data['reason_index_doesnt_exist_to_drop'], $index,$param[2]);

        } elseif (strtoupper($param[4])=='PRIMARY') {
          
          if ($param[5] != 'KEY') return;
          $sql = "show index from " . DB_PREFIX . $param[2];
          $results = $this->db->query($sql);
          foreach ($results as $result) {            
            if  ($result['Key_name'] == 'PRIMARY') {
              return;
            }            
          }
          
          return sprintf($this->session->data['reason_primary_key_doesnt_exist_to_drop'], $param[2]);

        } elseif (!in_array(strtoupper($param[4]),array('CONSTRAINT','UNIQUE','PRIMARY','FULLTEXT','FOREIGN','SPATIAL'))) {
          
          $colname = ($param[4]=='COLUMN') ? $param[5] : $param[4];
          $sql = "show fields from " . DB_PREFIX . $param[2];
          $results = $this->db->query($sql);
          foreach ($results as $result) {            
            if  ($result['Field'] == $colname) {
              return;
            }            
          }
          
          return sprintf($this->session->data['reason_column_doesnt_exist_to_drop'], $colname);
        }
        break;
      case ("ALTER"):
      case ("MODIFY"):
      case ("CHANGE"):
        
        $colname = ($param[4]=='COLUMN') ? $param[5] : $param[4];
        $sql = "show fields from " . DB_PREFIX . $param[2];
        $results = $this->db->query($sql);
        foreach ($results as $result) {          
          if  ($result['Field'] == $colname) {
            return; 
          }          
        }
        
        return sprintf($this->session->data['reason_column_doesnt_exist_to_change'], $colname);
        break;
      default:
        
        return;
        break;
    } 
  }

  private function oc_check_config_key($line) {    
    $values=array();
    $values=explode("'",$line);
     
    $title = $values[1];
    $key  =  $values[3];
	if ((int)$key == (int)$this->config->get('config_store_id')) {
		$sql = "select `group` from " . DB_PREFIX . "setting where store_id ='".$this->config->get($key)."'";
		$results = $this->db->query($sql);	
		if ($results->num_rows) { 
			return sprintf($this->session->data['reason_config_key_already_exists'], $key);
		}
	}
  }

  private function oc_write_to_upgrade_exceptions_table($line, $reason, $sql_file) {    
    $this->oc_create_exceptions_table();	
    $sql="INSERT INTO " . $this->session->data['table_upgrade_exceptions'] . " VALUES (0,'". $sql_file."','".$reason."', now(), '".$this->db->escape($line)."')";     
    $results = $this->db->query($sql);
    return $results;
  }

  private function oc_purge_exceptions_table() {    
    $this->oc_create_exceptions_table();
    $result = $this->db->query("TRUNCATE TABLE " . $this->session->data['table_upgrade_exceptions']);
    return $results;
  }

  private function oc_create_exceptions_table() {    
    if (!$this->oc_table_exists($this->session->data['table_upgrade_exceptions'])) {		
		$result = $this->db->query("CREATE TABLE IF NOT EXISTS " . $this->session->data['table_upgrade_exceptions'] . " (
            upgrade_exception_id smallint(5) NOT NULL auto_increment,
            sql_file varchar(50) default NULL,
            reason varchar(200) default NULL,
            errordate datetime default '0001-01-01 00:00:00',
            sqlstatement text, PRIMARY KEY  (upgrade_exception_id)
          )");
		return $result;
    }
  }
}
?>