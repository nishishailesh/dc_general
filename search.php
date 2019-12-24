<?php
//$GLOBALS['nojunk']='';
require_once 'project_common.php';
require_once 'base/verify_login.php';
echo '<link rel="stylesheet" type="text/css" media="print" href="bootstrap/css/bootstrap.min.css">';
	////////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

main_menu();
echo '<div id=response></div>';

if($_POST['action']=='get_search_condition')
{
	get_search_condition($link);
}
elseif($_POST['action']=='set_search')
{
	set_search($link);
}
elseif($_POST['action']=='search')
{
	$search_array=prepare_search_array($link);
	//print_r($search_array);
	$first=TRUE;
	$temp=array();
	foreach ($search_array as $sk=>$sv)
	{
		$temp=get_sample_with_condition($link,$sk,$sv,$temp,$first);
		$first=FALSE;
	}
	//print_r($temp);
	
	echo '<h2>Discharge Summary O&G, NCH, Surat</h2>';
	foreach ($temp as $sid)
	{
		view_sample($link,$sid);
		echo '<br>';
	}	
}

//////////////user code ends////////////////
tail();

//echo '<pre>';print_r($_POST);echo '</pre>';

//////////////Functions///////////////////////

function get_search_condition($link)
{
	echo '<form method=post>';
	echo '<div class="basic_form">';
	get_examination_data($link);
	echo '</div>';
	echo '<button type=submit class="btn btn-primary form-control" name=action value=set_search>Set Search</button>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo '</form>';
}

function set_search($link)
{
	$ex_requested=explode(',',$_POST['list_of_selected_examination']);
	echo '<form method=post>';
		foreach($ex_requested as $v)
		{
			$examination_details=get_one_examination_details($link,$v);
			echo '<div class="basic_form">';
			echo '	<label class="my_label" for="'.$examination_details['name'].'">'.$examination_details['name'].'</label>
					<input 
						id="'.$examination_details['name'].'" 
						name="'.$examination_details['examination_id'].'" 
						data-exid="'.$examination_details['examination_id'].'" 
						class="form-control" >
					<p class="help">Enter details for search</p>';
			echo '</div>';
		}
	echo '<button type=submit class="btn btn-primary form-control" name=action value=search>Search</button>';
	echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
	echo '</form>';
}

function prepare_search_array($link)
{
	foreach($_POST as $k=>$v)
	{
		if(is_int($k))
		{
			$ret[$k]=$v;
		}
	}	
	return $ret;
}

function get_sample_with_condition($link,$exid,$ex_result,$sid_array=array(),$first=FALSE)
{
	$ret=array();
	
	if($first===TRUE)
	{
		$sql='select sample_id from result 
				where 
					examination_id=\''.$exid.'\' and 
					result like \'%'.$ex_result.'%\' ';
		//echo $sql.'<br>';
		$result=run_query($link,$GLOBALS['database'],$sql);
		while($ar=get_single_row($result))
		{
			$ret[]=$ar['sample_id'];
		}
		return $ret;
	}
	
	//else do as follows
	foreach($sid_array as $v)
	{
		$sql='select sample_id from result 
				where 
					examination_id=\''.$exid.'\' and 
					result like \'%'.$ex_result.'%\' and
					sample_id=\''.$v.'\'';
		//echo $sql.'<br>';
		$result=run_query($link,$GLOBALS['database'],$sql);
		if(get_row_count($result)>0)
		{
			$ret[]=$v;
		}
	}
	return $ret;
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

@media only print 
{
	.basic_form 
	{
	  display: grid;
	  grid-template-columns: 30% 70%;
	}	

	 .help , #main_menu, #root_menu, #data_header
	 {
		 display:none
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

$(document).ready
	(
		function()
		{
			//$("input[type!=file]").change(
					$(".autosave").change(
								function()
								{
									
									$.post(
											"save_record.php",
											{
												examination_id: $(this).attr('data-exid'),
												sample_id: $(this).attr('data-sid'),
												result: $(this).val()
											 },
											function(data,status)
											{
												//alert("Data: " + data + "\nStatus: " + status);
												$("#response").html(data)
											}
										);
								}
							);


					$(".autosave-yesno").click(
								function()
								{
									if($(this).val()!='yes')
									{
										$(this).val()=='yes'
									}
									else
									{
										$(this).val()=='no'
									}
									
									$.post(
											"save_record.php",
											{
												examination_id: $(this).attr('data-exid'),
												sample_id: $(this).attr('data-sid'),
												result: $(this).val()
											 },
											function(data,status)
											{
												//alert("Data: " + data + "\nStatus: " + status);
												$("#response").html(data)
											}
										);
								}
							);
														
		}
	);

</script>
<script>
var selected_ex=[]
var selected_profile=[]
var selected_ex_blob=[]

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

function select_examination_blob_js(me,ex_id,list_id)
{
	if(selected_ex_blob.indexOf(ex_id) !== -1)
	{
		selected_ex_blob.splice(selected_ex_blob.indexOf(ex_id),1)
		document.getElementById(list_id).value=selected_ex_blob
		me.classList.remove('bg-warning')
	}
	else
	{
		selected_ex_blob.push(ex_id);
		document.getElementById(list_id).value=selected_ex_blob
		me.classList.add('bg-warning')
	}
}					
</script>
