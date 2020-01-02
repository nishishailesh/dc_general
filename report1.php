<?php
//$GLOBALS['nojunk']='';
require_once 'project_common.php';
require_once 'base/verify_login.php';
echo '<link rel="stylesheet" type="text/css" media="print" href="bootstrap/css/bootstrap.min.css">';
	////////User code below/////////////////////
echo '		  <link rel="stylesheet" href="project_common.css">
		  <script src="project_common.js"></script>';
$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

main_menu();
echo '<div id=response></div>';


		report1($link,1);

//////////////user code ends////////////////
tail();

//echo '<pre>';print_r($_POST);echo '</pre>';

function report1($link,$sample_id)
{
	$ex_list=get_result_of_sample_in_array($link,$sample_id);
	$profile_wise_ex_list=ex_to_profile($link,$ex_list);
	//echo '<pre>';
	//print_r($profile_wise_ex_list);
	//echo '</pre>';
	echo '<div class="basic_form">
			<div class=my_label >Edit ID</div>
			<div>';sample_id_edit_button($sample_id);echo '</div>
			<div class=help>Unique Number to get this data</div>';
	echo '</div>';		
	foreach($profile_wise_ex_list as $kp=>$vp)
	{
		$pinfo=get_profile_info($link,$kp);
		echo '<h3 data-toggle="collapse" class=sh href="#'.$pinfo['name'].'" >Show/Hide</h3><div></div><div></div>';
		echo '<div class="collapse" id='.$pinfo['name'].'>';
		echo '<h3>'.$pinfo['name'].'</h3><div></div><div></div>';
		foreach($vp as $ex_id)
		{
			if($ex_id==1){$readonly='readonly';}else{$readonly='';}
			
			view_field($link,$ex_id,$ex_list[$ex_id]);	
		}
		echo '</div>';
	}
	
	$rblob=get_result_blob_of_sample_in_array($link,$sample_id);
	//print_r($rblob);
	foreach($rblob as $kblob=>$vblob)
	{
		$sql_blob='select * from result_blob where sample_id=\''.$sample_id.'\' and examination_id=\''.$kblob.'\'';
		$result_blob=run_query($link,$GLOBALS['database'],$sql_blob);
		$ar_blob=get_single_row($result_blob);
	
		//print_r($ar);
		$examination_blob_details=get_one_examination_details($link,$kblob);
		
		//print_r($examination_details);
		echo '	<div class="basic_form">
	
				<div class=my_label>'.$examination_blob_details['name'].'</div>
				<div>';
				echo_download_button_two_pk('result_blob','result',
									'sample_id',$sample_id,
									'examination_id',$examination_blob_details['examination_id'],
									$sample_id.'-'.$examination_blob_details['examination_id'].'-'.$vblob
									);
				echo '</div>';
				echo '<div  class=help  >Current File:'.$ar_blob['fname'].'</div>
				</div>';
				
	}
		
}



?>
