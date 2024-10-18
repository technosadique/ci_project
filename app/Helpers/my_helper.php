<?php

use App\Models\HelperModel;
use App\Models\AdminModel;
use Twilio\Rest\Client;

use function PHPSTORM_META\type;

function get_single_row_helper($table, $select = "", $where = "", $order_by = [])
{
	$helper_model = new HelperModel();
	return $helper_model->get_single_row($table, $where, $select, $order_by);
}

function get_list_helper($table, $select = "", $where = "", $order_by = [], $group_by = "", $limit="")
{
	$helper_model = new HelperModel();
	return $helper_model->get_list($table, $where, $select, $order_by, $group_by, $limit);
}

function has_permission($class, $method)
{
	$model = new AdminModel();
	$access_data = $model->check_access($_SESSION['access_id'], $class, $method);

	if (isset($access_data['status']) && $access_data['status'] == 1)
		return true;

	return false;
}

function get_dropdown_helper($table, $value_field = "", $name_field = "", $first_null_val = "", $where = "", $order_by = [], $group_by = '')
{

	$helper_model = new HelperModel();

	$name_field_key = $name_field;
	if (is_array($name_field)) {
		$name_field_key = $name_field[1];
		$name_field = $name_field[0];
	}

	$result = $helper_model->get_list($table, $where, $value_field . ',' . $name_field, $order_by);

	// echo "<pre>"; print_r($result);die;
	$dropdown_data = [];
	if ($first_null_val != "")
		$dropdown_data[""] = $first_null_val;

	foreach ($result as $row) {
		$dropdown_data[$row[$value_field]] = $row[$name_field_key];
	}
	return $dropdown_data;
}

// Ajax data for select2 dynamic search for infinity data
function get_dropdown_list_select2($table, $value_field = "", $name_field = "", $where =""){
	if (!$_SESSION['user_id']) {
		echo '{}';
	}
	
	$page = ($_GET['page']?($_GET['page']-1)*10:0);
	$search_key = ($_GET['q']?:'');

	$where = ($where?$where." and ":$where).$name_field." like '%".$search_key."%' and client_id=".$_SESSION['client_id']; 
	$result = get_list_helper($table, $value_field." as id, ".$name_field." as text", $where, [], "", [10, $page]);

	$total_count = get_single_row_helper($table, "count(".$value_field.") as total_count", $where);
	// echo "<pre>"; print_r($total_count); die;
	$output = array(
		"items" => $result, 
		"total_count" => ($total_count['total_count']?:0),
		"per_page" => 10
	);
	
	return $output;
}

// Get next auto inc ID
function get_autoincrement_no($table = '', $inc = 0)
{
	$helper_model = new HelperModel();
	$next_id = $helper_model->get_next_inc_id($table, $inc);

	return $next_id;
}

// Date time => Y-m-d h:i:s
function validate_datetime_format($datetime)
{
	// Valid  format => Y-m-d h:i:s
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$/", $datetime)) {
		return true;
	} else {
		return false;
	}
}

// Valid  format => Y-m-d
function validate_date_format($date)
{
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
		return true;
	} else {
		return false;
	}
}


function ocd_image_resize($file_name, $target_width = 200, $target_height = 200, $folderPath = 'uploads/temp/')
{
	$file = false;
	if (is_array($_FILES)) {
		// Create temp directory if no exist
		if (!file_exists($folderPath)) {
			mkdir($folderPath, 0777, true);
		}

		$file = (is_array($_FILES[$file_name]['tmp_name'])) ? $_FILES[$file_name]['tmp_name'][0] : $_FILES[$file_name]['tmp_name'];

		// echo "<pre>"; print_r($_FILES); die;
		$sourceProperties = getimagesize($file);

		$name = (is_array($_FILES[$file_name]['name'])) ? $_FILES[$file_name]['name'][0] : $_FILES[$file_name]['name'];

		$ext = pathinfo($name, PATHINFO_EXTENSION);
		$fileNewName = time() . "_" . rand(1000, 9999) . "_resized." . $ext;
		$imageType = $sourceProperties[2];

		switch ($imageType) {
			case IMAGETYPE_PNG:
				$imageResourceId = imagecreatefrompng($file);
				$targetLayer = imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1], $target_width, $target_height);
				imagepng($targetLayer, $folderPath . $fileNewName);
				break;

			case IMAGETYPE_GIF:
				$imageResourceId = imagecreatefromgif($file);
				$targetLayer = imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1], $target_width, $target_height);
				imagegif($targetLayer, $folderPath . $fileNewName);
				break;

			case IMAGETYPE_JPEG:
				$imageResourceId = imagecreatefromjpeg($file);
				$targetLayer = imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1], $target_width, $target_height);
				imagejpeg($targetLayer, $folderPath . $fileNewName);
				break;

			default:
				echo "Invalid Image type.";
				break;
		}
		return ($folderPath . $fileNewName);
	}
	return false;
}


function imageResize($imageResourceId, $width, $height, $targetWidth = 200, $targetHeight = 200)
{

	$targetLayer = imagecreatetruecolor($targetWidth, $targetHeight);
	imagecopyresampled($targetLayer, $imageResourceId, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

	return $targetLayer;
}


function ocd_format_size($bytes)
{
	if ($bytes >= 1073741824) {
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	} elseif ($bytes >= 1048576) {
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	} elseif ($bytes > 0) {
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	} else {
		$bytes = '0 bytes';
	}

	return $bytes;
}

function ocd_get_permission_actions($class, $method = "", $module_id = "")
{
	$model = new AdminModel();
	$access_data = $model->check_access($_SESSION['access_id'], $class, $method, $module_id);

	$permission_list = [];
	if($method != "" && !empty($access_data)){
		if ($access_data['status'] == 1)
			return [$access_data['function']];
	}

	else if ($access_data) {
		$permission_list = array_map(function ($row) {
			if ($row['status'] == 1)
				return $row['function'];
		}, $access_data);
	}

	return array_filter($permission_list);
}

/*
	$module: module name flag (customer | booking | invoice | receipt | salik | traffic_fine)
	$type: (email | sms | whatsapp)
	$reference: module ids (comma seperated)
	$receivers: email addresses or phone numbers (comma seperated)
*/
function ocd_add_communication_log($module, $type, $log_data)
{
	$helper_model = new HelperModel();

	// Construct data for log
	$data = [];
	foreach ($log_data as $log) {
		$data[] = [
			'client_id' => $_SESSION['client_id'],
			'user_id' => $_SESSION['user_id'],
			'module' => $module,
			'type' => $type,
			'receiver' => $log['receiver'],
			'reference' => $log['reference'],
			'status' => $log['status'],
		];
	}
	return $helper_model->add_communication_log($data);
}

function ocd_send_sms($phone_number, $message)
{
	$twilio = new Client(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN);

	try {
		$phone_number = $twilio->lookups->v1->phoneNumbers($phone_number)->fetch();
		$message = $twilio->messages->create(
			$phone_number->phoneNumber, // to
			["body" => $message, "from" => TWILIO_NUMBER]
		);
		return $message->sid;
	} catch (Exception $e) {
		return false;
	}
}

// Return an array of the national & Intl format phone numbers if phone no is valid else return false
function ocd_validate_phone_no($phone_number)
{
	$twilio = new Client(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN);

	try {
		$phone_number = $twilio->lookups->v1->phoneNumbers($phone_number)->fetch();
		$data =  [
			'phone_no' => $phone_number->phoneNumber,
			'national_format' => str_replace(" ", "", $phone_number->nationalFormat)
		];
		return $data;
	} catch (Exception $e) {
		return false;
	}
}

// This function will return the link or create & return the link of not already exist
// type can be : booking, receipt or invoice
function ocd_get_public_pdf_link($type, $id)
{
	$helper_model = new HelperModel();

	// Check hash already exist
	$hash_record = get_single_row_helper('hash_id_reference', "*", "module='" . $type . "' and reference_id=" . $id . " and status=1 and in_deleted=0");

	if (empty($hash_record)) {

		switch ($type) {
			case 'booking':
				$group = 'B' . $id . 'Z';
				break;
			case 'invoice':
				$group = 'I' . $id . 'Z';
				break;
			case 'receipt':
				$group = 'R' . $id . 'Z';
				break;
			default:
				$group = '';
				break;
		}
		// Create hash record with alphanumeric (length => 10)
		$shuffled_str = substr(str_shuffle('0123456789ABCDEFGHIJKLMNPQRSTUVWXYabcdefghijklmnpqrstuvwxy'), 0, 10);

		// Hash format => XXB1ZXXXXXXXX , X: Random letters, Z: Devider between Id and random str
		// (ID 1 For booking => B1Z, Invoice => I1Z, Receipt => R1Z)
		$hash_value = substr($shuffled_str, 0, 2) . $group . substr($shuffled_str, 2, 9);

		// Insert record i hash table
		$hash_record = [
			'client_id' => $_SESSION['client_id'],
			'user_id' => $_SESSION['user_id'], // Created by
			'module' => $type,
			'reference_id' => $id,
			'hash' => $hash_value,
		];
		$helper_model->insert_single_record('hash_id_reference', $hash_record);
	}

	// Create the link for public
	$public_link = base_url('customers/pdf/' . $hash_record['hash']);

	return $public_link;
}


function generate_serial_specific($table, $column, $prefix = '', $serial_no_col='', $start_booking, $counter=0)
{  
		// Get max sequence from table
		$serial_info = get_single_row_helper(
			$table . ' tb',
			"(select company_code from company where id=" . $_SESSION['client_id'] . " limit 1) as client_code",
			"client_id=" . $_SESSION['client_id']
		);
		// If empty record for client set the client code
		if(empty($serial_info)){
			$serial_info = get_single_row_helper('company', "id, company_code as client_code", "id=".$_SESSION['client_id']);
		}

		$serial_arr = array_filter([$serial_info['client_code'], $prefix, str_pad(($start_booking + $counter), 4, '0', STR_PAD_LEFT)]);
		// Check serial already exist (Serial no column required to check and prevent duplicate serial..)
		 $new_serial_no = implode('-', $serial_arr);
		if($serial_no_col != ''){
			$check_duplicate = get_single_row_helper($table, $column,"client_id=".$_SESSION['client_id']." and ".$serial_no_col."='".$new_serial_no."'");
			
			if(!empty($check_duplicate)){
				 $counter++;
				return generate_serial_specific($table, $column, $prefix, $serial_no_col, $start_booking, $counter);
			}
		}
		return $new_serial_no;
	 
		
	}

/*
	* Generate unique sequence/serial code for table
	* @param string $table for table name
	* @param string $column column name for get max no for client
	* @param string $prefix 
*/
function generate_serial($table, $column, $prefix = '', $serial_no_col='', $counter=0)
{
	// Get max sequence from table
	$serial_info = get_single_row_helper(
		$table . ' tb',
		"IFNULL(COUNT(tb." . $column . "),0) AS max_no, (select company_code from company where id=" . $_SESSION['client_id'] . " limit 1) as client_code",
		"client_id=" . $_SESSION['client_id']
	);
	//print_r($serial_info);exit;
	// If empty record for client set the client code
	if(empty($serial_info)){
		$serial_info = get_single_row_helper('company', "id, company_code as client_code", "id=".$_SESSION['client_id']);
	}

	$serial_arr = array_filter([$serial_info['client_code'], $prefix, "1" . str_pad(($serial_info['max_no'] + 1 + $counter), 4, '0', STR_PAD_LEFT)]);
	//print_r($serial_arr);exit;
	// Check serial already exist (Serial no column required to check and prevent duplicate serial..)
	$new_serial_no = implode('-', $serial_arr);
	if($serial_no_col != ''){
		$check_duplicate = get_single_row_helper($table, $column,"client_id=".$_SESSION['client_id']." and ".$serial_no_col."='".$new_serial_no."'");
		if(!empty($check_duplicate)){
			$counter++;
			return generate_serial($table, $column, $prefix, $serial_no_col, $counter);
		}
	}
	
	return $new_serial_no;
}

// Get next auto inc ID
function db_backup($tbls=false)
{
	$helper_model = new HelperModel();
	$tbls = ($tbls!="")?[$tbls]:false;
	$helper_model->db_backup_dwn($tbls);
}

// Get db info
function get_db_info()
{
	$helper_model = new HelperModel();

	// Test query
	get_single_row_helper('company', 'id', 'in_deleted=0');
	$db_data = $helper_model->get_db_info();

	echo "<pre>";
	print_r($db_data->mysqli);
}



// Encrypt Function
function ocd_encrypt($string, $secret_key = ENCRYPTION_KEY)
{ //support php 7
	$output = false;
	$encrypt_method = "AES-256-CBC";
	$secret_iv = 'ocd_crm_23';
	// hash
	$key = hash('sha256', $secret_key);

	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($output);
	return $output;
}

// Decrypt Function
function ocd_decrypt($string, $secret_key = ENCRYPTION_KEY)
{ //support php 7
	$output = false;
	$encrypt_method = "AES-256-CBC";
	$secret_iv = 'ocd_crm_23';
	// hash
	$key = hash('sha256', $secret_key);

	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}

function ocd_http_request($method, $url, $data = false) {
	$curl = curl_init();

	switch ($method) {
		case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);

			if ($data) {
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}
			
			break;
		case "PUT":
			curl_setopt($curl, CURLOPT_PUT, 1);
			
			break;
		default:
			if ($data) {
				$url = sprintf("%s?%s", $url, http_build_query($data));
			}
	}

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //If SSL Certificate Not Available, for example, I am calling from http://localhost URL

	$result = curl_exec($curl);

	curl_close($curl);

	return $result;
}

// Return array to XML
function ocd_array_to_xml($array=''){
	
	// Create a new XML element
	$xml = new SimpleXMLElement('<data></data>');
	
	// Function to convert array to XML
	function array_to_xml($array, &$xml) {
		foreach($array as $key => $value) {
			if(is_array($value)) {
				$subnode = $xml->addChild($key);
				array_to_xml($value, $subnode);
			} else {
				$xml->addChild($key, $value);
			}
		}
	}
	
	// Call the function to convert array to XML
	array_to_xml($array, $xml);
	
	// Output the XML
	echo $xml->asXML();
}

function remove_directory($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object)) {
                    remove_directory($dir . "/" . $object);
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }
}

// Check for the super admin (Firs admin for the clieent)
function super_admin_permssions(){
	// Get the first admin (super admin) for the client
	$admin_acc = get_single_row_helper("user","id, first_name, client_id, last_name,email", "access_id = 1 and client_id=".$_SESSION['client_id']." and id=".$_SESSION['user_id']." and in_deleted=0 and status=1");

	if(!empty($admin_acc))
		return true;

	return false;

}
