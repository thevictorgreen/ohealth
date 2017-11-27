<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>

    <!-- dhtmlx.js contains all necessary dhtmlx library javascript code -->
    <script src="codebase/dhtmlx.js" type="text/javascript"></script>

    <!-- connector.js used to integrate with the server-side -->
    <script src="codebase/connector/connector.js" type="text/javascript"></script>

    <!-- dhtmlx.css contains styles definitions for all included components -->
    <link rel="STYLESHEET" type="text/css" href="codebase/dhtmlx.css">

    <link rel="stylesheet" type="text/css" href="codebase/dhtmlxvault.css" />
    <script language="JavaScript" type="text/javascript" src="codebase/dhtmlxvault.js"></script>

    <!-- Fusion Charts Components  -->
    <script type="text/javascript" src="FusionCharts/FusionCharts.js"></script>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>


    <style>
        /*these styles allow dhtmlxLayout to work in fullscreen mode in different browsers correctly*/
        html, body {
           width: 100%;
           height: 100%;
           margin: 0px;
           overflow: hidden;
           background-color:white;
        }
    </style>

</head>

<body>


<script>

var url = "http://api.firstmedisource.com/call.php/states?user_key=654628232eb57960ccad23ec60d1a150";
    $.ajax({ type: "GET",
        url: url,
        dataType: "json",
        error: function (xhr, status, error) {
            alert(error);
        },
        success: function (json) {
            alert(json.length);
        }
    });

</script>


</body>

</html>
