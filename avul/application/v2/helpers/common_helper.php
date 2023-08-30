<?php
// check user is active or not
function is_user_active($url_after_login = '', $redirect = TRUE)
{
    $CI = &get_instance();

    /*
	| check request require redirect or not
	| if not then return respone or output
	*/
    if (!$redirect) {
        return ($CI->session->userdata('user_id') == FALSE) ? FALSE : TRUE;
    }

    // if the user is active, then return response or output
    if ($CI->session->userdata('user_id') !== FALSE) {
        return TRUE;
    }

    // set next page url to redirect after user login
    if ($url_after_login !== '') {
        $CI->session->set_userdata('next_url', $url_after_login);
    }

    //$CI->session->set_flashdata('noti_msg', '<p>You must login to continue</p>');

    safe_redirect();
}

function get_user_name()
{
    $CI = &get_instance();
    $username = $CI->session->userdata('user_name');

    return $username;
}

function safe_redirect()
{
    //echo "dfdfgfdg";
    $CI = &get_instance();

    if ($CI->input->is_ajax_request() === FALSE) {
        redirect('login');
    }

    $base_url = $CI->config->item('base_url');

    echo '<script>window.location = "' . $base_url . 'login";</script>';
}

// Mail Validate
function mailvalidate($email)
{
    // echo "fdgfh";
    $CI = &get_instance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return FALSE;
    } else {
        return TRUE;
    }
}

//Check Password
function checkpass($pass)
{
    $CI = &get_instance();

    if (1 !== preg_match("/^.*(?=.{6,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()\-_=+{};:,<.>§~]).*$/", $pass)) {
        return FALSE;
    } else {
        return TRUE;
    }
}
// function testencrypting(){
//   $CI =& get_instance();
//   $str = '12345';
//   $key = 'my-secret-key';
//   $encrypted = $CI->encrypt->encode($str, $key);
//   echo $CI->encrypt->decode($encrypted, $key);
//   exit;
// }

// Mail Validate
function avul_call($avul_url = "", $data = '')
{
    $CI = &get_instance();
    $postData = '';
    //create name value pairs seperated by &
    foreach ($data as $k => $v) {
        $postData .= $k . '=' . $v . '&';
    }
    rtrim($postData, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $avul_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $output = curl_exec($ch);

    curl_close($ch);
    $arraydata = json_decode($output, true);
    if ($arraydata) {
        return $arraydata;
    } else {
        return false;
    }
}

function avul_call_check($avul_url = "", $data = '')
{
    $CI = &get_instance();
    $postData = '';
    //create name value pairs seperated by &
    foreach ($data as $k => $v) {
        $postData .= $k . '=' . $v . '&';
    }
    // print_r($postData);
    // exit;
    rtrim($postData, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $avul_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $output = curl_exec($ch);

    curl_close($ch);
    // print_r($output);exit;
    $arraydata = json_decode($output, true);
    if ($arraydata['data']) {
        return $arraydata['data'];
    } else {
        return false;
    }
}

function avul_get_val($url)
{
    $jsondata = file_get_contents($url);
    $arraydata = json_decode($jsondata, true);
    if ($arraydata['data']) {
        return $arraydata['data'];
    } else {
        return false;
    }
}

function avul_call_many($avul_url = "", $data = '')
{
    $CI = &get_instance();
    $post = ['insertval' => $data];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $avul_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $output = curl_exec($ch);

    curl_close($ch);
    //print_r($output);exit;
    return $output;
    //         $arraydata=json_decode($output, true);
    //         if($arraydata['data'])
    // 		{
    //         	return $arraydata['data'];
    //     	}
    //     	else
    //     	{
    //     		return false;
    //     	}
}

function avul_call_full_res($avul_url = "", $data = '')
{
    $CI = &get_instance();
    $postData = '';
    //create name value pairs seperated by &
    foreach ($data as $k => $v) {
        $postData .= $k . '=' . $v . '&';
    }
    rtrim($postData, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $avul_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    //curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $output = curl_exec($ch);

    curl_close($ch);
    //print_r($output);exit;
    $arraydata = json_decode($output, true);
    if ($arraydata) {
        return $arraydata;
    } else {
        return false;
    }
}

function avul_bulk_insert($avul_url = "", $data = '')
{
    $CI = &get_instance();
    $s = explode(":", $avul_url);
    $ss = explode("/", $s[2]);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_PORT => $ss[0],
        CURLOPT_URL => $avul_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return false;
    } else {
        return true;
    }
}

function user_priv($key)
{
    // echo "fdgfh";
    $CI = &get_instance();
    $url = API_URL . '/api/v1/privilege/check';
    $where = array('privilege_key' => $key, 'process' => '5');
    $check = avul_call($url, $where);
    //print_r($check);
    if ($check) {
        $privilege_key = $check[0]['_id'];
        $url1 = API_URL . '/api/v1/privilege/userprivilege_check';
        $where1 = array('user' => $CI->session->userdata('user_id'), 'process' => '5');
        $check1 = avul_call($url1, $where1);
        if ($check1) {
            $privileges = $check1[0]['privilege_key'];
            $privcheck = explode(',', $privileges);
            if ($privcheck) {
                $val = 0;
                foreach ($privcheck as $key => $value) {
                    if ($value == $privilege_key) {
                        $val = 1;
                    }
                }
                if ($val == 0) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

function leadingZeros($num, $numDigits)
{
    return sprintf("%0" . $numDigits . "d", $num);
}

function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function monthName($value)
{
    if ($value == 1) {
        return 'January';
    } else if ($value == 2) {
        return 'February';
    } else if ($value == 3) {
        return 'March';
    } else if ($value == 4) {
        return 'April';
    } else if ($value == 5) {
        return 'May';
    } else if ($value == 6) {
        return 'June';
    } else if ($value == 7) {
        return 'July';
    } else if ($value == 8) {
        return 'August';
    } else if ($value == 9) {
        return 'September';
    } else if ($value == 10) {
        return 'October';
    } else if ($value == 11) {
        return 'November';
    } else if ($value == 12) {
        return 'December';
    }
}

function new_dis($lat1, $lon1, $lat2, $lon2)
{
    $theta = $lon1 - $lon2;
    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $feet  = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
}

function avul_fileUpload($avul_url = '', $postData = '', $postImage = '')
{
    $files = array();

    foreach ($_FILES["image"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {

            $files["image[$key]"] = curl_file_create(
                $_FILES['image']['tmp_name'][$key],
                $_FILES['image']['type'][$key],
                $_FILES['image']['name'][$key]
            );
        }
    }

    $data = $postData + $files;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_POST => 1,
        CURLOPT_URL  => $avul_url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLINFO_HEADER_OUT => 1,
        CURLOPT_POSTFIELDS => $data
    ));

    $response = curl_exec($curl);
    $info = curl_getinfo($curl);
    // echo $response; exit();
    curl_close($curl);

    $arraydata = json_decode($response, true);
    if ($arraydata) {
        return $arraydata;
    } else {
        return false;
    }
}

function post_img($fileName, $tempFile, $targetFolder)
{
    if ($fileName != "") {
        copy($tempFile, $targetFolder . "/" . $fileName);
        return $fileName;
    }
}

function getStartAndEndDate($week, $year)
{
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}

function numberTowords($num)
{

    $ones = array(
        0 => "zero",
        1 => "one",
        2 => "two",
        3 => "three",
        4 => "four",
        5 => "five",
        6 => "six",
        7 => "seven",
        8 => "eight",
        9 => "nine",
        10 => "ten",
        11 => "eleven",
        12 => "twelve",
        13 => "thirteen",
        14 => "fourteen",
        15 => "fifteen",
        16 => "sixteen",
        17 => "seventeen",
        18 => "eighteen",
        19 => "nineteen",
        "014" => "fourteen"
    );
    $tens = array(
        0 => "zero",
        1 => "ten",
        2 => "twenty",
        3 => "thirty",
        4 => "forty",
        5 => "fifty",
        6 => "sixty",
        7 => "seventy",
        8 => "eighty",
        9 => "ninety"
    );
    $hundreds = array(
        "hundred",
        "thousand",
        "million",
        "billion",
        "trillion",
        "quardrillion"
    ); /*limit t quadrillion */
    $num = number_format($num, 2, ".", ",");
    $num_arr = explode(".", $num);
    $wholenum = $num_arr[0];
    $decnum = $num_arr[1];
    $whole_arr = array_reverse(explode(",", $wholenum));
    krsort($whole_arr, 1);
    $rettxt = "";
    foreach ($whole_arr as $key => $i) {

        while (substr($i, 0, 1) == "0")
            $i = substr($i, 1, 5);
        if ($i < 20) {
            /* echo "getting:".$i; */
            $rettxt .= $ones[$i];
        } elseif ($i < 100) {
            if (substr($i, 0, 1) != "0")  $rettxt .= $tens[substr($i, 0, 1)];
            if (substr($i, 1, 1) != "0") $rettxt .= " " . $ones[substr($i, 1, 1)];
        } else {
            if (substr($i, 0, 1) != "0") $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
            if (substr($i, 1, 1) != "0") $rettxt .= " " . $tens[substr($i, 1, 1)];
            if (substr($i, 2, 1) != "0") $rettxt .= " " . $ones[substr($i, 2, 1)];
        }
        if ($key > 0) {
            $rettxt .= " " . $hundreds[$key] . " ";
        }
    }
    if ($decnum > 0) {
        $rettxt .= " and ";
        if ($decnum < 20) {
            $rettxt .= $ones[$decnum];
        } elseif ($decnum < 100) {
            $rettxt .= $tens[substr($decnum, 0, 1)];
            $rettxt .= " " . $ones[substr($decnum, 1, 1)];
        }
    }
    return $rettxt;
}

function getBetweenDates($startDate, $endDate)
{
    $rangArray = [];
    $startDate = strtotime($startDate);
    $endDate   = strtotime($endDate);

    for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {

        $date = date('Y-m-d', $currentDate);
        $rangArray[] = $date;
    }

    return $rangArray;
}


function dateDiffInDays($date1, $date2)
{
    // Calculating the difference in timestamps
    $diff = strtotime($date2) - strtotime($date1);

    // 1 day = 24 hours
    // 24 * 60 * 60 = 86400 seconds
    return abs(round($diff / 86400));
}

function empty_check($value)
{
    return !empty($value) ? $value : NULL;
}

function zero_check($value)
{
    return !empty($value) ? $value : '0';
}

function date_check($value)
{
    if (!empty($value)) {
        $result = date('d-m-Y', strtotime($value));
    } else {
        $result = null;
    }

    return $result;
}

function system_date($value)
{
    if (!empty($value)) {
        $result = date('Y-m-d', strtotime($value));
    } else {
        $result = null;
    }

    return $result;
}

function time_check($value)
{
    if (!empty($value)) {
        $result = date('h:i A', strtotime($value));
    } else {
        $result = null;
    }

    return $result;
}

function discount_check($value = '', $discout = '')
{
    if (!empty($discout)) {
        $calculate = $value * $discout * 100;

        return number_format((float)round($calculate), 2, '.', '');
    } else {
        return number_format((float)round($value), 2, '.', '');
    }
}

function urlSlug($text, string $divider = '-')
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

function userAccess($prevKey)
{
    $CI = &get_instance();
    if ($prevKey != '') {

        $userAccess   = $CI->session->userdata('role_list');
        $explodeRoles = explode(",", $userAccess);
        if (!empty($explodeRoles)) {
            $access = 0;
            foreach ($explodeRoles as $key => $value) {
                if ($value == $prevKey) {
                    $access = 1;
                }
            }
            if ($access == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

function digit_val($value = '', $digit = '2')
{
    return number_format((float)$value, $digit, '.', '');
}

// Random Number
function generateRandomnumber($length = '')
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    if ($randomString) {
        return $randomString;
    } else {
        return 0;
    }
}

// SMS Function
function sendSMS($mobile, $message)
{
    $message = urlencode($message);
    $sms_url = "http://login.bulksmsservice.net.in/api/mt/SendSMS?user=nananani&password=Ananya@2010&senderid=ANYGRP&channel=TRANS&DCS=0&flashsms=0&number=" . $mobile . "&text=" . $message . "&route=8";

    $ch = curl_init($sms_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return TRUE;
}

function gst_validation($value = '')
{
    $pattern = "/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i";

    if (preg_match($pattern, $value)) {
        return 1;
    } else {
        return 0;
    }
}

function contact_validation($value = '')
{
    $pattern_1 = "/^\+?(\d{1,4})?[-.\s]?\(?(\d{3})\)?[-.\s]?(\d{3})[-.\s]?(\d{4})$/";

    if (preg_match($pattern_1, $value)) {
        return 1;
    } else {
        return 0;
    }
}

// Image Compress
function compressImage($source, $destination, $quality)
{
    // Get image info 
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file 
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            $image = imagecreatefromjpeg($source);
    }

    // Save image 
    imagejpeg($image, $destination, $quality);

    // Return compressed image 
    return $destination;
}

function convert_filesize($bytes, $decimals = 2)
{
    $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

function avul_sendmail($data = '')
{
    $postData = '';
    foreach ($data as $k => $v) {
        $postData .= $k . '=' . $v . '&';
    }
    rtrim($postData, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.datasense.in/demo/ci/v2.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $output = curl_exec($ch);

    curl_close($ch);

    $arraydata = json_decode($output, true);

    return $arraydata ? true : false;
}

function avul_attachmentMail($data = '')
{
    $postData = '';
    foreach ($data as $k => $v) {
        $postData .= $k . '=' . $v . '&';
    }
    rtrim($postData, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.datasense.in/demo/ci/v3.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $output = curl_exec($ch);

    curl_close($ch);

    $arraydata = json_decode($output, true);
    if ($arraydata) {
        return true;
    } else {
        return false;
    }
}

// get date range
function getDatesFromRange($start, $end, $format = 'Y-m-d')
{

    $array    = array();
    $interval = new DateInterval('P1D');
    $realEnd  = new DateTime($end);
    $realEnd->add($interval);
    $period   = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach ($period as $date) {
        $array[] = $date->format($format);
    }

    return $array;
}

// Generate E-invoice & E-waybill
function generateEinvoice($url_call = '', $order_json = '')
{
    $curl = curl_init($url_call);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $order_json);
    $result  = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
}
