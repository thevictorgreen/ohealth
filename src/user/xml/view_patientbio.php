<?php
        require("../codebase/connector/grid_connector.php");
        $conn=mysql_connect("localhost","root","g0th@m");

        mysql_select_db("OPENHEALTH");
        $data = new GridConnector($conn,"MySQL");

        //GET APPROPRIATE TABLE(S)
        $result = mysql_query("SELECT dr_patients,api_key from gl_droffice where id = 1");
        $row = mysql_fetch_array($result);
        $dr_patients = $row['dr_patients'];
        $api_key = $row['api_key'];

        //GET TDE KEY FROM TDE SERVER
        $service_url = 'http://api.firstmedisource.com/call.php/gettdekey/' . $api_key . '?user_key=' . $api_key;
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);

        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }

        curl_close($curl);
        $decoded = json_decode($curl_response,true);

        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occured: ' . $decoded->response->errormessage);
        }

        $tde_key = $decoded[0]["tde_key"];


        if ($data->is_select_mode()) {
            $data->render_sql("select id,AES_DECRYPT(last_name,'$tde_key') as last_name,AES_DECRYPT(first_name,'$tde_key') as first_name,AES_DECRYPT(dob,'$tde_key') as dob,AES_DECRYPT(addr,'$tde_key') as addr,AES_DECRYPT(city,'$tde_key') as city,AES_DECRYPT(state,'$tde_key') as state,AES_DECRYPT(zip,'$tde_key') as zip, AES_DECRYPT(phone,'$tde_key') as phone,AES_DECRYPT(cell,'$tde_key') as cell,AES_DECRYPT(email,'$tde_key') as email,AES_DECRYPT(ssec,'$tde_key') as ssec FROM $dr_patients","id","id,last_name,first_name,dob,addr,city,state,zip,phone,cell,email,ssec");
        }

?>

