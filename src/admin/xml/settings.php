//DATABASE PARAMETERS
//$con = mysql_connect("DATABASE SERVER ADDRESS HERE!!!!!!!!","root","g0tham",false,MYSQL_CLIENT_SSL);

$con = mysql_connect("localhost","root","g0th@m"); //,false,MYSQL_CLIENT_SSL);
mysql_select_db("OPENHEALTH",$con);
