<?php
    require_once '../class-db.php';
    require_once '../config.php';
      
    function create_spreadsheet() {
      
        $client = new Google_Client();
      
        $db = new DB();
      
        $arr_token = (array) $db->get_access_token();
        $accessToken = array(
            'access_token' => $arr_token['access_token'],
            'expires_in' => $arr_token['expires_in'],
        );
      
        $client->setAccessToken($accessToken);
      
        $service = new Google_Service_Sheets($client);
      
        try {
            $spreadsheet = new Google_Service_Sheets_Spreadsheet([
                'properties' => [
                    'title' => 'Current Stock Report '.date('d-m-Y')
                ]
            ]);
            $spreadsheet = $service->spreadsheets->create($spreadsheet, [
                'fields' => 'spreadsheetId'
            ]);

            printf("Spreadsheet ID: %s\n", $spreadsheet->spreadsheetId);
            return $spreadsheet->spreadsheetId;

        } catch(Exception $e) {
            if( 401 == $e->getCode() ) {
                $refresh_token = $db->get_refersh_token();
      
                $client = new GuzzleHttp\Client(['base_uri' => 'https://accounts.google.com']);
      
                $response = $client->request('POST', '/o/oauth2/token', [
                    'form_params' => [
                        "grant_type" => "refresh_token",
                        "refresh_token" => $refresh_token,
                        "client_id" => GOOGLE_CLIENT_ID,
                        "client_secret" => GOOGLE_CLIENT_SECRET,
                    ],
                ]);
      
                $data = (array) json_decode($response->getBody());
                $data['refresh_token'] = $refresh_token;
      
                $db->update_access_token(json_encode($data));
      
                create_spreadsheet();
            } else {
                echo $e->getMessage(); //print the error just in case your sheet is not created.
            }
        }
    }

    $sheet_name = 'Current Stock Report '.date('d-m-Y');
    $sheet_id   = create_spreadsheet();

    $db     = new DB();
    $insert = $db->store_sheet_name($sheet_name, $sheet_id);
?>