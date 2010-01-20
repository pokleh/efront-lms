<?php
/**
*/

$path = "../libraries/";
error_reporting(E_ALL);
/** Configuration file.*/
include_once $path."configuration.php";
session_start();

$preffix = $_POST['preffix'];
$currentUser = EfrontUserFactory :: factory($_SESSION['s_login']);

if($_SESSION['s_type'] == "administrator"){
	$tests_info = eF_getTableDataFlat("tests t, lessons l", "t.id, t.name as test_name, l.name as lesson_name ","t.active=1  and t.lessons_ID = l.id AND t.name like '%$preffix%'", "t.name");  	
} else {
	$tests_info = eF_getTableDataFlat("tests t, users_to_lessons ul, lessons l", "t.id, t.name as test_name, l.name as lesson_name ", "(ul.user_type = 'professor' OR ul.user_type =".$currentUser->user['user_types_ID'].") AND t.active=1 and t.lessons_ID = l.id AND ul.users_LOGIN='".$_SESSION['s_login']."' and ul.lessons_ID=l.id AND t.name like '%$preffix%'", "t.name"); 	
}	

			$info_array = array();              
            for($i = 0 ; $i < sizeof($tests_info['test_name']) ; $i ++){
                $hiname = highlight_search($tests_info['test_name'][$i], $preffix);
                
				$path_string = $tests_info['lesson_name'][$i]."->".$hiname;
                $info_array[$i] = array('id' => $tests_info['id'][$i],'name' => $tests_info['test_name'][$i],'path_string' =>$path_string); 
            }



$str = '<ul>';
for ($k = 0; $k < sizeof($info_array); $k++){
	$str = $str.'<li id='.$info_array[$k]['id'].'>'.$info_array[$k]['path_string'].'</li>';
}
$str = $str.'</ul>';

echo $str;

function highlight_search($search_results, $search_criteria, $bgcolor='Yellow'){
    $start_tag = '<span style="background-color: '.$bgcolor.'">';
    $end_tag = '</span>';
    $search_results = eregi_replace($search_criteria, $start_tag . $search_criteria . $end_tag, $search_results);  
    return $search_results;
}
?>