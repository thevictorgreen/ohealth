<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Web Title Goes Here!!</title>

    <!-- dhtmlx.js contains all necessary dhtmlx library javascript code -->
    <script src="codebase/dhtmlx.js" type="text/javascript"></script>

    <!-- connector.js used to integrate with the server-side -->
    <script src="codebase/connector/connector.js" type="text/javascript"></script>

    <!-- dhtmlx.css contains styles definitions for all included components -->
    <link rel="STYLESHEET" type="text/css" href="codebase/dhtmlx.css">

    <!-- Fusion Charts Components  -->
    <script type="text/javascript" src="FusionCharts/FusionCharts.js"></script>

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

    <script type="text/javascript">
        //Here we'll put the code of the application

        var layout;
        var leftNav;


        dhtmlxEvent(window,"load",function(){

              //layout
              layout = new dhtmlXLayoutObject(document.body,"3L");
              layout.cells("a").setText("Navigate");
              layout.cells("b").hideHeader();
              layout.cells("c").hideHeader();
              layout.cells("a").setWidth(200);
              layout.cells("c").setHeight(350);

        })

    </script>
</head>

<body>



</body>
</html>
