<?php



//link with the php wrapper
include ("keylemon/klAPI.php");

try{
    //instantiate API (replace here with your credentials)
    $kl_api = new KLAPI("vgreen","vANBDx1SjeIEdLGbJo17DJ6yQ4sBDzg3KjfqtWxib6qwEV5xJlmP8W","https://api.keylemon.com/api/infos/");
}
catch (KL_Exception $e){
    print $e->getMessage();
}



?>
