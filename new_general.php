<?php
//$GLOBALS['nojunk']='';
require_once 'project_common.php';
require_once 'base/verify_login.php';
	////////User code below/////////////////////
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

		main_menu();
if($_POST['action']=='new_general')
{
	get_data($link);
}
elseif($_POST['action']=='insert')
{
	$sample_id=save_insert($link);
	view_sample($link,$sample_id);
}

//////////////user code ends////////////////
tail();

//echo '<pre>';print_r($_POST);echo '</pre>';

//////////////Functions///////////////////////


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
