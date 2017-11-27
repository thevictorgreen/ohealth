<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>OPEN HEALTH EMR</title>

    <!-- dhtmlx.js contains all necessary dhtmlx library javascript code -->
    <script src="codebase/dhtmlx.js" type="text/javascript"></script>

    <!-- connector.js used to integrate with the server-side -->
    <script src="codebase/connector/connector.js" type="text/javascript"></script>

    <!-- dhtmlx.css contains styles definitions for all included components -->
    <link rel="STYLESHEET" type="text/css" href="codebase/dhtmlx.css">

    <!-- dhtmlx.js contains all necessary dhtmlx library javascript code -->
    <script src="codebase/dhtmlxscheduler.js" type="text/javascript"></script>

    <!-- dhtmlx.css contains styles definitions for all included components -->
    <link rel="STYLESHEET" type="text/css" href="codebase/dhtmlxscheduler.css">



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

        var mainLayout;
        var subLayout;
        var leftNav;

        dhtmlxEvent(window,"load",function(){

              //Main Layout
              mainLayout = new dhtmlXLayoutObject(document.body,"1C");
              mainLayout.cells("a").hideHeader();
              var mainToolbar = mainLayout.cells("a").attachToolbar();
              mainToolbar.addButton("acctBtn",200,"My Account");
              mainToolbar.addButton("logsBtn",200,"Logs");
              mainToolbar.addButton("signoutBtn",200,"Sign Out");

              //Sub layout
              subLayout = mainLayout.cells("a").attachLayout("3L");


              //Login Code
              lgX = screen.width / 2 - 250;
              lgY = screen.height / 2 - 150;
              loginWin = subLayout.dhxWins.createWindow("lgWin", lgX, lgY, 300, 175);
              loginWin.center();
              loginWin.setText("User Authentication");
              loginWin.denyResize();
              loginWin.denyMove();
              loginWin.denyPark();
              loginWin.button("close").hide();
              loginWin.setModal(true);

              var formData = [
                     {type: "fieldset", name: "myLgForm", label: "Login", list: [
                         {type: "input", label: "Username", labelAlign: "right", position: "label-right", name:"uname"},
                         {type: "password", label: "Password", labelAlign: "right", position: "label-right", name:"pass"},
                         {type: "button", name: "submit",value: "Proceed"}
                      ]}
              ];

              var lgForm = loginWin.attachForm(formData);
              lgForm.attachEvent("onButtonClick",function(id) {

                           if (id == "submit") {
                               loginWin.hide();
                               loginWin.setModal(false);
                               var uname = lgForm.getItemValue("uname");
                               var upass = lgForm.getItemValue("pass");
                               params = "uname=" + uname + "&upass=" + upass;
                           }
              });

              subLayout.cells("a").hideHeader();
              subLayout.cells("a").setWidth(200);
              subLayout.cells("a").fixSize(true,true);
              subLayout.cells("b").attachObject("splash_canvas");
              subLayout.cells("c").setHeight(350);

              leftNav = subLayout.cells("a").attachAccordion();
              leftNav.addItem("a1","SECTION 1");
              leftNav.cells("a1").open();

        })

    </script>
</head>

<body>

 <div id="splash_canvas"><h1>OPEN HEALTH APP</h1></div>


</body>
</html>
