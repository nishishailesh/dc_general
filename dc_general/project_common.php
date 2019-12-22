<?php

function main_menu()
{
	echo '
	
	<div class="dropdown btn-group m-0 p-0">
		<form method=post class="form-group m-0 p-0">
			<input type=hidden name=session_name value=\''.session_name().'\'>
			<button class="btn btn-primary m-0 p-0" formaction=new_general.php type=submit name=action value=new_general>New</button>
			<button class="btn btn-primary m-0 p-0" formaction=view_mrd.php type=submit name=action value=get_mrd>View</button>			
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

function view_sample_table($link,$sample_id)
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

function view_sample($link,$sample_id)
{
	$sql='select * from result where sample_id=\''.$sample_id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	
	echo '<div class="basic_form">';
	echo '	<div class="my_label border border-dark">ID</div>
			<div class=" border border-dark">';
			sample_id_edit_button($sample_id);
			echo '</div>
			<div class="help border border-dark">Click on ID number (green button) to edit</div>';
			
	echo '<div class="my_label border border-info">Name</div><div class=" border border-info">Data</div><div class="help  border border-info">Help</div>';
	while($ar=get_single_row($result))
	{
		//print_r($ar);
		$examination_details=get_one_examination_details($link,$ar['examination_id']);
		$edit_specification=json_decode($examination_details['edit_specification']);
		$h=isset($edit_specification->{'help'})?($edit_specification->{'help'}):'No help';
		//print_r($edit_specification);
		//print_r($examination_details);
		echo '	<div class="my_label border border-dark text-wrap">'.$examination_details['name'].'</div>
				<div class="border border-dark">'.$ar['result'].'</div>
				<div class="help border border-dark">'.($h).'</div>';
	}
	
	$sql_blob='select * from result_blob where sample_id=\''.$sample_id.'\'';
	$result_blob=run_query($link,$GLOBALS['database'],$sql_blob);
	while($ar_blob=get_single_row($result_blob))
	{
		//print_r($ar);
		$examination_blob_details=get_one_examination_details($link,$ar_blob['examination_id']);
		//print_r($examination_details);
		echo '	
				<div class=my_label>'.$examination_blob_details['name'].'</div>
				<div>';
				echo_download_button_two_pk('result_blob','result',
									'sample_id',$sample_id,
									'examination_id',$examination_blob_details['examination_id'],
									$sample_id.'-'.$examination_blob_details['examination_id'].'-'.$ar_blob['fname'],
									round(strlen($ar_blob['result'])/1024,0));
				echo '</div>';
				echo '<div  class=help  >Current File:'.$ar_blob['fname'].'</div>';
	}	
		
	echo '</div>';
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

function edit_sample($link,$sample_id)
{
	$r=get_result_of_sample_in_array($link,$sample_id);
	echo '<div class="bg-success">
	<div class="basic_form">
		<div class=my_label >Edit ID</div>
		<div>'.$sample_id.'</div>
		<div class=help>Unique Number to get this data</div>
	</div>
	</div>';
	foreach($r as $k=>$v)
	{
		if($k==1){$readonly='readonly';}else{$readonly='';}
		edit_field($link,$k,$r,$sample_id,$readonly);	
	}
	
	$rblob=get_result_blob_of_sample_in_array($link,$sample_id);
	
	foreach($rblob as $kblob=>$vblob)
	{
		edit_blob_field($link,$kblob,$rblob,$sample_id);	
	}
		
}

function get_result_of_sample_in_array($link,$sample_id)
{
	$sql='select * from result where sample_id=\''.$sample_id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$result_array=array();
	while($ar=get_single_row($result))
	{
		$result_array[$ar['examination_id']]=$ar['result'];
	}
	//print_r($result_array);
	return $result_array;
}

function get_result_blob_of_sample_in_array($link,$sample_id)
{
	$sql='select * from result_blob where sample_id=\''.$sample_id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	$result_array=array();
	while($ar=get_single_row($result))
	{
		$result_array[$ar['examination_id']]='';	//no blob as result
	}
	//print_r($result_array);
	return $result_array;
}
function edit_basic($link,$result_array)
{
	if(array_key_exists('1',$result_array)){$mrd=$result_array['1'];}else{$mrd='';}
	
	echo '<div id=basic class="tab-pane active">';
	echo '<div class="basic_form">';
		echo '	<label class="my_label text-danger" for="mrd">MRD</label>
				<input size=13 id=mrd name=mrd class="form-control text-danger" 
				required="required" type=text pattern="SUR/[0-9][0-9]/[0-9]{8}" placeholder="MRD"
				value=\''.$mrd.'\'>
				<p class="help"><span class=text-danger>Must have</span> 8 digit after SUR/YY/</p>';			
	echo '</div>';
	echo '</div>';
}

function edit_field($link,$examination_id,$result_array,$sample_id,$readonly='')
{
	if(array_key_exists($examination_id,$result_array)){$result=$result_array[$examination_id];}else{$result='';}
	$examination_details=get_one_examination_details($link,$examination_id);
	$edit_specification=json_decode($examination_details['edit_specification']);
	
	if($edit_specification)
	{
		if(isset($edit_specification->{'type'})){$type=$edit_specification->{'type'};}else{$type='text';}
		if(isset($edit_specification->{'help'})){$help=$edit_specification->{'help'};}else{$help='No help';}
	}
	else
	{
		$edit_specication=	$edit_specification=json_decode('
		{
		"type":"text",
		"help":"No help"
		}');
		$type='text';
	}
	
	//var_dump($edit_specification);
	if($type=='text' || $type=='number' || $type=='date')
	{
		echo '<div class="basic_form">';
			echo '	<label class="my_label" for="'.$examination_details['name'].'">'.$examination_details['name'].'</label>
					<input 
						'.$readonly.'
						id="'.$examination_details['name'].'" 
						name="'.$examination_id.'" 
						data-exid="'.$examination_id.'" 
						data-sid="'.$sample_id.'" 
						class="form-control autosave" 
						type=\''.$edit_specification->{'type'}.'\' 
						value=\''.$result.'\'>
					<p class="help">'.$edit_specification->{'help'}.'</p>';
		echo '</div>';
	}
	elseif($type=='yesno')
	{
		echo '<div class="basic_form">';
			echo '	<label class="my_label" for="'.$examination_details['name'].'">'.$examination_details['name'].'</label>
					<button 
						'.$readonly.'
						id="'.$examination_details['name'].'" 
						name="'.$examination_id.'" 
						data-exid="'.$examination_id.'" 
						data-sid="'.$sample_id.'" 
						class="form-control autosave btn btn-info autosave-yesno"
						value=\''.$result.'\'
						type=button
						>'.$result.'</button>
					<p class="help">'.$edit_specification->{'help'}.'</p>';
		echo '</div>';
	}
}

function edit_blob_field($link,$examination_id,$result_array,$sample_id)
{
	//get examination details
	$examination_details=get_one_examination_details($link,$examination_id);
	//get result_blob details
	$sql_blob='select * from result_blob where sample_id=\''.$sample_id.'\' and examination_id=\''.$examination_id.'\' ';
	$result_blob=run_query($link,$GLOBALS['database'],$sql_blob);
	$ar_blob=get_single_row($result_blob);

	echo '<div class="basic_form">';
	echo '	<div class=my_label>'.$examination_details['name'].'</div>
			<div>';	
	echo_download_button_two_pk('result_blob','result',
								'sample_id',$sample_id,
								'examination_id',$examination_details['examination_id'],
								$sample_id.'-'.$examination_details['examination_id'].'-'.$ar_blob['fname'],
								round(strlen($ar_blob['result'])/1024,0));
	
	echo_upload_two_pk($sample_id,$examination_id);							
	//echo
	echo '</div>';
	echo '<div  class=help  >Current File:'.$ar_blob['fname'].'</div>';
	echo '</div>';
}

function echo_upload_two_pk($sample_id,$examination_id)
{
	echo '<form method=post enctype="multipart/form-data">';
	echo '<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';
	echo '<input type=hidden readonly size=8  name=examination_id value=\''.$examination_id.'\'>';
	echo '<input type=hidden name=sample_id value=\''.$sample_id.'\'>';		
	echo '<input type=file name=fvalue >';
	echo '<button  class="btn btn-success" type=submit name=action value=upload>Upload</button>';
	echo'</form>';
}

function file_to_str($link,$file)
{
	if($file['size']>0)
	{
		$fd=fopen($file['tmp_name'],'r');
		$size=$file['size'];
		$str=fread($fd,$size);
		return my_safe_string($link,$str);
	}
	else
	{
		return false;
	}
}

function save_result_blob($link)
{
		$blob=file_to_str($link,$_FILES['fvalue']);
		if(strlen($blob)!=0)
		{
		$sql='update result_blob 
				set 
					fname=\''.$_FILES['fvalue']['name'].'\'	,
					result=\''.$blob.'\'	
				where 
					sample_id=\''.$_POST['sample_id'].'\' 
					and
					examination_id=\''.$_POST['examination_id'].'\'';
		
			if(!$result=run_query($link,$GLOBALS['database'],$sql))
			{
				echo '<br>Data not updated';
			}
			else
			{
				echo '<p>'.$_FILES['fvalue']['name'].' Saved</p>';				
			}	
		}
		else
		{
			echo '<p>0 size file. data not updated</p>';				
		}
}

?>
