<?php

include_once ('../db/db2.php');
include_once ('../db/error.php');
include_once '../db/config.php';

    $db = new DB2('babyplanID');
    if (isset($_POST["userName"]) 
        && $_POST["userName"] != ''
        && isset($_POST["pwd"])
        && $_POST["pwd"] != '')
    {
       $username = $_POST['userName'];
       $result = $db->select2('gitUserID','*',"userName = '".$_POST['userName']."'",false,P_Android);
       if($result)
       {
           SendError2(E_AUTH,"The Same Name");
       }
       else
       {
            $db->start_transaction();
            $newUserName = array();
            $newUserName['userName'] = "'".$_POST['userName']."'";
            $newUserID = $db->insert2('babyplanID.gitUserID',$newUserName,true);
            $newUser = array();
            $id = $newUserID % 10;
            $newUser['userID'] = $newUserID;
            $newUser['pwd'] = "'".$_POST['pwd']."'";
            $newUser['userName'] = "'".$_POST['userName']."'";
            $userID = $db->insert2('babyplanID.userID'.$id,$newUser,true);
            $db->commit_transaction();
            SendOk2();
       } 
    }
    else 
    {
        SendError2(E_ARG,"Accept RegisterInfo Error !!!");
    }
?>
