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
        $data->render_table("gl_droffice","id","id,name,addr,city,state,zip,phone");
?>
