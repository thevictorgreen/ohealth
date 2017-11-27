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
        var uname;

        dhtmlxEvent(window,"load",function(){

              //Main Layout
              mainLayout = new dhtmlXLayoutObject(document.body,"1C");
              mainLayout.cells("a").hideHeader();
              var mainToolbar = mainLayout.cells("a").attachToolbar();
              mainToolbar.addButton("homeBtn",200,"Home");
              mainToolbar.addButton("acctBtn",200,"My Account");
              mainToolbar.addButton("logsBtn",200,"Logs");
              mainToolbar.addButton("signoutBtn",200,"Sign Out");

              mainToolbar.attachEvent("onClick",function(id){
                  if (id == "homeBtn") {
                        //alert("test");
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();
                         subLayout.cells("b").setText("APPOINTMENTS");
                         subLayout.cells("c").setText("DOCTORS");

                         var scriptTools = subLayout.cells("b").attachToolbar();
                         scriptTools.addButton("newApp",0,"NEW APPOINTMENT");

                         scheduler.config.xml_date="%Y-%m-%d %H:%i";
                         scheduler.init('app_canvas',new Date(2010,0,10),"week");
                         scheduler.load("./data/events.xml");
                         subLayout.cells("b").attachObject("app_canvas");
                         //subLayout.cells("c").setHeight(350);
                  }

                  if (id == "signoutBtn") {
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
                               uname = lgForm.getItemValue("uname");
                               var upass = lgForm.getItemValue("pass");

                               params = "uname=" + uname + "&upass=" + upass;
                               //alert(params);
                           }
                       });
                 }
              });

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
                               //alert(params);
                           }
              });

              subLayout.cells("a").hideHeader();
              subLayout.cells("b").setText("APPOINTMENTS");
              subLayout.cells("c").setText("DOCTORS");
              subLayout.cells("a").setWidth(200);
              subLayout.cells("a").fixSize(true,true);
              //subLayout.cells("b").attachObject("splash_canvas");
              subLayout.cells("c").setHeight(350);
              var scriptTools = subLayout.cells("b").attachToolbar();
              scriptTools.addButton("newApp",0,"NEW APPOINTMENT");

              scheduler.config.xml_date="%Y-%m-%d %H:%i";
              scheduler.init("app_canvas",new Date(2010,0,10),"week");
              scheduler.load("./data/events.xml");
              subLayout.cells("b").attachObject("app_canvas");

              leftNav = subLayout.cells("a").attachAccordion();
              leftNav.addItem("a1","APPOINTMENTS");
              leftNav.addItem("b1","PATIENTS");
              leftNav.addItem("c1","PRESCRIPTIONS");
              leftNav.addItem("d1","REPORTS");
              leftNav.cells("a1").open();


              var appForm = leftNav.cells("a1").attachForm();
              var patForm = leftNav.cells("b1").attachForm();
              var preForm = leftNav.cells("c1").attachForm();
              var repForm = leftNav.cells("d1").attachForm();

              appForm.loadStructString('<items><item width="193" type="button" name="viewApp" value="View Appointments"/></items>');
              //viewApp
              patForm.loadStructString('<items><item width="193" type="button" name="viewPat" value="View Patients"/><item width="193" type="button" name="viewChart" value="View Chart"/></items>');
              //viewPat
              preForm.loadStructString('<items><item width="193" type="button" name="createMed" value="Submit Prescriptions"/><item width="193" type="button" name="viewMed" value="View Prescriptions"/><item width="193" type="button" name="viewMedQue" value="View Prescriptions Queue"/><item width="193" type="button" name="viewMedRef" value="Refill Authorization"/><item width="193" type="button" name="viewMedForm" value="View Formularies"/></items>');
              //createMed
              //viewMed
              //viewMedQue
              //viewMedRef
              //viewMedForm
              repForm.loadStructString('<items><item width="193" type="button" name="viewDrRep" value="Dr Reports"/><item width="193" type="button" name="viewPatRep" value="Patient Reports"/><item width="193" type="button" name="viewMedRep" value="Medication Reports"/></items>');
              //viewDrRep
              //viewPatRep
              //viewMedRep

             appForm.attachEvent("onButtonClick",function(id){

                    if (id == "viewApp") {
                        //alert("test");
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();
                         subLayout.cells("b").setText("APPOINTMENTS");
                         subLayout.cells("c").setText("DOCTORS");

                         var scriptTools = subLayout.cells("b").attachToolbar();
                         scriptTools.addButton("newApp",0,"NEW APPOINTMENT");

                         scheduler.config.xml_date="%Y-%m-%d %H:%i";
                         scheduler.init('app_canvas',new Date(2010,0,10),"week");
                         scheduler.load("./data/events.xml");
                         subLayout.cells("b").attachObject("app_canvas");
                         //subLayout.cells("c").setHeight(350);
                    }

             });


             patForm.attachEvent("onButtonClick",function(id){

                    if (id == "viewPat") {
                         //alert("View Patients");
                         //Create layout
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         //subLayout.cells("b").hideHeader();
                         subLayout.cells("c").setHeight(350);

                         var subTopLayout = subLayout.cells("b").attachLayout("2U");
                         subTopLayout.cells("a").setText("PATIENTS");
                         subTopLayout.cells("b").setText("PATIENT DETAILS");

                         var subBotLayout = subLayout.cells("c").attachLayout("2U");
                         subBotLayout.cells("a").setText("APPOINTMENT HISTORY");
                         subBotLayout.cells("b").setText("PRESCRIPTION HISTORY");

                         //Create toolbar and grid for patients
                         var userTools = subTopLayout.cells("a").attachToolbar();
                         userTools.addText("text_user",0,"SEARCH PATIENT");
                         userTools.addInput("userInput",100);
                         userTools.addButton("userChoose",350,"SEARCH");
                         userTools.addButton("userChart",550,"PULL PATIENT CHART");
                         userTools.addButton("userAdd",450,"NEW PATIENT");

                         var patientsGrid = subTopLayout.cells("a").attachGrid();
                         patientsGrid.setHeader("PAT_ID,LAST NAME,FIRST NAME,DOB");
                         patientsGrid.setColumnIds("pat_id,pat_last,pat_first,pat_dob");
                         patientsGrid.setInitWidths("100,*,*,*");
                         patientsGrid.setColTypes("ro,ro,ro,ro");
                         //patientsGrid.init();

                         //Create tools and grid for prescription history
                         var appHistTools = subBotLayout.cells("a").attachToolbar();
                         appHistTools.addButton("updateApp",0,"UPDATE STATUS");
                         appHistTools.addButton("newApp",0,"NEW APPOINTMENT");

                         var appHistGrid = subBotLayout.cells("a").attachGrid();
                         appHistGrid.setHeader("APP_ID,DATE,DAY,TIME,DOCTOR,STATUS");
                         appHistGrid.setColumnIds("app_id,date,day,time,doctor,status");
                         appHistGrid.setInitWidths("100,100,100,100,*,*");
                         appHistGrid.setColTypes("ro,ro,ro,ro,ro,ro");
                         //appHistGrid.init();


                         //Create tools and grid for prescription history
                         var preHistTools = subBotLayout.cells("b").attachToolbar();
                         preHistTools.addButton("scriptPreview",0,"VIEW PRESCRIPTION");

                         var preHistGrid = subBotLayout.cells("b").attachGrid();
                         preHistGrid.setHeader("PRE_ID,DATE,TIME,MED,PHARMACY");
                         preHistGrid.setColumnIds("pre_id,date,time,med,pharm");
                         preHistGrid.setInitWidths("100,100,100,*,*");
                         preHistGrid.setColTypes("ro,ro,ro,ro,ro");
                         //preHistGrid.init();

                    }

             });

             preForm.attachEvent("onButtonClick",function(id){

                    if (id == "createMed") {
                        alert("test");

                    }

                    if (id == "viewMed") {
                         //alert("test");
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();
                         subLayout.cells("b").setText("SET PRESCRIPTIONS");
                         subLayout.cells("c").setText("DESTINATION PHARMACY");
                         subLayout.cells("c").setHeight(350);

                         var scriptTools = subLayout.cells("b").attachToolbar();
                         scriptTools.addText("text_user",0,"SEARCH PATIENT");
                         scriptTools.addInput("userInput",100);
                         scriptTools.addButton("userChoose",350,"SEARCH");
                         scriptTools.addButton("userChart",550,"PREVIEW");

                         var scriptsGrid = subLayout.cells("b").attachGrid();
                         scriptsGrid.setHeader("PAT_ID,DATE,TIME,PATIENT,DOB,MED,PHARMACY,PHARMACY PHONE");
                         scriptsGrid.setColumnIds("pat_id,date,time,patient,dob,med,pharmacy,ph_phone");
                         scriptsGrid.setInitWidths("100,100,100,*,*,*,*,*");
                         scriptsGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
                         scriptsGrid.init();
                    }

                    if (id == "viewMedQue") {
                        //alert("test");
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();
                         subLayout.cells("b").setText("PRESCRIPTION QUEUE");
                         subLayout.cells("c").setText("DESTINATION PHARMACY");
                         subLayout.cells("c").setHeight(350);

                         var scriptTools = subLayout.cells("b").attachToolbar();
                         scriptTools.addButton("scriptPre",350,"PREVIEW");
                         scriptTools.addButton("scriptDel",350,"DELETE");
                         scriptTools.addButton("scriptSub",550,"SUBMIT");

                         var scriptsGrid = subLayout.cells("b").attachGrid();
                         scriptsGrid.setHeader("PAT_ID,PATIENT,DOB,SIG,MED,PHARMACY,PHARMACY PHONE");
                         scriptsGrid.setColumnIds("pat_id,patient,dob,sig,med,pharmacy,ph_phone");
                         scriptsGrid.setInitWidths("100,*,*,*,*,*,*");
                         scriptsGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
                         scriptsGrid.init();


                    }

                    if (id == "viewMedRef") {
                        //alert("test");
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();
                         subLayout.cells("b").setText("SENT PRESCRIPTIONS");
                         subLayout.cells("c").setText("AUTHORIZED REFILLS");

                         //subLayout.cells("c").setHeight(350);

                         var scriptTools = subLayout.cells("b").attachToolbar();
                         scriptTools.addText("text_user",0,"SEARCH PATIENT");
                         scriptTools.addInput("userInput",100);
                         scriptTools.addButton("userChoose",350,"SEARCH");
                         scriptTools.addButton("userChart",550,"VIEW ORIGINAL");
                         scriptTools.addButton("userRef",550,"VIEW REFILL");


                         var scriptsGrid = subLayout.cells("b").attachGrid();
                         scriptsGrid.setHeader("PAT_ID,DATE,TIME,PATIENT,DOB,MED,PHARMACY,PHARMACY PHONE");
                         scriptsGrid.setColumnIds("pat_id,date,time,patient,dob,med,pharmacy,ph_phone");
                         scriptsGrid.setInitWidths("100,100,100,*,*,*,*,*");
                         scriptsGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
                         //scriptsGrid.init();



                         var refGrid = subLayout.cells("c").attachGrid();
                         refGrid.setHeader("REF_ID,AUTH DATE,AUTH TIME,REFILLS,PATIENT,DOB,MED");
                         refGrid.setColumnIds("pat_id,date,time,patient,dob,med,pharmacy");
                         refGrid.setInitWidths("100,100,100,*,*,*,*");
                         refGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
                         //refGrid.init();

                    }

                    if (id == "viewMedForm") {
                        //alert("test");
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();
                         subLayout.cells("b").setText("INSURANCE COMPANY");
                         subLayout.cells("c").setText("FORMULARY");

                         var insGrid = subLayout.cells("b").attachGrid();
                         insGrid.setHeader("PAT_ID,LAST NAME,FIRST NAME,DOB");
                         insGrid.setColumnIds("pat_id,pat_last,pat_first,pat_dob");
                         insGrid.setInitWidths("100,*,*,*");
                         insGrid.setColTypes("ro,ro,ro,ro");
                         //patientsGrid.init();

                         var formGrid = subLayout.cells("b").attachGrid();
                         formGrid.setHeader("PAT_ID,LAST NAME,FIRST NAME,DOB");
                         formGrid.setColumnIds("pat_id,pat_last,pat_first,pat_dob");
                         formGrid.setInitWidths("100,*,*,*");
                         formGrid.setColTypes("ro,ro,ro,ro");
                         //patientsGrid.init();
                    }

             });

             repForm.attachEvent("onButtonClick",function(id){

                    if (id == "viewDrRep") {
                        alert("test");

                    }

                    if (id == "viewPatRep") {
                        alert("test");

                    }

                    if (id == "viewMedRep") {
                        alert("test");

                    }

             });


        })

    </script>
</head>

<body>

 <div id="splash_canvas"><h1>OPEN HEALTH APP</h1></div>

 <div id="no_access1_canvas"><h1>ACCESS DENIED</h1></div>
 <div id="no_access2_canvas"><h3>CONTACT YOUR SYSTEM ADMINISTRATOR</h3></div>

 <div id="app_canvas" class="dhx_cal_container" style='width:100%; height:100%;'>
                <div class="dhx_cal_navline">
			<div class="dhx_cal_prev_button">&nbsp;</div>
			<div class="dhx_cal_next_button">&nbsp;</div>
			<div class="dhx_cal_today_button"></div>
			<div class="dhx_cal_date"></div>
			<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
			<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
			<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
		</div>
		<div class="dhx_cal_header">
		</div>
		<div class="dhx_cal_data">
		</div>
 </div>

</body>
</html>
