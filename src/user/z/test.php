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

    <link href="/maps/documentation/javascript/examples/default.css" rel="stylesheet">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

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
        var api_key;
        var perm = {"submitScript":"", "viewScript":"", "viewScriptQue":"", "viewRefills":"", "viewAdherence":"", "viewPatients":"", "viewCharts":"", "viewFormulary":"", "viewReports":"", "is_dr":"", "md_id":""};

        var geocoder;
        var marker;
        var viewMap;
        var patlat;
        var patlng;

        function initializeViewMap() {
           var latlng = new google.maps.LatLng(-34.397, 150.644);
           geocoder = new google.maps.Geocoder();

           var mapOptions = {
             zoom: 8,
             center: latlng,
             mapTypeId: google.maps.MapTypeId.ROADMAP
           }

           viewMap = new google.maps.Map(document.getElementById('viewMap_canvas'), mapOptions);
        }

      function codeAddress(address) {
        //var address = "1642 6th st nw washington,dc 20001";
        geocoder.geocode( { 'address': address}, function(results, status) {

          if (status == google.maps.GeocoderStatus.OK) {

            viewMap.setCenter(results[0].geometry.location);

                marker = new google.maps.Marker({
                map: viewMap,
                draggable: false,
                position: results[0].geometry.location
            });

           patlat = marker.getPosition().lat();
           patlng = marker.getPosition().lng();
           //patlat = marker.getPosition().lat();
           //patlng = marker.getPosition().lng();



          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }


        dhtmlxEvent(window,"load",function(){

              //Main Layout
              mainLayout = new dhtmlXLayoutObject(document.body,"1C");
              mainLayout.cells("a").hideHeader();
              var mainToolbar = mainLayout.cells("a").attachToolbar();
              mainToolbar.addButton("homeBtn",200,"Home");
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

                               uname = lgForm.getItemValue("uname");
                               var upass = lgForm.getItemValue("pass");

                               dhtmlxAjax.get("auth/authenticate.php?uname="+uname+"&upass="+upass,function(loader) {

                                  if (loader.xmlDoc.responseText == "authenticated") {
                                      dhtmlx.message("Success");
                                      loginWin.hide();
                                      loginWin.setModal(false);

                                      dhtmlxAjax.get("auth/check_api_key.php",function(loader) {
                                                      api_key = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=submitScript",function(loader) {
                                                      perm.submitScript = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=viewScript",function(loader) {
                                                      perm.viewScript = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=viewScriptQue",function(loader) {
                                                      perm.viewScriptQue = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=viewRefills",function(loader) {
                                                      perm.viewRefills = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=viewAdherence",function(loader) {
                                                      perm.viewAdherence = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=viewPatients",function(loader) {
                                                      perm.viewPatients = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=viewCharts",function(loader) {
                                                      perm.viewCharts = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=viewFormulary",function(loader) {
                                                      perm.viewFormulary = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=viewReports",function(loader) {
                                                      perm.viewReports = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=is_dr",function(loader) {
                                                      perm.is_dr = loader.xmlDoc.responseText;
                                      });

                                      dhtmlxAjax.get("auth/check_permissions.php?uname="+uname+"&perm=md_id",function(loader) {
                                                      perm.md_id = loader.xmlDoc.responseText;
                                      });


                                  }

                                  else {
                                       dhtmlx.message("Invalid Credentials");
                                  }
                               });

                           }
              });

              initializeViewMap();

              subLayout.cells("a").hideHeader();
              subLayout.cells("b").setText("OPEN HEALTH");
              subLayout.cells("c").setText("APP");
              subLayout.cells("a").setWidth(200);
              subLayout.cells("a").fixSize(true,true);
              subLayout.cells("b").attachObject("splash_canvas");
              subLayout.cells("c").setHeight(350);

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
              patForm.loadStructString('<items><item width="193" type="button" name="viewPat" value="View Patients"/><item width="193" type="button" name="viewIns" value="Patient Insurance"/><item width="193" type="button" name="viewChart" value="View Chart"/></items>');
              //viewPat
              //viewChart
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
                         //alert(perm.viewScript);
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

                         subLayout.cells("c").setHeight(350);
                    }

             });


             patForm.attachEvent("onButtonClick",function(id){//BEGIN PATIENT FORM

                    if (id == "viewIns") {

                             if (perm.viewPatients == "true") {
                                 //build success UI
                                 subLayout.cells("b").detachObject();
                                 subLayout.cells("b").detachToolbar();
                                 subLayout.cells("c").detachObject();
                                 subLayout.cells("c").detachToolbar();
                                 subLayout.cells("b").showHeader();
                                 subLayout.cells("c").showHeader();
                                 subLayout.cells("c").setHeight(350);
                                 //subLayout.cells("b").setText("PATIENTS");

                                 var subTopLayout = subLayout.cells("b").attachLayout("2U");
                                 subTopLayout.cells("a").setText("PATIENTS");
                                 subTopLayout.cells("b").setText("PATIENT INSURANCE");

                                 var subBotLayout = subLayout.cells("c").attachLayout("2U");
                                 subBotLayout.cells("a").setText("PATIENT INSURANCE DETAILS");
                                 subBotLayout.cells("b").setText("INSURANCE SUBSCRIPTIONS");

                                 var patientsGrid = subTopLayout.cells("a").attachGrid();
                                 patientsGrid.setHeader("PAT_ID,LAST NAME,FIRST NAME,DOB,ADDR,CITY,STATE,ZIP");
                                 patientsGrid.setColumnIds("id,last_name,first_name,dob,addr,city,state,zip");
                                 patientsGrid.setInitWidths("100,*,*,*,*,*,*,*,*,*,*");
                                 patientsGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                                 patientsGrid.init();
                                 patientsGrid.loadXML("xml/view_patients.php");

                                 patientsGrid.attachEvent("onRowSelect",function(id,ind) {

                                     editInsForm.setItemValue("ins_id",'');
                                     editInsForm.setItemValue("name",'');
                                     editInsForm.setItemValue("ins_mri",'');
                                     editInsForm.setItemValue("ins_grp",'');

                                     var selectedRowId = patientsGrid.getSelectedRowId();
                                     var cellObj = patientsGrid.cellById(selectedRowId,0);
                                     var pt_id = cellObj.getValue();

                                     patientsInsGrid.clearAll();
                                     patientsInsGrid.updateFromXML("xml/view_insurance.php?id="+pt_id);

                                });

                                 var patientsInsGrid = subTopLayout.cells("b").attachGrid();
                                 patientsInsGrid.setHeader("INS_ID,INS NAME,INS MRI,INS GRP");
                                 patientsInsGrid.setColumnIds("ins_id,name,ins_mri,ins_grp");
                                 patientsInsGrid.setInitWidths("100,*,*,*");
                                 patientsInsGrid.setColTypes("ro,ro,ro,ro");
                                 patientsInsGrid.init();

                                 //Create toolbar and grid for patients
                                 var insTools = subBotLayout.cells("b").attachToolbar();
                                 insTools.addButton("insChange",350,"CHANGE INSURANCE");

                                 insTools.attachEvent("onClick",function(id){

                                           if (id == "insChange") {

                                               var selectedRowId = myinsGrid.getSelectedRowId();
                                               var cellObj = myinsGrid.cellById(selectedRowId,0);
                                               var ins_id = cellObj.getValue();

                                               var selectedRowId = myinsGrid.getSelectedRowId();
                                               var cellObj = myinsGrid.cellById(selectedRowId,1);
                                               var ins_name = cellObj.getValue();

                                               editInsForm.setItemValue("ins_id",ins_id);
                                               editInsForm.setItemValue("name",ins_name);
                                               editInsForm.setItemValue("ins_mri","");
                                               editInsForm.setItemValue("ins_grp","");

                                           }
                                 });

                                 var editInsFormData = [
                                     {type: "fieldset", name: "custFormD", label: "EDIT INSURANCE", list: [
                                         {type: "hidden", name: "ins_id"},
                                         {type: "input", inputWidth: "200", label: "INS NAME", labelAlign: "right", position: "label-right", name:"name"},
                                         {type: "input", inputWidth: "200", label: "INS MRI", labelAlign: "right", position: "label-right", name:"ins_mri"},
                                         {type: "input", inputWidth: "200", label: "INS GRP", labelAlign: "right", position: "label-right", name:"ins_grp"},
                                         {type: "button", name:"submit", value:"EDIT INSURANCE"}
                                    ]}
                                 ];

                                 var editInsForm = subBotLayout.cells("a").attachForm(editInsFormData);
                                 editInsForm.bind(patientsInsGrid);
                                 editInsForm.attachEvent("onButtonClick",function(id){

                                        if (id == "submit") {

                                               var selectedRowId = patientsGrid.getSelectedRowId();
                                               var cellObj = patientsGrid.cellById(selectedRowId,0);
                                               var pt_id = cellObj.getValue();

                                               var ins_id  = editInsForm.getItemValue("ins_id");
                                               var ins_mri = editInsForm.getItemValue("ins_mri");
                                               var ins_grp = editInsForm.getItemValue("ins_grp");

                                               //alert(ins_grp);
                                               dhtmlxAjax.get("xml/edit_insurance.php?id="+pt_id+"&ins_id="+ins_id+"&ins_mri="+ins_mri+"&ins_grp="+ins_grp,function(loader) {

                                                               if (loader.xmlDoc.responseText == "success") {
                                                                        dhtmlx.message("SUCCESS");
                                                                        //reload patientGrid
                                                                        patientsGrid.clearAll();
                                                                        patientsGrid.updateFromXML("xml/view_patients.php");
                                                               }

                                                               else {
                                                                        dhtmlx.message("Invalid Credentials");
                                                               }
                                             });

                                        }
                                 });

                                 var myinsGrid = subBotLayout.cells("b").attachGrid();
                                 myinsGrid.setHeader("INS_ID,NAME,STATE");
                                 myinsGrid.setColumnIds("ins_id,name,state");
                                 myinsGrid.setInitWidths("100,*,*");
                                 myinsGrid.setColTypes("ro,ro,ro");
                                 myinsGrid.init();
                                 myinsGrid.loadXML("xml/view_my_insurance.php");

                             }

                             else {

                                 dhtmlx.message("ACCESS DENIED");
                                 //build failure UI
                                 subLayout.cells("b").detachObject();
                                 subLayout.cells("b").detachToolbar();
                                 subLayout.cells("c").detachObject();
                                 subLayout.cells("c").detachToolbar();
                                 subLayout.cells("b").showHeader();
                                 subLayout.cells("c").showHeader();
                                 subLayout.cells("b").setText("ACCESS");
                                 subLayout.cells("c").setText("DENIED");
                                 subLayout.cells("c").setHeight(350);
                                 subLayout.cells("b").attachObject("no_access1_canvas");
                                 subLayout.cells("c").attachObject("no_access2_canvas");

                             }
                    }


                    if (id == "viewPat") {

                             if (perm.viewPatients == "true") {
                                 //build success UI
                                 subLayout.cells("b").detachObject();
                                 subLayout.cells("b").detachToolbar();
                                 subLayout.cells("c").detachObject();
                                 subLayout.cells("c").detachToolbar();
                                 subLayout.cells("b").showHeader();
                                 subLayout.cells("c").showHeader();
                                 subLayout.cells("c").setHeight(350);
                                 subLayout.cells("b").setText("PATIENTS");

                                 var subBotLayout = subLayout.cells("c").attachLayout("2U");
                                 subBotLayout.cells("a").setText("PATIENT DETAILS");
                                 subBotLayout.cells("b").setText("PATIENT MAP");
                                 //initializeViewMap();
                                 subBotLayout.cells("b").attachObject("viewMap_canvas");


                                 var patientsGrid = subLayout.cells("b").attachGrid();
                                 patientsGrid.setHeader("PAT_ID,LAST NAME,FIRST NAME,DOB,ADDR,CITY,STATE,ZIP,PHONE,CELL,EMAIL,SSEC");
                                 patientsGrid.setColumnIds("id,last_name,first_name,dob,addr,city,state,zip,phone,cell,email,ssec");
                                 patientsGrid.setInitWidths("100,*,*,*,*,*,*,*,*,*,*,*,*,*,*");
                                 patientsGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                                 patientsGrid.init();
                                 patientsGrid.loadXML("xml/view_patients.php");

                                 patientsGrid.attachEvent("onRowSelect",function(id,ind) {

                                     var selectedRowId = patientsGrid.getSelectedRowId();
                                     var cellObj = patientsGrid.cellById(selectedRowId,4);
                                     var addr = cellObj.getValue();

                                     var selectedRowId = patientsGrid.getSelectedRowId();
                                     var cellObj = patientsGrid.cellById(selectedRowId,5);
                                     var city = cellObj.getValue();

                                     var selectedRowId = patientsGrid.getSelectedRowId();
                                     var cellObj = patientsGrid.cellById(selectedRowId,6);
                                     var state = cellObj.getValue();

                                     var selectedRowId = patientsGrid.getSelectedRowId();
                                     var cellObj = patientsGrid.cellById(selectedRowId,7);
                                     var zip = cellObj.getValue();
                                     var fulladdr = addr + " " + city + "," + state + " " + zip;
                                     //alert(fulladdr);
                                     codeAddress(fulladdr);

                                });


                                 var editUserFormData = [
                                     {type: "fieldset", name: "custFormD", label: "NEW PATIENT", list: [
                                         {type: "hidden", name: "id"},
                                         {type: "input", inputWidth: "200", label: "LAST NAME", labelAlign: "right", position: "label-right", name:"last_name"},
                                         {type: "input", inputWidth: "200", label: "FIRST NAME", labelAlign: "right", position: "label-right", name:"first_name"},
                                         {type: "input", inputWidth: "200", label: "DOB", labelAlign: "right", position: "label-right", name:"dob"},

                                         {type: "input", inputWidth: "200", label: "ADDR", labelAlign: "right", position: "label-right", name:"addr"},
                                         {type: "input", inputWidth: "200", label: "CITY", labelAlign: "right", position: "label-right", name:"city"},
                                         {type: "input", inputWidth: "200", label: "STATE", labelAlign: "right", position: "label-right", name:"state"},
                                         {type: "input", inputWidth: "200", label: "ZIP", labelAlign: "right", position: "label-right", name:"zip"},
                                         {type: "input", inputWidth: "200", label: "PHONE", labelAlign: "right", position: "label-right", name:"phone"},
                                         {type: "input", inputWidth: "200", label: "CELL", labelAlign: "right", position: "label-right", name:"cell"},
                                         {type: "input", inputWidth: "200", label: "EMAIL", labelAlign: "right", position: "label-right", name:"email"},
                                         {type: "input", inputWidth: "200", label: "SSEC", labelAlign: "right", position: "label-right", name:"ssec"},
                                         {type: "button", name:"submit", value:"ADD PATIENT"}
                                    ]}
                                 ];


                                 var editUserForm = subBotLayout.cells("a").attachForm(editUserFormData);
                                 editUserForm.bind(patientsGrid);

                                 editUserForm.attachEvent("onButtonClick",function(id) {

                                            if (id == "submit") {
                                                var id = editUserForm.getItemValue("id");
                                                var last_name = editUserForm.getItemValue("last_name");
                                                var first_name = editUserForm.getItemValue("first_name");
                                                var dob = editUserForm.getItemValue("dob");

                                                var addr = editUserForm.getItemValue("addr");
                                                var city = editUserForm.getItemValue("city");
                                                var state = editUserForm.getItemValue("state");

                                                var zip = editUserForm.getItemValue("zip");
                                                var phone = editUserForm.getItemValue("phone");
                                                var cell = editUserForm.getItemValue("cell");

                                                var email = editUserForm.getItemValue("email");
                                                var ssec = editUserForm.getItemValue("ssec");

                                                dhtmlxAjax.get("xml/edit_patient.php?last_name="+last_name+"&first_name="+first_name+"&dob="+dob+"&addr="+addr+"&city="+city+"&state="+state+"&zip="+zip+"&phone="+phone+"&cell="+cell+"&email="+email+"&ssec="+ssec+"&id="+id,function(loader) {

                                                     if (loader.xmlDoc.responseText == "success") {
                                                         dhtmlx.message("PATIENT UPDATED");
                                                         patientsGrid.clearAll();
                                                         patientsGrid.updateFromXML("xml/view_patients.php");
                                                     }

                                                     else {
                                                         dhtmlx.message("Invalid Input");
                                                     }
                                                });
                                           }
                                 });


                                 //Create toolbar and grid for patients
                                 var userTools = subLayout.cells("b").attachToolbar();
                                 userTools.addText("text_user",0,"SEARCH PATIENT");
                                 userTools.addInput("userInput",100);
                                 userTools.addButton("userChoose",350,"SEARCH");
                                 userTools.addButton("userChart",550,"PULL PATIENT CHART");
                                 userTools.addButton("userAdd",450,"NEW PATIENT");
                                 userTools.addButton("gcode",650,"GEOCODE");

                                 userTools.attachEvent("onClick",function(id){

                                           if (id == "gcode") {

                                              var selectedRowId = patientsGrid.getSelectedRowId();
                                              var cellObj = patientsGrid.cellById(selectedRowId,0);
                                              var pt_id = cellObj.getValue();

                                              dhtmlxAjax.get("xml/geocode_patient.php?pt_id="+pt_id+"&patlat="+patlat+"&patlng="+patlng,function(loader) {

                                                      if (loader.xmlDoc.responseText == "success") {
                                                          dhtmlx.message("Success");
                                                      }

                                                      else {
                                                          dhtmlx.message("Invalid Credentials");
                                                      }
                                              });
                                           }

                                           if (id == "userAdd") {
                                               //dhtmlx.message("user add");
                                               var popupWindow = subLayout.dhxWins.createWindow("newcontact_win", 0, 0, 700, 400);
                                               popupWindow.center();
                                               popupWindow.setText("Add User");
                                               var popLayout = popupWindow.attachLayout("2U");
                                               popLayout.cells("a").hideHeader();
                                               popLayout.cells("b").setText("PATIENT MAP");

                                               var addUserFormData = [
                                                       {type: "fieldset", name: "custFormD", label: "NEW PATIENT", list: [
                                                           {type: "hidden", name: "id"},
                                                           {type: "input", inputWidth: "200", label: "LAST NAME", labelAlign: "right", position: "label-right", name:"last_name"},
                                                           {type: "input", inputWidth: "200", label: "FIRST NAME", labelAlign: "right", position: "label-right", name:"first_name"},
                                                           {type: "input", inputWidth: "200", label: "DOB", labelAlign: "right", position: "label-right", name:"dob"},

                                                           {type: "input", inputWidth: "200", label: "ADDR", labelAlign: "right", position: "label-right", name:"addr"},
                                                           {type: "input", inputWidth: "200", label: "CITY", labelAlign: "right", position: "label-right", name:"city"},
                                                           {type: "input", inputWidth: "200", label: "STATE", labelAlign: "right", position: "label-right", name:"state"},
                                                           {type: "input", inputWidth: "200", label: "ZIP", labelAlign: "right", position: "label-right", name:"zip"},
                                                           {type: "input", inputWidth: "200", label: "PHONE", labelAlign: "right", position: "label-right", name:"phone"},
                                                           {type: "input", inputWidth: "200", label: "CELL", labelAlign: "right", position: "label-right", name:"cell"},
                                                           {type: "input", inputWidth: "200", label: "EMAIL", labelAlign: "right", position: "label-right", name:"email"},
                                                           {type: "input", inputWidth: "200", label: "SSEC", labelAlign: "right", position: "label-right", name:"ssec"},
                                                           {type: "button", name:"submit", value:"ADD PATIENT"}
                                                       ]}
                                               ];

                                               var addUserForm = popLayout.cells("a").attachForm(addUserFormData);//popupWindow.attachForm(addUserFormData);
                                               //initializeViewMap();
                                               //popLayout.cells("b").attachObject("viewMap_canvas");
                                               //popLayout.cells("b").attachObject("viewMap_canvas");

                                               addUserForm.attachEvent("onButtonClick",function(id){

                                                          if (id == "submit") {
                                                              var last_name  = addUserForm.getItemValue("last_name");
                                                              var first_name = addUserForm.getItemValue("first_name");
                                                              var dob        = addUserForm.getItemValue("dob");
                                                              var addr       = addUserForm.getItemValue("addr");
                                                              var city       = addUserForm.getItemValue("city");
                                                              var state      = addUserForm.getItemValue("state");
                                                              var zip        = addUserForm.getItemValue("zip");
                                                              var phone      = addUserForm.getItemValue("phone");
                                                              var cell       = addUserForm.getItemValue("cell");
                                                              var email      = addUserForm.getItemValue("email");
                                                              var ssec       = addUserForm.getItemValue("ssec");

                                                              dhtmlxAjax.get("xml/add_patient.php?last_name="+last_name+"&first_name="+first_name+"&dob="+dob+"&addr="+addr+"&city="+city+"&state="+state+"&zip="+zip+"&phone="+phone+"&cell="+cell+"&email="+email+"&ssec="+ssec,function(loader) {

                                                                    if (loader.xmlDoc.responseText == "success") {
                                                                        dhtmlx.message("PATIENT ADDED");
                                                                        popupWindow.close();
                                                                        //reload patientGrid
                                                                        patientsGrid.clearAll();
                                                                        patientsGrid.updateFromXML("xml/view_patients.php");
                                                                    }

                                                                    else {
                                                                        dhtmlx.message("Invalid Credentials");
                                                                     }
                                                             });
                                                          }
                                               });
                                           }
                                 });
                            }

                             else {

                                 dhtmlx.message("ACCESS DENIED");
                                 //build failure UI
                                 subLayout.cells("b").detachObject();
                                 subLayout.cells("b").detachToolbar();
                                 subLayout.cells("c").detachObject();
                                 subLayout.cells("c").detachToolbar();
                                 subLayout.cells("b").showHeader();
                                 subLayout.cells("c").showHeader();
                                 subLayout.cells("b").setText("ACCESS");
                                 subLayout.cells("c").setText("DENIED");
                                 subLayout.cells("c").setHeight(350);
                                 subLayout.cells("b").attachObject("no_access1_canvas");
                                 subLayout.cells("c").attachObject("no_access2_canvas");

                             }
                   }
             });// END PATIENT FORM


             preForm.attachEvent("onButtonClick",function(id){

                    if (id == "createMed") {
                         //alert(perm.viewScript);
                             if (perm.submitScript == "true") {
                                 subLayout.cells("b").detachObject();
                                 subLayout.cells("c").detachObject();
                                 subLayout.cells("b").detachToolbar();
                                 subLayout.cells("c").detachToolbar();
                                 subLayout.cells("b").showHeader();
                                 subLayout.cells("c").showHeader();

                                 var subTopLayout = subLayout.cells("b").attachLayout("2U");
                                 var subBotLayout = subLayout.cells("c").attachLayout("2U");

                                 var patientScript = {"md_id":"", "pt_id":"", "med":"", "qty":"", "sig":"", "refills":"", "sub_perm":""};

                                 subTopLayout.cells("a").setText("PATIENTS");
                                 subTopLayout.cells("b").setText("PRESCRIPTION HISTORY");
                                 subBotLayout.cells("a").setText("PATIENT DETAILS");
                                 subBotLayout.cells("b").setText("DIAGNOSIS HISTORY");

                                 //SELECT PATIENT STATE
                                 var patientsGrid = subTopLayout.cells("a").attachGrid();
                                 patientsGrid.setHeader("PAT_ID,LAST NAME,FIRST NAME,DOB,ADDR,CITY,STATE");
                                 patientsGrid.setColumnIds("id,last_name,first_name,dob,addr,city,state");
                                 patientsGrid.setInitWidths("100,*,*,*,*,*,*,*,*,*");
                                 patientsGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                                 patientsGrid.init();
                                 patientsGrid.loadXML("xml/view_patients.php");

                                 var selPatFormData = [
                                     {type: "fieldset", name: "custFormD", label: "SELECT PATIENT", list: [
                                         {type: "hidden", name: "id"},
                                         {type: "input", inputWidth: "200", label: "LAST NAME", labelAlign: "right", position: "label-right", name:"last_name"},
                                         {type: "input", inputWidth: "200", label: "FIRST NAME", labelAlign: "right", position: "label-right", name:"first_name"},
                                         {type: "button", name:"submit", value:"SELECT (NEXT)"}
                                    ]}
                                 ];

                                 var selPatForm = subBotLayout.cells("a").attachForm(selPatFormData);
                                 selPatForm.bind(patientsGrid);
                                 selPatForm.attachEvent("onButtonClick",function(id){

                                        if (id == "submit") {

                                            var selectedRowId = patientsGrid.getSelectedRowId();
                                            var cellObj = patientsGrid.cellById(selectedRowId,0);
                                            patientScript.md_id = perm.md_id;
                                            patientScript.pt_id = cellObj.getValue();

                                            subLayout.cells("b").detachObject();
                                            subLayout.cells("c").detachObject();
                                            subLayout.cells("b").detachToolbar();
                                            subLayout.cells("c").detachToolbar();
                                            subLayout.cells("b").showHeader();
                                            subLayout.cells("c").showHeader();

                                            var subTopLayout = subLayout.cells("b").attachLayout("2U");
                                            var subBotLayout = subLayout.cells("c").attachLayout("2U");

                                            subTopLayout.cells("a").setText("MEDICATIONS");
                                            subTopLayout.cells("b").setText("STRENGTH - DOSAGE FORM");
                                            subBotLayout.cells("a").setText("SELECT MEDICATION DETAILS");
                                            subBotLayout.cells("b").setText("WARNINGS");
                                        }
                                 });

                                 //SELECT DRUG STATE
                                 var drugsGrid;
                                 var drugDetailGrid;
                                 var warningGrid;

                                 //var scriptTools = subLayout.cells("b").attachToolbar();
                                 //scriptTools.addButton("newApp",0,"NEW APPOINTMENT");

                                 //subLayout.cells("c").setHeight(350);
                            }

                             else {

                                 dhtmlx.message("ACCESS DENIED");
                                 //build failure UI
                                 subLayout.cells("b").detachObject();
                                 subLayout.cells("b").detachToolbar();
                                 subLayout.cells("c").detachObject();
                                 subLayout.cells("c").detachToolbar();
                                 subLayout.cells("b").showHeader();
                                 subLayout.cells("c").showHeader();
                                 subLayout.cells("b").setText("ACCESS");
                                 subLayout.cells("c").setText("DENIED");
                                 subLayout.cells("c").setHeight(350);
                                 subLayout.cells("b").attachObject("no_access1_canvas");
                                 subLayout.cells("c").attachObject("no_access2_canvas");

                             }
                    }// end create med

             });


        });

    </script>
</head>

<body>

 <div id="splash_canvas"><h1>OPEN HEALTH APP</h1></div>

 <div id="no_access1_canvas"><h1>ACCESS DENIED</h1></div>
 <div id="no_access2_canvas"><h3>CONTACT YOUR SYSTEM ADMINISTRATOR</h3></div>

 <div id="viewMap_canvas" style="height:100%;width:100%"></div>
</body>
</html>
