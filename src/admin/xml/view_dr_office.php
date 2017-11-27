<?php
        require("../codebase/connector/grid_connector.php");
        $conn=mysql_connect("localhost","root","g0th@m");

        mysql_select_db("OPENHEALTH");

        $data = new GridConnector($conn,"MySQL");
        $data->render_table("gl_droffice","id","id,name,addr,city,state,zip,phone");

?>

