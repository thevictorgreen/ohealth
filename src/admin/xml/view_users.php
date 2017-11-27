<?php
        require("../codebase/connector/grid_connector.php");
        $conn=mysql_connect("localhost","root","g0th@m");

        mysql_select_db("OPENHEALTH");
        $data = new GridConnector($conn,"MySQL");

        $id = 1;//$_GET["id"];
        $result = mysql_query("SELECT dr_users from gl_droffice where id = $id");
        $row = mysql_fetch_array($result);
        $dr_users = $row["dr_users"];

        if ($data->is_select_mode()) {
            $data->render_sql("select id,last_name,first_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num from $dr_users","id","id,last_name,first_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num");
        }

        else {
            $data->render_table("$dr_users","id","id,last_name,first_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num");
        }
?>

