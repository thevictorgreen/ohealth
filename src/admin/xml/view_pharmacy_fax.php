<?php
        require("../codebase/connector/grid_connector.php");
        $conn=mysql_connect("localhost","root","g0th@m");

        mysql_select_db("OPENHEALTH");
        $data = new GridConnector($conn,"MySQL");

        if ($data->is_select_mode()) {
            $data->render_table("gl_pharmacy1","id","id,name,addr,city,state,fax,phone");
        }

        else {
            $data->render_table("gl_pharmacy1","fax");
        }
?>

