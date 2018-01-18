<?php
/**
 * csvCRUD
 *
 * @package   Manipulate CSV files and view contents in HTML table
 * @author    Mark Berube
 * @license   Distributed under GNU/GPL
 * @version   0.1
 * @access    public
 * 
 * modified: 02-27-2013
 */

class csvCRUD{
	var $f = false;
	var $delim;
	var	$rows = array();
	var $first_line_headers = false;
	var $action = 'update';
	var $update_arr = array();
	var $where = false;
	var $data_changed = false;
    var $headers;
    var $custom_tbl_headers = array();
    var $hide_cols = array(); //first column is '1'...there is no column 0 for this class
    var $show_tbl_headers = false;
    var $show_only_where = false;
    var $show_line_nums = false;
    var $tblID = 'csvtbl';
    var $tblClass = 'sortable';
    var $tblCellpadding = 3;
    var $tblBorder = 1;
    var $template_content_cols = array();
    var $form_input_templates 	= array();
    var $urlencode_templates = false;
	var $last_column_letter;
	var $col_index = array('A'=>0,'B'=>1,'C'=>2,'D'=>3,'E'=>4,'F'=>5,'G'=>6,'H'=>7,'I'=>8,'J'=>9,'K'=>10,'L'=>11,'M'=>12,'N'=>13,'O'=>14,'P'=>15,'Q'=>16,'R'=>17,'S'=>18,'T'=>19,'U'=>20,'V'=>21,'W'=>22,'X'=>23,'Y'=>24,'Z'=>25);
	var $col_letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	var $default_url = '';


	function __construct($filename,$delim='|'){
		$this->f = $filename;
		$this->delim = $delim;
            if(file_exists($filename)){
                $this->extract_data();
            }
	}
	
	/**
     * csvCRUD::extract_data()
     * @desc Load entire CSV contents into 2 dim array (mimics as a mysql 'SELECT * FROM ...' result set). Each row will be automatically loaded into key->value pairs with the key being the column letter (like a spreadsheet, first column is 'A', etc)
     * @return void
     */
	function extract_data(){
		$arr = file($this->f);
		$x = 0;
			foreach($arr as $r){
				$r = trim($r);
				$cells = explode($this->delim,$r);
                $this->rows[$x]['RowNum'] = $x;
				$y = 0;
					foreach($cells as $c){
						$this->rows[$x][$this->col_letters[$y]] = $c;
						$y++;
					}
				$x++;
			}
		$this->last_column_letter = $this->col_letters[$y - 1];
	}

	/**
     * csvCRUD::update_cell()
     * @desc Update value of a given CSV cell (cell is passed as "<col_letter>|<row>")
     * @param string $cell
     * @param string $new_val
     * @return boolean
     */
    function update_cell($cell,$new_val){
        list($col,$row) = explode('|',$cell);
        $cur_val = $this->rows[$row][$col];
            if($cur_val == $new_val){
                return false;
            }else{
                $this->rows[$row][$col] = $new_val;
                $this->data_changed = true;
                return true;
            }
    }

	/**
     * csvCRUD::update_cells_where()
     * @desc Updates the specified column cells, in all rows, where another specified columns value matches
     * @param string $srch_col
     * @param string $srch_val
     * @param string $set_col
     * @param string $new_val
     * @return void
     */
    function update_cells_where($srch_col,$srch_val,$set_col,$new_val){
        $x = 0;
        foreach($this->rows as $r){
                if($r[$srch_col] == $srch_val){
                    $this->update_cell($set_col.'|'.$x,$new_val);
                }
            $x++;
        }

    }
	
	/**
     * csvCRUD::add_record()
     * @desc Append new row to the current row data array
     * @return void
     */
    function add_record($arr){
        $next = count($this->rows);
        $x=0;
            foreach ($arr as $val){
                $letter = $this->col_letters[$x];
                $this->rows[$next][$letter] = ($val == '') ? 'unset' : $val;
                    if($letter == $this->last_column_letter){
                        break;
                    }
                $x++;
            }
        $this->data_changed = true;
    }
	
	/**
     * csvCRUD::delete_record()
     * @desc Deletes the specified Row number from the Row data array
     * @param int $row_num
     * @return void
     */
    function delete_record($row_num){
        unset($this->rows[$row_num]);
        $this->data_changed = true;
    }
    
    /**
     * csvCRUD::set_default_url()
     * @desc Sets the url which the form uses for cancel
     * @param str $url
     * @return void
     */
    function set_default_url($url){
        $this->default_url = $url;
    }
	
	/**
     * csvCRUD::save_file()
     * @desc Commits the current row data array to the CSV file by overwriting existing data
     * @return boolean
     */
	function save_file(){
		if($this->data_changed){
			$txt = $this->get_latest_text();
                if(file_put_contents($this->f,$txt)){
                    return true;
                }else{
                    return false;
                }
		}
	}
	
	/**
     * csvCRUD::get_latest_text()
     * @desc Formats current content of the row data array into CSV text format
     * @return void
     */
    function get_latest_text(){
        $txt = '';
        foreach($this->rows as $r){
            foreach($r as $key => $val){
                if($key == 'RowNum'){
                    continue;
                }
                $txt .= $val;
                $txt .= ($key == $this->last_column_letter) ? "\n" : $this->delim;
            }
        }
        return $txt;
    }
	
	/**
     * csvCRUD::print_latest_text()
     * @desc Prints current CSV content to browser window
     * @return void
     */
    function print_latest_text(){
        echo "<pre>\n";
        echo $this->get_latest_text();
        echo "\n</pre>\n";

    }

	/**
     * csvCRUD::get_rows_array()
     * @desc Retrieve the current row data array
     * @return void
     */
    function get_rows_array(){
        return $this->rows;
    }

	/**
     * csvCRUD::get_record_array()
     * @desc Retrieve a single rows data array
     * @param int $row_num
     * @return void
     */
    function get_record_array($row_num){
        return $this->rows[$row_num];
    }
	
	/**
     * csvCRUD::get_cell_value()
     * @desc Retrieve a single cells value
     * @param string $cell
     * @return string
     */
    function get_cell_value($cell){
        list($col,$row) = explode('|',$cell);
        $val = $this->rows[$row][$col];
        return $val;
    }
	
	/**
     * csvCRUD::print_rows_array()
     * @desc prints pre-formatted row array to browser...useful for debugging
     * @return void
     */
	function print_rows_array(){
		echo "<pre>\n";
        echo "<h3>Last Column in Array: ".$this->last_column_letter."</h3>\n";
		print_r($this->rows);
		echo "\n</pre>\n";
	}

    /**
     * csvCRUD::array2str()
     * @desc Converts any array of values to a comma delimited string for display purposes. If quotes are desired around the values, set quotes arg to true, else default will be no quotes
     * @param array $arr
     * @param bool $quotes
     * @return string
     */
    function array2str($arr,$quotes=false){
        $str = '';
        $cnt = 0;
        foreach($arr as $x){
            $x = trim($x);
            if($quotes){
                $str .= ($cnt < sizeof($arr)-1) ? "'$x', " : "'$x' ";
            }else{
                $str .= ($cnt < sizeof($arr)-1) ? $x .', ' : $x;
            }
            $cnt++;
        }
        return $str;
    }

    /**
     * csvCRUD::arrayKeys2str()
     * @desc Converts the keys of an assoc array to a comma delimited string for display purposes. This func is called when assembling the column names for an insert action
     * @param array $arr
     * @return string
     */
    function arrayKeys2str($arr){
        $str = '';
        $cnt = 0;
        foreach($arr as $key => $val){
            $str .= ($cnt < sizeof($arr)-1) ? $key .', ' : $key;
            $cnt++;
        }
        return $str;
    }

    /**
     * csvCRUD::get_string_between()
     * @desc extract text between two strings (or chars)...useful for templates, etc.
     * @param string $str
     * @param string $start
     * @param string $end
     * @return string
     */
    function get_string_between($str, $start, $end){
        $str = " ".$str;
        $ini = strpos($str,$start);
        if ($ini == 0){
            return "";
        }
        $ini += strlen($start);
        $len = strpos($str,$end,$ini) - $ini;
        return substr($str,$ini,$len);
    }
    
    /**
     * csvCRUD::get_column_sum()
     * @desc Checks each cell in the specified column letter of a result set, and if numeric, and adds it up
     * @param string $col
     */
    function get_column_sum($col){
        $sum = 0;
        if(!empty($this->rows)){
                foreach($this->rows as $row){
					if (is_numeric($row[$col])){
						$sum = $sum + $row[$col];
					}
				}
        }
        return $sum;
    }

    /**
     * csvCRUD::output_column()
     * @desc Outputs Only the specified column of the result in the specified manner: Delimeted Str(str), Single dim array(arr), or Form dropdown(select)
     * @param string $col
     * @param string $display
     * @param bool $quotes
     * @return string
     */
    function output_column($col, $display, $quotes=false){
        $arr = array();
        if(!empty($this->rows)){
                foreach($this->rows as $row){
                    $arr[] = $row[$col];
            }
        }
        switch($display){
            case 'string':
                $content = $this->array2str($arr,$quotes);
                break;
            case 'array':
                $content = $arr;
                break;

        }
        return $content;
    }
    
    /**
     * csvCRUD::get_latest_XML()
     * @desc Delivers select result in xml output
     * @return string
     */
    function get_latest_XML(){
        $xml = '<'.'?'.'xml version="1.0" encoding="ISO-8859-1" '.'?'.'>';
        $xml .= "\n<ITEMS>\n";
            foreach($this->rows as $row){
                $xml .= "\t<ITEM>\n";
                $x = 0;//reset
                    foreach ($row as $key=>$val){
                        //$tag_name = $this->result_tag_names[$col];
							if($x == 0){
								$tag_name = $key;//RowNum
							}else{
								$tag_name = (array_key_exists($key,$this->custom_tbl_headers)) ? $this->custom_tbl_headers[$key] : $key;
							}
                        $xml .= (!in_array($key,$this->hide_cols)) ? "\t\t<$tag_name>$val</$tag_name>\n" : '';
                        $x++;
                    }
                $xml .= "\t</ITEM>\n";
            }
        $xml .= "</ITEMS>\n";
        return $xml;
    }

    /**
     * csvCRUD::get_latest_JSON()
     * @desc Delivers select result in JSON output
     * @param array $skip
     * @return string
     */
    function get_latest_JSON(){
        if(empty($this->rows)){
            return "No Results to Display.";
        }
        if(function_exists('json_encode')){
            $arr = array();
                foreach($this->rows as $row){
                    $tmp = array();
                    $col = 0;//reset
                        foreach ($row as $key=>$val){
                            if(!in_array($key, $this->hide_cols)){
                                $tmp[$key] = $val;
                            }
                            $col++;
                        }
                    $arr[] = $tmp;
                }
            unset($tmp);
            return json_encode($arr);
        }else{
            $json = '[';
                foreach($this->rows as $row){
                    $json .= '{';
                    $col = 1;//reset
                    foreach ($row as $key=>$val){
                        if(!in_array($key, $this->hide_cols)){
                            $json .= "\"$key\":\"$val\"";
                            $json .= ($col < sizeof($row)) ? "," : "";
                        }
                        $col++;
                    }
                    $json .= '},';
                }
            $json .= ']';
        }
        return $json;
    }

    /**
     * csvCRUD::data_dump()
     * @desc Displays select result in one of three ways: html table(tbl), CSV format(csv), or XML(xml)
     * @param string $format
     * @return string
     */
    function data_dump($format){
        if(empty($this->rows)){
            return "No Records Found";
        }
        switch($format){
            case 'tbl':
				$content = $this->get_tbl_html();
            break;
            case 'csv':
                $content = $this->get_latest_text();
            break;
            case 'xml':
                $content = $this->get_latest_XML();
            break;
            case 'json':
                $content = $this->get_latest_JSON();
            break;
        }
        return $content;
    }

    /**
     * csvCRUD::show_line_nums()
     * @desc If set will prepend each row with a record number.
     * @param boolean $val
     * @return void
     */
    function show_line_nums($val = true){
        $this->show_line_nums = $val;
    }

    /**
     * csvCRUD::set_tbl_border()
     * @desc Table border attribute
     * @param int $b
     * @return void
     */
    function set_tbl_border($b){
        $this->tblBorder = $b;
    }

    /**
     * csvCRUD::set_hidden_cols()
     * @desc Single dim array of col letters to hide from displaying
     * @param array $arr
     * @return void
     */
    function set_hidden_cols($arr){
        $this->hide_cols = $arr;
    }

    /**
     * csvCRUD::show_only_where()
     * @desc single element assoc array where the key represents the letter of column to be checked (first column is 'A') and the value is the string to be checked for. If not matching the entire row will be skipped
     * @param array $arr
     * @return void
     */
    function show_only_where($arr){
        ///////////////
        //$key=>$val array...
        //$key = Letter of column (first column being 'A')
        //$val = value that column should contain
        ///////////////
        $this->show_only_where = $arr;
    }

    /**
     * csvCRUD::set_custom_tbl_headers()
     * @desc Sets an assoc array of col->header values to be diplayed rather then the default first line
     * @param array $arr
     * @return void
     */
    function set_custom_tbl_headers($arr){
        $this->custom_tbl_headers = $arr;
        $this->show_tbl_headers = true;
    }

    /**
     * csvCRUD::set_template_cols_array()
     * @desc Sets an array of cols that will apply a template to the output
     * @param array $arr
     * @return void
     */
    function set_template_cols_array($arr){
        $this->template_content_cols = $arr;
    }

    /**
     * csvCRUD::set_rows_import_array()
     * @desc Imports an external array to use in the event a CSV file does not exist, or you simply want to use the class to create a CSV from an array
     * @param array $arr
     * @return void
     */
    function set_rows_import_array($arr){
        $x = 0;
            foreach($arr as $r){
                $this->rows[$x]['RowNum'] = $x;
                $y = 0;
                    foreach($r as $c){
                        $this->rows[$x][$this->col_letters[$y]] = $c;
                        $y++;
                    }
                $x++;
            }
        $this->last_column_letter = $this->col_letters[$y - 1];
        $this->data_changed = true;
    }
    
    /**
     * csvCRUD::set_form_input_templates()
     * @desc Sets an array of cols that will apply a template to the output
     * @param array $arr
     * @return void
     */
    function set_form_input_templates($arr){
        $this->form_input_templates = $arr;
    }
    
    /**
     * csvCRUD::get_input_by_column()
     * @desc Looks at the form inputs template array and builds an input based on the column letter
     * @param str $col
     * @param str $input_name
     * @param str $default_val
     * @return string
     */
    function get_input_by_column($col,$input_name,$default_val=''){
		if(array_key_exists($col,$this->form_input_templates)){
			list($input_type, $values) = explode('|', $this->form_input_templates[$col]);
			switch($input_type){
				case 'select':
					$i = "<select name=\"$input_name\">\n";
						foreach(explode(',',$values) as $v){
							$selected = ($v == $default_val) ? " selected=\"selected\"" : '' ;
							$i .= "<option$selected>$v</option>\n";
						}
					$i .= "</select>\n";
				break;
			}
		}else{
			$i = "<input type=\"text\" name=\"$input_name\" value=\"$default_val\" />";
		}
		return $i;
    }
    
    /**
     * csvCRUD::get_add_record_form()
     * @desc Create a form to add or edit record
     * @param string $action
     * @param int $row
     * @return string
     */
    function get_add_record_form(){
		$header = "Add New Record to CSV";
		$row = count($this->rows);
        $html = "<form name=\"frm_csvEdit\" action=\"\" method=\"POST\" enctype=\"multipart/form-data\">\n";
        $html .= "<table width=\"100%\">\n<tr><th colspan=\"2\">$header</th></tr>\n<tr><th>Column:</th><th>Value:</th></tr>\n";
            foreach($this->rows[0] as $key => $val){
                if($key == 'RowNum') continue;
                //if(in_array($key,$this->hide_cols)) continue;
                $cell = $key.'|'.$row;
                $th = (array_key_exists($key,$this->custom_tbl_headers)) ? $this->custom_tbl_headers[$key] : 'Column: '.$key ;
                $input = $this->get_input_by_column($key,"add_cells[$cell]");
                $html .= "<tr><td class=\"frm_lbl\">".$th."</td><td class=\"frm_input\">$input</td></tr>\n";
            }
        $html .= "<tr><td colspan=\"2\" align=\"right\"><input type=\"hidden\" name=\"row\" value=\"$row\" /><input type=\"hidden\" name=\"action\" value=\"add\" /><input type=\"button\" name=\"cancel\" value=\"Cancel Changes\" onclick=\"location.href='".$this->default_url."'\" />&nbsp;&nbsp;<input type=\"submit\" name=\"submit\" value=\"Submit\" /></td></tr>\n";
        $html .= "</table>\n</form>\n";
        return $html;
    }

    /**
     * csvCRUD::get_row_edit_form()
     * @desc Create a form to add or edit record
     * @param int $row
     * @return string
     */
    function get_row_edit_form($row){
		$header = "Edit CSV Record #".$row;
        $html = "<form name=\"frm_csvEdit\" action=\"\" method=\"POST\" enctype=\"multipart/form-data\">\n";
        $html .= "<table width=\"100%\">\n<tr><th colspan=\"2\">$header<hr><br></th></tr>\n";
            foreach($this->rows[$row] as $key => $val){
                if($key == 'RowNum') continue;
                //if(in_array($key,$this->hide_cols)) continue;
                $cell = $key.'|'.$row;
                $th = (array_key_exists($key,$this->custom_tbl_headers)) ? $this->custom_tbl_headers[$key] : 'Column: '.$key ;
                $input = $this->get_input_by_column($key,"update_cells[$cell]",$val);
                $html .= "<tr><td class=\"frm_lbl\">".$th."</td><td class=\"frm_input\">$input</td></tr>\n";
            }
        $html .= "<tr><td colspan=\"2\" align=\"right\"><br><br><input type=\"hidden\" name=\"row\" value=\"$row\" /><input type=\"hidden\" name=\"action\" value=\"edit\" /><input type=\"button\" name=\"cancel\" value=\"Cancel Changes\" onclick=\"location.href='".$this->default_url."'\" />&nbsp;&nbsp;<input type=\"submit\" name=\"submit\" value=\"Submit\" /></td></tr>\n";
        $html .= "</table>\n</form>\n";
        return $html;
    }
    
    /**
     * csvCRUD::get_vert_record_table()
     * @desc Create a form to edit a specific cell with the entire record contents displayed
     * @param int $row
     * @param str $edit_col
     * @return string
     */
    function get_vert_record_table($row,$edit_col=false){

		$html = ($edit_col) ? "<form name=\"frm_csvEdit\" action=\"\" method=\"POST\" enctype=\"multipart/form-data\">\n" : "";
		$html .= "<hr><table width=\"100%\">\n";
            foreach($this->rows[$row] as $key => $val){
                if($key == 'RowNum') continue;
                $cell = $key.'|'.$row;
                $th = (array_key_exists($key,$this->custom_tbl_headers)) ? $this->custom_tbl_headers[$key] : 'Column: '.$key ;
                $input = ($key !== $edit_col) ? $val : $this->get_input_by_column($key,"update_cells[$cell]",$val);
                $html .= "<tr><td class=\"frm_lbl\">$th</td><td class=\"frm_input\">$input</td></tr>\n";
            }
        $html .= ($edit_col) ? "<tr><td colspan=\"2\" align=\"right\"><br><br><input type=\"hidden\" name=\"row\" value=\"$row\" /><input type=\"hidden\" name=\"action\" value=\"edit\" /><input type=\"button\" name=\"cancel\" value=\"Cancel Changes\" onclick=\"location.href='".$this->default_url."'\" />&nbsp;&nbsp;<input type=\"submit\" name=\"submit\" value=\"Submit\" /></td></tr>\n" : "";
        $html .= "</table>\n";
        $html .= ($edit_col) ? "</form>\n" : "";
        return $html;
    }
    
    /**
     * csvCRUD::get_cell_edit_form()
     * @desc Create a form to edit a specific cell
     * @param int $row
     * @return string
     */
    function get_cell_edit_form($cell){
		$val = $this->get_cell_value($cell);
		list($col,$row) = explode('|',$cell);
		$col_name = $this->custom_tbl_headers[$col];
		$header = "Edit CSV Cell: ".$cell." (".$col_name.")";
        $html = "<form name=\"frm_csvEdit\" action=\"\" method=\"POST\" enctype=\"multipart/form-data\">\n";
        $html .= "<table width=\"100%\">\n<tr><th colspan=\"2\">$header</th></tr>\n";
        //$html .= "<tr><th>Column:</th><th>Value:</th></tr>\n";
		$input = $this->get_input_by_column($col,"update_cells[$cell]",$val);
        $html .= "<tr><td class=\"frm_lbl\">New Value: </td><td class=\"frm_input\">$input</td></tr>\n";
        $html .= "<tr><td colspan=\"2\" align=\"right\"><br><br><input type=\"hidden\" name=\"row\" value=\"$row\" /><input type=\"hidden\" name=\"action\" value=\"edit\" /><input type=\"button\" name=\"cancel\" value=\"Cancel Changes\" onclick=\"location.href='".$this->default_url."'\" />&nbsp;&nbsp;<input type=\"submit\" name=\"submit\" value=\"Submit\" /></td></tr>\n";
        $html .= "<tr><td colspan=2>".$this->get_row_detail_table($row,$col)."</td></tr>";
        $html .= "</table>\n</form>\n";
        return $html;
    }
    
    /**
     * csvCRUD::get_cell_edit_form_table()
     * @desc Create a form to edit a specific cell
     * @param int $row
     * @return string
     */
    function get_cell_edit_form_table($cell){
		$val = $this->get_cell_value($cell);
		list($col,$row) = explode('|',$cell);
		$col_name = $this->custom_tbl_headers[$col];
		$header = "Edit CSV Cell: ".$cell." (".$col_name.")";
        $html = "<table width=\"100%\">\n<tr><th colspan=\"2\">$header</th></tr>\n";
        $html .= "<tr><td>".$this->get_vert_record_table($row,$col)."</td></tr>";
        $html .= "</table>\n";
        return $html;
    }

    /**
     * csvCRUD::get_tbl_html()
     * @desc Return formatted HTML table from ARRAY converted from CSV.
     * @return string
     */
    function get_tbl_html(){
        $tbl = "<table width=\"100%\" id=\"".$this->tblID."\" class=\"".$this->tblClass."\" cellpadding=\"".$this->tblCellpadding."\" cellspacing=0 border=\"".$this->tblBorder."\">\n";
        if($this->show_tbl_headers){
            $this->headers = $this->get_header_arr();
            $tbl .= $this->get_th_row_html();
        }
        $row_cnt = 1;
        $data_row = 1;
        $ttl_rows = count($this->rows);
        $start_row = ($this->first_line_headers) ? 1 : 0;
        for ($i = $start_row; $i < $ttl_rows; $i++) {
            $row_class = ($row_cnt % 2) ? 'csvtbl_row_even' : 'csvtbl_row_odd';
            $row_html = $this->get_td_row_html($i,$row_class,$row_cnt);
            if($row_html){
                $tbl .= $row_html;
                $row_cnt++;
            }
            $data_row++;
        }
        $tbl .= "</table>\n";
        return $tbl;
    }

    /**
     * csvCRUD::get_header_arr()
     * @desc If show headers is TRUE yet no headers are defined, the class will create a generic set of numbered column headers
     * @return array
     */
    function get_header_arr(){
        $arr = array();
        if($this->show_line_nums){
            $arr[] = "&nbsp;";
        }

        if($this->first_line_headers){
            foreach($this->rows[0] as $key=>$val){
                if(!in_array($key,$this->hide_cols)){
                    $arr[] = $val;
                }
            }
            return $arr;
        }

        if(!empty($this->custom_tbl_headers)){
            foreach($this->custom_tbl_headers as $key=>$val){
                if(!in_array($key,$this->hide_cols)){
                    $arr[] = $val;
                }
            }
            return $arr;
        }else{
            foreach($this->rows[0] as $key=>$val){
                if(!in_array($key,$this->hide_cols)){
                    $arr[] = $key;
                }
            }
            return $arr;
        }
    }

    /**
     * csvCRUD::get_th_row_html()
     * @desc If show headers is TRUE, the class will use the headers array and build the header row
     * @return string
     */
    function get_th_row_html(){
        $html = "<tr class=\"csvtbl_th_row\">\n";
        $col = 1;//reset
        foreach ($this->headers as $value) {
            $html .= "<th id=\"th_$col\"><span style=\"cursor:pointer;\">$value</span></th>\n";
            $col++;
        }
        $html .= "</tr>\n";
        return $html;
    }

    /**
     * csvCRUD::get_td_row_html()
     * @desc converts the array of cell data values for a single line into a table row and assigns class attributes for CSS styling
     * @param array $cells
     * @param string $row_class
     * @param int $line_num
     * @param int $data_row
     * @return string
     */
    function get_td_row_html($record,$row_class,$line_num){

        //check if only certain rows should be displayed or skipped
        if($this->show_only_where){
            foreach($this->show_only_where as $chk_col => $chk_val){
                if($this->rows[$record][$chk_col] !== $chk_val){
                    return false;
                }
            }
        }

        //if we got this far, the row should be parsed & displayed
        $html = "<tr class=\"$row_class\">\n";
        $col = 1;//reset
        $show_col = 1;
        ///*
        if($this->show_line_nums){
            $html .= "<td id=\"td_lineNum_$line_num\" class=\"td_$show_col\" nowrap>$line_num.</td>\n";
            $show_col++;
        }
        //*/

        foreach ($this->rows[$record] as $key=>$val) {
            //check if that letter column should be displayed
            if(!in_array($key,$this->hide_cols)){
                //check if that letter column should have a template applied to it
                if(array_key_exists($key, $this->template_content_cols)){
                    $content = $this->template_content_cols[$key];
                    while(substr_count($content,'[') > 0){
                        $replace_key = $this->get_string_between($content,'[',']');
                        $replace_val = ($this->urlencode_templates) ? urlencode($this->rows[$record][$replace_key]) : $this->rows[$record][$replace_key];
                        $content = str_replace('['.$replace_key.']',$replace_val,$content);
                    }
                    $val = $content;
                }

                $cell_id = $key.'|'.$record;
                $html .= "<td id=\"$cell_id\" class=\"td_$show_col\" nowrap>".$val."</td>\n";
                $show_col++;
            }

            $col++;
        }

        $html .= "</tr>\n";
        //unset($row_data_array);
        return $html;
    }

}
?>
