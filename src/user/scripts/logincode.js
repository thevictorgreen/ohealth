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

