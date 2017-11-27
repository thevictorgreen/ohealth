<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>OPEN HEALTH ADMIN APP</title>

    <!-- dhtmlx.js contains all necessary dhtmlx library javascript code -->
    <script src="codebase/dhtmlx.js" type="text/javascript"></script>

    <!-- connector.js used to integrate with the server-side -->
    <script src="codebase/connector/connector.js" type="text/javascript"></script>

    <!-- dhtmlx.css contains styles definitions for all included components -->
    <link rel="STYLESHEET" type="text/css" href="codebase/dhtmlx.css">

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

    //Simple JQuery Ajax PHP file upload code
    <script type="text/javascript">
        $(document).ready(function()
        {

	  var options = {
            beforeSend: function()
            {
    	        $("#progress").show();
    	        //clear everything
    	        $("#bar").width('0%');
    	        $("#message").html("");
		        $("#percent").html("0%");
            },
            uploadProgress: function(event, position, total, percentComplete)
            {
    	        $("#bar").width(percentComplete+'%');
    	        $("#percent").html(percentComplete+'%');


            },
            success: function()
            {
                $("#bar").width('100%');
    	        $("#percent").html('100%');

            },
	        complete: function(response)
	        {
		        $("#message").html("<font color='green'>"+response.responseText+"</font>");
	        },
	        error: function()
	        {
		        $("#message").html("<font color='red'> ERROR: unable to upload files</font>");
	        }

           };
             $("#myForm").ajaxForm(options);
        });
    </script>

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
              mainToolbar.addButton("acctBtn",200,"My Account");
              mainToolbar.addButton("logsBtn",200,"Logs");
              mainToolbar.addButton("signoutBtn",200,"Sign Out");

              //Sub Layout
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
                                      //layout.cells("b").attachObject("splash_canvas");
                                  }

                                  else {
                                       dhtmlx.message("Invalid Credentials");
                                  }
                               });

                           }
              });

              subLayout.cells("a").hideHeader();
              subLayout.cells("b").hideHeader();
              subLayout.cells("c").hideHeader();
              subLayout.cells("a").setWidth(200);
              subLayout.cells("a").fixSize(true,true);
              subLayout.cells("c").setHeight(350);
              //layout.cells("b").attachObject("splash_canvas");

              leftNav = subLayout.cells("a").attachAccordion();
              leftNav.addItem("a1","DRS OFFICES");
              leftNav.addItem("b1","USERS");
              leftNav.addItem("c1","INSURANCE");
              leftNav.addItem("d1","PHARMACIES");

              leftNav.cells("a1").open();

              var ofcForm = leftNav.cells("a1").attachForm();
              var useForm = leftNav.cells("b1").attachForm();
              var insForm = leftNav.cells("c1").attachForm();
              var phaForm = leftNav.cells("d1").attachForm();

              ofcForm.loadStructString('<items><item width="193" type="button" name="viewOfc" value="View Offices"/></items>');
              useForm.loadStructString('<items><item width="193" type="button" name="viewUse" value="View Users"/><item width="193" type="button" name="viewUsePerm" value="View User Permissions"/></items>');
              insForm.loadStructString('<items><item width="193" type="button" name="viewIns" value="View Insurance"/></items>');
              phaForm.loadStructString('<items><item width="193" type="button" name="viewPha" value="View Pharmacies"/><item width="193" type="button" name="viewPhaFax" value="Pharmacy Fax Numbers"/></items>');

              ofcForm.attachEvent("onButtonClick",function(id){

                    if (id == "viewOfc") {
                        subLayout.cells("b").detachObject();
                        subLayout.cells("b").detachToolbar();
                        subLayout.cells("c").detachObject();
                        subLayout.cells("c").detachToolbar();
                        subLayout.cells("b").showHeader();
                        subLayout.cells("c").showHeader();
                        subLayout.cells("b").setText("DRS OFFICES");
                        subLayout.cells("c").setText("DR OFFICE DETAILS");
                        subLayout.cells("c").setHeight(350);

                        var ofcTools = subLayout.cells("b").attachToolbar();
                        ofcTools.addButton("nofc",350,"NEW OFFICE");
                        ofcTools.addButton("dofc",550,"DROP OFFICE")

                        var ofcGrid = subLayout.cells("b").attachGrid();
                        ofcGrid.setHeader("ID,NAME,ADDR,CITY,STATE,ZIP,PHONE");
                        ofcGrid.setColumnIds("id,name,addr,city,state,zip,phone");
                        ofcGrid.setInitWidths("*,*,*,*,*,*,*");
                        ofcGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");

                        ofcGrid.init();
                        ofcGrid.loadXML("xml/view_dr_office.php");

                        var ofcgDP = new dataProcessor("xml/view_dr_office.php");
                        ofcgDP.init(ofcGrid);

                         var editOfcFormData = [
                                        {type: "fieldset", name: "custFormD", label: "CREATE NEW OFFICE", list: [
                                            {type: "hidden", name: "id"},
                                            {type: "input", inputWidth: "200", label: "Office Name", labelAlign: "right", position: "label-right", name:"name"},
                                            {type: "input", inputWidth: "200", label: "Address", labelAlign: "right", position: "label-right", name:"addr"},
                                            {type: "input", inputWidth: "200", label: "City", labelAlign: "right", position: "label-right", name:"city"},
                                            {type: "input", inputWidth: "200", label: "State", labelAlign: "right", position: "label-right", name:"state"},
                                            {type: "input", inputWidth: "200", label: "Zip", labelAlign: "right", position: "label-right", name:"zip"},
                                            {type: "input", inputWidth: "200", label: "Phone", labelAlign: "right", position: "label-right", name:"phone"},
                                            {type: "button", name:"submit", value:"EDIT DR OFFICE"}
                                         ]}
                         ];

                         var editOfcForm = subLayout.cells("c").attachForm(editOfcFormData);
                         editOfcForm.bind(ofcGrid);

                         editOfcForm.attachEvent("onButtonClick",function(id){

                                    if (id == "submit") {
                                        editOfcForm.save();
                                    }
                         });

                         ofcTools.attachEvent("onClick",function(id){

                               if (id == "nofc") {
                                   //alert("new");
                                 var popupWindow = subLayout.dhxWins.createWindow("newofc_win", 0, 0, 375, 325);
                                 popupWindow.center();
                                 popupWindow.setText("NEW OFFICE");

                                 var newOfcFormData = [
                                        {type: "fieldset", name: "custFormD", label: "CREATE NEW OFFICE", list: [
                                            {type: "hidden", name: "cust_id"},
                                            {type: "input", inputWidth: "200", label: "Office Name", labelAlign: "right", position: "label-right", name:"name"},
                                            {type: "input", inputWidth: "200", label: "Address", labelAlign: "right", position: "label-right", name:"addr"},
                                            {type: "input", inputWidth: "200", label: "City", labelAlign: "right", position: "label-right", name:"city"},
                                            {type: "input", inputWidth: "200", label: "State", labelAlign: "right", position: "label-right", name:"state"},
                                            {type: "input", inputWidth: "200", label: "Zip", labelAlign: "right", position: "label-right", name:"zip"},
                                            {type: "input", inputWidth: "200", label: "Phone", labelAlign: "right", position: "label-right", name:"phone"},
                                            {type: "input", inputWidth: "200", label: "Office ID", labelAlign: "right", position: "label-right", name:"o_id"},
                                            {type: "input", inputWidth: "200", label: "API KEY", labelAlign: "right", position: "label-right", name:"api_key"},
                                            {type: "button", name:"submit", value:"CREATE NEW DR OFFICE"}
                                         ]}
                                ];

                                var newOfcForm = popupWindow.attachForm(newOfcFormData);
                                newOfcForm.attachEvent("onButtonClick",function(id){

                                       if (id == "submit") {
                                           //alert("lets do this");
                                           var name    = newOfcForm.getItemValue("name");
                                           var addr    = newOfcForm.getItemValue("addr");
                                           var city    = newOfcForm.getItemValue("city");
                                           var state   = newOfcForm.getItemValue("state");
                                           var zip     = newOfcForm.getItemValue("zip");
                                           var phone   = newOfcForm.getItemValue("phone");
                                           var o_id    = newOfcForm.getItemValue("o_id");
                                           var api_key = newOfcForm.getItemValue("api_key");

                                           dhtmlxAjax.get("xml/add_dr_office.php?name="+name+"&addr="+addr+"&city="+city+"&state="+state+"&zip="+zip+"&phone="+phone+"&o_id="+o_id+"&api_key="+api_key,function(loader) {

                                              //alert(loader.xmlDoc.responseText);

                                              if (loader.xmlDoc.responseText == "success") {
                                                  dhtmlx.message("DR OFFICE CREATED");
                                                  popupWindow.close();
                                                  ofcGrid.clearAll();
                                                  ofcGrid.updateFromXML("xml/view_dr_office.php");
                                              }

                                              else {
                                                   dhtmlx.message("Invalid Input");
                                              }
                                           });
                                      }
                                });

                               }

                               if (id == "dofc") {
                                   //alert("old");
                                   var selectedRowID = ofcGrid.getSelectedRowId();
                                   var cellObj = ofcGrid.cellById(selectedRowID,0);
                                   var id = cellObj.getValue();
                                   //alert(id);

                                   dhtmlxAjax.get("xml/drop_dr_office.php?id="+id,function(loader) {

                                       if (loader.xmlDoc.responseText == "success") {
                                            dhtmlx.message("OFFICE DROPPED");
                                            ofcGrid.clearAll();
                                            ofcGrid.updateFromXML("xml/view_dr_office.php");
                                       }

                                       else {
                                            dhtmlx.message("COULD NOT DROP OFFICE");
                                       }

                                   });
                               }
                         });
                    }

             });

             useForm.attachEvent("onButtonClick",function(id){

                    if (id == "viewUse") {
                         //alert("test");
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();
                         //subLayout.cells("c").setText("USER DETAILS");

                         var subTopLayout = subLayout.cells("b").attachLayout("2U");
                         subTopLayout.cells("a").setText("DR OFFICE");
                         subTopLayout.cells("b").setText("USERS");

                         var subBotLayout = subLayout.cells("c").attachLayout("2U");
                         subBotLayout.cells("a").setText("USER DETAILS");
                         subBotLayout.cells("b").setText("UPLOAD USER FILE FOR IMPORT");


                         //Create toolbar and grid for patients
                         var userTools = subTopLayout.cells("a").attachToolbar();
                         userTools.addButton("userAdd",350,"ADD USER");
                         userTools.addButton("userRemove",550,"REMOVE USER");
                         userTools.addButton("userImport",550,"IMPORT USERS");

                         userTools.attachEvent("onClick",function(id){

                                    if (id == "userAdd") {
                                        //alert("userAdd");
                                        var popupWindow = subLayout.dhxWins.createWindow("newcontact_win", 0, 0, 375, 350);
                                        popupWindow.center();
                                        popupWindow.setText("Add User");

                                        var addUserFormData = [
                                                       {type: "fieldset", name: "custFormD", label: "ADD USER", list: [
                                                           {type: "hidden", name: "id"},
                                                           {type: "input", inputWidth: "200", label: "LAST NAME", labelAlign: "right", position: "label-right", name:"last_name"},
                                                           {type: "input", inputWidth: "200", label: "FIRST NAME", labelAlign: "right", position: "label-right", name:"first_name"},
                                                           {type: "input", inputWidth: "200", label: "LOGIN", labelAlign: "right", position: "label-right", name:"login"},

                                                           {type: "input", inputWidth: "200", label: "PASSD", labelAlign: "right", position: "label-right", name:"passd"},
                                                           {type: "input", inputWidth: "200", label: "IS_DR", labelAlign: "right", position: "label-right", name:"is_dr"},
                                                           {type: "input", inputWidth: "200", label: "DEA", labelAlign: "right", position: "label-right", name:"dea"},
                                                           {type: "input", inputWidth: "200", label: "SEND_SCRIPT", labelAlign: "right", position: "label-right", name:"send_script"},
                                                           {type: "input", inputWidth: "200", label: "APRR_REQ", labelAlign: "right", position: "label-right", name:"appr_req"},
                                                           {type: "input", inputWidth: "200", label: "EMAIL", labelAlign: "right", position: "label-right", name:"email"},
                                                           {type: "input", inputWidth: "200", label: "LIC_NUM", labelAlign: "right", position: "label-right", name:"lic_num"},
                                                           {type: "button", name:"submit", value:"ADD USER"}
                                                       ]}
                                        ];

                                        var addUserForm = popupWindow.attachForm(addUserFormData);
                                        addUserForm.attachEvent("onButtonClick",function(id){

                                              if (id == "submit") {
                                                  //alert("Hello");

                                                  var last_name = addUserForm.getItemValue("last_name");
                                                  var first_name = addUserForm.getItemValue("first_name");
                                                  var login = addUserForm.getItemValue("login");
                                                  var passd = addUserForm.getItemValue("passd");
                                                  var is_dr = addUserForm.getItemValue("is_dr");
                                                  var dea = addUserForm.getItemValue("dea");
                                                  var send_script = addUserForm.getItemValue("send_script");
                                                  var appr_req = addUserForm.getItemValue("appr_req");
                                                  var email = addUserForm.getItemValue("email");
                                                  var lic_num = addUserForm.getItemValue("lic_num");

                                                  dhtmlxAjax.get("xml/add_dr_office_user.php?last_name="+last_name+"&first_name="+first_name+"&login="+login+"&passd="+passd+"&is_dr="+is_dr+"&dea="+dea+"&send_script="+send_script+"&appr_req="+appr_req+"&email="+email+"&lic_num="+lic_num,function(loader) { 

                                                        if (loader.xmlDoc.responseText == "success") {
                                                                dhtmlx.message("USER CREATED");
                                                                popupWindow.close();
                                                                var selectedRowId = ofcGrid.getSelectedRowId();
                                                                var cellObj = ofcGrid.cellById(selectedRowId,0);
                                                                var id = cellObj.getValue();

                                                                userGrid.clearAll();
                                                                userGrid.updateFromXML("xml/view_users.php?id="+id);
                                                        }

                                                        else {
                                                                dhtmlx.message("Invalid Input");
                                                        }

                                                  });
                                              }
                                        });
                                    }

                                    if (id == "userRemove") {
                                        //alert("remove");
                                        var selectedRowId = userGrid.getSelectedRowId();
                                        var cellObj = userGrid.cellById(selectedRowId,0);
                                        var uid = cellObj.getValue();
                                        //alert(uid);
                                        dhtmlxAjax.get("xml/remove_dr_office_user.php?uid="+uid,function(loader) {

                                                        if (loader.xmlDoc.responseText == "success") {
                                                                dhtmlx.message("USER REMOVED");
                                                                userGrid.clearAll();
                                                                userGrid.updateFromXML("xml/view_users.php?id="+uid);
                                                        }
                                                        else {
                                                                dhtmlx.message("Invalid Input");
                                                        }
                                       });
                                    }


                                    if (id == "userImport") {
                                        //alert("user import");
                                        var selectedRowId = ofcGrid.getSelectedRowId();
                                        var cellObj = ofcGrid.cellById(selectedRowId,0);
                                        var uid = cellObj.getValue();
                                        dhtmlxAjax.get("xml/import_dr_office_user.php?uid="+uid,function(loader) {

                                                        if (loader.xmlDoc.responseText == "success") {
                                                                dhtmlx.message("USER(s) IMPORTED");
                                                                userGrid.clearAll();
                                                                userGrid.updateFromXML("xml/view_users.php?id="+uid);
                                                        }
                                                        else {
                                                                dhtmlx.message("Invalid Input");
                                                        }
                                       });
                                    }

                         });


                         var ofcGrid = subTopLayout.cells("a").attachGrid();
                         ofcGrid.setHeader("ID,NAME,ADDR,CITY,STATE,ZIP,PHONE");
                         ofcGrid.setColumnIds("id,name,addr,city,state,zip,phone");
                         ofcGrid.setInitWidths("50,*,*,*,50,50,*");
                         ofcGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");

                         ofcGrid.init();
                         ofcGrid.loadXML("xml/view_dr_office.php");

                         var userGrid = subTopLayout.cells("b").attachGrid();
                         userGrid.setHeader("U_ID,LAST NAME,FIRST NAME,LOGIN,PASSD,IS_DR,DEA,SEND_SCRIPT,APPR_REQ,EMAIL,LIC_NUM");
                         userGrid.setColumnIds("id,last_name,first_name,login,passd,is_dr,dea,send_script,appr_req,email,lic_num");
                         userGrid.setInitWidths("100,*,*,*,*,*,*,*,*,*,*");
                         userGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                         userGrid.init();

                         var usergDP = new dataProcessor("xml/view_users.php");
                         usergDP.init(userGrid);

                         var editUserFormData = [
                                        {type: "fieldset", name: "custFormD", label: "EDIT USER DATA", list: [
                                            {type: "hidden", name: "id"},
                                            {type: "input", inputWidth: "200", label: "LAST NAME", labelAlign: "right", position: "label-right", name:"last_name"},
                                            {type: "input", inputWidth: "200", label: "FIRST NAME", labelAlign: "right", position: "label-right", name:"first_name"},
                                            {type: "input", inputWidth: "200", label: "LOGIN", labelAlign: "right", position: "label-right", name:"login"},

                                            {type: "input", inputWidth: "200", label: "PASSD", labelAlign: "right", position: "label-right", name:"passd"},
                                            {type: "input", inputWidth: "200", label: "IS_DR", labelAlign: "right", position: "label-right", name:"is_dr"},
                                            {type: "input", inputWidth: "200", label: "DEA", labelAlign: "right", position: "label-right", name:"dea"},
                                            {type: "input", inputWidth: "200", label: "SEND_SCRIPT", labelAlign: "right", position: "label-right", name:"send_script"},
                                            {type: "input", inputWidth: "200", label: "APRR_REQ", labelAlign: "right", position: "label-right", name:"appr_req"},
                                            {type: "input", inputWidth: "200", label: "EMAIL", labelAlign: "right", position: "label-right", name:"email"},
                                            {type: "input", inputWidth: "200", label: "LIC_NUM", labelAlign: "right", position: "label-right", name:"lic_num"},

                                            {type: "button", name:"submit", value:"EDIT USER"}
                                         ]}
                         ];

                         var editUserForm = subBotLayout.cells("a").attachForm(editUserFormData);
                         editUserForm.bind(userGrid);

                         ofcGrid.attachEvent("onRowSelect",function(id,ind) {

                                var selectedRowId = ofcGrid.getSelectedRowId();
                                var cellObj = ofcGrid.cellById(selectedRowId,0);
                                var id = cellObj.getValue();

                                userGrid.clearAll();
                                userGrid.updateFromXML("xml/view_users.php?id="+id);
                         });

                         editUserForm.attachEvent("onButtonClick",function(id){

                                    if (id == "submit") {
                                        editUserForm.save();
                                    }
                         });

                         //Upload vault
                         var myValut = subBotLayout.cells("b").attachObject(vaultDiv);

                    }

                    if (id == "viewUsePerm") {
                        //alert("test");
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();
                         //subLayout.cells("c").setText("USER DETAILS");

                         var subTopLayout = subLayout.cells("b").attachLayout("2U");
                         subTopLayout.cells("a").setText("DR OFFICE");
                         subTopLayout.cells("b").setText("USERS");

                         subLayout.cells("c").setText("USER DETAILS");


                         var ofcGrid = subTopLayout.cells("a").attachGrid();
                         ofcGrid.setHeader("ID,NAME,ADDR,CITY,STATE,ZIP,PHONE");
                         ofcGrid.setColumnIds("id,name,addr,city,state,zip,phone");
                         ofcGrid.setInitWidths("50,*,*,*,50,50,*");
                         ofcGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");

                         ofcGrid.init();
                         ofcGrid.loadXML("xml/view_dr_office.php");

                         var userGrid = subTopLayout.cells("b").attachGrid();
                         userGrid.setHeader("U_ID,LAST NAME,FIRST NAME,LOGIN,SubScr,SubScrDir,SubScrQueOn,VScr,VScrQue,VRef,VAd,VPt,VCh,VFor,VRep");
                         userGrid.setColumnIds("u_id,last_name,first_name,login,submitScript,submitScriptDirect,submitScriptQueOnly,viewScript,viewScriptQue,viewRefills,viewAdherence,viewPatients,viewCharts,viewFormulary,viewReports");
                         userGrid.setInitWidths("100,*,*,*,*,*,*,*,*,*,*,*,*,*,*");
                         userGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                         userGrid.init();

                         //var usergDP = new dataProcessor("xml/view_user_permissions.php");
                         //usergDP.init(userGrid);

                         var editUserFormData = [
                                        {type: "fieldset", name: "custFormD", label: "EDIT USER PERMISSIONS", list: [
                                            {type: "hidden", name: "u_id"},

                                            {type: "input", inputWidth: "200", label: "SUBMIT SCRIPT", labelAlign: "right", position: "label-right", name:"submitScript"},
                                            {type: "input", inputWidth: "200", label: "SUBMIT SCRIPT DIRECT", labelAlign: "right", position: "label-right", name:"submitScriptDirect"},
                                            {type: "input", inputWidth: "200", label: "SUBMIT SCRIPT QUE ONLY", labelAlign: "right", position: "label-right", name:"submitScriptQueOnly"},
                                            {type: "input", inputWidth: "200", label: "VIEW SCRIPT", labelAlign: "right", position: "label-right", name:"viewScript"},
                                            {type: "input", inputWidth: "200", label: "VIEW SCRIPT QUE", labelAlign: "right", position: "label-right", name:"viewScriptQue"},
                                            {type: "input", inputWidth: "200", label: "VIEW REFILLS", labelAlign: "right", position: "label-right", name:"viewRefills"},
                                            {type: "input", inputWidth: "200", label: "VIEW ADHERENCE", labelAlign: "right", position: "label-right", name:"viewAdherence"},

                                            {type: "input", inputWidth: "200", label: "VIEW PATIENTS", labelAlign: "right", position: "label-right", name:"viewPatients"},
                                            {type: "input", inputWidth: "200", label: "VIEW CHARTS", labelAlign: "right", position: "label-right", name:"viewCharts"},
                                            {type: "input", inputWidth: "200", label: "VIEW FORMULARY", labelAlign: "right", position: "label-right", name:"viewFormulary"},
                                            {type: "input", inputWidth: "200", label: "VIEW REPORTS", labelAlign: "right", position: "label-right", name:"viewReports"},

                                            {type: "button", name:"submit", value:"EDIT USER PERMISSIONS"}
                                         ]}
                         ];

                         var editUserForm = subLayout.cells("c").attachForm(editUserFormData);
                         editUserForm.bind(userGrid);

                         ofcGrid.attachEvent("onRowSelect",function(id,ind) {

                                var selectedRowId = ofcGrid.getSelectedRowId();
                                var cellObj = ofcGrid.cellById(selectedRowId,0);
                                var id = cellObj.getValue();

                                userGrid.clearAll();
                                userGrid.updateFromXML("xml/view_user_permissions.php?id="+id);
                         });

                         editUserForm.attachEvent("onButtonClick",function(id){

                                    if (id == "submit") {

                                        var u_id = editUserForm.getItemValue("u_id");
                                        var submitScript = editUserForm.getItemValue("submitScript");
                                        var submitScriptDirect = editUserForm.getItemValue("submitScriptDirect");
                                        var submitScriptQueOnly = editUserForm.getItemValue("submitScriptQueOnly");
                                        var viewScript = editUserForm.getItemValue("viewScript");
                                        var viewScriptQue = editUserForm.getItemValue("viewScriptQue");
                                        var viewRefills = editUserForm.getItemValue("viewRefills");
                                        var viewAdherence = editUserForm.getItemValue("viewAdherence");

                                        var viewPatients = editUserForm.getItemValue("viewPatients");
                                        var viewCharts = editUserForm.getItemValue("viewCharts");
                                        var viewFormulary = editUserForm.getItemValue("viewFormulary");
                                        var viewReports = editUserForm.getItemValue("viewReports");


                                        dhtmlxAjax.get("xml/update_user_permissions.php?u_id="+u_id+"&submitScript="+submitScript+"&submitScriptDirect="+submitScriptDirect+"&submitScriptQueOnly="+submitScriptQueOnly+"&viewScript="+viewScript+"&viewScriptQue="+viewScriptQue+"&viewRefills="+viewRefills+"&viewAdherence="+viewAdherence+"&viewPatients="+viewPatients+"&viewCharts="+viewCharts+"&viewFormulary="+viewFormulary+"&viewReports="+viewReports,function(loader) {
                                                        if (loader.xmlDoc.responseText == "success") {
                                                                dhtmlx.message("PERMISSIONS UPDATED");
                                                                userGrid.clearAll();
                                                                userGrid.updateFromXML("xml/view_user_permissions.php?id=1");
                                                        }
                                                        else {
                                                                dhtmlx.message("Invalid Input");
                                                        }
                                        });
                                    }
                         });


                    }
             });


             insForm.attachEvent("onButtonClick",function(id){

                    if (id == "viewIns") {
                         //alert("test");
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();
                         subLayout.cells("c").setText("MY INSURANCE SUBSCRIPTIONS");

                         var subTopLayout = subLayout.cells("b").attachLayout("2U");
                         subTopLayout.cells("a").setText("STATES");
                         subTopLayout.cells("b").setText("INSURANCE SUBSCRIPTIONS");

                         //Create toolbar and grid for patients
                         var userTools = subTopLayout.cells("a").attachToolbar();
                         userTools.addButton("userAdd",350,"ADD INSURANCE");
                         userTools.addButton("userRemove",550,"REMOVE INSURANCE");

                         userTools.attachEvent("onClick",function(id){
                                 if (id == "userAdd") {
                                     //alert("User Add");
                                     var selectedRowId = staGrid.getSelectedRowId();
                                     var cellObj = staGrid.cellById(selectedRowId,1);
                                     var st_short = cellObj.getValue();

                                     var selectedRowId = insGrid.getSelectedRowId();
                                     var cellObj = insGrid.cellById(selectedRowId,0);
                                     var ins_id = cellObj.getValue();

                                     var selectedRowId = insGrid.getSelectedRowId();
                                     var cellObj = insGrid.cellById(selectedRowId,1);
                                     var ins_name = cellObj.getValue();

                                     var url = "xml/add_insurance.php?ins_id="+ins_id+"&st_short="+st_short+"&ins_name="+ins_name;
                                     $.ajax({ type: "GET",
                                           url: url,
                                           dataType: "text",
                                           error: function (xhr, status, error) {
                                              alert(error);
                                           },
                                           success: function (text) {
                                              dhtmlx.message(text);
                                              myinsGrid.clearAll();
                                              myinsGrid.updateFromXML("xml/view_insurance.php");
                                           }
                                     });

                                 }

                                 if (id == "userRemove") {
                                     //alert("User Remove");
                                     var selectedRowId = myinsGrid.getSelectedRowId();
                                     var cellObj = myinsGrid.cellById(selectedRowId,0);
                                     var ins_id = cellObj.getValue();
                                     //alert(ins_id);
                                     var url = "xml/remove_insurance.php?ins_id="+ins_id;
                                     $.ajax({ type: "GET",
                                           url: url,
                                           dataType: "text",
                                           error: function (xhr, status, error) {
                                              alert(error);
                                           },
                                           success: function (text) {
                                              dhtmlx.message(text);
                                              myinsGrid.clearAll();
                                              myinsGrid.updateFromXML("xml/view_insurance.php");
                                           }
                                     });

                                 }
                         });

                         var staGrid = subTopLayout.cells("a").attachGrid();
                         staGrid.setHeader("ST_ID,ST SHORT,ST LONG");
                         staGrid.setColumnIds("st_id,st_short,st_long");
                         staGrid.setInitWidths("100,*,*");
                         staGrid.setColTypes("ro,ro,ro");
                         staGrid.init();

                         var url = "http://api.firstmedisource.com/call.php/states?user_key=654628232eb57960ccad23ec60d1a150";
                         $.ajax({ type: "GET",
                               url: url,
                               dataType: "json",
                               error: function (xhr, status, error) {
                                  alert(error);
                               },
                               success: function (json) {
                                  //alert(json.length);
                                  for ( var i = 0;i < json.length;i++ ) {
                                        var st_id    = json[i].st_id;
                                        var st_short = json[i].st_short;
                                        var st_long  = json[i].st_long
                                        staGrid.addRow(i,st_id+","+st_short+","+st_long+","+i);
                                  }
                               }
                         });

                         staGrid.attachEvent("onRowSelect",function(id,ind) {

                                var selectedRowId = staGrid.getSelectedRowId();
                                var cellObj = staGrid.cellById(selectedRowId,1);
                                var st_short = cellObj.getValue();

                                insGrid.clearAll();

                                var url = "http://api.firstmedisource.com/call.php/insurance/" + st_short + "?user_key=654628232eb57960ccad23ec60d1a150";
                                $.ajax({ type: "GET",
                                      url: url,
                                      dataType: "json",
                                      error: function (xhr, status, error) {
                                         alert(error);
                                      },
                                      success: function (json) {
                                         //alert(json.length);
                                         for ( var i = 0;i < json.length;i++ ) {
                                               var ins_id   = json[i].ins_id;
                                               var ins_name = json[i].ins_name;
                                               insGrid.addRow(i,ins_id+","+ins_name+","+i);
                                         }
                                      }
                                });

                         });

                         var insGrid = subTopLayout.cells("b").attachGrid();
                         insGrid.setHeader("INS_ID,INS NAME");
                         insGrid.setColumnIds("ins_id,ins_name");
                         insGrid.setInitWidths("100,*");
                         insGrid.setColTypes("ro,ro");
                         insGrid.init();

                         var myinsGrid = subLayout.cells("c").attachGrid();
                         myinsGrid.setHeader("INS_ID,NAME,STATE");
                         myinsGrid.setColumnIds("ins_id,name,state");
                         myinsGrid.setInitWidths("100,*,*");
                         myinsGrid.setColTypes("ro,ro,ro");
                         myinsGrid.init();
                         myinsGrid.loadXML("xml/view_insurance.php");
                    }
             });

             phaForm.attachEvent("onButtonClick",function(id){

                    if (id == "viewPha") {
                         //alert("test");
                         //Create layout
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();
                         //subLayout.cells("b").hideHeader();
                         subLayout.cells("c").setHeight(350);

                         var subTopLayout = subLayout.cells("b").attachLayout("2U");
                         subTopLayout.cells("a").setText("STATES");
                         subTopLayout.cells("b").setText("CITIES");

                         var subBotLayout = subLayout.cells("c").attachLayout("2U");
                         subBotLayout.cells("a").setText("AVAILABLE PHARMACIES");
                         subBotLayout.cells("b").setText("MY PHARMACIES");

                         //Create toolbar and grid for patients
                         var userTools = subTopLayout.cells("a").attachToolbar();
                         userTools.addButton("userAdd",550,"ADD PHARMACY");
                         userTools.addButton("userRemove",450,"REMOVE PHARMACY");

                         userTools.attachEvent("onClick",function(id){
                                 if (id == "userAdd") {
                                     //alert("USER ADD");

                                     var selectedRowId = phaGrid.getSelectedRowId();
                                     var cellObj = phaGrid.cellById(selectedRowId,0);
                                     var ph_id = cellObj.getValue();

                                     var selectedRowId = phaGrid.getSelectedRowId();
                                     var cellObj = phaGrid.cellById(selectedRowId,1);
                                     var name = cellObj.getValue();

                                     var selectedRowId = phaGrid.getSelectedRowId();
                                     var cellObj = phaGrid.cellById(selectedRowId,2);
                                     var addr = cellObj.getValue();

                                     var selectedRowId = cityGrid.getSelectedRowId();
                                     var cellObj = cityGrid.cellById(selectedRowId,2);
                                     var city = cellObj.getValue();

                                     var selectedRowId = staGrid.getSelectedRowId();
                                     var cellObj = staGrid.cellById(selectedRowId,1);
                                     var state = cellObj.getValue();

                                     var selectedRowId = phaGrid.getSelectedRowId();
                                     var cellObj = phaGrid.cellById(selectedRowId,3);
                                     var phone = cellObj.getValue();

                                     var selectedRowId = phaGrid.getSelectedRowId();
                                     var cellObj = phaGrid.cellById(selectedRowId,4);
                                     var lat = cellObj.getValue();

                                     var selectedRowId = phaGrid.getSelectedRowId();
                                     var cellObj = phaGrid.cellById(selectedRowId,5);
                                     var lon = cellObj.getValue();


                                     var url = "xml/add_pharmacy.php?id="+ph_id+"&name="+name+"&addr="+addr+"&city="+city+"&state="+state+"&phone="+phone+"&lat="+lat+"&lon="+lon;
                                     $.ajax({ type: "GET",
                                           url: url,
                                           dataType: "text",
                                           error: function (xhr, status, error) {
                                              alert(error);
                                           },
                                           success: function (text) {
                                              dhtmlx.message(text);
                                              myphaGrid.clearAll();
                                              myphaGrid.updateFromXML("xml/view_pharmacy.php");
                                           }
                                     });
                                 }


                                 if (id == "userRemove") {
                                     //alert("USER REMOVE");
                                     var selectedRowId = myphaGrid.getSelectedRowId();
                                     var cellObj = myphaGrid.cellById(selectedRowId,0);
                                     var pha_id = cellObj.getValue();
                                     //alert(pha_id);
                                     var url = "xml/remove_pharmacy.php?id="+pha_id;
                                     $.ajax({ type: "GET",
                                           url: url,
                                           dataType: "text",
                                           error: function (xhr, status, error) {
                                              alert(error);
                                           },
                                           success: function (text) {
                                              dhtmlx.message(text);
                                              myphaGrid.clearAll();
                                              myphaGrid.updateFromXML("xml/view_pharmacy.php");
                                           }
                                     });
                                 }
                         });

                         var staGrid = subTopLayout.cells("a").attachGrid();
                         staGrid.setHeader("ST_ID,ST SHORT,ST LONG");
                         staGrid.setColumnIds("st_id,st_short,st_long");
                         staGrid.setInitWidths("100,*,*");
                         staGrid.setColTypes("ro,ro,ro");
                         staGrid.init();

                         var url = "http://api.firstmedisource.com/call.php/states?user_key=654628232eb57960ccad23ec60d1a150";
                         $.ajax({ type: "GET",
                               url: url,
                               dataType: "json",
                               error: function (xhr, status, error) {
                                  alert(error);
                               },
                               success: function (json) {
                                  //alert(json.length);
                                  for ( var i = 0;i < json.length;i++ ) {
                                        var st_id    = json[i].st_id;
                                        var st_short = json[i].st_short;
                                        var st_long  = json[i].st_long
                                        staGrid.addRow(i,st_id+","+st_short+","+st_long+","+i);
                                  }
                               }
                         });

                         staGrid.attachEvent("onRowSelect",function(id,ind) {
                                var selectedRowId = staGrid.getSelectedRowId();
                                var cellObj = staGrid.cellById(selectedRowId,1);
                                var st_short = cellObj.getValue();

                                cityGrid.clearAll();
                                var url = "http://api.firstmedisource.com/call.php/cities/"+ st_short + "?user_key=654628232eb57960ccad23ec60d1a150";
                                $.ajax({ type: "GET",
                                      url: url,
                                      dataType: "json",
                                      error: function (xhr, status, error) {
                                         alert(error);
                                      },
                                      success: function (json) {
                                         for ( var i = 0;i < json.length;i++ ) {
                                               var ct_id   = json[i].ct_id;
                                               var st_id   = json[i].st_id;
                                               var ct_name = json[i].ct_name;
                                               cityGrid.addRow(i,ct_id+","+st_id+","+ct_name+","+i);
                                         }
                                      }
                                });


                         });


                         var cityGrid = subTopLayout.cells("b").attachGrid();
                         cityGrid.setHeader("CT_ID,ST_ID,CT NAME");
                         cityGrid.setColumnIds("ct_id,st_id,ct_name");
                         cityGrid.setInitWidths("100,100,*");
                         cityGrid.setColTypes("ro,ro,ro");
                         cityGrid.init();

                         cityGrid.attachEvent("onRowSelect",function(id,ind) {
                                var selectedRowId = staGrid.getSelectedRowId();
                                var cellObj = staGrid.cellById(selectedRowId,1);
                                var st_short = cellObj.getValue();

                                var selectedRowId = cityGrid.getSelectedRowId();
                                var cellObj = cityGrid.cellById(selectedRowId,2);
                                var ct_name = cellObj.getValue();

                                phaGrid.clearAll();
                                var url = "http://api.firstmedisource.com/call.php/pharmacies/" +st_short+ "/" +ct_name+ "?user_key=654628232eb57960ccad23ec60d1a150";
                                $.ajax({ type: "GET",
                                      url: url,
                                      dataType: "json",
                                      error: function (xhr, status, error) {
                                         alert(error);
                                      },
                                      success: function (json) {
                                         for ( var i = 0;i < json.length;i++ ) {
                                               var ph_id = json[i].ph_id;
                                               var cname = json[i].cname;
                                               var addr  = json[i].addr;
                                               var phone = json[i].phone;
                                               var lat   = json[i].lat;
                                               var lon   = json[i].lon;
                                               phaGrid.addRow(i,ph_id+","+cname+","+addr+","+phone+","+lat+","+lon+","+i);
                                         }
                                      }
                                });

                         });


                         var phaGrid = subBotLayout.cells("a").attachGrid();
                         phaGrid.setHeader("PH_ID,CNAME,ADDR,PHONE,LAT,LON");
                         phaGrid.setColumnIds("ph_id,cname,addr,phone,lat,lon");
                         phaGrid.setInitWidths("100,*,*,*,*,*");
                         phaGrid.setColTypes("ro,ro,ro,ro,ro,ro");
                         phaGrid.init();


                         var myphaGrid = subBotLayout.cells("b").attachGrid();
                         myphaGrid.setHeader("ID,NAME,ADDR,CITY,STATE,PHONE,LAT,LON");
                         myphaGrid.setColumnIds("id,name,addr,city,state,phone,lat,lng");
                         myphaGrid.setInitWidths("*,*,*,*,*,*,*,*");
                         myphaGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
                         myphaGrid.init();
                         myphaGrid.loadXML("xml/view_pharmacy.php");
                    }

                    if (id == "viewPhaFax") {
                        //alert("VIEW FAX NUMBERS");
                         //alert("test");
                         //Create layout
                         subLayout.cells("b").detachObject();
                         subLayout.cells("c").detachObject();
                         subLayout.cells("b").detachToolbar();
                         subLayout.cells("c").detachToolbar();

                         subLayout.cells("b").showHeader();
                         subLayout.cells("c").showHeader();

                         subLayout.cells("b").setText("MY PHARMACIES");
                         subLayout.cells("c").setText("PHARMACY FAX NUMBER");

                         subLayout.cells("c").setHeight(350);

                         var myphaGrid = subLayout.cells("b").attachGrid();
                         myphaGrid.setHeader("ID,NAME,ADDR,CITY,STATE,FAX,PHONE");
                         myphaGrid.setColumnIds("id,name,addr,city,state,fax,phone");
                         myphaGrid.setInitWidths("100,*,*,*,*,*,*");
                         myphaGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
                         myphaGrid.init();
                         myphaGrid.loadXML("xml/view_pharmacy_fax.php");

                         var editPhaFormData = [
                                        {type: "fieldset", name: "custFormD", label: "EDIT PHARMACY FAX NUMBER", list: [
                                            {type: "hidden", name: "id"},
                                            {type: "input", inputWidth: "200", label: "Fax Number", labelAlign: "right", position: "label-right", name:"fax"},
                                            {type: "button", name:"submit", value:"UPDATE"}
                                         ]}
                         ];

                         var editPhaForm = subLayout.cells("c").attachForm(editPhaFormData);
                         editPhaForm.bind(myphaGrid);

                         editPhaForm.attachEvent("onButtonClick",function(id){

                                    if (id == "submit") {

                                        var id  = editPhaForm.getItemValue("id");
                                        var fax = editPhaForm.getItemValue("fax");

                                        dhtmlxAjax.get("xml/update_fax.php?id="+id+"&fax="+fax,function(loader) {
                                                        if (loader.xmlDoc.responseText == "success") {
                                                                dhtmlx.message("FAX UPDATED");
                                                                myphaGrid.clearAll();
                                                                myphaGrid.updateFromXML("xml/view_pharmacy.php");
                                                        }
                                                        else {
                                                                dhtmlx.message("Invalid Input");
                                                        }
                                        });
                                    }
                         });

                    }
             });

        })

    </script>
</head>

<body>

<div id="vaultDiv">
  <form id="myForm" action="upload.php" method="post" enctype="multipart/form-data">
       <input type="file" size="60" name="myfile">
       <input type="submit" value="Upload User CSV File">
  </form>

  <div id="progress">
        <div id="bar"></div>
        <div id="percent">0%</div >
  </div>

  <br/>
  <div id="message"></div>

</div>

</body>
</html>
