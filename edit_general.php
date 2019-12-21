<?php
//$GLOBALS['nojunk']='';
require_once 'project_common.php';
require_once 'base/verify_login.php';
	////////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

main_menu();

if($_POST['action']=='edit_general')
{
	edit_sample($link,$_POST['sample_id']);
}
elseif($_POST['action']=='save')
{
	save_edit($link);
}

//////////////user code ends////////////////
tail();

echo '<pre>';print_r($_POST);echo '</pre>';

//////////////Functions///////////////////////


//////////////Functions///////////////////////
function edit_sample($link,$sample_id)
{
	$r=get_result_of_sample_in_array($link,$sample_id);
	echo '<div class="bg-success">
	<div class="basic_form">
		<div>Edit ID</div>
		<div>'.$_POST['sample_id'].'</div>
		<div>Unique Number to get this data</div>
	</div>
	</div>';
	foreach($r as $k=>$v)
	{
		edit_field($link,$k,$r);	
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

function edit_field($link,$examination_id,$result_array)
{
	if(array_key_exists($examination_id,$result_array)){$result=$result_array[$examination_id];}else{$result='';}
	$examination_details=get_one_examination_details($link,$examination_id);
	$edit_specification=json_decode($examination_details['edit_specification']);
	
	if($edit_specification)
	{
		if(isset($edit_specification->{'type'})){$type=$edit_specification->{'type'};}else{$type='text';}
		if(isset($edit_specification->{'help'})){$type=$edit_specification->{'help'};}else{$help='No help';}
	}
	else
	{
		$edit_specication=	$edit_specification=json_decode('
		{
		"type":"text",
		"help":"No help"
		}');
	}
	
	//var_dump($edit_specification);
	echo '<div class="basic_form">';
		echo '	<label class="my_label" for="'.$examination_details['name'].'">'.$examination_details['name'].'</label>
				<input size=13 id="'.$examination_details['name'].'" name="'.$examination_details['name'].'" class="form-control" 
				type=\''.$edit_specification->{'type'}.'\' 
				value=\''.$result.'\'>
				<p class="help">'.$edit_specification->{'help'}.'</p>';
	echo '</div>';
}

?>
<style>

@media only screen and (max-width: 400px) 
{ 
	  .basic_form 
	{
	  display: grid;
	  grid-template-columns: auto;
	}
	
	.my_label
	 {
		 display:none
	 }
	 	
	 .help
	 {
		 display:none
	 }
	 
	.ex_profile 
	{
	  display: grid;
	  grid-template-columns: auto auto;
	}		 
}

/* Tablet Styles */
@media only screen and (min-width: 401px) and (max-width: 960px) 
{
  
	.basic_form 
	{
	  display: grid;
	  grid-template-columns: 33% 67%;
	}

	 .help
	 {
		 display:none
	 }

	.ex_profile 
	{
	  display: grid;
	  grid-template-columns: auto auto auto auto;
	}	   
}

@media only screen and (min-width: 961px) 
{
	.basic_form 
	{
	  display: grid;
	  grid-template-columns: 20% 30% 50%;
	}	
	
	.ex_profile 
	{
	  display: grid;
	  grid-template-columns: auto auto auto auto auto auto auto auto;
	}	  
}

</style>

<script>
var selected_ex=[]
var selected_profile=[]

function select_examination_js(me,ex_id,list_id)
{
	if(selected_ex.indexOf(ex_id) !== -1)
	{
		selected_ex.splice(selected_ex.indexOf(ex_id),1)
		document.getElementById(list_id).value=selected_ex
		me.classList.remove('bg-warning')
	}
	else
	{
		selected_ex.push(ex_id);
		document.getElementById(list_id).value=selected_ex
		me.classList.add('bg-warning')
	}
}

function select_profile_js(me,ex_id,list_id)
{
	if(selected_profile.indexOf(ex_id) !== -1)
	{
		selected_profile.splice(selected_profile.indexOf(ex_id),1)
		document.getElementById(list_id).value=selected_profile
		me.classList.remove('bg-warning')
	}
	else
	{
		selected_profile.push(ex_id);
		document.getElementById(list_id).value=selected_profile
		me.classList.add('bg-warning')
	}
}						
</script>
