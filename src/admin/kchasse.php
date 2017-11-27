<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Martin Luther King Day Control Panel</title>

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

        //All Charting Code goes here
        var myAllStudentsChart = new FusionCharts( "FusionCharts/Pie2D.swf","myChartId", "850", "600", "0", "1" );
        myAllStudentsChart.render("allStudents");

        var myfreshmanChart = new FusionCharts( "FusionCharts/Pie2D.swf","myChartId", "350", "450", "0", "1" );
        myfreshmanChart.render("freshmanChart");

        var mysophmoreChart = new FusionCharts( "FusionCharts/Pie2D.swf","myChartId", "350", "450", "0", "1" );
        mysophmoreChart.render("sophmoreChart");

        var myjuniorChart = new FusionCharts( "FusionCharts/Pie2D.swf","myChartId", "350", "450", "0", "1" );
        myjuniorChart.render("juniorChart");

        var myseniorChart = new FusionCharts( "FusionCharts/Pie2D.swf","myChartId", "350", "450", "0", "1" );
        myseniorChart.render("seniorChart");


        //Here we'll put the code of the application
        var layout;
        var leftNav;


        dhtmlxEvent(window,"load",function(){

              //layout
              layout = new dhtmlXLayoutObject(document.body,"3L");

              //Login Code
              lgX = screen.width / 2 - 250;
              lgY = screen.height / 2 - 150;
              loginWin = layout.dhxWins.createWindow("lgWin", lgX, lgY, 300, 175);
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

                               var uname = lgForm.getItemValue("uname");
                               var upass = lgForm.getItemValue("pass");
                               //alert(uname);
                               dhtmlxAjax.get("auth/authenticate.php?uname="+uname+"&upass="+upass,function(loader) {

                                      //alert(loader.xmlDoc.responseText);
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

              layout.cells("a").hideHeader();
              layout.cells("b").hideHeader();
              layout.cells("c").hideHeader();
              layout.cells("a").setWidth(200);
              layout.cells("c").setHeight(350);
              //layout.cells("b").attachObject("splash_canvas");

              leftNav = layout.cells("a").attachAccordion();
              leftNav.addItem("f1","HOME");
              leftNav.addItem("a1","STUDENTS");
              leftNav.addItem("b1","SESSIONS");
              leftNav.addItem("c1","REGISTRATION");
              leftNav.addItem("d1","REPORTS");
              leftNav.addItem("e1","SETTINGS");

              leftNav.cells("b1").open();

              var homeForm = leftNav.cells("f1").attachForm();
              var studForm = leftNav.cells("a1").attachForm();
              var sessForm = leftNav.cells("b1").attachForm();
              var regiForm = leftNav.cells("c1").attachForm();
              var repoForm = leftNav.cells("d1").attachForm();
              var settForm = leftNav.cells("e1").attachForm();

              homeForm.loadStructString('<items><item width="193" type="button" name="viewhome" value="Home"/></items>');
              studForm.loadStructString('<items><item width="193" type="button" name="viewstud" value="View Students"/></items>');
              sessForm.loadStructString('<items><item width="193" type="button" name="viewsess" value="View Sessions"/></items>');
              regiForm.loadStructString('<items><item width="193" type="button" name="viewFreshregi" value="9th Grade Registration Details"/><item width="193" type="button" name="viewSophregi" value="10th Grade Registration Details"/><item width="193" type="button" name="viewJuniregi" value="11th Grade Registration Details"/><item width="193" type="button" name="viewSeniregi" value="12th Grade Registration Details"/><item width="193" type="button" name="viewWorkregi" value="Workshop Registration Details"/></items>');
              repoForm.loadStructString('<items><item width="193" type="button" name="viewrepo" value="Reports"/></items>');
              settForm.loadStructString('<items><item width="193" type="button" name="viewsett" value="Reset Registration"/></items>');

              homeForm.attachEvent("onButtonClick",function(id){

                       //alert("HOME");
                       layout.cells("b").detachToolbar();
                       layout.cells("b").detachObject();
                       layout.cells("c").detachToolbar();
                       layout.cells("c").detachObject();

                       layout.cells("b").attachObject("splash_canvas");
                       myAllStudentsChart.setXMLUrl("xml/allStudentsChart.php");
                       myAllStudentsChart.render("allStudentsChart");
              });

              studForm.attachEvent("onButtonClick",function(id){

                       //alert("STUDENTS");
                       layout.cells("b").detachToolbar();
                       layout.cells("b").detachObject();
                       layout.cells("c").detachToolbar();
                       layout.cells("c").detachObject();
                       layout.cells("c").setHeight(350);

                       var studGrid = layout.cells("b").attachGrid();
                       studGrid.setHeader("ST_ID,U NAME,FULL NAME,GRADE,SESSION 1,SESSION 2,SESSION 3");
                       studGrid.setColumnIds("st_id,u_name,f_name,grade,sess_1_v,sess_2_v,sess_3_v");
                       studGrid.setInitWidths("50,*,*,50,100,100,100");
                       studGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
                       studGrid.init();
                       studGrid.loadXML("xml/mlk_students.php");

                       var studDetailsFormData = [
                            {type: "fieldset", name: "myStudData", label: "Student Details", list: [
                                {type: "input", label: "User Name", labelAlign: "right", position: "label-right", name:"u_name"},
                                {type: "input", label: "Full Name", labelAlign: "right", position: "label-right", name:"f_name"},
                                {type: "input", label: "Grade", labelAlign: "right", position: "label-right", name:"grade"},
                                {type: "button", name: "remove", value: "REMOVE STUDENT"},
                                {type: "button", name: "update", value: "SAVE CHANGES"},
                                {type: "button", name: "new", value: "NEW STUDENT"}
                            ]}
                       ];

                       var studDetailsForm = layout.cells("c").attachForm(studDetailsFormData);
                       studDetailsForm.bind(studGrid);
              });


              sessForm.attachEvent("onButtonClick",function(id){

                       //alert("SESSIONS");
                       layout.cells("b").detachToolbar();
                       layout.cells("b").detachObject();
                       layout.cells("c").detachToolbar();
                       layout.cells("c").detachObject();
                       layout.cells("c").setHeight(350);

                       var sessGrid = layout.cells("b").attachGrid();
                       sessGrid.setHeader("S_ID,NAME,DESCRIPTION,LOCATION,SESSION 1?,SESSION 2?,SESSION 3?,SESSION 1 SEATS,SESSION 2 SEATS,SESSION 3 SEATS");
                       sessGrid.setColumnIds("s_id,name,desc,locale,sess_1,sess_2,sess_3,sess_1_seats,sess_2_seats,sess_3_seats");
                       sessGrid.setInitWidths("50,*,*,*,50,50,50,100,100,100");
                       sessGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                       sessGrid.init();
                       sessGrid.loadXML("xml/mlk_sessions.php");

                       sessDP = new dataProcessor("xml/mlk_sessions.php");
                       sessDP.init(sessGrid);

                       var sessDetailsFormData = [
                            {type: "fieldset", name: "mySessData", label: "Session Details", list: [
                                {type: "input", label: "Location", labelAlign: "top", position: "label-top", name:"locale"},
                                {type: "input", label: "SESSION 1", labelAlign: "top", position: "label-top", name:"sess_1"},
                                {type: "input", label: "SESSION 2", labelAlign: "top", position: "label-top", name:"sess_2"},
                                {type: "input", label: "SESSION 3", labelAlign: "top", position: "label-top", name:"sess_3"},
                                {type: "input", label: "SESS 1 SEATS", labelAlign: "top", position: "label-top", name:"sess_1_seats"},
                                {type: "input", label: "SESS 2 SEATS", labelAlign: "top", position: "label-top", name:"sess_2_seats"},
                                {type: "input", label: "SESS 3 SEATS", labelAlign: "top", position: "label-top", name:"sess_3_seats"},
                                {type: "newcolumn"},
                                {type: "label", label:"                      "},
                                {type: "newcolumn"},
                                {type: "label", label:"                      "},
                                {type: "newcolumn"},
                                {type: "label", label:"                      "},
                                {type: "newcolumn"},
                                {type: "label", label:"                      "},
                                {type: "input", inputWidth: "700", label: "Title", labelAlign: "right", position: "label-right", name: "name", rows: "3"},
                         {type: "input", inputWidth: "700", label: "Description", labelAlign: "right", position: "label-right", name: "desc", rows: "7"},
                                {type: "button", name: "remove", value: "REMOVE SESSION"},
                                {type: "button", name: "update", value: "SAVE CHANGES"},
                                {type: "button", name: "new", value: "NEW SESSION"}
                            ]}
                       ];

                       var sessDetailsForm = layout.cells("c").attachForm(sessDetailsFormData);
                       sessDetailsForm.bind(sessGrid);

                       sessDetailsForm.attachEvent("onButtonClick",function(id){

                                if (id == "update") {
                                    sessDetailsForm.save();
                                }

                                if (id == "remove") {
                                    sessGrid.deleteRow( sessGrid.getSelectedRowId() );
                                    sessDetailsForm.clear();
                                }

                                if (id == "new") {
                                     var popupWindow = layout.dhxWins.createWindow("newsession_win", 0, 0, 1000, 350);
                                     popupWindow.center();
                                     popupWindow.setText("New Session");

                                     var newSessDetailsFormData = [
                                          {type: "fieldset", name: "mySessData", label: "Session Details", list: [
                                              {type: "input", label: "Location", labelAlign: "top", position: "label-top", name:"locale"},
                                              {type: "input", label: "SESSION 1", labelAlign: "top", position: "label-top", name:"sess_1"},
                                              {type: "input", label: "SESSION 2", labelAlign: "top", position: "label-top", name:"sess_2"},
                                              {type: "input", label: "SESSION 3", labelAlign: "top", position: "label-top", name:"sess_3"},
                                              {type: "input", label: "SESS 1 SEATS", labelAlign: "top", position: "label-top", name:"sess_1_seats"},
                                              {type: "input", label: "SESS 2 SEATS", labelAlign: "top", position: "label-top", name:"sess_2_seats"},
                                              {type: "input", label: "SESS 3 SEATS", labelAlign: "top", position: "label-top", name:"sess_3_seats"},
                                              {type: "newcolumn"},
                                              {type: "label", label:"                      "},
                                              {type: "newcolumn"},
                                              {type: "label", label:"                      "},
                                              {type: "newcolumn"},
                                              {type: "label", label:"                      "},
                                              {type: "newcolumn"},
                                              {type: "label", label:"                      "},
                                              {type: "input", inputWidth: "700", label: "Title", labelAlign: "right", position: "label-right", name: "name", rows: "3"},
                                              {type: "input", inputWidth: "700", label: "Description", labelAlign: "right", position: "label-right", name: "descr", rows: "7"},
                                              {type: "button", name: "new", value: "CREATE"}
                                         ]}
                                    ];

                                    var newSessionForm = popupWindow.attachForm(newSessDetailsFormData);
                                    var dpnc = new dataProcessor("xml/mlk_sessions.php");
                                    dpnc.init(newSessDetailsForm);

                                    newSessionForm.attachEvent("onButtonClick", function(id) {
                                        dpnc.sendData();
                                    });

                                }
                       });
              });

              regiForm.attachEvent("onButtonClick",function(id){

                    if (id == "viewFreshregi") {
                        //alert("REGISTRATION");
                        layout.cells("b").detachToolbar();
                        layout.cells("b").detachObject();
                        layout.cells("c").detachToolbar();
                        layout.cells("c").detachObject();
                        layout.cells("c").setHeight(550);

                        var layout2 = layout.cells("b").attachLayout("2U");
                        layout2.cells("a").hideHeader();
                        layout2.cells("a").fixSize(true,true);
                        layout2.cells("a").setWidth(350);
                        layout2.cells("b").setText("Non Registered Freshmen");

                        layout2.cells("a").attachObject("9thchart");
                        myfreshmanChart.setXMLUrl("xml/allStudentsChart.php");
                        myfreshmanChart.render("freshmanChart");

                        var ninthGradeTools = layout2.cells("b").attachToolbar();
                        ninthGradeTools.addButton("regStudent",0,"REGISTER");

                        var noSessGrid = layout2.cells("b").attachGrid();
                        noSessGrid.setHeader("ST_ID,STUDENT");
                        noSessGrid.setColumnIds("f_name,name");
                        noSessGrid.setInitWidths("100,*");
                        noSessGrid.setColTypes("ro,ro");
                        noSessGrid.init();
                        noSessGrid.loadXML("xml/mlk_9thgrade_not_reg.php");

                        ninthGradeTools.attachEvent("onClick",function(id){

                             //alert("REGISTER STUDENT");
                             var popupWindow = layout.dhxWins.createWindow("newcontact_win", 0, 0, 1000, 500);
                             popupWindow.center();
                             popupWindow.setText("REGISTER STUDENT");
                             var layout1 = popupWindow.attachLayout("2E");
                             layout1.cells("a").hideHeader();
                             layout1.cells("b").hideHeader();
                             layout1.cells("a").setHeight(480);
                             layout1.cells("a").fixSize(true,true);

                             var layout2 = layout1.cells("a").attachLayout("2U");
                             layout2.cells("a").setText("SESSION 1");
                             layout2.cells("b").setText("SESSION 2");
                             layout2.cells("a").fixSize(true,true);

                             var man1Grid = layout2.cells("a").attachGrid();
                             man1Grid.setHeader("S_ID,KIDS,NAME");
                             man1Grid.setColumnIds("s_id,sess_1_seats,name");
                             man1Grid.setInitWidths("50,50,*");
                             man1Grid.setColTypes("ro,ro,ro");
                             man1Grid.init();
                             man1Grid.loadXML("xml/mlk_9thgrade_man_reg1.php");

                             var man2Grid = layout2.cells("b").attachGrid();
                             man2Grid.setHeader("S_ID,KIDS,NAME");
                             man2Grid.setColumnIds("s_id,sess_2_seats,name");
                             man2Grid.setInitWidths("50,50,*");
                             man2Grid.setColTypes("ro,ro,ro");
                             man2Grid.init();
                             man2Grid.loadXML("xml/mlk_9thgrade_man_reg2.php");

                             var regForm = layout1.cells("b").attachForm();
                             regForm.loadStructString('<items><item width="193" type="button" name="regStud" value="Enroll Student"/></items>');
                             regForm.attachEvent("onButtonClick",function(id){

                                     var selectedRowID = noSessGrid.getSelectedRowId();
                                     var cellObj = noSessGrid.cellById(selectedRowID,0);
                                     var st_id = cellObj.getValue();

                                     selectedRowID = man1Grid.getSelectedRowId();
                                     cellObj = man1Grid.cellById(selectedRowID,0);
                                     var s1_id = cellObj.getValue();

                                     selectedRowID = man2Grid.getSelectedRowId();
                                     cellObj = man2Grid.cellById(selectedRowID,0);
                                     var s2_id = cellObj.getValue();

                                     //alert("ST_ID: " + st_id + " S1: " + s1_id + " S2:" + s2_id);
                                     dhtmlxAjax.get("xml/enrollStudent.php?st_id="+st_id+"&s1_id="+s1_id+"&s2_id="+s2_id, function(loader){

                                           if (loader.xmlDoc.responseText == "Success") {

                                                dhtmlx.message("STUDENT ENROLLED");
                                                popupWindow.hide();

                                                noSessGrid.clearAll();
                                                noSessGrid.updateFromXML("xml/mlk_9thgrade_not_reg.php");

                                                sessGrid1st.clearAll();
                                                sessGrid1st.updateFromXML("xml/mlk_9thgrade_sess1_reg.php");

                                                sessGrid2nd.clearAll();
                                                sessGrid2nd.updateFromXML("xml/mlk_9thgrade_sess2_reg.php");

                                                myfreshmanChart.setXMLUrl("xml/allStudentsChart.php");
                                                myfreshmanChart.render("freshmanChart");
                                           }
                                     });
                             });
                        });

                        var layout3 = layout.cells("c").attachLayout("2U");
                        layout3.cells("a").setText("SESSION 1");
                        layout3.cells("a").fixSize(true,true);
                        layout3.cells("a").setWidth(500);
                        layout3.cells("b").setText("SESSION 2");

                        var sessGrid1st = layout3.cells("a").attachGrid();
                        sessGrid1st.setHeader("STUDENT,SESSION");
                        sessGrid1st.setColumnIds("f_name,name");
                        sessGrid1st.setInitWidths("150,*");
                        sessGrid1st.setColTypes("ro,ro");
                        sessGrid1st.init();
                        sessGrid1st.loadXML("xml/mlk_9thgrade_sess1_reg.php");

                        var sessGrid2nd = layout3.cells("b").attachGrid();
                        sessGrid2nd.setHeader("STUDENT,SESSION");
                        sessGrid2nd.setColumnIds("f_name,name");
                        sessGrid2nd.setInitWidths("150,*");
                        sessGrid2nd.setColTypes("ro,ro");
                        sessGrid2nd.init();
                        sessGrid2nd.loadXML("xml/mlk_9thgrade_sess2_reg.php");
                    }

                    if (id == "viewSophregi") {
                        //alert("REGISTRATION");
                        layout.cells("b").detachToolbar();
                        layout.cells("b").detachObject();
                        layout.cells("c").detachToolbar();
                        layout.cells("c").detachObject();
                        layout.cells("c").setHeight(550);

                        var layout2 = layout.cells("b").attachLayout("2U");
                        layout2.cells("a").hideHeader();
                        layout2.cells("a").fixSize(true,true);
                        layout2.cells("a").setWidth(350);
                        layout2.cells("b").setText("Non Registered Sophmores");

                        layout2.cells("a").attachObject("10thchart");
                        mysophmoreChart.setXMLUrl("xml/allStudentsChart.php");
                        mysophmoreChart.render("sophmoreChart");

                        var ninthGradeTools = layout2.cells("b").attachToolbar();
                        ninthGradeTools.addButton("regStudent",0,"REGISTER");

                        var noSessGrid = layout2.cells("b").attachGrid();
                        noSessGrid.setHeader("ST_ID,STUDENT");
                        noSessGrid.setColumnIds("f_name,name");
                        noSessGrid.setInitWidths("100,*");
                        noSessGrid.setColTypes("ro,ro");
                        noSessGrid.init();
                        noSessGrid.loadXML("xml/mlk_10thgrade_not_reg.php");

                        ninthGradeTools.attachEvent("onClick",function(id){

                             //alert("REGISTER STUDENT");
                             var popupWindow = layout.dhxWins.createWindow("newcontact_win", 0, 0, 1000, 500);
                             popupWindow.center();
                             popupWindow.setText("REGISTER STUDENT");
                             var layout1 = popupWindow.attachLayout("2E");
                             layout1.cells("a").hideHeader();
                             layout1.cells("b").hideHeader();
                             layout1.cells("a").setHeight(480);
                             layout1.cells("a").fixSize(true,true);

                             var layout2 = layout1.cells("a").attachLayout("2U");
                             layout2.cells("a").setText("SESSION 1");
                             layout2.cells("b").setText("SESSION 2");
                             layout2.cells("a").fixSize(true,true);

                             var man1Grid = layout2.cells("a").attachGrid();
                             man1Grid.setHeader("S_ID,KIDS,NAME");
                             man1Grid.setColumnIds("s_id,sess_1_seats,name");
                             man1Grid.setInitWidths("50,50,*");
                             man1Grid.setColTypes("ro,ro,ro");
                             man1Grid.init();
                             man1Grid.loadXML("xml/mlk_10thgrade_man_reg1.php");

                             var man2Grid = layout2.cells("b").attachGrid();
                             man2Grid.setHeader("S_ID,KIDS,NAME");
                             man2Grid.setColumnIds("s_id,sess_2_seats,name");
                             man2Grid.setInitWidths("50,50,*");
                             man2Grid.setColTypes("ro,ro,ro");
                             man2Grid.init();
                             man2Grid.loadXML("xml/mlk_10thgrade_man_reg2.php");

                             var regForm = layout1.cells("b").attachForm();
                             regForm.loadStructString('<items><item width="193" type="button" name="regStud" value="Enroll Student"/></items>');
                             regForm.attachEvent("onButtonClick",function(id){

                                     var selectedRowID = noSessGrid.getSelectedRowId();
                                     var cellObj = noSessGrid.cellById(selectedRowID,0);
                                     var st_id = cellObj.getValue();

                                     selectedRowID = man1Grid.getSelectedRowId();
                                     cellObj = man1Grid.cellById(selectedRowID,0);
                                     var s1_id = cellObj.getValue();

                                     selectedRowID = man2Grid.getSelectedRowId();
                                     cellObj = man2Grid.cellById(selectedRowID,0);
                                     var s2_id = cellObj.getValue();

                                     //alert("ST_ID: " + st_id + " S1: " + s1_id + " S2:" + s2_id);
                                     dhtmlxAjax.get("xml/enrollStudent.php?st_id="+st_id+"&s1_id="+s1_id+"&s2_id="+s2_id, function(loader){

                                           if (loader.xmlDoc.responseText == "Success") {

                                                dhtmlx.message("STUDENT ENROLLED");
                                                popupWindow.hide();

                                                noSessGrid.clearAll();
                                                noSessGrid.updateFromXML("xml/mlk_10thgrade_not_reg.php");

                                                sessGrid1st.clearAll();
                                                sessGrid1st.updateFromXML("xml/mlk_10thgrade_sess1_reg.php");

                                                sessGrid2nd.clearAll();
                                                sessGrid2nd.updateFromXML("xml/mlk_10thgrade_sess2_reg.php");

                                                mysophmoreChart.setXMLUrl("xml/allStudentsChart.php");
                                                mysophmoreChart.render("sophmoreChart");
                                           }
                                     });
                             });
                        });

                        var layout3 = layout.cells("c").attachLayout("2U");
                        layout3.cells("a").setText("SESSION 1");
                        layout3.cells("a").fixSize(true,true);
                        layout3.cells("a").setWidth(500);
                        layout3.cells("b").setText("SESSION 2");

                        var sessGrid1st = layout3.cells("a").attachGrid();
                        sessGrid1st.setHeader("STUDENT,SESSION");
                        sessGrid1st.setColumnIds("f_name,name");
                        sessGrid1st.setInitWidths("150,*");
                        sessGrid1st.setColTypes("ro,ro");
                        sessGrid1st.init();
                        sessGrid1st.loadXML("xml/mlk_10thgrade_sess1_reg.php");

                        var sessGrid2nd = layout3.cells("b").attachGrid();
                        sessGrid2nd.setHeader("STUDENT,SESSION");
                        sessGrid2nd.setColumnIds("f_name,name");
                        sessGrid2nd.setInitWidths("150,*");
                        sessGrid2nd.setColTypes("ro,ro");
                        sessGrid2nd.init();
                        sessGrid2nd.loadXML("xml/mlk_10thgrade_sess2_reg.php");
                    }


                    if (id == "viewJuniregi") {
                        //alert("REGISTRATION");
                        layout.cells("b").detachToolbar();
                        layout.cells("b").detachObject();
                        layout.cells("c").detachToolbar();
                        layout.cells("c").detachObject();
                        layout.cells("c").setHeight(550);

                        var layout2 = layout.cells("b").attachLayout("2U");
                        layout2.cells("a").hideHeader();
                        layout2.cells("a").fixSize(true,true);
                        layout2.cells("a").setWidth(350);
                        layout2.cells("b").setText("Non Registered Juniors");

                        layout2.cells("a").attachObject("11thchart");
                        myjuniorChart.setXMLUrl("xml/allStudentsChart.php");
                        myjuniorChart.render("juniorChart");

                        var ninthGradeTools = layout2.cells("b").attachToolbar();
                        ninthGradeTools.addButton("regStudent",0,"REGISTER");

                        var noSessGrid = layout2.cells("b").attachGrid();
                        noSessGrid.setHeader("ST_ID,STUDENT");
                        noSessGrid.setColumnIds("f_name,name");
                        noSessGrid.setInitWidths("100,*");
                        noSessGrid.setColTypes("ro,ro");
                        noSessGrid.init();
                        noSessGrid.loadXML("xml/mlk_11thgrade_not_reg.php");

                        ninthGradeTools.attachEvent("onClick",function(id){

                             //alert("REGISTER STUDENT");
                             var popupWindow = layout.dhxWins.createWindow("newcontact_win", 0, 0, 1000, 500);
                             popupWindow.center();
                             popupWindow.setText("REGISTER STUDENT");
                             var layout1 = popupWindow.attachLayout("2E");
                             layout1.cells("a").hideHeader();
                             layout1.cells("b").hideHeader();
                             layout1.cells("a").setHeight(480);
                             layout1.cells("a").fixSize(true,true);

                             var layout2 = layout1.cells("a").attachLayout("2U");
                             layout2.cells("a").setText("SESSION 1");
                             layout2.cells("b").setText("SESSION 2");
                             layout2.cells("a").fixSize(true,true);

                             var man1Grid = layout2.cells("a").attachGrid();
                             man1Grid.setHeader("S_ID,KIDS,NAME");
                             man1Grid.setColumnIds("s_id,sess_1_seats,name");
                             man1Grid.setInitWidths("50,50,*");
                             man1Grid.setColTypes("ro,ro,ro");
                             man1Grid.init();
                             man1Grid.loadXML("xml/mlk_11thgrade_man_reg1.php");

                             var man2Grid = layout2.cells("b").attachGrid();
                             man2Grid.setHeader("S_ID,KIDS,NAME");
                             man2Grid.setColumnIds("s_id,sess_2_seats,name");
                             man2Grid.setInitWidths("50,50,*");
                             man2Grid.setColTypes("ro,ro,ro");
                             man2Grid.init();
                             man2Grid.loadXML("xml/mlk_11thgrade_man_reg2.php");

                             var regForm = layout1.cells("b").attachForm();
                             regForm.loadStructString('<items><item width="193" type="button" name="regStud" value="Enroll Student"/></items>');
                             regForm.attachEvent("onButtonClick",function(id){

                                     var selectedRowID = noSessGrid.getSelectedRowId();
                                     var cellObj = noSessGrid.cellById(selectedRowID,0);
                                     var st_id = cellObj.getValue();

                                     selectedRowID = man1Grid.getSelectedRowId();
                                     cellObj = man1Grid.cellById(selectedRowID,0);
                                     var s1_id = cellObj.getValue();

                                     selectedRowID = man2Grid.getSelectedRowId();
                                     cellObj = man2Grid.cellById(selectedRowID,0);
                                     var s2_id = cellObj.getValue();

                                     //alert("ST_ID: " + st_id + " S1: " + s1_id + " S2:" + s2_id);
                                     dhtmlxAjax.get("xml/enrollStudent.php?st_id="+st_id+"&s1_id="+s1_id+"&s2_id="+s2_id, function(loader){

                                           if (loader.xmlDoc.responseText == "Success") {

                                                dhtmlx.message("STUDENT ENROLLED");
                                                popupWindow.hide();

                                                noSessGrid.clearAll();
                                                noSessGrid.updateFromXML("xml/mlk_11thgrade_not_reg.php");

                                                sessGrid1st.clearAll();
                                                sessGrid1st.updateFromXML("xml/mlk_11thgrade_sess1_reg.php");

                                                sessGrid2nd.clearAll();
                                                sessGrid2nd.updateFromXML("xml/mlk_11thgrade_sess2_reg.php");

                                                myjuniorChart.setXMLUrl("xml/allStudentsChart.php");
                                                myjuniorChart.render("juniorChart");
                                           }
                                     });
                             });
                        });

                        var layout3 = layout.cells("c").attachLayout("2U");
                        layout3.cells("a").setText("SESSION 1");
                        layout3.cells("a").fixSize(true,true);
                        layout3.cells("a").setWidth(500);
                        layout3.cells("b").setText("SESSION 2");

                        var sessGrid1st = layout3.cells("a").attachGrid();
                        sessGrid1st.setHeader("STUDENT,SESSION");
                        sessGrid1st.setColumnIds("f_name,name");
                        sessGrid1st.setInitWidths("150,*");
                        sessGrid1st.setColTypes("ro,ro");
                        sessGrid1st.init();
                        sessGrid1st.loadXML("xml/mlk_11thgrade_sess1_reg.php");

                        var sessGrid2nd = layout3.cells("b").attachGrid();
                        sessGrid2nd.setHeader("STUDENT,SESSION");
                        sessGrid2nd.setColumnIds("f_name,name");
                        sessGrid2nd.setInitWidths("150,*");
                        sessGrid2nd.setColTypes("ro,ro");
                        sessGrid2nd.init();
                        sessGrid2nd.loadXML("xml/mlk_11thgrade_sess2_reg.php");
                    }

                    if (id == "viewSeniregi") {
                        //alert("REGISTRATION");
                        layout.cells("b").detachToolbar();
                        layout.cells("b").detachObject();
                        layout.cells("c").detachToolbar();
                        layout.cells("c").detachObject();
                        layout.cells("c").setHeight(550);

                        var layout2 = layout.cells("b").attachLayout("2U");
                        layout2.cells("a").hideHeader();
                        layout2.cells("a").fixSize(true,true);
                        layout2.cells("a").setWidth(350);
                        layout2.cells("b").setText("Non Registered Seniors");

                        layout2.cells("a").attachObject("12thchart");
                        myseniorChart.setXMLUrl("xml/allStudentsChart.php");
                        myseniorChart.render("seniorChart");

                        var ninthGradeTools = layout2.cells("b").attachToolbar();
                        ninthGradeTools.addButton("regStudent",0,"REGISTER");

                        var noSessGrid = layout2.cells("b").attachGrid();
                        noSessGrid.setHeader("ST_ID,STUDENT");
                        noSessGrid.setColumnIds("f_name,name");
                        noSessGrid.setInitWidths("100,*");
                        noSessGrid.setColTypes("ro,ro");
                        noSessGrid.init();
                        noSessGrid.loadXML("xml/mlk_12thgrade_not_reg.php");

                        ninthGradeTools.attachEvent("onClick",function(id){

                             //alert("REGISTER STUDENT");
                             var popupWindow = layout.dhxWins.createWindow("newcontact_win", 0, 0, 1000, 500);
                             popupWindow.center();
                             popupWindow.setText("REGISTER STUDENT");
                             var layout1 = popupWindow.attachLayout("2E");
                             layout1.cells("a").hideHeader();
                             layout1.cells("b").hideHeader();
                             layout1.cells("a").setHeight(480);
                             layout1.cells("a").fixSize(true,true);

                             var layout2 = layout1.cells("a").attachLayout("2U");
                             layout2.cells("a").setText("SESSION 1");
                             layout2.cells("b").setText("SESSION 2");
                             layout2.cells("a").fixSize(true,true);

                             var man1Grid = layout2.cells("a").attachGrid();
                             man1Grid.setHeader("S_ID,KIDS,NAME");
                             man1Grid.setColumnIds("s_id,sess_1_seats,name");
                             man1Grid.setInitWidths("50,50,*");
                             man1Grid.setColTypes("ro,ro,ro");
                             man1Grid.init();
                             man1Grid.loadXML("xml/mlk_12thgrade_man_reg1.php");

                             var man2Grid = layout2.cells("b").attachGrid();
                             man2Grid.setHeader("S_ID,KIDS,NAME");
                             man2Grid.setColumnIds("s_id,sess_2_seats,name");
                             man2Grid.setInitWidths("50,50,*");
                             man2Grid.setColTypes("ro,ro,ro");
                             man2Grid.init();
                             man2Grid.loadXML("xml/mlk_12thgrade_man_reg2.php");

                             var regForm = layout1.cells("b").attachForm();
                             regForm.loadStructString('<items><item width="193" type="button" name="regStud" value="Enroll Student"/></items>');
                             regForm.attachEvent("onButtonClick",function(id){

                                     var selectedRowID = noSessGrid.getSelectedRowId();
                                     var cellObj = noSessGrid.cellById(selectedRowID,0);
                                     var st_id = cellObj.getValue();

                                     selectedRowID = man1Grid.getSelectedRowId();
                                     cellObj = man1Grid.cellById(selectedRowID,0);
                                     var s1_id = cellObj.getValue();

                                     selectedRowID = man2Grid.getSelectedRowId();
                                     cellObj = man2Grid.cellById(selectedRowID,0);
                                     var s2_id = cellObj.getValue();

                                     //alert("ST_ID: " + st_id + " S1: " + s1_id + " S2:" + s2_id);
                                     dhtmlxAjax.get("xml/enrollStudent.php?st_id="+st_id+"&s1_id="+s1_id+"&s2_id="+s2_id, function(loader){

                                           if (loader.xmlDoc.responseText == "Success") {

                                                dhtmlx.message("STUDENT ENROLLED");
                                                popupWindow.hide();

                                                noSessGrid.clearAll();
                                                noSessGrid.updateFromXML("xml/mlk_12thgrade_not_reg.php");

                                                sessGrid1st.clearAll();
                                                sessGrid1st.updateFromXML("xml/mlk_12thgrade_sess1_reg.php");

                                                sessGrid2nd.clearAll();
                                                sessGrid2nd.updateFromXML("xml/mlk_12thgrade_sess2_reg.php");

                                                myjuniorChart.setXMLUrl("xml/allStudentsChart.php");
                                                myjuniorChart.render("juniorChart");
                                           }
                                     });
                             });
                        });

                        var layout3 = layout.cells("c").attachLayout("2U");
                        layout3.cells("a").setText("SESSION 1");
                        layout3.cells("a").fixSize(true,true);
                        layout3.cells("a").setWidth(500);
                        layout3.cells("b").setText("SESSION 2");

                        var sessGrid1st = layout3.cells("a").attachGrid();
                        sessGrid1st.setHeader("STUDENT,SESSION");
                        sessGrid1st.setColumnIds("f_name,name");
                        sessGrid1st.setInitWidths("150,*");
                        sessGrid1st.setColTypes("ro,ro");
                        sessGrid1st.init();
                        sessGrid1st.loadXML("xml/mlk_12thgrade_sess1_reg.php");

                        var sessGrid2nd = layout3.cells("b").attachGrid();
                        sessGrid2nd.setHeader("STUDENT,SESSION");
                        sessGrid2nd.setColumnIds("f_name,name");
                        sessGrid2nd.setInitWidths("150,*");
                        sessGrid2nd.setColTypes("ro,ro");
                        sessGrid2nd.init();
                        sessGrid2nd.loadXML("xml/mlk_12thgrade_sess2_reg.php");
                    }


                    if (id == "viewWorkregi") {

                        //alert("WORKSHOP REGISTRATION DETAILS");
                        layout.cells("b").detachToolbar();
                        layout.cells("b").detachObject();
                        layout.cells("c").detachToolbar();
                        layout.cells("c").detachObject();
                        layout.cells("c").setHeight(450);
                        layout.cells("c").setText("ENROLLED STUDENTS");

                        var layout1 = layout.cells("b").attachLayout("2U");
                        layout1.cells("a").setText("SESSION 1");
                        layout1.cells("b").setText("SESSION 2");
                        layout1.cells("a").fixSize(true,true);

                        var man1Grid = layout1.cells("a").attachGrid();
                        man1Grid.setHeader("S_ID,KIDS,NAME");
                        man1Grid.setColumnIds("s_id,sess_1_seats,name");
                        man1Grid.setInitWidths("50,50,*");
                        man1Grid.setColTypes("ro,ro,ro");
                        man1Grid.init();
                        man1Grid.loadXML("xml/mlk_9thgrade_man_reg1.php");

                        var man2Grid = layout1.cells("b").attachGrid();
                        man2Grid.setHeader("S_ID,KIDS,NAME");
                        man2Grid.setColumnIds("s_id,sess_2_seats,name");
                        man2Grid.setInitWidths("50,50*");
                        man2Grid.setColTypes("ro,ro,ro");
                        man2Grid.init();
                        man2Grid.loadXML("xml/mlk_9thgrade_man_reg2.php");

                        var selStudGrid = layout.cells("c").attachGrid();
                        selStudGrid.setHeader("NAME,GRADE");
                        selStudGrid.setColumnIds("f_name,grade");
                        selStudGrid.setInitWidths("*,50");
                        selStudGrid.setColTypes("ro,ro");
                        selStudGrid.init();
                        selStudGrid.loadXML("xml/mlk_session1_wkshop_students.php");

                        man1Grid.attachEvent("onRowSelect",function(id,ind){
                                     var selectedRowID = man1Grid.getSelectedRowId();
                                     var cellObj = man1Grid.cellById(selectedRowID,0);
                                     var s_id = cellObj.getValue();
                                     selStudGrid.clearAll();
                                     selStudGrid.updateFromXML("xml/mlk_session1_wkshop_students.php?s_id="+s_id);
                        });

                        man2Grid.attachEvent("onRowSelect",function(id,ind){
                                     var selectedRowID = man2Grid.getSelectedRowId();
                                     var cellObj = man1Grid.cellById(selectedRowID,0);
                                     var s_id = cellObj.getValue();
                                     selStudGrid.clearAll();
                                     selStudGrid.updateFromXML("xml/mlk_session2_wkshop_students.php?s_id="+s_id);
                        });

                    }
              });

              repoForm.attachEvent("onButtonClick",function(id){alert("REPORTS");});

              settForm.attachEvent("onButtonClick",function(id){alert("SETTINGS");});



        })

    </script>
</head>

<body>


<div id="splash_canvas">
 <div id="allStudentsChart"></div>
</div>

<div id="9thchart">
  <div id="freshmanChart"></div>
</div>

<div id="10thchart">
  <div id="sophmoreChart"></div>
</div>

<div id="11thchart">
  <div id="juniorChart"></div>
</div>

<div id="12thchart">
  <div id="seniorChart"></div>
</div>


</body>
</html>
