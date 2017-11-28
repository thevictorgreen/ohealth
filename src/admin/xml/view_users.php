<?php
        require("../codebase/connector/grid_connector.php");
        require("../codebase/connector/db_pdo.php");

        // DATABASE CONNECTION CODE
        $db_host = getenv('DB_HOST');
        $db_name = getenv('DB_NAME');
        $db_user = getenv('DB_USER');
        $db_pass = getenv('DB_PASS');
        $db = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4',$db_user,$db_pass);
        // DATABASE CONNECTION CODE

        $data = new GridConnector($db,"PDO");

        $id = 1;//$_GET["id"];
        $result = $db->query("SELECT dr_users from gl_droffice where id = $id");
        $row = $result->fetch();
        $dr_users = $row["dr_users"];

        if ($data->is_select_mode()) {
            $data->render_sql("select id,last_name,first_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num from $dr_users","id","id,last_name,first_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num");
        }

        else {
            $data->render_table("$dr_users","id","id,last_name,first_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num");
        }
?>
