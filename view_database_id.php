<?php
//$GLOBALS['nojunk']='';
require_once 'project_common.php';
require_once 'base/verify_login.php';
	////////User code below/////////////////////
echo '		  <link rel="stylesheet" href="project_common.css">
		  <script src="project_common.js"></script>';	
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

/////Note////
//it is not by mrd
//it is by databaase ID
main_menu();
echo '<div id=response></div>';

if($_POST['action']=='get_dbid')
{
	get_dbid($link);
}
elseif($_POST['action']=='view_dbid')
{
	echo_class_button($link,'OGDC')	;
	view_sample($link,$_POST['sample_id']);
}

//////////////user code ends////////////////
tail();

//echo '<pre>';print_r($_POST);echo '</pre>';

//////////////Functions///////////////////////

function get_dbid()
{
	$YY=strftime("%y");

echo '<form method=post>';
echo '<div class="basic_form">';
	echo '	<label class="my_label text-danger" for="mrd">Database ID</label>
			<input type=number size=13 id=mrd name=sample_id class="form-control text-danger" required="required" \>
			<p class="help"><span class=text-danger>Must be</span> number</p>';
echo '</div>';
echo '<button type=submit class="btn btn-primary form-control" name=action value=view_dbid>View</button>';
echo '<input type=hidden name=session_name value=\''.session_name().'\'>';
echo '</form>';
}

function view_mrd($link,$mrd)
{
	$sql='select sample_id from result where examination_id=1 and result=\''.$mrd.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	while($ar=get_single_row($result))
	{
		//print_r($ar);
		view_sample($link,$ar['sample_id']);
		//edit_sample($link,$ar['sample_id']);
	}
	
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
