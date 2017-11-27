        //Here we'll put the code of the application

        var mainLayout;
        var subLayout;
        var leftNav;
        var appForm;
        var patForm;
        var preForm;
        var repForm;

        var chartNav;
        var chartForm;

        var uname;
        var api_key;
        var perm = {"submitScript":"", "viewScript":"", "viewScriptQue":"", "viewRefills":"", "viewAdherence":"", "viewPatients":"", "viewCharts":"", "viewFormulary":"", "viewReports":"", "is_dr":"", "md_id":""};
        var patientScript = {"md_id":"", "pt_id":"", "med":"", "qty":"", "sig":"", "refills":"", "sub_perm":"", "ph_id":"", "med_id":""};

        var geocoder;
        var marker;
        var viewMap;
        var patlat;
        var patlng;


function state_escript() {

          subLayout.cells("a").detachObject();
          subLayout.cells("b").detachObject();
          subLayout.cells("c").detachObject();
          subLayout.cells("b").detachToolbar();
          subLayout.cells("c").detachToolbar();

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
              leftNav.cells("b1").open();


              var appForm = leftNav.cells("a1").attachForm();
              var patForm = leftNav.cells("b1").attachForm();
              var preForm = leftNav.cells("c1").attachForm();
              var repForm = leftNav.cells("d1").attachForm();

              appForm.loadStructString('<items><item width="193" type="button" name="viewApp" value="View Appointments"/></items>');
              //viewApp
              patForm.loadStructString('<items><item width="193" type="button" name="viewPat" value="View Patients"/><item width="193" type="button" name="viewIns" value="Patient Insurance"/></items>');
              //viewPat
              //viewChart
              preForm.loadStructString('<items><item width="193" type="button" name="createMed" value="Submit Prescriptions"/><item width="193" type="button" name="viewMed" value="View Prescriptions"/><item width="193" type="button" name="viewMedQue" value="View Prescriptions Queue"/><item width="193" type="button" name="viewMedRef" value="Refill Authorization"/></items>');
              //createMed
              //viewMed
              //viewMedQue
              //viewMedRef
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
                                 patientsGrid.loadXML("xml/view_patientbio.php");

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
                                 subBotLayout.cells("a").setText("PATIENT BIOGRAPHICAL INFORMATION");
                                 subBotLayout.cells("b").setText("PATIENT MAP");
                                 //initializeViewMap();
                                 subBotLayout.cells("b").attachObject("viewMap_canvas");


                                 var patientsGrid = subLayout.cells("b").attachGrid();
                                 patientsGrid.setHeader("PAT_ID,LAST NAME,FIRST NAME,DOB,ADDR,CITY,STATE,ZIP,PHONE,CELL,EMAIL,SSEC");
                                 patientsGrid.setColumnIds("id,last_name,first_name,dob,addr,city,state,zip,phone,cell,email,ssec");
                                 patientsGrid.setInitWidths("100,*,*,*,*,*,*,*,*,*,*,*,*,*,*");
                                 patientsGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                                 patientsGrid.init();
                                 patientsGrid.loadXML("xml/view_patientbio.php");

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

                                           if (id == "userChart") {


                                               var selectedRowId = patientsGrid.getSelectedRowId();
                                               var cellObj = patientsGrid.cellById(selectedRowId,0);
                                               pat_id = cellObj.getValue();

                                               var selectedRowId = patientsGrid.getSelectedRowId();
                                               var cellObj = patientsGrid.cellById(selectedRowId,2);
                                               var first = cellObj.getValue();

                                               var selectedRowId = patientsGrid.getSelectedRowId();
                                               var cellObj = patientsGrid.cellById(selectedRowId,1);
                                               var last = cellObj.getValue();

                                               var selectedRowId = patientsGrid.getSelectedRowId();
                                               var cellObj = patientsGrid.cellById(selectedRowId,3);
                                               var dob = cellObj.getValue();

                                               state_chart(pat_id,first,last,dob);
                                           }

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

                             if (perm.submitScript == "true") {
                                 selectPatient();
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

                    if (id == "viewMed") {

                             if (perm.submitScript == "true") {
                                 //selectPatient();
                                 subLayout.cells("b").detachObject();
                                 subLayout.cells("c").detachObject();
                                 subLayout.cells("b").detachToolbar();
                                 subLayout.cells("c").detachToolbar();
                                 subLayout.cells("b").showHeader();
                                 subLayout.cells("c").showHeader();

                                 subLayout.cells("c").setHeight(350);
                                 subLayout.cells("b").setText("PATIENTS");
                                 subLayout.cells("c").setText("PRESCRIPTIONS FOR SELECTED PATIENT");

                                 var patientsGrid = subLayout.cells("b").attachGrid();
                                 patientsGrid.setHeader("PAT_ID,LAST NAME,FIRST NAME,DOB,ADDR,CITY,STATE,ZIP,PHONE,CELL,EMAIL,SSEC");
                                 patientsGrid.setColumnIds("id,last_name,first_name,dob,addr,city,state,zip,phone,cell,email,ssec");
                                 patientsGrid.setInitWidths("100,*,*,*,*,*,*,*,*,*,*,*,*,*,*");
                                 patientsGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                                 patientsGrid.init();
                                 patientsGrid.loadXML("xml/view_patientbio.php");


                                 var scriptsGrid = subLayout.cells("c").attachGrid();
                                 scriptsGrid.setHeader("ID,DATE,TIME,MED,PHARMACY,PHARMACY PHONE");
                                 scriptsGrid.setColumnIds("id,s_date,s_time,med,pharmacy,pphone");
                                 scriptsGrid.setInitWidths("100,*,*,*,*,*");
                                 scriptsGrid.setColTypes("ro,ro,ro,ro,ro,ro");
                                 scriptsGrid.init();


                                 //Create toolbar and grid for patients
                                 var userTools = subLayout.cells("b").attachToolbar();
                                 userTools.addText("text_user",0,"SEARCH PATIENT");
                                 userTools.addInput("userInput",100);
                                 userTools.addButton("userChoose",350,"SEARCH");
                                 userTools.addButton("preview",350,"PREVIEW SCRIPT");

                                 userTools.attachEvent("onClick",function(id){

                                         if (id == "preview") {

                                             var selectedRowId = scriptsGrid.getSelectedRowId();
                                             var cellObj = scriptsGrid.cellById(selectedRowId,0);
                                             var script_id = cellObj.getValue();
                                             var url = "pdf/get_patient_escriptpdf.php?script_id="+script_id;
                                             window.open(url,'_blank');

                                         }
                                 });

                                 patientsGrid.attachEvent("onRowSelect",function(id,ind) {

                                     var selectedRowId = patientsGrid.getSelectedRowId();
                                     var cellObj = patientsGrid.cellById(selectedRowId,0);
                                     var pt_id = cellObj.getValue();

                                     scriptsGrid.clearAll();

                                     var i = 0;
                                     var url = "pdf/get_patient_escriptsxml.php?pt_id="+pt_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('script').each(function() {

                                                   var id        = $(this).find('id').text();
                                                   var s_date    = $(this).find('s_date').text();
                                                   var s_time    = $(this).find('s_time').text();
                                                   var med       = $(this).find('med').text();
                                                   var pharmacy  = $(this).find('pharmacy').text();
                                                   var pphone    = $(this).find('pphone').text();
                                                   scriptsGrid.addRow(i,id+","+s_date+","+s_time+","+med+","+pharmacy+","+pphone+","+i);
                                                   i++;
                                              });
                                         }
                                     });

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
                    }// end create med
                    
                    
                    if (id == "viewMedQue") {
                        
                        if (perm.viewScriptQue == "true") {
                            
                                 subLayout.cells("b").detachObject();
                                 subLayout.cells("c").detachObject();
                                 subLayout.cells("b").detachToolbar();
                                 subLayout.cells("c").detachToolbar();
                                 subLayout.cells("b").showHeader();
                                 subLayout.cells("c").hideHeader();

                                 subLayout.cells("c").setHeight(350);
                                 subLayout.cells("b").setText("PRESCRIPTIONS IN QUEUE");
                                 //subLayout.cells("c").setText("PRESCRIPTIONS FOR SELECTED PATIENT");
                                 
                                 var scriptsGrid = subLayout.cells("b").attachGrid();
                                 scriptsGrid.setHeader("ID,PATIENT,DOB,MED,PHARMACY,PHARMACY PHONE");
                                 scriptsGrid.setColumnIds("id,pt_name,dob,med,pharmacy,pphone");
                                 scriptsGrid.setInitWidths("100,*,*,*,*,*");
                                 scriptsGrid.setColTypes("ro,ro,ro,ro,ro,ro");
                                 scriptsGrid.init();
                                 //scriptsGrid.loadXML("pdf/get_escriptquexml.php");
                                 i = 0;
                                 var url = "pdf/get_escriptquexml.php";
                                 $.ajax({ type: "GET",
                                             url: url,
                                             dataType: "xml",
                                             error: function (xhr, status, error) {
                                                alert(error);
                                             },
                                             success: function (xml) {
                                                 
                                                 //alert(xml);
                                                 
                                               $(xml).find('script').each(function() {

                                                    var id       = $(this).find('id').text();
                                                    var pt_name  = $(this).find('pt_name').text();
                                                    var dob      = $(this).find('dob').text();
                                                    var med      = $(this).find('med').text();
                                                    var pharmacy = $(this).find('pharmacy').text();
                                                    var pphone   = $(this).find('pphone').text();

                                                    //dhtmlx.message(pt_name);
                                                    scriptsGrid.addRow(i,id+","+pt_name+","+dob+","+med+","+pharmacy+","+pphone+","+i);
                                                    i++;
                                               });
                                             }
                                });
                                
                                 //Create toolbar and grid for patients
                                 var userTools = subLayout.cells("b").attachToolbar();
                                 userTools.addButton("preview",350,"PREVIEW SCRIPT");
                                 userTools.addButton("submit",350,"SEND SCRIPT");
                                 userTools.attachEvent("onClick",function(id){

                                         if (id == "preview") {

                                             var selectedRowId = scriptsGrid.getSelectedRowId();
                                             var cellObj = scriptsGrid.cellById(selectedRowId,0);
                                             var script_id = cellObj.getValue();
                                             //alert(script_id);
                                             var url = "pdf/get_patient_escriptquepdf.php?script_id="+script_id;
                                             window.open(url,'_blank');

                                         }
                                         
                                         if (id == "submit") {
                                             //alert("submit");
                                             var selectedRowId = scriptsGrid.getSelectedRowId();
                                             var cellObj = scriptsGrid.cellById(selectedRowId,0);
                                             var script_id = cellObj.getValue();
                                             //alert(script_id);
                                             var url = "pdf/send_patient_escriptquepdf.php?script_id="+script_id;
                                             $.ajax({ type: "GET",
                                                      url: url,
                                                      dataType: "xml",
                                                      error: function (xhr, status, error) {
                                                             alert(error);
                                                      },
                                                      success: function (xml) {
                                                         $(xml).find('messages').each(function() {

                                                          var msg = $(this).find('message').text();
                                                          dhtmlx.message(msg);

                                                         });
                                                      }
                                             });
                                             scriptsGrid.deleteRow(selectedRowId);
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

             });



}


function state_chart(pat_id,first,last,dob) {

          var pt_id = pat_id;
          subLayout.cells("a").detachObject();
          subLayout.cells("b").detachObject();
          subLayout.cells("c").detachObject();
          subLayout.cells("b").detachToolbar();
          subLayout.cells("c").detachToolbar();
          subLayout.cells("b").setText(first+ " " + last + " " + dob);

          chartNav = subLayout.cells("a").attachAccordion();
          chartNav.addItem("a1","PATIENT CHART");

          chartForm = chartNav.cells("a1").attachForm();
          chartForm.loadStructString('<items><item width="193" type="button" name="viewPMH" value="Past Medical History"/><item width="193" type="button" name="viewDXH" value="Diagnosis History"/><item width="193" type="button" name="viewRXL" value="RX List"/><item width="193" type="button" name="viewRXA" value="Medication Allergies"/><item width="193" type="button" name="viewSoap" value="SOAP Notes"/></items>');
          //viewPMH
          //viewDXH
          //viewRXL
          //viewRXA
          //viewSOAP

          subLayout.cells("b").attachObject("chart1_canvas");
          subLayout.cells("c").attachObject("chart2_canvas");

          chartForm.attachEvent("onButtonClick",function(id){

                  if (id == "viewPMH") {

                      subLayout.cells("b").detachObject();
                      subLayout.cells("c").detachObject();
                      subLayout.cells("b").detachToolbar();
                      subLayout.cells("c").detachToolbar();
                      //subLayout.cells("b").showHeader();
                      //subLayout.cells("c").hideHeader();
                      //subLayout.cells("b").setText("PAST MEDICAL HISTORY");

                      var pFormData = [
                           {type: "fieldset", name: "custFormD", label: "PAST MEDICAL HISTORY", list: [
                               {type: "hidden", name: "pt_id"},
                               {type: "input", inputWidth: "400", label: "MAJOR EVENTS, HOSPITALIZATIONS, SURGERIES", labelAlign: "right", position: "label-right", name:"p1"},
                               {type: "input", inputWidth: "400", label: "ALLERGIES", labelAlign: "right", position: "label-right", name:"p2"},
                               {type: "input", inputWidth: "400", label: "ONGOING MEDICAL PROBLEMS", labelAlign: "right", position: "label-right", name:"p3"},

                               {type: "input", inputWidth: "400", label: "FAMILY MEDICAL HISTORY", labelAlign: "right", position: "label-right", name:"p4"},
                               {type: "input", inputWidth: "400", label: "PREVENATIVE CARE", labelAlign: "right", position: "label-right", name:"p5"},
                               {type: "input", inputWidth: "400", label: "SOCIAL HISTORY", labelAlign: "right", position: "label-right", name:"p6"},

                               {type: "input", inputWidth: "400", label: "NUTRITIONAL HISTORY", labelAlign: "right", position: "label-right", name:"p7"},
                               {type: "input", inputWidth: "400", label: "DEVELOPMENTAL HISTORY", labelAlign: "right", position: "label-right", name:"p8"}
                           ]}
                     ];

                     var pForm = subLayout.cells("b").attachForm(pFormData);

                     var cFormData = [
                           {type: "fieldset", name: "custFormD", label: "UPDATE PATIENT PAST MEDICAL HISTORY", list: [
                               {type: "button", name:"submit", value:"UPDATE"}
                           ]}
                     ];

                     var cForm = subLayout.cells("c").attachForm(cFormData);

                     var url = "xml/get_pmh_xml.php?pt_id="+pt_id;
                                $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('pmh').each(function() {

                                                   var p1 = $(this).find('p1').text();
                                                   var p2 = $(this).find('p2').text();
                                                   var p3 = $(this).find('p3').text();
                                                   var p4 = $(this).find('p4').text();
                                                   var p5 = $(this).find('p5').text();
                                                   var p6 = $(this).find('p6').text();
                                                   var p7 = $(this).find('p7').text();
                                                   var p8 = $(this).find('p8').text();

                                                   pForm.setItemValue("p1",p1);
                                                   pForm.setItemValue("p2",p2);
                                                   pForm.setItemValue("p3",p3);
                                                   pForm.setItemValue("p4",p4);
                                                   pForm.setItemValue("p5",p5);
                                                   pForm.setItemValue("p6",p6);
                                                   pForm.setItemValue("p7",p7);
                                                   pForm.setItemValue("p8",p8);

                                              });
                                         }
                     });

                     cForm.attachEvent("onButtonClick",function(id){

                            if (id == "submit") {
                                //alert("SELECTED PATIENT ID IS " + pt_id);

                                var p1 = pForm.getItemValue("p1");
                                var p2 = pForm.getItemValue("p2");
                                var p3 = pForm.getItemValue("p3");
                                var p4 = pForm.getItemValue("p4");
                                var p5 = pForm.getItemValue("p5");
                                var p6 = pForm.getItemValue("p6");
                                var p7 = pForm.getItemValue("p7");
                                var p8 = pForm.getItemValue("p8");

                                i = 0;
                                var url = "xml/add_pmh_xml.php?pt_id="+pt_id+"&p8="+p8+"&p1="+p1+"&p2="+p2+"&p3="+p3+"&p4="+p4+"&p5="+p5+"&p6="+p6+"&p7="+p7;
                                $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('messages').each(function() {

                                                   var msg = $(this).find('message').text();
                                                   dhtmlx.message(msg);
                                              });
                                         }
                                  });

                            }
                     });


                  }


                  if (id == "viewDXH") {

                      subLayout.cells("b").detachObject();
                      subLayout.cells("c").detachObject();
                      subLayout.cells("b").detachToolbar();
                      subLayout.cells("c").detachToolbar();
                      subLayout.cells("b").showHeader();
                      subLayout.cells("c").showHeader();
                      subLayout.cells("b").setText("CHRONIC DIAGNOSIS");
                      subLayout.cells("c").setText("ACUTE DIAGNOSIS");

                      var chronicGrid = subLayout.cells("b").attachGrid();
                      chronicGrid.setHeader("ID,DATE,SEEN BY,ICD10 CODE,DESCRIPTION");
                      chronicGrid.setColumnIds("diag_id,diag_date,u_id,code,descr");
                      chronicGrid.setInitWidths("100,*,*,*,*");
                      chronicGrid.setColTypes("ro,ro,ro,ro,ro");
                      chronicGrid.init();

                      i = 0;
                      var url = "xml/get_chronic_diagnosis_xml.php?pt_id="+pt_id;
                                          $.ajax({ type: "GET",
                                             url: url,
                                             dataType: "xml",
                                             error: function (xhr, status, error) {
                                                alert(error);
                                             },
                                             success: function (xml) {
                                               $(xml).find('condition').each(function() {

                                                    var diag_id = $(this).find('diag_id').text();
                                                    var diag_date = $(this).find('diag_date').text();
                                                    var u_id = $(this).find('u_id').text();
                                                    var code = $(this).find('code').text();
                                                    var descr = $(this).find('descr').text();

                                                    //dhtmlx.message(diag_id);
                                                    chronicGrid.addRow(i,diag_id+","+diag_date+","+u_id+","+code+","+descr+","+i);
                                                    i++;
                                               });
                                             }
                      });

                      /*i = 0;
                      var url = "xml/get_acute_diagnosis_xml.php?pt_id="+pt_id;
                                          $.ajax({ type: "GET",
                                             url: url,
                                             dataType: "xml",
                                             error: function (xhr, status, error) {
                                                alert(error);
                                             },
                                             success: function (xml) {
                                               $(xml).find('condition').each(function() {

                                                    var diag_id = $(this).find('diag_id').text();
                                                    var diag_date = $(this).find('diag_date').text();
                                                    var u_id = $(this).find('u_id').text();
                                                    var code = $(this).find('code').text();
                                                    var descr = $(this).find('descr').text();

                                                    //dhtmlx.message(diag_id);
                                                    acuteGrid.addRow(i,diag_id+","+diag_date+","+u_id+","+code+","+descr+","+i);
                                                    i++;
                                               });
                                             }
                      });*/

                      var acuteGrid = subLayout.cells("c").attachGrid();
                      acuteGrid.setHeader("ID,DATE,SEEN BY,ICD10 CODE,DESCRIPTION");
                      acuteGrid.setColumnIds("diag_id,diag_date,u_id,code,descr");
                      acuteGrid.setInitWidths("100,*,*,*,*");
                      acuteGrid.setColTypes("ro,ro,ro,ro,ro");
                      acuteGrid.init();

                      i = 0;
                      var url = "xml/get_acute_diagnosis_xml.php?pt_id="+pt_id;
                                          $.ajax({ type: "GET",
                                             url: url,
                                             dataType: "xml",
                                             error: function (xhr, status, error) {
                                                alert(error);
                                             },
                                             success: function (xml) {
                                               $(xml).find('condition').each(function() {

                                                    var diag_id = $(this).find('diag_id').text();
                                                    var diag_date = $(this).find('diag_date').text();
                                                    var u_id = $(this).find('u_id').text();
                                                    var code = $(this).find('code').text();
                                                    var descr = $(this).find('descr').text();

                                                    //dhtmlx.message(diag_id);
                                                    acuteGrid.addRow(i,diag_id+","+diag_date+","+u_id+","+code+","+descr+","+i);
                                                    i++;
                                               });
                                             }
                      });

                      var chronicTools = subLayout.cells("b").attachToolbar();
                      chronicTools.addButton("add",350,"ADD");
                      chronicTools.addButton("remove",550,"REMOVE");

                      var acuteTools = subLayout.cells("c").attachToolbar();
                      acuteTools.addButton("add",350,"ADD");
                      acuteTools.addButton("remove",550,"REMOVE");


                      chronicTools.attachEvent("onClick",function(id){

                             if (id == "add") {

                                 var popupWindow = subLayout.dhxWins.createWindow("newcontact_win", 0, 0, 700, 400);
                                 popupWindow.center();
                                 popupWindow.setText("Add Chronic Diagnosis");

                                 var popLayout = popupWindow.attachLayout("2E");
                                 popLayout.cells("a").setText("DIAGNOSIS AND CODES");
                                 popLayout.cells("b").hideHeader();

                                 var diagGrid = popLayout.cells("a").attachGrid();
                                 diagGrid.setHeader("ID,ICD10 CODE,DESCRIPTION");
                                 diagGrid.setColumnIds("id,code,descr");
                                 diagGrid.setInitWidths("100,*,*,*");
                                 diagGrid.setColTypes("ro,ro,ro");
                                 diagGrid.init();


                                 var cFormData = [
                                       {type: "fieldset", name: "custFormD", label: "CHRONIC DIAGNOSIS", list: [
                                           {type: "input", inputWidth: "200", label: "DIAGNOSIS SEARCH TERM", labelAlign: "right", position: "label-right", name:"searchterm"},
                                           {type: "button", name:"search", value:"SEARCH"},
                                           {type: "button", name:"submit", value:"ADD"}
                                       ]}
                                 ];

                                var cForm = popLayout.cells("b").attachForm(cFormData);
                                cForm.attachEvent("onButtonClick",function(id){

                                      if (id == "search") {

                                          diagGrid.clearAll();
                                          var searchterm = cForm.getItemValue("searchterm");
                                          var url = "http://api.firstmedisource.com/call.php/geticd10code/" +searchterm+ "?user_key=654628232eb57960ccad23ec60d1a150";
                                          $.ajax({ type: "GET",
                                            url: url,
                                            dataType: "json",
                                            error: function (xhr, status, error) {
                                               alert(error);
                                            },
                                            success: function (json) {
                                               //alert(json.length);
                                               for ( var i = 0;i < json.length;i++ ) {
                                                     var icd_id = json[i].icd_id;
                                                     var code   = json[i].code;
                                                     var descr  = json[i].descr;
                                                     diagGrid.addRow(i,icd_id+","+code+","+descr+","+i);
                                               }
                                            }
                                          });
                                      }

                                      if (id == "submit") {

                                          var u_id = perm.md_id;
                                          var selectedRowId = diagGrid.getSelectedRowId();
                                          var cellObj = diagGrid.cellById(selectedRowId,0);
                                          var icd_id = cellObj.getValue();

                                          var selectedRowId = diagGrid.getSelectedRowId();
                                          var cellObj = diagGrid.cellById(selectedRowId,1);
                                          var code = cellObj.getValue();

                                          var selectedRowId = diagGrid.getSelectedRowId();
                                          var cellObj = diagGrid.cellById(selectedRowId,2);
                                          var descr = cellObj.getValue();
                                          var d_type = "Chronic";
                                          //alert(u_id+":"+icd_id+":"+code+":"+descr+":"+pt_id);

                                          chronicGrid.clearAll();
                                          var url = "xml/add_chronic_diagnosis_xml.php?pt_id="+pt_id+"&u_id="+u_id+"&code="+code+"&descr="+descr+"&d_type="+d_type;
                                          $.ajax({ type: "GET",
                                             url: url,
                                             dataType: "xml",
                                             error: function (xhr, status, error) {
                                                alert(error);
                                             },
                                             success: function (xml) {
                                               $(xml).find('messages').each(function() {

                                                    var msg = $(this).find('message').text();
                                                    dhtmlx.message(msg);
                                                    popupWindow.close();

                                                    i = 0;
                                                    var url = "xml/get_chronic_diagnosis_xml.php?pt_id="+pt_id;
                                                    $.ajax({ type: "GET",
                                                             url: url,
                                                             dataType: "xml",
                                                             error: function (xhr, status, error) {
                                                                alert(error);
                                                             },
                                                             success: function (xml) {
                                                               $(xml).find('condition').each(function() {

                                                                  var diag_id = $(this).find('diag_id').text();
                                                                  var diag_date = $(this).find('diag_date').text();
                                                                  var u_id = $(this).find('u_id').text();
                                                                  var code = $(this).find('code').text();
                                                                  var descr = $(this).find('descr').text();

                                                                  //dhtmlx.message(diag_id);
                                                                  chronicGrid.addRow(i,diag_id+","+diag_date+","+u_id+","+code+","+descr+","+i);
                                                                  i++;
                                                              });
                                                            }
                                                   });

                                               });
                                             }
                                          });

                                      }
                                });
                             }


                             if (id == "remove") {

                                 var selectedRowId = chronicGrid.getSelectedRowId();
                                 var cellObj = chronicGrid.cellById(selectedRowId,0);
                                 var diag_id = cellObj.getValue();

                                 //alert(diag_id);
//////////////////////////////////////////////////
                                          chronicGrid.clearAll();
                                          var url = "xml/remove_diagnosis_xml.php?diag_id="+diag_id;
                                          $.ajax({ type: "GET",
                                             url: url,
                                             dataType: "xml",
                                             error: function (xhr, status, error) {
                                                alert(error);
                                             },
                                             success: function (xml) {
                                               $(xml).find('messages').each(function() {

                                                    var msg = $(this).find('message').text();
                                                    dhtmlx.message(msg);
                                                    //popupWindow.close();

                                                    i = 0;
                                                    var url = "xml/get_chronic_diagnosis_xml.php?pt_id="+pt_id;
                                                    $.ajax({ type: "GET",
                                                             url: url,
                                                             dataType: "xml",
                                                             error: function (xhr, status, error) {
                                                                alert(error);
                                                             },
                                                             success: function (xml) {
                                                               $(xml).find('condition').each(function() {

                                                                  var diag_id = $(this).find('diag_id').text();
                                                                  var diag_date = $(this).find('diag_date').text();
                                                                  var u_id = $(this).find('u_id').text();
                                                                  var code = $(this).find('code').text();
                                                                  var descr = $(this).find('descr').text();

                                                                  //dhtmlx.message(diag_id);
                                                                  chronicGrid.addRow(i,diag_id+","+diag_date+","+u_id+","+code+","+descr+","+i);
                                                                  i++;
                                                              });
                                                            }
                                                   });

                                               });
                                             }
                                          });
//////////////////////////////////////////////////
                             }

                      });


                      acuteTools.attachEvent("onClick",function(id){

                             if (id == "add") {

                                 var popupWindow = subLayout.dhxWins.createWindow("newcontact_win", 0, 0, 700, 400);
                                 popupWindow.center();
                                 popupWindow.setText("Add Chronic Diagnosis");

                                 var popLayout = popupWindow.attachLayout("2E");
                                 popLayout.cells("a").setText("DIAGNOSIS AND CODES");
                                 popLayout.cells("b").hideHeader();

                                 var diagGrid = popLayout.cells("a").attachGrid();
                                 diagGrid.setHeader("ID,ICD10 CODE,DESCRIPTION");
                                 diagGrid.setColumnIds("id,code,descr");
                                 diagGrid.setInitWidths("100,*,*,*");
                                 diagGrid.setColTypes("ro,ro,ro");
                                 diagGrid.init();


                                 var cFormData = [
                                       {type: "fieldset", name: "custFormD", label: "CHRONIC DIAGNOSIS", list: [
                                           {type: "input", inputWidth: "200", label: "DIAGNOSIS SEARCH TERM", labelAlign: "right", position: "label-right", name:"searchterm"},
                                           {type: "button", name:"search", value:"SEARCH"},
                                           {type: "button", name:"submit", value:"ADD"}
                                       ]}
                                 ];

                                var cForm = popLayout.cells("b").attachForm(cFormData);
                                cForm.attachEvent("onButtonClick",function(id){

                                      if (id == "search") {

                                          diagGrid.clearAll();
                                          var searchterm = cForm.getItemValue("searchterm");
                                          var url = "http://api.firstmedisource.com/call.php/geticd10code/" +searchterm+ "?user_key=654628232eb57960ccad23ec60d1a150";
                                          $.ajax({ type: "GET",
                                            url: url,
                                            dataType: "json",
                                            error: function (xhr, status, error) {
                                               alert(error);
                                            },
                                            success: function (json) {
                                               //alert(json.length);
                                               for ( var i = 0;i < json.length;i++ ) {
                                                     var icd_id = json[i].icd_id;
                                                     var code   = json[i].code;
                                                     var descr  = json[i].descr;
                                                     diagGrid.addRow(i,icd_id+","+code+","+descr+","+i);
                                               }
                                            }
                                          });
                                      }

                                      if (id == "submit") {

                                          var u_id = perm.md_id;
                                          var selectedRowId = diagGrid.getSelectedRowId();
                                          var cellObj = diagGrid.cellById(selectedRowId,0);
                                          var icd_id = cellObj.getValue();

                                          var selectedRowId = diagGrid.getSelectedRowId();
                                          var cellObj = diagGrid.cellById(selectedRowId,1);
                                          var code = cellObj.getValue();

                                          var selectedRowId = diagGrid.getSelectedRowId();
                                          var cellObj = diagGrid.cellById(selectedRowId,2);
                                          var descr = cellObj.getValue();
                                          var d_type = "Acute";
                                          //alert(u_id+":"+icd_id+":"+code+":"+descr+":"+pt_id);

                                          acuteGrid.clearAll();
                                          var url = "xml/add_acute_diagnosis_xml.php?pt_id="+pt_id+"&u_id="+u_id+"&code="+code+"&descr="+descr+"&d_type="+d_type;
                                          $.ajax({ type: "GET",
                                             url: url,
                                             dataType: "xml",
                                             error: function (xhr, status, error) {
                                                alert(error);
                                             },
                                             success: function (xml) {
                                               $(xml).find('messages').each(function() {

                                                    var msg = $(this).find('message').text();
                                                    dhtmlx.message(msg);
                                                    popupWindow.close();


                                                    var i = 0;
                                                    var url = "xml/get_acute_diagnosis_xml.php?pt_id="+pt_id;
                                                    $.ajax({ type: "GET",
                                                             url: url,
                                                             dataType: "xml",
                                                             error: function (xhr, status, error) {
                                                                    alert(error);
                                                             },
                                                             success: function (xml) {
                                                                      $(xml).find('condition').each(function() {

                                                                           var diag_id = $(this).find('diag_id').text();
                                                                           var diag_date = $(this).find('diag_date').text();
                                                                           var u_id = $(this).find('u_id').text();
                                                                           var code = $(this).find('code').text();
                                                                           var descr = $(this).find('descr').text();

                                                                           //dhtmlx.message(diag_id);
                                                                          acuteGrid.addRow(i,diag_id+","+diag_date+","+u_id+","+code+","+descr+","+i);
                                                                          i++;
                                                                      });
                                                            }
                                                      });



                                               });
                                             }
                                          });

                                      }
                                });
                             }


                             if (id == "remove") {

                                 var selectedRowId = acuteGrid.getSelectedRowId();
                                 var cellObj = acuteGrid.cellById(selectedRowId,0);
                                 var diag_id = cellObj.getValue();

                                 //alert(diag_id);
//////////////////////////////////////////////////
                                          acuteGrid.clearAll();
                                          var url = "xml/remove_diagnosis_xml.php?diag_id="+diag_id;
                                          $.ajax({ type: "GET",
                                             url: url,
                                             dataType: "xml",
                                             error: function (xhr, status, error) {
                                                alert(error);
                                             },
                                             success: function (xml) {
                                               $(xml).find('messages').each(function() {

                                                    var msg = $(this).find('message').text();
                                                    dhtmlx.message(msg);
                                                    //popupWindow.close();

                                                    i = 0;
                                                    var url = "xml/get_acute_diagnosis_xml.php?pt_id="+pt_id;
                                                    $.ajax({ type: "GET",
                                                             url: url,
                                                             dataType: "xml",
                                                             error: function (xhr, status, error) {
                                                                alert(error);
                                                             },
                                                             success: function (xml) {
                                                               $(xml).find('condition').each(function() {

                                                                  var diag_id = $(this).find('diag_id').text();
                                                                  var diag_date = $(this).find('diag_date').text();
                                                                  var u_id = $(this).find('u_id').text();
                                                                  var code = $(this).find('code').text();
                                                                  var descr = $(this).find('descr').text();

                                                                  //dhtmlx.message(diag_id);
                                                                  acuteGrid.addRow(i,diag_id+","+diag_date+","+u_id+","+code+","+descr+","+i);
                                                                  i++;
                                                              });
                                                            }
                                                   });

                                               });
                                             }
                                          });
//////////////////////////////////////////////////
                             }


                      });


                  }


                  if (id == "viewRXL") {

                      subLayout.cells("b").detachObject();
                      subLayout.cells("c").detachObject();
                      subLayout.cells("b").detachToolbar();
                      subLayout.cells("c").detachToolbar();
                      subLayout.cells("b").showHeader();
                      subLayout.cells("c").showHeader();
                      subLayout.cells("b").setText("ESCRIPTS (SENT FROM WITHIN OPENHEALTH)");
                      subLayout.cells("c").setText("OTHER SCRIPTS (NOT SENT FROM WITH OPENHEALTH. CURRENT AND PAST)");

                      var scriptsGrid = subLayout.cells("b").attachGrid();
                      scriptsGrid.setHeader("ID,DATE,TIME,MED,PHARMACY,PHARMACY PHONE");
                      scriptsGrid.setColumnIds("id,s_date,s_time,med,pharmacy,pphone");
                      scriptsGrid.setInitWidths("100,*,*,*,*,*");
                      scriptsGrid.setColTypes("ro,ro,ro,ro,ro,ro");
                      scriptsGrid.init();

                      //alert(pt_id);

                                     var i = 0;
                                     var url = "pdf/get_patient_escriptsxml.php?pt_id="+pt_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('script').each(function() {

                                                   var id        = $(this).find('id').text();
                                                   var s_date    = $(this).find('s_date').text();
                                                   var s_time    = $(this).find('s_time').text();
                                                   var med       = $(this).find('med').text();
                                                   var pharmacy  = $(this).find('pharmacy').text();
                                                   var pphone    = $(this).find('pphone').text();
                                                   scriptsGrid.addRow(i,id+","+s_date+","+s_time+","+med+","+pharmacy+","+pphone+","+i);
                                                   i++;
                                              });
                                         }
                                     });

                      var otherScriptsGrid = subLayout.cells("c").attachGrid();
                      otherScriptsGrid.setHeader("ID,DOC,DATE,MED,SIG");
                      otherScriptsGrid.setColumnIds("os_id,doc,os_date,med,sig");
                      otherScriptsGrid.setInitWidths("100,*,*,*,*");
                      otherScriptsGrid.setColTypes("ro,ro,ro,ro,ro");
                      otherScriptsGrid.init();

                                     var i = 0;
                                     var url = "xml/get_other_script_xml.php?pt_id="+pt_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('script').each(function() {

                                                   var os_id   = $(this).find('os_id').text();
                                                   var doc     = $(this).find('doc').text();
                                                   var os_date = $(this).find('os_date').text();
                                                   var med     = $(this).find('med').text();
                                                   var sig     = $(this).find('sig').text();
                                                   otherScriptsGrid.addRow(i,os_id+","+doc+","+os_date+","+med+","+sig+","+i);
                                                   i++;
                                              });
                                         }
                                     });


                      var osTools = subLayout.cells("c").attachToolbar();
                      osTools.addButton("add",350,"ADD");
                      osTools.addButton("remove",550,"REMOVE");

                      osTools.attachEvent("onClick",function(id){

                           if (id == "add") {

                                 var popupWindow = subLayout.dhxWins.createWindow("newcontact_win", 0, 0, 700, 400);
                                 popupWindow.center();
                                 popupWindow.setText("Add Other Medication");

                                 var popLayout = popupWindow.attachLayout("2E");
                                 popLayout.cells("a").setText("MEDICATIONS");
                                 popLayout.cells("b").hideHeader()

                                 var drugGrid = popLayout.cells("a").attachGrid();
                                 drugGrid.setHeader("D_ID,DRUG NAME,INGRIEDENT NAME");
                                 drugGrid.setColumnIds("d_id,d_name,i_name");
                                 drugGrid.setInitWidths("100,*,*");
                                 drugGrid.setColTypes("ro,ro,ro");
                                 drugGrid.init();

                                 var cFormData = [
                                       {type: "fieldset", name: "custFormD", label: "CHRONIC DIAGNOSIS", list: [
                                           {type: "input", inputWidth: "200", label: "DIAGNOSIS SEARCH TERM", labelAlign: "right", position: "label-right", name:"searchterm"},
                                           {type: "button", name:"search", value:"SEARCH"},
                                           {type: "input", inputWidth: "200", label: "BY DOCTOR", labelAlign: "right", position: "label-right", name:"doc"},
                                           {type: "input", inputWidth: "200", label: "SIG", labelAlign: "right", position: "label-right", name:"sig"},
                                           {type: "button", name:"submit", value:"ADD"}
                                       ]}
                                 ];

                                var cForm = popLayout.cells("b").attachForm(cFormData);
                                cForm.attachEvent("onButtonClick",function(id){

                                      if (id == "submit") {

                                          var selectedRowId = drugGrid.getSelectedRowId();
                                          var cellObj = drugGrid.cellById(selectedRowId,1);

                                          var med = cellObj.getValue();
                                          var doc = cForm.getItemValue("doc");
                                          var sig = cForm.getItemValue("sig");

                                          otherScriptsGrid.clearAll();
                                          var url = "xml/add_other_script_xml.php?pt_id="+pt_id+"&doc="+doc+"&med="+med+"&sig="+sig;
                                          $.ajax({ type: "GET",
                                               url: url,
                                               dataType: "xml",
                                               error: function (xhr, status, error) {
                                                     alert(error);
                                               },
                                               success: function (xml) {
                                                  $(xml).find('messages').each(function() {

                                                      var msg = $(this).find('message').text();
                                                      dhtmlx.message(msg);
                                                      popupWindow.close();

                                     var i = 0;
                                     var url = "xml/get_other_script_xml.php?pt_id="+pt_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('script').each(function() {

                                                   var os_id   = $(this).find('os_id').text();
                                                   var doc     = $(this).find('doc').text();
                                                   var os_date = $(this).find('os_date').text();
                                                   var med     = $(this).find('med').text();
                                                   var sig     = $(this).find('sig').text();
                                                   otherScriptsGrid.addRow(i,os_id+","+doc+","+os_date+","+med+","+sig+","+i);
                                                   i++;
                                              });
                                         }
                                     });


                                                  });
                                              }
                                          });
                                          //dhtmlx.message(pt_id + ":"+ med + ":" + doc + ":" + sig);

                                      }

                                      if (id == "search") {

                                          drugGrid.clearAll();
                                          var searchterm = cForm.getItemValue("searchterm");
                                          //dhtmlx.message(searchterm);

                                          var url = "http://api.firstmedisource.com/call.php/searchmeds/" +searchterm+ "?user_key=654628232eb57960ccad23ec60d1a150";
                                          $.ajax({ type: "GET",
                                            url: url,
                                            dataType: "json",
                                            error: function (xhr, status, error) {
                                               alert(error);
                                            },
                                            success: function (json) {
                                               //alert(json.length);
                                              for ( var i = 0;i < json.length;i++ ) {
                                                    var d_id    = json[i].d_id;
                                                    var d_name  = json[i].d_name;
                                                    var i_name  = json[i].i_name;
                                                    drugGrid.addRow(i,d_id+","+d_name+","+i_name+","+i);
                                             }
                                            }
                                          });
                                      }

                               });

                           }


                           if (id == "remove") {

                                          var selectedRowId = otherScriptsGrid.getSelectedRowId();
                                          var cellObj = otherScriptsGrid.cellById(selectedRowId,0);
                                          var os_id = cellObj.getValue();

                                          otherScriptsGrid.clearAll();
                                          var url = "xml/remove_other_script_xml.php?os_id="+os_id;
                                          $.ajax({ type: "GET",
                                               url: url,
                                               dataType: "xml",
                                               error: function (xhr, status, error) {
                                                     alert(error);
                                               },
                                               success: function (xml) {
                                                  $(xml).find('messages').each(function() {

                                                      var msg = $(this).find('message').text();
                                                      dhtmlx.message(msg);
                                                      //popupWindow.close();

                                     var i = 0;
                                     var url = "xml/get_other_script_xml.php?pt_id="+pt_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('script').each(function() {

                                                   var os_id   = $(this).find('os_id').text();
                                                   var doc     = $(this).find('doc').text();
                                                   var os_date = $(this).find('os_date').text();
                                                   var med     = $(this).find('med').text();
                                                   var sig     = $(this).find('sig').text();
                                                   otherScriptsGrid.addRow(i,os_id+","+doc+","+os_date+","+med+","+sig+","+i);
                                                   i++;
                                              });
                                         }
                                     });


                                                  });
                                              }
                                          });
                           }

                      });

                  }


                  if (id == "viewRXA") {

                      subLayout.cells("b").detachObject();
                      subLayout.cells("c").detachObject();
                      subLayout.cells("b").detachToolbar();
                      subLayout.cells("c").detachToolbar();
                      subLayout.cells("b").showHeader();
                      subLayout.cells("c").showHeader();
                      subLayout.cells("b").setText("Drug Allergies");
                      subLayout.cells("c").setText("All Medications");

                      var allergyGrid = subLayout.cells("b").attachGrid();
                      allergyGrid.setHeader("ID,DRUG NAME");
                      allergyGrid.setColumnIds("al_id,d_name");
                      allergyGrid.setInitWidths("100,*");
                      allergyGrid.setColTypes("ro,ro");
                      allergyGrid.init();

                                     var i = 0;
                                     var url = "xml/get_drug_allergy_xml.php?pt_id="+pt_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('med').each(function() {

                                                   var al_id   = $(this).find('al_id').text();
                                                   var d_name     = $(this).find('d_name').text();
                                                   allergyGrid.addRow(i,al_id+","+d_name+","+i);
                                                   i++;
                                              });
                                         }
                                     });



                      var drugGrid = subLayout.cells("c").attachGrid();
                      drugGrid.setHeader("D_ID,DRUG NAME,INGRIEDENT NAME");
                      drugGrid.setColumnIds("d_id,d_name,i_name");
                      drugGrid.setInitWidths("100,*,*");
                      drugGrid.setColTypes("ro,ro,ro");
                      drugGrid.init();

                      var userTools = subLayout.cells("c").attachToolbar();
                      userTools.addText("text_user",0,"SEARCH MED");
                      userTools.addInput("userInput",100);
                      userTools.addButton("userChoose",350,"SEARCH");
                      userTools.addButton("add",450,"ADD");
                      userTools.addButton("remove",550,"REMOVE");

                      userTools.attachEvent("onClick",function(id){

                           if (id == "remove") {

                                          var selectedRowId = allergyGrid.getSelectedRowId();
                                          var cellObj = allergyGrid.cellById(selectedRowId,0);
                                          var al_id = cellObj.getValue();

                                          allergyGrid.clearAll();
                                          var url = "xml/remove_drug_allergy_xml.php?al_id="+al_id;
                                          $.ajax({ type: "GET",
                                               url: url,
                                               dataType: "xml",
                                               error: function (xhr, status, error) {
                                                     alert(error);
                                               },
                                               success: function (xml) {
                                                  $(xml).find('messages').each(function() {

                                                      var msg = $(this).find('message').text();
                                                      dhtmlx.message(msg);
                                                      //popupWindow.close();

                                     var i = 0;
                                     var url = "xml/get_drug_allergy_xml.php?pt_id="+pt_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('med').each(function() {

                                                   var al_id   = $(this).find('al_id').text();
                                                   var d_name    = $(this).find('d_name').text();
                                                   allergyGrid.addRow(i,al_id+","+d_name+","+i);
                                                   i++;
                                              });
                                         }
                                     });


                                                  });
                                              }
                                          });
                           }


                         if (id == "add") {

                             var selectedRowId = drugGrid.getSelectedRowId();
                             var cellObj = drugGrid.cellById(selectedRowId,0);
                             var d_id = cellObj.getValue();

                             var selectedRowId = drugGrid.getSelectedRowId();
                             var cellObj = drugGrid.cellById(selectedRowId,1);
                             var d_name = cellObj.getValue();

                             //dhtmlx.message(pt_id + ":" + d_id + ":" + d_name);
                             allergyGrid.clearAll();
                                     var i = 0;
                                     var url = "xml/add_drug_allergy_xml.php?pt_id="+pt_id+"&d_id="+d_id+"&d_name="+d_name;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('messages').each(function() {

                                                   var msg  = $(this).find('message').text();
                                                   dhtmlx.message(msg);
///////////
                                     var i = 0;
                                     var url = "xml/get_drug_allergy_xml.php?pt_id="+pt_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('med').each(function() {

                                                   var al_id   = $(this).find('al_id').text();
                                                   var d_name     = $(this).find('d_name').text();
                                                   allergyGrid.addRow(i,al_id+","+d_name+","+i);
                                                   i++;
                                              });
                                         }
                                     });
///////////
                                              });
                                         }
                                     });
                         }


                         if (id == "userChoose") {
                             var searchTerm = userTools.getValue("userInput");

                             drugGrid.clearAll();
                             var url = "http://api.firstmedisource.com/call.php/searchmeds/" +searchTerm+ "?user_key=654628232eb57960ccad23ec60d1a150";
                             $.ajax({ type: "GET",
                               url: url,
                               dataType: "json",
                               error: function (xhr, status, error) {
                                  alert(error);
                               },
                               success: function (json) {
                                  //alert(json.length);
                                  for ( var i = 0;i < json.length;i++ ) {
                                        var d_id    = json[i].d_id;
                                        var d_name  = json[i].d_name;
                                        var i_name  = json[i].i_name;
                                        drugGrid.addRow(i,d_id+","+d_name+","+i_name+","+i);
                                  }
                               }
                             });
                         }

                      });


                  }


                  if (id == "viewSoap") {

                      var u_id = perm.md_id;

                      subLayout.cells("b").detachObject();
                      subLayout.cells("c").detachObject();
                      subLayout.cells("b").detachToolbar();
                      subLayout.cells("c").detachToolbar();
                      subLayout.cells("b").showHeader();
                      subLayout.cells("c").showHeader();
                      subLayout.cells("b").setText("NOTE SUMMARY");
                      subLayout.cells("c").setText("NOTE DETAIL");
                      //subLayout.cells("c").hideHeader();

                      var userTools = subLayout.cells("b").attachToolbar();
                      userTools.addButton("new",450,"NEW NOTE");

                      var soapGrid = subLayout.cells("b").attachGrid();
                      soapGrid.setHeader("ID,DATE,TIME,SEEN BY,CC,S,O,P");
                      soapGrid.setColumnIds("soap_id,soap_date,soap_time,u_id,cc,s,o,p");
                      soapGrid.setInitWidths("100,*,*,*,*,*,*,*");
                      soapGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
                      soapGrid.init();

                      soapGrid.attachEvent("onRowSelect",function(id,ind){

                           var selectedRowId = soapGrid.getSelectedRowId();
                           var cellObj = soapGrid.cellById(selectedRowId,0);
                           var soap_id = cellObj.getValue();

                           var url = "xml/get_this_soap_note_xml.php?soap_id="+soap_id;
                           $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('note').each(function() {

                                                   var ht    = $(this).find('ht').text();
                                                   var wt    = $(this).find('wt').text();
                                                   var bmi   = $(this).find('bmi').text();
                                                   var bpmm  = $(this).find('bpmm').text();
                                                   var bphg  = $(this).find('bphg').text();
                                                   var temp  = $(this).find('temp').text();
                                                   var pulse = $(this).find('pulse').text();
                                                   var resp  = $(this).find('resp').text();

                                                   soapForm.setItemValue("ht",ht);
                                                   soapForm.setItemValue("wt",wt);
                                                   soapForm.setItemValue("bmi",bmi);
                                                   soapForm.setItemValue("bphg",bphg);
                                                   soapForm.setItemValue("bpmm",bpmm);
                                                   soapForm.setItemValue("temp",temp);
                                                   soapForm.setItemValue("pulse",pulse);
                                                   soapForm.setItemValue("resp",resp);

                                              });
                                         }
                           });

                      });

                      var soapFormData = [
                                     {type: "fieldset", name: "vitalsigns", label: "VS (Vital Signs)", list: [
                                         {type: "hidden", name: "soap_id"},
                                         {type: "input", inputWidth: "200", label: "Height: in", labelAlign: "right", position: "label-right", name:"ht"},
                                         {type: "input", inputWidth: "200", label: "Weight: lb", labelAlign: "right", position: "label-right", name:"wt"},
                                         {type: "input", inputWidth: "200", label: "BMI:", labelAlign: "right", position: "label-right", name:"bmi"},
                                         {type: "input", inputWidth: "200", label: "Blood Pressure: mm", labelAlign: "right", position: "label-right", name:"bpmm"},
                                         {type: "input", inputWidth: "200", label: "Blood Pressure: Hg:", labelAlign: "right", position: "label-right", name:"bphg"},
                                         {type: "input", inputWidth: "200", label: "Temp: F", labelAlign: "right", position: "label-right", name:"temp"},
                                         {type: "input", inputWidth: "200", label: "Pulse: bpm", labelAlign: "right", position: "label-right", name:"pulse"},
                                         {type: "input", inputWidth: "200", label: "Resp Rate: rpm", labelAlign: "right", position: "label-right", name:"resp"}
                                     ]},
                                     {type: "fieldset", name: "subjective", label: "S (Subjective)", list: [
                                         {type: "input", inputWidth: "300", label: "", labelAlign: "right", position: "label-right", name:"s",rows:11}
                                     ]},
                                     {type: "fieldset", name: "assement1", label: "A (Assement) Linked Diagnosis", list: [
                                         {type: "container", inputWidth: "300", inputHeight: "160", name:"ass1_grid"},
                                         {type: "button", name:"unlink", value:"UNLINK DIAGNOSIS FROM THIS NOTE"}
                                     ]},
                                     {type: "button", name:"update", value:"SAVE CHANGES"},
                                     {type: "newcolumn", offset:30},
                                     {type: "fieldset", name: "chiefcomplaint", label: "CC (Chief Complaint)", list: [
                                         {type: "input", inputWidth: "400", label: "", labelAlign: "right", position: "label-right", name:"cc",rows:11}
                                     ]},
                                     {type: "fieldset", name: "objective", label: "O (Objective)", list: [
                                         {type: "input", inputWidth: "400", label: "", labelAlign: "right", position: "label-right", name:"o",rows:11}
                                     ]},
                                     {type: "fieldset", name: "assement2", label: "A (Assement) Un Linked Diagnosis", list: [
                                         {type: "container", inputWidth: "300", inputHeight: "160", name:"ass2_grid"},
                                         {type: "button", name:"link", value:"LINK DIAGNOSIS TO THIS NOTE"}
                                     ]},
                                     {type: "newcolumn", offset:30},
                                     {type: "fieldset", name: "plantext", label: "P (PLAN)", list: [
                                         {type: "input", inputWidth: "300", label: "", labelAlign: "right", position: "label-right", name:"p",rows:11}
                                     ]},
                                     {type: "fieldset", name: "plan2", label: "P (Plan) Linked E-Scripts", list: [
                                         {type: "container", inputWidth: "300", inputHeight: "160", name:"plan1_grid"},
                                         {type: "button", name:"link", value:"UNLINK E-SCRIPT FROM THIS NOTE"}
                                     ]},
                                     {type: "fieldset", name: "plan3", label: "P (Plan) UnLinked E-Scripts", list: [
                                         {type: "container", inputWidth: "300", inputHeight: "160", name:"plan2_grid"},
                                         {type: "button", name:"link", value:"LINK E-SCRIPT TO THIS NOTE"}
                                     ]}
                      ];

                      var soapForm = subLayout.cells("c").attachForm(soapFormData);
                      soapForm.bind(soapGrid);

                      soapForm.attachEvent("onButtonClick",function(id) {

                            var selectedRowId = soapGrid.getSelectedRowId();
                            var cellObj = soapGrid.cellById(selectedRowId,0);

                            var soap_id = cellObj.getValue();
                            var ht      = soapForm.getItemValue("ht");
                            var wt      = soapForm.getItemValue("wt");
                            var bmi     = soapForm.getItemValue("bmi");
                            var bpmm    = soapForm.getItemValue("bpmm");
                            var bphg    = soapForm.getItemValue("bphg");
                            var temp    = soapForm.getItemValue("temp");
                            var pulse   = soapForm.getItemValue("pulse");
                            var resp    = soapForm.getItemValue("resp");
                            var cc      = soapForm.getItemValue("cc");
                            var s       = soapForm.getItemValue("s");
                            var o       = soapForm.getItemValue("o");
                            var p       = soapForm.getItemValue("p");

                            var url = "xml/update_soap_note_xml.php?soap_id="+soap_id+"&ht="+ht+"&wt="+wt+"&bmi="+bmi+"&bpmm="+bpmm+"&bphg="+bphg+"&temp="+temp+"&pulse="+pulse+"&resp="+resp+"&cc="+cc+"&s="+s+"&o="+o+"&p="+p;
                            $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('messages').each(function() {

                                                   var msg = $(this).find('message').text();
                                                   dhtmlx.message(msg);
                                              });
                                         }
                           });

                      });

                      var assesment1Grid = new dhtmlXGridObject(soapForm.getContainer("ass1_grid"));
                      assesment1Grid.setHeader("PAT_ID,LAST NAME,FIRST NAME");
                      assesment1Grid.setColumnIds("id,last_name");
                      assesment1Grid.setInitWidths("100,*");
                      assesment1Grid.setColTypes("ro,ro");
                      assesment1Grid.init();
                      assesment1Grid.loadXML("xml/view_patientbio.php");

                      var assesment2Grid = new dhtmlXGridObject(soapForm.getContainer("ass2_grid"));
                      assesment2Grid.setHeader("DOB,ADDR,CITY,STATE");
                      assesment2Grid.setColumnIds("dob,addr,city,state");
                      assesment2Grid.setInitWidths("100,*,*,*");
                      assesment2Grid.setColTypes("ro,ro,ro,ro");
                      assesment2Grid.init();
                      assesment2Grid.loadXML("xml/view_patientbio.php");

                      var plan1Grid = new dhtmlXGridObject(soapForm.getContainer("plan1_grid"));
                      plan1Grid.setHeader("PAT_ID,LAST NAME,FIRST NAME");
                      plan1Grid.setColumnIds("id,last_name");
                      plan1Grid.setInitWidths("100,*");
                      plan1Grid.setColTypes("ro,ro");
                      plan1Grid.init();
                      plan1Grid.loadXML("xml/view_patientbio.php");

                      var plan2Grid = new dhtmlXGridObject(soapForm.getContainer("plan2_grid"));
                      plan2Grid.setHeader("DOB,ADDR,CITY,STATE");
                      plan2Grid.setColumnIds("dob,addr,city,state");
                      plan2Grid.setInitWidths("100,*,*,*");
                      plan2Grid.setColTypes("ro,ro,ro,ro");
                      plan2Grid.init();
                      plan2Grid.loadXML("xml/view_patientbio.php");



                                     var i = 0;
                                     var url = "xml/get_soap_note_xml.php?pt_id="+pt_id+"&u_id="+u_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('note').each(function() {

                                                   var soap_id    = $(this).find('soap_id').text();
                                                   var soap_date  = $(this).find('soap_date').text();
                                                   var soap_time  = $(this).find('soap_time').text();
                                                   var u_id       = $(this).find('u_id').text();
                                                   var cc         = $(this).find('cc').text();
                                                   var s          = $(this).find('s').text();
                                                   var o          = $(this).find('o').text();
                                                   var p          = $(this).find('p').text();
                                                   soapGrid.addRow(i,soap_id+","+soap_date+","+soap_time+","+u_id+","+cc+","+s+","+o+","+p+","+i);
                                                   i++;
                                              });
                                         }
                                     });

                      userTools.attachEvent("onClick",function(id){


                               if (id == "new") {

                                   //alert(pt_id);
                                   //alert(u_id);
                                     soapGrid.clearAll();

                                     var i = 0;
                                     var url = "xml/create_soap_note_xml.php?pt_id="+pt_id+"&u_id="+u_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             dhtmlx.message("Soap Note Created");
                                             $(xml).find('note').each(function() {

                                                   var soap_id    = $(this).find('soap_id').text();
                                                   var soap_date  = $(this).find('soap_date').text();
                                                   var soap_time  = $(this).find('soap_time').text();
                                                   var u_id       = $(this).find('u_id').text();
                                                   var cc         = $(this).find('cc').text();
                                                   var s          = $(this).find('s').text();
                                                   var o          = $(this).find('o').text();
                                                   var p          = $(this).find('p').text();
                                                   soapGrid.addRow(i,soap_id+","+soap_date+","+soap_time+","+u_id+","+cc+","+s+","+o+","+p+","+i);
                                                   i++;
                                              });
                                         }
                                     });
                               }


                      });
                  }

          });
}


function route_pharmacy(patlat,patlng,phlat,phlng) {


    var markers = [
            {
                "title": 'Patient',
                "lat": patlat,
                "lng": patlng,
                "description": 'Mumbai formerly Bombay, is the capital city of the Indian state of Maharashtra.'
            }
        ,
            {
                "title": 'Pharmacy',
                "lat": phlat,
                "lng": phlng,
                "description": 'Pune is the seventh largest metropolis in India, the second largest in the state of Maharashtra after Mumbai.'
            }
    ];
        var mapOptions = {
            center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        var infoWindow = new google.maps.InfoWindow();
        var lat_lng = new Array();
        var latlngbounds = new google.maps.LatLngBounds();
        for (i = 0; i < markers.length; i++) {
            var data = markers[i]
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            lat_lng.push(myLatlng);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: data.title
            });
            latlngbounds.extend(marker.position);
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent(data.description);
                    infoWindow.open(map, marker);
                });
            })(marker, data);
        }
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);

        //***********ROUTING****************//

        //Intialize the Path Array
        var path = new google.maps.MVCArray();

        //Intialize the Direction Service
        var service = new google.maps.DirectionsService();

        //Set the Path Stroke Color
        var poly = new google.maps.Polyline({ map: map, strokeColor: '#4986E7' });

        //Loop and Draw Path Route between the Points on MAP
        for (var i = 0; i < lat_lng.length; i++) {
            if ((i + 1) < lat_lng.length) {
                var src = lat_lng[i];
                var des = lat_lng[i + 1];
                path.push(src);
                poly.setPath(path);
                service.route({
                    origin: src,
                    destination: des,
                    travelMode: google.maps.DirectionsTravelMode.DRIVING
                }, function (result, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                            path.push(result.routes[0].overview_path[i]);
                        }
                    }
                });
            }
        }

}



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


     function selectPatient() {

                                 subLayout.cells("b").detachObject();
                                 subLayout.cells("c").detachObject();
                                 subLayout.cells("b").detachToolbar();
                                 subLayout.cells("c").detachToolbar();
                                 subLayout.cells("b").showHeader();
                                 subLayout.cells("c").showHeader();

                                 var subTopLayout = subLayout.cells("b").attachLayout("2U");
                                 var subBotLayout = subLayout.cells("c").attachLayout("2U");


                                 subTopLayout.cells("a").setText("PATIENTS");
                                 subTopLayout.cells("b").setText("PRESCRIPTION HISTORY");
                                 subBotLayout.cells("a").setText("PATIENT DETAILS");
                                 subBotLayout.cells("b").setText("DIAGNOSIS HISTORY");

                                 //SELECT PATIENT STATE
                                 var patientsGrid = subTopLayout.cells("a").attachGrid();
                                 patientsGrid.setHeader("PAT_ID,LAST NAME,FIRST NAME,DOB,ADDR,CITY,STATE,LAT,LNG");
                                 patientsGrid.setColumnIds("id,last_name,first_name,dob,addr,city,state,lat,lng");
                                 patientsGrid.setInitWidths("100,*,*,*,*,*,*,*,*,*,*,*");
                                 patientsGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                                 patientsGrid.init();
                                 patientsGrid.loadXML("xml/view_patients.php");

                                 patientsGrid.attachEvent("onRowSelect",function(id,ind) {

                                     var selectedRowId = patientsGrid.getSelectedRowId();
                                     var cellObj = patientsGrid.cellById(selectedRowId,0);
                                     var pt_id = cellObj.getValue();

                                     scriptsGrid.clearAll();

                                     var i = 0;
                                     var url = "pdf/get_patient_escriptsxml.php?pt_id="+pt_id;
                                     $.ajax({ type: "GET",
                                          url: url,
                                          dataType: "xml",
                                          error: function (xhr, status, error) {
                                                alert(error);
                                          },
                                          success: function (xml) {
                                             $(xml).find('script').each(function() {

                                                   var id        = $(this).find('id').text();
                                                   var s_date    = $(this).find('s_date').text();
                                                   var s_time    = $(this).find('s_time').text();
                                                   var med       = $(this).find('med').text();
                                                   var pharmacy  = $(this).find('pharmacy').text();
                                                   var pphone    = $(this).find('pphone').text();
                                                   scriptsGrid.addRow(i,id+","+s_date+","+s_time+","+med+","+pharmacy+","+pphone+","+i);
                                                   i++;
                                              });
                                         }
                                     });
                                });

                                 var scriptsGrid = subTopLayout.cells("b").attachGrid();
                                 scriptsGrid.setHeader("ID,DATE,TIME,MED,PHARMACY,PHARMACY PHONE");
                                 scriptsGrid.setColumnIds("id,s_date,s_time,med,pharmacy,pphone");
                                 scriptsGrid.setInitWidths("100,*,*,*,*,*");
                                 scriptsGrid.setColTypes("ro,ro,ro,ro,ro,ro");
                                 scriptsGrid.init();

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

                                            var selectedRowId = patientsGrid.getSelectedRowId();
                                            var cellObj = patientsGrid.cellById(selectedRowId,7);
                                            patlat = cellObj.getValue();

                                            var selectedRowId = patientsGrid.getSelectedRowId();
                                            var cellObj = patientsGrid.cellById(selectedRowId,8);
                                            patlng = cellObj.getValue();

                                            selectDrug();
                                        }
                                 });
     }


     function selectDrug(){

                 //alert(patlat);
                 //alert(patlng);

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

                 var drugGrid = subTopLayout.cells("a").attachGrid();
                 drugGrid.setHeader("D_ID,DRUG NAME,INGRIEDENT NAME");
                 drugGrid.setColumnIds("d_id,d_name,i_name");
                 drugGrid.setInitWidths("100,*,*");
                 drugGrid.setColTypes("ro,ro,ro");
                 drugGrid.init();

                 var drugDetGrid = subTopLayout.cells("b").attachGrid();
                 drugDetGrid.setHeader("RMD_ID,STRENGTH,FORM,ROUTE");
                 drugDetGrid.setColumnIds("rmd_id,rmd_strength,rdf_name,rr_name");
                 drugDetGrid.setInitWidths("100,*,*,*");
                 drugDetGrid.setColTypes("ro,ro,ro,ro");
                 drugDetGrid.init();

                 drugDetGrid.attachEvent("onRowSelect",function(id,ind){

                     var selectedRowId = drugDetGrid.getSelectedRowId();
                     var cellObj = drugDetGrid.cellById(selectedRowId,1);
                     var med_str = cellObj.getValue();
                     selDrugForm.setItemValue("med_str",med_str);

                     var selectedRowId = drugDetGrid.getSelectedRowId();
                     var cellObj = drugDetGrid.cellById(selectedRowId,2);
                     var med_form = cellObj.getValue();
                     selDrugForm.setItemValue("med_form",med_form);

                 });

                 drugGrid.attachEvent("onRowSelect",function(id,ind){

                     var selectedRowId = drugGrid.getSelectedRowId();
                     var cellObj = drugGrid.cellById(selectedRowId,0);
                     var d_id = cellObj.getValue();
                     patientScript.med_id = d_id;

                     var selectedRowId = drugGrid.getSelectedRowId();
                     var cellObj = drugGrid.cellById(selectedRowId,1);
                     var med = cellObj.getValue();

                     selDrugForm.setItemValue("med",med);
                     selDrugForm.setItemValue("med_str","");
                     selDrugForm.setItemValue("med_form","");

                     //alert(d_id);

                             drugDetGrid.clearAll();
                             var url = "http://api.firstmedisource.com/call.php/meddetails/" +d_id+ "?user_key=654628232eb57960ccad23ec60d1a150";
                             $.ajax({ type: "GET",
                               url: url,
                               dataType: "json",
                               error: function (xhr, status, error) {
                                  alert(error);
                               },
                               success: function (json) {
                                  //alert(json.length);
                                  for ( var i = 0;i < json.length;i++ ) {
                                        var rmd_id       = json[i].rmd_id;
                                        var rmd_strength = json[i].rmd_strength;
                                        var rdf_name     = json[i].rdf_name;
                                        var rr_name      = json[i].rr_name
                                        drugDetGrid.addRow(i,rmd_id+","+rmd_strength+","+rdf_name+","+rr_name+","+i);
                                  }
                               }
                             });

                 });

                 var userTools = subLayout.cells("b").attachToolbar();
                 userTools.addText("text_user",0,"SEARCH MED");
                 userTools.addInput("userInput",100);
                 userTools.addButton("userChoose",350,"SEARCH");

                 userTools.attachEvent("onClick",function(id){

                         if (id == "userChoose") {
                             var searchTerm = userTools.getValue("userInput");

                             drugGrid.clearAll();
                             var url = "http://api.firstmedisource.com/call.php/searchmeds/" +searchTerm+ "?user_key=654628232eb57960ccad23ec60d1a150";
                             $.ajax({ type: "GET",
                               url: url,
                               dataType: "json",
                               error: function (xhr, status, error) {
                                  alert(error);
                               },
                               success: function (json) {
                                  //alert(json.length);
                                  for ( var i = 0;i < json.length;i++ ) {
                                        var d_id    = json[i].d_id;
                                        var d_name  = json[i].d_name;
                                        var i_name  = json[i].i_name;
                                        drugGrid.addRow(i,d_id+","+d_name+","+i_name+","+i);
                                  }
                               }
                             });
                         }
                 });

                 var selDrugFormData = [
                       {type: "fieldset", name: "custFormD", label: "SELECT DRUG", list: [
                               {type: "hidden", name: "d_id"},
                               {type: "input", inputWidth: "200", label: "DRUG", labelAlign: "right", position: "label-right", name:"med"},
                               {type: "input", inputWidth: "200", label: "STRENGTH", labelAlign: "right", position: "label-right", name:"med_str"},
                               {type: "input", inputWidth: "200", label: "DOSAGE FORM", labelAlign: "right", position: "label-right", name:"med_form"},
                               {type: "button", name:"cancel", value:"CANCEL"},
                               {type: "button", name:"submit", value:"SELECT (NEXT)"}
                       ]}
                 ];

                 var selDrugForm = subBotLayout.cells("a").attachForm(selDrugFormData);
                 selDrugForm.attachEvent("onButtonClick",function(id){


                       if (id == "cancel") {
                           selectPatient();
                       }


                       if (id == "submit") {

                           var selectedRowId = drugGrid.getSelectedRowId();
                           var cellObj = drugGrid.cellById(selectedRowId,2);
                           var med_ing = cellObj.getValue();

                           var med = selDrugForm.getItemValue("med") + " (" + med_ing + ") " + selDrugForm.getItemValue("med_str") + " " + selDrugForm.getItemValue("med_form");
                           patientScript.med = med;
                           //alert(med);
                           selectDrugQty();
                       }
               });
     }


     function selectDrugQty(){

                 subLayout.cells("b").detachObject();
                 subLayout.cells("c").detachObject();
                 subLayout.cells("b").detachToolbar();
                 subLayout.cells("c").detachToolbar();
                 subLayout.cells("b").showHeader();
                 subLayout.cells("c").showHeader();

                 subLayout.cells("b").setText("DRUG SIG TABLE");

                 var sigGrid = subLayout.cells("b").attachGrid();
                 sigGrid.setHeader("ID,NAME,EXPANDED");
                 sigGrid.setColumnIds("id,name,expanded");
                 sigGrid.setInitWidths("100,200,*");
                 sigGrid.setColTypes("ro,ro,ro");
                 sigGrid.init();
                 sigGrid.loadXML("xml/view_sig.php");

                 var subBotLayout = subLayout.cells("c").attachLayout("2U");

                 //subTopLayout.cells("a").setText("MEDICATIONS");
                 //subTopLayout.cells("b").setText("STRENGTH - DOSAGE FORM");
                 subBotLayout.cells("a").setText("SIG QTY GENERIC SUBSTIUTION");
                 subBotLayout.cells("b").setText("WARNINGS");

                 var selFormData = [
                       {type: "fieldset", name: "custFormD", label: "DRUG DETAILS", list: [
                               {type: "hidden", name: "id"},
                               {type: "input", inputWidth: "400", label: "SIG", labelAlign: "right", position: "label-right", name:"expanded",rows:4},
                               {type: "input", inputWidth: "400", label: "QTY", labelAlign: "right", position: "label-right", name:"qty"},
                               {type: "input", inputWidth: "400", label: "REFILLS", labelAlign: "right", position: "label-right", name:"refills"},
                               {type: "input", inputWidth: "400", label: "GENERIC OK", labelAlign: "right", position: "label-right", name:"sub_perm"},
                               {type: "button", name:"back", value:"BACK"},
                               {type: "button", name:"cancel", value:"CANCEL"},
                               {type: "button", name:"submit", value:"SELECT (NEXT)"}
                       ]}
                 ];

                 var selForm = subBotLayout.cells("a").attachForm(selFormData);
                 selForm.bind(sigGrid);
                 selForm.attachEvent("onButtonClick",function(id){


                       if (id == "cancel") {
                           selectPatient();
                           //alert("hello");
                       }


                       if (id == "back") {
                           selectDrug();
                           //alert("hello");
                       }


                       if (id == "submit") {

                           var qty      = selForm.getItemValue("qty");
                           var sig      = selForm.getItemValue("expanded");
                           var refills  = selForm.getItemValue("refills");
                           var sub_perm = selForm.getItemValue("sub_perm");

                           patientScript.qty      = qty;
                           patientScript.sig      = sig;
                           patientScript.refills  = refills;
                           patientScript.sub_perm = sub_perm;

                           selectPharmacy();
                       }
               });
     }


     function selectPharmacy(){

                 //route_pharmacy();

                 subLayout.cells("b").detachObject();
                 subLayout.cells("c").detachObject();
                 subLayout.cells("b").detachToolbar();
                 subLayout.cells("c").detachToolbar();
                 subLayout.cells("b").showHeader();
                 subLayout.cells("c").showHeader();

                 subLayout.cells("b").setText("PHARMACY AND PATIENT MAP");
                 var subBotLayout = subLayout.cells("c").attachLayout("2U");

                 //subLayout.cells("b").attachObject("viewMap_canvas");
                 //subLayout.cells("b").attachObject("dvMap");

                 //subTopLayout.cells("b").setText("STRENGTH - DOSAGE FORM");
                 subBotLayout.cells("a").setText("SELECTED PHARMACY");
                 subBotLayout.cells("b").setText("PHARMACIES IN ORDER OF DISTANCE FROM PATIENT");

                 var phaGrid = subBotLayout.cells("b").attachGrid();
                 phaGrid.setHeader("ID,NAME,ADDR,CITY,STATE,GADDR,PHONE,LAT,LNG,DISTANCE");
                 phaGrid.setColumnIds("id,name,addr,city,state,g_addr,phone,lat,lng,dist");
                 phaGrid.setInitWidths("100,*,*,*,*,*,*,*,*,*");
                 phaGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
                 phaGrid.init();

                 phaGrid.attachEvent("onRowSelect",function(id,ind){

                                     //subLayout.cells("b").detachObject();

                                     var selectedRowId = phaGrid.getSelectedRowId();
                                     var cellObj = phaGrid.cellById(selectedRowId,7);
                                     var phlat = cellObj.getValue();

                                     var selectedRowId = phaGrid.getSelectedRowId();
                                     var cellObj = phaGrid.cellById(selectedRowId,8);
                                     var phlng = cellObj.getValue();

                                     route_pharmacy(patlat,patlng,phlat,phlng);
                                     subLayout.cells("b").attachObject("dvMap");

                                     //alert(lat);
                                     //alert(lng);
                 });

                 var i = 0;
                 var url = "xml/view_pharmacy.php?pt_id="+patientScript.pt_id;
                             $.ajax({ type: "GET",
                               url: url,
                               dataType: "xml",
                               error: function (xhr, status, error) {
                                  alert(error);
                               },
                               success: function (xml) {
                                  $(xml).find('drugstore').each(function() {

                                    var id     = $(this).find('id').text();
                                    var name   = $(this).find('name').text();
                                    var addr   = $(this).find('addr').text();
                                    var city   = $(this).find('city').text();
                                    var state  = $(this).find('state').text();
                                    var phone  = $(this).find('phone').text();
                                    var lat    = $(this).find('lat').text();
                                    var lng    = $(this).find('lng').text();
                                    var dist   = $(this).find('dist').text();
                                    var g_addr = $(this).find('g_addr').text();
                                    var index  = $(this).find('index').text();
                                    phaGrid.addRow(i,id+","+name+","+addr+","+city+","+state+","+g_addr+","+phone+","+lat+","+lng+","+dist+","+i);
                                    i++;

                                  });
                               }
                             });


                 var selPharmFormData = [
                       {type: "fieldset", name: "custFormD", label: "SELECT DRUG", list: [
                               {type: "hidden", name: "id"},
                               //{type: "input", inputWidth: "200", label: "LAST NAME", labelAlign: "right", position: "label-right", name:"last_name"},
                               //{type: "input", inputWidth: "200", label: "FIRST NAME", labelAlign: "right", position: "label-right", name:"first_name"},
                               {type: "button", name:"back", value:"BACK"},
                               {type: "button", name:"cancel", value:"CANCEL"},
                               {type: "button", name:"submit", value:"SELECT (NEXT)"}
                       ]}
                 ];

                 var selPharmForm = subBotLayout.cells("a").attachForm(selPharmFormData);
                 selPharmForm.attachEvent("onButtonClick",function(id){


                       if (id == "cancel") {
                           selectPatient();
                           //alert("hello");
                       }


                       if (id == "back") {
                           selectDrugQty();
                           //alert("hello");
                       }


                       if (id == "submit") {

                           var selectedRowId = phaGrid.getSelectedRowId();
                           var cellObj = phaGrid.cellById(selectedRowId,0);
                           var ph_id = cellObj.getValue();

                           patientScript.ph_id = ph_id;
                           reviewScript();
                       }
               });
     }


     function reviewScript(){

                 subLayout.cells("b").detachObject();
                 subLayout.cells("c").detachObject();
                 subLayout.cells("b").detachToolbar();
                 subLayout.cells("c").detachToolbar();
                 subLayout.cells("b").showHeader();
                 subLayout.cells("c").hideHeader();

                 subLayout.cells("b").setText("PRESCRIPTION DETAILS");

                 var previewData = [
                       {type: "fieldset", name: "custFormD", label: "PREVIEW PRESCRIPTION INFORMATION", list: [
                               {type: "hidden", name: "id"},
                               {type: "input", inputWidth: "400", label: "MD", labelAlign: "right", position: "label-right", name:"md"},
                               {type: "input", inputWidth: "400", label: "PATIENT", labelAlign: "right", position: "label-right", name:"patient"},
                               {type: "input", inputWidth: "400", label: "MEDICATION", labelAlign: "right", position: "label-right", name:"medication"},
                               {type: "input", inputWidth: "400", label: "QUANTITY", labelAlign: "right", position: "label-right", name:"quantity"},
                               {type: "input", inputWidth: "400", label: "SIG", labelAlign: "right", position: "label-right", name:"sig"},
                               {type: "input", inputWidth: "400", label: "REFILLS", labelAlign: "right", position: "label-right", name:"refills"},
                               {type: "input", inputWidth: "400", label: "GENERIC OK", labelAlign: "right", position: "label-right", name:"sub_perm"},
                               {type: "input", inputWidth: "400", label: "PHARMACY", labelAlign: "right", position: "label-right", name:"pharmacy"}
                       ]}
                 ];

                 var previewForm = subLayout.cells("b").attachForm(previewData);

                 var md_id    = patientScript.md_id;
                 var pt_id    = patientScript.pt_id;
                 var med      = patientScript.med;
                 var qty      = patientScript.qty;
                 var sig      = patientScript.sig;
                 var refills  = patientScript.refills;
                 var sub_perm = patientScript.sub_perm;
                 var ph_id    = patientScript.ph_id;

                 var url = "xml/preview_scriptxml.php?md_id="+md_id+"&pt_id="+pt_id+"&med="+med+"&qty="+qty+"&sig="+sig+"&refills="+refills+"&sub_perm="+sub_perm+"&ph_id="+ph_id;
                 //var url = "xml/view_pharmacy.php?pt_id="+patientScript.pt_id;
                 $.ajax({ type: "GET",
                               url: url,
                               dataType: "xml",
                               error: function (xhr, status, error) {
                                  alert(error);
                               },
                               success: function (xml) {
                                  $(xml).find('script').each(function() {

                                    var md         = $(this).find('md').text();
                                    var patient    = $(this).find('patient').text();
                                    var medication = $(this).find('medication').text();
                                    var quantity   = $(this).find('quantity').text();
                                    var sig        = $(this).find('sig').text();
                                    var refills    = $(this).find('refills').text();
                                    var sub_perm   = $(this).find('sub_perm').text();
                                    var pharmacy   = $(this).find('pharmacy').text();

                                    previewForm.setItemValue("md",md);
                                    previewForm.setItemValue("patient",patient);
                                    previewForm.setItemValue("medication",medication);
                                    previewForm.setItemValue("quantity",quantity);
                                    previewForm.setItemValue("sig",sig);
                                    previewForm.setItemValue("refills",refills);
                                    previewForm.setItemValue("sub_perm",sub_perm);
                                    previewForm.setItemValue("pharmacy",pharmacy);

                                    //phaGrid.addRow(i,id+","+name+","+addr+","+city+","+state+","+g_addr+","+phone+","+lat+","+lng+","+dist+","+i);

                                  });
                               }
                 });




                 var selPharmFormData = [
                       {type: "fieldset", name: "custFormD", label: "SELECT DRUG", list: [
                               {type: "hidden", name: "id"},
                               //{type: "input", inputWidth: "200", label: "LAST NAME", labelAlign: "right", position: "label-right", name:"last_name"},
                               //{type: "input", inputWidth: "200", label: "FIRST NAME", labelAlign: "right", position: "label-right", name:"first_name"},
                               {type: "button", name:"back", value:"BACK"},
                               {type: "button", name:"cancel", value:"CANCEL"},
                               {type: "button", name:"preview", value:"PREVIEW PRESCRIPTION"},
                               {type: "button", name:"preque", value:"SEND TO SCRIPT QUE"},
                               {type: "button", name:"submit", value:"SEND TO PHARMACY"}
                       ]}
                 ];

                 var selPharmForm = subLayout.cells("c").attachForm(selPharmFormData);
                 selPharmForm.attachEvent("onButtonClick",function(id){

                       if (id == "preview") {

                           var md_id    = patientScript.md_id;
                           var pt_id    = patientScript.pt_id;
                           var med      = patientScript.med;
                           var qty      = patientScript.qty;
                           var sig      = patientScript.sig;
                           var refills  = patientScript.refills;
                           var sub_perm = patientScript.sub_perm;
                           var ph_id    = patientScript.ph_id;

                           var url = "pdf/preview_scriptpdf.php?md_id="+md_id+"&pt_id="+pt_id+"&med="+med+"&qty="+qty+"&sig="+sig+"&refills="+refills+"&sub_perm="+sub_perm+"&ph_id="+ph_id;
                           window.open(url,'_blank');

                       }


                       if (id == "cancel") {
                           selectPatient();
                           //alert("hello");
                       }


                       if (id == "back") {
                           selectPharmacy();
                           //alert("hello");
                       }
                       
                       
                       if (id == "preque") {
                     
                           var md_id    = patientScript.md_id;
                           var pt_id    = patientScript.pt_id;
                           var med      = patientScript.med;
                           var qty      = patientScript.qty;
                           var sig      = patientScript.sig;
                           var refills  = patientScript.refills;
                           var sub_perm = patientScript.sub_perm;
                           var ph_id    = patientScript.ph_id;
                           var med_id   = patientScript.med_id;

                           var url = "pdf/send_scriptque.php?md_id="+md_id+"&pt_id="+pt_id+"&med="+med+"&qty="+qty+"&sig="+sig+"&refills="+refills+"&sub_perm="+sub_perm+"&ph_id="+ph_id+"&med_id="+med_id;

                           $.ajax({ type: "GET",
                               url: url,
                               dataType: "xml",
                               error: function (xhr, status, error) {
                                  alert(error);
                               },
                               success: function (xml) {
                                  $(xml).find('messages').each(function() {

                                    var msg = $(this).find('message').text();
                                    dhtmlx.message(msg);

                                  });
                               }
                           });

                           selectPatient();                          
                       }


                       if (id == "submit") {

                           var md_id    = patientScript.md_id;
                           var pt_id    = patientScript.pt_id;
                           var med      = patientScript.med;
                           var qty      = patientScript.qty;
                           var sig      = patientScript.sig;
                           var refills  = patientScript.refills;
                           var sub_perm = patientScript.sub_perm;
                           var ph_id    = patientScript.ph_id;
                           var med_id   = patientScript.med_id;

                           var url = "pdf/send_scriptpdf.php?md_id="+md_id+"&pt_id="+pt_id+"&med="+med+"&qty="+qty+"&sig="+sig+"&refills="+refills+"&sub_perm="+sub_perm+"&ph_id="+ph_id+"&med_id="+med_id;

                           $.ajax({ type: "GET",
                               url: url,
                               dataType: "xml",
                               error: function (xhr, status, error) {
                                  alert(error);
                               },
                               success: function (xml) {
                                  $(xml).find('messages').each(function() {

                                    var msg = $(this).find('message').text();
                                    dhtmlx.message(msg);

                                  });
                               }
                           });

                           selectPatient();

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

              mainToolbar.attachEvent("onClick",function(id){

                       if (id == "homeBtn") {
                           state_escript();
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
              state_escript();

        });
