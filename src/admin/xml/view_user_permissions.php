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
        $result = $db->query("SELECT dr_users,dr_permissions from gl_droffice where id = $id");
        $row = $result->fetch();
        $dr_users = $row["dr_users"];
        $dr_permissions = $row["dr_permissions"];

        if ($data->is_select_mode() && isset($_GET["id"])) {
            $data->render_sql("select $dr_permissions.u_id,$dr_users.last_name,$dr_users.first_name,$dr_users.login,$dr_permissions.submitScript,$dr_permissions.submitScriptDirect,$dr_permissions.submitScriptQueOnly,$dr_permissions.viewScript,$dr_permissions.viewScriptQue,$dr_permissions.viewRefills,$dr_permissions.viewAdherence,$dr_permissions.viewPatients,$dr_permissions.viewCharts,$dr_permissions.viewFormulary,$dr_permissions.viewReports from $dr_permissions,$dr_users where $dr_permissions.u_id = $dr_users.id","u_id","u_id,last_name,first_name,login,submitScript,submitScriptDirect,submitScriptQueOnly,viewScript,viewScriptQue,viewRefills,viewAdherence,viewPatients,viewCharts,viewFormulary,viewReports");
        }

?>
