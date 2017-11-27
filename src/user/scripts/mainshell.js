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
