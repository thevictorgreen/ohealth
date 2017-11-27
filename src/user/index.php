<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>OPEN HEALTH EHR APP</title>

    <!-- dhtmlx.js contains all necessary dhtmlx library javascript code -->
    <script src="codebase/dhtmlx.js" type="text/javascript"></script>

    <!-- connector.js used to integrate with the server-side -->
    <script src="codebase/connector/connector.js" type="text/javascript"></script>

    <!-- dhtmlx.css contains styles definitions for all included components -->
    <link rel="STYLESHEET" type="text/css" href="codebase/dhtmlx.css">

    <script src="dhtmlxForm/codebase/ext/dhtmlxform_item_container.js" type="text/javascript"></script>


    <link href="/maps/documentation/javascript/examples/default.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

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

    <script src="js/ohealth.js"></script>
</head>

<body>

 <div id="splash_canvas"><h1>OPEN HEALTH APP</h1></div>

 <div id="no_access1_canvas"><h1>ACCESS DENIED</h1></div>
 <div id="no_access2_canvas"><h3>CONTACT YOUR SYSTEM ADMINISTRATOR</h3></div>

 <div id="viewMap_canvas" style="height:100%;width:100%"></div>
 <div id="dvMap" style="height:100%;width:100%">

 <div id="chart1_canvas"><h1>PATIENT CHART</h1></div>
 <div id="chart2_canvas"><h3>SELECT AN AREA</h3></div>



</body>
</html>
