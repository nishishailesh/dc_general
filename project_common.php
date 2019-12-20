<?php

function main_menu()
{
	echo '
	
	<div class="dropdown btn-group m-0 p-0">
		<form method=post class="form-group m-0 p-0">
			<input type=hidden name=session_name value=\''.session_name().'\'>
			<button class="btn btn-primary m-0 p-0" formaction=new_general.php type=submit name=action value=new_general>New</button>
			<!--
			<button class="btn btn-primary dropdown-toggle m-0 p-0" type="button" data-toggle="dropdown">New</button>
			<div class="dropdown-menu m-0 p-0">		
					<button class="btn btn-secondary btn-block m-0 p-0" formaction=new_general.php type=submit name=action value=new_general>New (General)</button>
					<button class="btn btn-secondary btn-block m-0 p-0" formaction=new_opd.php type=submit name=action value=new_opd>New (OPD)</button>
			</div>
			-->
		</form>
	</div>
	';		
}


function mk_select_from_array($name, $select_array,$disabled='',$default='')
{	
	echo '<select  '.$disabled.' name=\''.$name.'\'>';
	foreach($select_array as $key=>$value)
	{
				//echo $default.'?'.$value;
		if($value==$default)
		{
			echo '<option  selected > '.$value.' </option>';
		}
		else
		{
			echo '<option > '.$value.' </option>';
		}
	}
	echo '</select>';	
	return TRUE;
}


function get_one_examination_details($link,$examination_id)
{
	$sql='select * from examination where examination_id=\''.$examination_id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	
	return $ar=get_single_row($result);
}

function view_sample($link,$sample_id)
{
	$sql='select * from result where sample_id=\''.$sample_id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	
	echo '<table class="table table-striped table-bordered">';
	echo '<tr>
			<td>Encounter ID</td>
			<td colspan=2>';
			sample_id_edit_button($sample_id);
			echo '</td></tr>';
	echo '<tr><th>Examination ID</th><th>Name</th><th>Result</th></tr>';
	while($ar=get_single_row($result))
	{
		//print_r($ar);
		$examination_details=get_one_examination_details($link,$ar['examination_id']);
		//print_r($examination_details);
		echo '	<tr><td>'.$examination_details['examination_id'].'</td>
				<td>'.$examination_details['name'].'</td>
				<td>'.$ar['result'].'</td></tr>';
	}
	
	$sql_blob='select * from result_blob where sample_id=\''.$sample_id.'\'';
	$result_blob=run_query($link,$GLOBALS['database'],$sql_blob);
	while($ar_blob=get_single_row($result_blob))
	{
		//print_r($ar);
		$examination_blob_details=get_one_examination_details($link,$ar_blob['examination_id']);
		//print_r($examination_details);
		echo '	<tr><td>'.$examination_blob_details['examination_id'].'</td>
				<td>'.$examination_blob_details['name'].'</td>
				<td>';
				echo_download_button_two_pk('result_blob','result',
									'sample_id',$sample_id,
									'examination_id',$examination_blob_details['examination_id'],
									$sample_id.'-'.$examination_blob_details['examination_id'].'-'.$ar_blob['fname'],
									round(strlen($ar_blob['result'])/1024,0));
				echo '</td></tr>';
	}	
		
	echo '</table>';
}

function sample_id_edit_button($sample_id)
{
	echo '<form method=post action=edit_general.php>
	<button class="btn btn-success" name=sample_id value=\''.$sample_id.'\' >'.$sample_id.'</button>
	<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
	<input type=hidden name=action value=edit_general>
	</form>';
}


function echo_download_button_two_pk($table,$field,$primary_key,$primary_key_value,$primary_key2,$primary_key_value2,$postfix='',$fsize)
{
	echo '<form method=post action=download2.php>
			<input type=hidden name=table value=\''.$table.'\'>
			<input type=hidden name=field value=\''.$field.'\' >
			<input type=hidden name=primary_key value=\''.$primary_key.'\'>
			<input type=hidden name=primary_key2 value=\''.$primary_key2.'\'>
			<input type=hidden name=fname_postfix value=\''.$postfix.'\'>
			<input type=hidden name=primary_key_value value=\''.$primary_key_value.'\'>
			<input type=hidden name=primary_key_value2 value=\''.$primary_key_value2.'\'>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
			
			<button class="btn btn-info btn-block"  
			formtarget=_blank
			type=submit
			name=action
			value=download>Download('.$fsize.' kb)</button>
		</form>';
}


?>
