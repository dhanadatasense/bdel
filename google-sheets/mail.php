<?php
	
	function avul_sendmail($data='')
    {
        $postData = '';
        foreach($data as $k => $v) 
        { 
            $postData .= $k . '='.$v.'&'; 
        }
        rtrim($postData, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://www.datasense.in/demo/ci/v2.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

        $output=curl_exec($ch);

        curl_close($ch);
        
        $arraydata=json_decode($output, true);

        return $arraydata ? true : false;
    }

	$buffer = "
		<html>
		    <head>
		        <title>Hoisst</title>
		    </head>
		    <body>
		        <span>Dear sir,</span>
		        <br>
		        <p style='margin-left: 30px;'>Kindly click the below link and login the HOISST ERP Gmail account for the data import purpose. And share the current stock sheet to management team.</p>
		        <span style='margin-left: 30px;'>To click on the link below:</span><br>
		        <span style='margin-left: 30px;'><a href='hoisst.com/google-sheets-api/callback.php'>click here</a></span><br><br>
		    </body>
		</html>
	";

	$data_1 = array(
        'to'       => 'muthurasu636@gmail.com',
        'subject'  => 'Reg: Hoisst ERP',
        'sitename' => 'Hoisst',
        'site'     => 'hoisst.com',
        'message'  => $buffer,
    );

    $data_2 = array(
        'to'       => 'prince@hoisst.in',
        'subject'  => 'Reg: Hoisst ERP',
        'sitename' => 'Hoisst',
        'site'     => 'hoisst.com',
        'message'  => $buffer,
    );

    $data_3 = array(
        'to'       => 'umesh.upendran@hoisst.in',
        'subject'  => 'Reg: Hoisst ERP',
        'sitename' => 'Hoisst',
        'site'     => 'hoisst.com',
        'message'  => $buffer,
    );

    $data_4 = array(
        'to'       => 'abdulvahid@datasense.in',
        'subject'  => 'Reg: Hoisst ERP',
        'sitename' => 'Hoisst',
        'site'     => 'hoisst.com',
        'message'  => $buffer,
    );

    $sendMail_1 = avul_sendmail($data_1);
    $sendMail_2 = avul_sendmail($data_2);
    $sendMail_3 = avul_sendmail($data_3);
    $sendMail_4 = avul_sendmail($data_4);
?>