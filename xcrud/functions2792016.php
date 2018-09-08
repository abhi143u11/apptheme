<?php
function publish_action($xcrud)
{
    if ($xcrud->get('primary'))
    {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE base_fields SET `bool` = b\'1\' WHERE id = ' . (int)$xcrud->get('primary');
        $db->query($query);
    }
}
function unpublish_action($xcrud)
{
    if ($xcrud->get('primary'))
    {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE base_fields SET `bool` = b\'0\' WHERE id = ' . (int)$xcrud->get('primary');
        $db->query($query);
    }
}

function exception_example($postdata, $primary, $xcrud)
{
    // get random field from $postdata
    $postdata_prepared = array_keys($postdata->to_array());
    shuffle($postdata_prepared);
    $random_field = array_shift($postdata_prepared);
    // set error message
    $xcrud->set_exception($random_field, 'This is a test error', 'error');
}

function test_column_callback($value, $fieldname, $primary, $row, $xcrud)
{
    return $value . ' - nice!';
}

function after_upload_example($field, $file_name, $file_path, $params, $xcrud)
{
    $ext = trim(strtolower(strrchr($file_name, '.')), '.');
    if ($ext != 'pdf' && $field == 'uploads.simple_upload')
    {
        unlink($file_path);
        $xcrud->set_exception('simple_upload', 'This is not PDF', 'error');
    }
}

function movetop($xcrud)
{
    if ($xcrud->get('primary') !== false)
    {
        $primary = (int)$xcrud->get('primary');
        $db = Xcrud_db::get_instance();
        $query = 'SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`';
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item)
        {
            if ($item['officeCode'] == $primary && $key != 0)
            {
                array_splice($result, $key - 1, 0, array($item));
                unset($result[$key + 1]);
                break;
            }
        }

        foreach ($result as $key => $item)
        {
            $query = 'UPDATE `offices` SET `ordering` = ' . $key . ' WHERE officeCode = ' . $item['officeCode'];
            $db->query($query);
        }
    }
}
function movebottom($xcrud)
{
    if ($xcrud->get('primary') !== false)
    {
        $primary = (int)$xcrud->get('primary');
        $db = Xcrud_db::get_instance();
        $query = 'SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`';
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item)
        {
            if ($item['officeCode'] == $primary && $key != $count - 1)
            {
                unset($result[$key]);
                array_splice($result, $key + 1, 0, array($item));
                break;
            }
        }

        foreach ($result as $key => $item)
        {
            $query = 'UPDATE `offices` SET `ordering` = ' . $key . ' WHERE officeCode = ' . $item['officeCode'];
            $db->query($query);
        }
    }
}

function show_description($value, $fieldname, $primary_key, $row, $xcrud)
{
    $result = '';
    if ($value == '1')
    {
        $result = '<i class="fa fa-check" />' . 'OK';
    }
    elseif ($value == '2')
    {
        $result = '<i class="fa fa-circle-o" />' . 'Pending';
    }
    return $result;
}

function custom_field($value, $fieldname, $primary_key, $row, $xcrud)
{
    return '<input type="text" readonly class="xcrud-input" name="' . $xcrud->fieldname_encode($fieldname) . '" value="' . $value .
    '" />';
}
function unset_val($postdata)
{
    $postdata->del('Paid');
}

function format_phone($new_phone)
{
    $new_phone = preg_replace("/[^0-9]/", "", $new_phone);

    if (strlen($new_phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $new_phone);
    elseif (strlen($new_phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $new_phone);
    else
        return $new_phone;
}

function before_list_example($list, $xcrud)
{
    var_dump($list);
}

function after_update_test($pd, $pm, $xc)
{
    $xc->search = 0;
}

function after_upload_test($field, &$filename, $file_path, $upload_config, $this)
{
    $filename = 'bla-bla-bla';
}

function after_insert_student_fees($postdata, $primary, $xcrud){


    $discount=$postdata->get('discount');
    
    
    $fees=$postdata->get('stud_fees_value');
    
    
    
    $total_amount=0;
    if($discount!=0 && $discount!="" && strlen($discount)>1)
    {
        $discount_amount=($fees/100)*$discount;
        
        
        $total_amount=$fees-$discount_amount;
        
        
 
        
    }
    else
    {
     $total_amount=$fees; 
 }
 $total_com=0;

 $per_com=$postdata->get('per_com');
 if($per_com!=0 && $per_com!="" && strlen($per_com)>1 )
 {
    $total_com=($total_amount/100)*$per_com;
    
   
}
$db=  Xcrud_db::get_instance();
$query=" UPDATE student_fees SET 	tot_amount='$total_amount' , tot_com='$total_com' WHERE stud_fees_id='$primary'";
$db->query($query);

}function after_update_student_fees($postdata, $primary, $xcrud){
   $discount=$postdata->get('discount');

   $fees=$postdata->get('stud_fees_value');
   $total_amount=0;
   if($discount!=0 && $discount!="" && strlen($discount)>1)
   {
    $discount_amount=($fees/100)*$discount;
    $total_amount=$fees-$discount_amount;
}
else
{
 $total_amount=$fees; 
}
$total_com=0;

$per_com=$postdata->get('per_com');
if($per_com!=0 && $per_com!="" && strlen($per_com)>1 )
{
    $total_com=($total_amount/100)*$per_com;
}
$db=  Xcrud_db::get_instance();
$query=" UPDATE student_fees SET 	tot_amount='$total_amount' , tot_com='$total_com' WHERE stud_fees_id='$primary'";
$db->query($query);

}

function before_student_notifications($postdata)
{

    $postdata->set('role','student' );

}

function before_staff_notifications($postdata)
{

    $postdata->set('role','staff' );

}

function after_upload_document($postdata)
{

    $postdata->set('role','staff' );

}

function before_general_notifications($postdata)
{

    $postdata->set('role','general' );

}

function before_password_update($postdata)
{

    $postdata->set('role','general' );

}



function staff_after_insert($postdata, $primary, $xcrud)
{
  $to=$postdata->get('staff_email');
  $staff_name=$postdata->get('staff_name');
  $staff_email=$postdata->get('staff_email');
  $staff_password=$postdata->get('staff_password');

  $subject="Login Details";
  $message = file_get_contents("../email_templates/staff_login_mail.tpl");
  $message = str_replace("[NAME]", $staff_name, $message);
  $message = str_replace("[USERNAME]", $staff_email, $message);
  $message = str_replace("[PASSWORD]", $staff_password, $message);


  $txt=$message;
// Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
  $headers .= 'From: support@emcaustralia.com.au';


  mail($to,$subject,$txt,$headers);
}



function student_after_insert($postdata, $primary, $xcrud)
{
  $to=$postdata->get('stud_email');
  $staff_name=$postdata->get('stud_name');
  $staff_email=$postdata->get('stud_email');
  $staff_password=$postdata->get('stud_password');

  $subject="Login Details";
  $message = file_get_contents("../email_templates/student_login_mail.tpl");
  $message = str_replace("[NAME]", $staff_name, $message);
  $message = str_replace("[USERNAME]", $staff_email, $message);
  $message = str_replace("[PASSWORD]", $staff_password, $message);


  $txt=$message;
// Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
  //$headers .= 'From: support@emcaustralia.com.au';
$headers = "From: support@emcaustralia.com.au" . "\r\n" .
"CC: anjali.shree@emcaustralia.com.au,geraldine.quance@emcaustralia.com.au,kathy.tong@emcaustralia.com.au";


  mail($to,$subject,$txt,$headers);
}

function agent_after_insert($postdata, $primary, $xcrud)
{
  $to=$postdata->get('agent_email');
  $staff_name=$postdata->get('agent_name');
  $staff_email=$postdata->get('agent_email');
  $staff_password=$postdata->get('agent_password');
$subject="Login Details";
  $message = file_get_contents("../email_templates/agent_login_mail.tpl");
        $message = str_replace("[NAME]", $staff_name, $message);
        $message = str_replace("[USERNAME]", $staff_email, $message);
        $message = str_replace("[PASSWORD]", $staff_password, $message);

$txt=$message;
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: support@emcaustralia.com.au';


mail($to,$subject,$txt,$headers);
}

function moderator_after_insert($postdata, $primary, $xcrud)
{
  $to=$postdata->get('admin_email');
  $staff_name=$postdata->get('admin_name');
  $staff_email=$postdata->get('admin_username');
  $staff_password=$postdata->get('admin_password');
$subject="Login Details";
  $message = file_get_contents("../email_templates/moderator_login_mail.tpl");
        $message = str_replace("[NAME]", $staff_name, $message);
        $message = str_replace("[USERNAME]", $staff_email, $message);
          $message = str_replace("[PASSWORD]", $staff_password, $message);

$txt=$message;
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: support@emcaustralia.com.au';


mail($to,$subject,$txt,$headers);
}



function student_course_assesment($postdata, $primary, $xcrud)
{
    $to='admin@emcaustralia.com.au';
  
  $id = $postdata->get('stud_id');
  
 
  $course_assesment_query = Xcrud_db::get_instance();
$assesment_query = "SELECT stud_name FROM `student_info` WHERE `stud_id`=".$id;

$course_assesment_query->query($assesment_query);

$course_assesment_record = $course_assesment_query->result();





$stud_name=$course_assesment_record[0]['stud_name'];



    

 // $staff_email=$postdata->get('stud_email');
  //$staff_password=$postdata->get('stud_password');

  $subject="Course Assesment Details";
  $message ="Course Assesment Details are Added for Student Name ".$stud_name;
 // $message = str_replace("[NAME]", $staff_name, $message);
  //$message = str_replace("[USERNAME]", $staff_email, $message);
  //$message = str_replace("[PASSWORD]", $staff_password, $message);


  $txt=$message;
// Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
  $headers .= 'From: support@emcaustralia.com.au';
  //$staff_name=$postdata->';


  mail($to,$subject,$txt,$headers);
}






function crs_after_insert($postdata, $primary, $xcrud)
{
   
   
    include_once 'DOCx.php';
    $doc = new DOCx('../1.docx');

  $name=$postdata->get('name');
  $age=$postdata->get('age');
  $nationality=$postdata->get('nationality');
  $high_qualification=$postdata->get('high_qualification');
  $intended_course=$postdata->get('intended_course');
  $intended_location=$postdata->get('intended_location');
  $ielts=$postdata->get('ielts');
  $industry_if_work=$postdata->get('industry_if_work');
  $year_exp=$postdata->get('year_exp');
  $agent_name=$postdata->get('agent_name');
  $agent_country=$postdata->get('agent_country');
$subject="Login Details";

$doc->setValue('name', $name);
  $doc->setValue('nationality', $nationality);
  $doc->setValue('high_qualification', $high_qualification);
  $doc->setValue('intended_course', $intended_course);
  $doc->setValue('intended_location', $intended_location);
  $doc->setValue('sdsd', $ielts);
  $doc->setValue('industry_if_work', $industry_if_work);
  $doc->setValue('year_exp', $year_exp);
  $doc->setValue('agent_name', $agent_name);
  $doc->setValue('agent_country', $agent_country);
$doc->save('2.docx');
header('Location:process_send_mail.php');


}
function document_after_insert($postdata, $primary, $xcrud)
{
    $filename=$postdata->get('file_upload');
    $student_id=$postdata->get('student_id');
   $directoryName = '../uploads/'.$student_id;
 
//Check if the directory already exists.
if(!is_dir($directoryName)){
    //Directory does not exist, so lets create it.
    mkdir($directoryName, 0755, true);
    
}

 copy('../uploads/'.$filename, $directoryName."/".$filename);
 //var_dump( $directoryName."/".$filename);
  
}