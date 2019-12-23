<?php

function main_menu()
{
	echo '
	
	<div class="dropdown btn-group m-0 p-0">
		<form method=post class="form-group m-0 p-0">
			<input type=hidden name=session_name value=\''.session_name().'\'>
			<button class="btn btn-primary m-0 p-0" formaction=new_general.php type=submit name=action value=new_general>New</button>
			<button class="btn btn-primary m-0 p-0" formaction=view_mrd.php type=submit name=action value=get_mrd>View</button>			
			<button class="btn btn-primary m-0 p-0" formaction=search.php type=submit name=action value=get_search_condition>Search</button>			
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
	$edit_specification=json_decode($examination_details['edit_specification'],true);
	if(!$edit_specification){$edit_specication=array();}
	
	$type=isset($edit_specification['type'])?$edit_specification['type']:'text';
	$help=isset($edit_specification['help'])?$edit_specification['help']:'No help';
	
	if($type=='yesno')
	{
		echo '<div class="basic_form">';
			echo '	<label class="my_label" for="'.$examination_details['name'].'">'.$examination_details['name'].'</label>
					<button 
						'.$readonly.'
						id="'.$examination_details['name'].'" 
						name="'.$examination_id.'" 
						data-exid="'.$examination_id.'" 
						data-sid="'.$sample_id.'" 
						class="form-control btn btn-info mb-1 autosave-yesno"
						value=\''.$result.'\'
						type=button
						>'.$result.'</button>
					<p class="help">'.$help.'</p>';
		echo '</div>';
	}
	else if($type=='select')
	{
		$option=isset($edit_specification['option'])?explode(',',$edit_specification['option']):array();
		$option_html='';
		foreach($option as $v)
		{
			if($v==$result)
			{
				$option_html=$option_html.'<option selected>'.$v.'</option>';
			}
			else
			{
				$option_html=$option_html.'<option>'.$v.'</option>';
			}
		}
		
		echo '<div class="basic_form">';
			echo '	<label class="my_label" for="'.$examination_details['name'].'">'.$examination_details['name'].'</label>
					<select '.$readonly.' 
						id="'.$examination_details['name'].'" 
						name="'.$examination_id.'" 
						data-exid="'.$examination_id.'" 
						data-sid="'.$sample_id.'" 
						class="form-control autosave-select">'.$option_html.'</select>
					<p class="help">'.$help.'</p>';
		echo '</div>';
	}
	
	elseif($type=='number')
	{
		$step=isset($edit_specification['step'])?$edit_specification['step']:1;
		echo '<div class="basic_form">';
			echo '	<label class="my_label" for="'.$examination_details['name'].'">'.$examination_details['name'].'</label>
					<input 
						'.$readonly.'
						id="'.$examination_details['name'].'" 
						name="'.$examination_id.'" 
						data-exid="'.$examination_id.'" 
						data-sid="'.$sample_id.'" 
						class="form-control autosave" 
						type=\''.$type.'\' 
						step=\''.$step.'\' 
						value=\''.$result.'\'>
					<p class="help">'.$help.'</p>';
		echo '</div>';
	}
	else  
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
						type=\''.$type.'\' 
						value=\''.$result.'\'>
					<p class="help">'.$help.'</p>';
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


function get_basic()
{
	$YY=strftime("%y");

echo '<div id=basic class="tab-pane active">';
echo '<div class="basic_form">';
	echo '	<label class="my_label text-danger" for="mrd">MRD</label>
			<input size=13 id=mrd name=mrd class="form-control text-danger" required="required" type=text pattern="SUR/[0-9][0-9]/[0-9]{8}" placeholder="MRD" value="SUR/'.$YY.'/"\>
			<p class="help"><span class=text-danger>Must have</span> 8 digit after SUR/YY/</p>';
/*			
	echo '	<label  class="my_label text-danger" for="name">Name</label>
			<input class="form-control text-danger" type=text required="required" pattern="[a-zA-Z\s]{2,}" id=name name=name placeholder=name>
			<p class="help"><span class=text-danger>Must have</span> atleast two characters</p>';

	echo '	<label  class="my_label" for="group_id">Request ID</label>
			<input class="form-control" type=text id=request_id name=request_id placeholder=request_id>
			<p class="help">Give single Request ID to all today\'s samples from this patient</p>';
*/
			
echo '</div>';
echo '</div>';	

}

function get_more_basic()
{

echo '<div id=more_basic class="tab-pane ">'; //donot mix basic_form(grid) with bootsrap class
echo '<div class="basic_form">';
	echo '	<label  class="my_label"  for="department">Department:</label>';
			mk_select_from_array('department',$GLOBALS['department']);
			echo '<p class="help">Select Department</p>';
			
	echo '	<label  class="my_label"  for="unit">Unit</label>';
			mk_select_from_array('unit',$GLOBALS['unit']);
			echo '<p class="help">Select Unit</p>';
			
	echo '	<label  class="my_label"  for="location3">Ward/OPD</label>
			<div class="form-control">
					<label class="radio-inline"><input type="radio" name="wardopd" value=OPD >OPD</label>
					<label class="radio-inline"><input type="radio" name="wardopd" value=Ward >Ward</label>
			</div>
			<p class="help">Ward/OPD</p>';
			
	echo '	<label  class="my_label"  for="ow_no">OPD/Ward No:</label>';
			mk_select_from_array('ow_no',$GLOBALS['ow_no']);
			echo '<p class="help">OPD/Ward Number</p>';

			
	echo '	<label  class="my_label" for="unique_id">AADHAR:</label>
			<input class="form-control" type=text id=unique_id name=unique_id placeholder=AADHAR>
			<p class="help">Full 12 Digit AADHAR number</p>';

	echo '	<label  class="my_label" for="unique_id">Mobile</label>
			<input class="form-control" type=text id=mobile name=mobile placeholder=Mobile>
			<p class="help">10 digit Mobile number</p>';
						
	echo '	<label  class="my_label" for="sex">Sex:</label>
			<select class="form-control" id=sex name=sex><option></option><option>M</option><option>F</option><option>O</option></select>
			<p class="help"> O for others</p>';
			
	echo '	<label   class="my_label" for="dob">DOB:</label>
			<input type=date id=dob name=dob>
			<p class="help">Approximate DOB</p>';

	echo '	<label  class="my_label" for="age">Age</label>
			<input class="form-control" type=text id=age name=age placeholder=Age>
			<p class="help">Write age in what ever way you like</p>';
			
	echo '	<label  class="my_label"  for="extra">Extra:</label>
			<input class="form-control" type=text id=extra name=extra placeholder=Extra>
			<p class="help">Any other extra details</p>';
echo '</div>';
echo '</div>';

}


function get_data($link)
{
	echo '<form method=post class="bg-light jumbotron">';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';

	echo '<ul class="nav nav-pills nav-justified">
			<li class="active" ><button class="btn btn-secondary" type=button data-toggle="tab" href="#basic">Basic</button></li>
			<li><button class="btn btn-secondary" type=button  data-toggle="tab" href="#examination">Examinations</button></li>
			<li><button class="btn btn-secondary" type=button  data-toggle="tab" href="#profile">Profiles</button></li>
			<li><button type=submit class="btn btn-primary form-control" name=action value=insert>Save</button></li>
		</ul>';
	echo '<div class="tab-content">';
		get_basic();
		get_examination_data($link);
		get_profile_data($link);
	echo '</div>';

	echo '</form>';			
}

function get_examination_data($link)
{
	$sql='select * from examination';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<div id=examination class="tab-pane">';
	echo '<div class="ex_profile">';
	while($ar=get_single_row($result))
	{
		my_on_off_ex($ar['name'],$ar['examination_id']);
	}
	echo '<input type=text name=list_of_selected_examination id=list_of_selected_examination>';
	echo '</div>';
	echo '</div>';
}

function get_profile_data($link)
{
	$sql='select * from profile';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<div id=profile  class="tab-pane">';
	echo '<div class="ex_profile">';
	while($ar=get_single_row($result))
	{
		my_on_off_profile($ar['name'],$ar['profile_id']);
	}
	echo '<input type=text name=list_of_selected_profile id=list_of_selected_profile>';
	echo '</div>';
	echo '</div>';
}

function get_examination_blob_data($link)
{
	$sql='select * from examination_blob';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<div id="examination_blob" class="tab-pane ">';
	while($ar=get_single_row($result))
	{
		my_on_off_ex_blob($ar['name'],$ar['examination_id']);
	}
	echo '<input type=text name=list_of_selected_examination_blob id=list_of_selected_examination_blob>';
	echo '</div>';
}

function my_on_off_ex($label,$id)
{
	
	echo '<button 
			class="btn btn-sm btn-outline-primary"
			type=button 
			onclick="select_examination_js(this, \''.$id.'\',\'list_of_selected_examination\')"
			>'.$label.'</button>';
}
function my_on_off_ex_blob($label,$id)
{
	
	echo '<button 
			class="btn btn-sm btn-outline-primary"
			type=button 
			onclick="select_examination_blob_js(this, \''.$id.'\',\'list_of_selected_examination_blob\')"
			>'.$label.'</button>';
}
function my_on_off_profile($label,$id)
{
	
	echo '<button 
			class="btn btn-sm btn-outline-primary"
			type=button 
			onclick="select_profile_js(this, \''.$id.'\',\'list_of_selected_profile\')"
			>'.$label.'</button>';
}

function save_insert($link)
{
	//find list of examinations requested//////////////////////////////
	$requested=array();
	$ex_requested=explode(',',$_POST['list_of_selected_examination']);
	$requested=array_merge($requested,$ex_requested);
	
	$profile_requested=explode(',',$_POST['list_of_selected_profile']);
	foreach($profile_requested as $value)
	{
		$psql='select * from profile where profile_id=\''.$value.'\'';
		$result=run_query($link,$GLOBALS['database'],$psql);
		$ar=get_single_row($result);
		$profile_ex_requested=explode(',',$ar['examination_id_list']);
		$requested=array_merge($requested,$profile_ex_requested);
	}

	$requested=array_filter(array_unique($requested));
	
//1	//must to link samples from single patients
	$sample_id=get_new_sample_id($link,$_POST['mrd']);

	//echo '<pre>following is requested:<br>';print_r($requested);echo '</pre>';
	foreach ($requested as $ex)
	{
			if($ex<100000)
			{
				insert_one_examination_without_result($link,$sample_id,$ex);
			}
			else  //blob as attachment 
			{
				insert_one_examination_blob_without_result($link,$sample_id,$ex);
			}
	}
	
	return $sample_id;
}

function get_new_sample_id($link,$mrd)
{
	$sample_id=find_next_sample_id($link);
	$sql='insert into result (sample_id,examination_id,result,recording_time,recorded_by)
			values (\''.$sample_id.'\', \'1\',\''.$mrd.'\',now(),\''.$_SESSION['login'].'\')';
	if(!run_query($link,$GLOBALS['database'],$sql))
		{echo 'Data not inserted(with)<br>'; return false;}
	else
	{
		return $sample_id;
	}
}

function find_next_sample_id($link)
{
	$sqls='select ifnull(max(sample_id)+1,1) as next_sample_id from result';
	//echo $sqls;
	$results=run_query($link,$GLOBALS['database'],$sqls);
	$ars=get_single_row($results);
	return $ars['next_sample_id'];
}

function insert_one_examination_without_result($link,$sample_id,$examination_id)
{
	$sql='insert into result (sample_id,examination_id)
			values ("'.$sample_id.'","'.$examination_id.'")';
	//echo $sql.'(without)<br>';
	if(!run_query($link,$GLOBALS['database'],$sql))
		{echo 'Data not inserted(without)<br>'; return false;}
	else{return true;}
}

function insert_one_examination_blob_without_result($link,$sample_id,$examination_id)
{
	$sql='insert into result_blob (sample_id,examination_id)
			values ("'.$sample_id.'","'.$examination_id.'")';
	//echo $sql.'(without)<br>';
	if(!run_query($link,$GLOBALS['database'],$sql))
		{echo 'Data not inserted(without)<br>'; return false;}
	else{return true;}
}

?>
