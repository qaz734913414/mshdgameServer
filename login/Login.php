<?php

session_start();

include_once '../db/error.php';
include_once ('../db/db2.php');
include_once ('../db/mem.php');

  global $_config;
  $db = new DB2('babyplanID');
   if (isset($_POST["userName"]) 
        && $_POST["userName"] != ''
        && isset($_POST["pwd"])
        && $_POST["pwd"] != '')
   {

       $result = $db->select2('gitUserID','userID',"userName = '".$_POST['userName']."'",false,P_Android);
       if($result)
       {
            $id = $result[0]['userID'] % 10;
            $pwd = $db->select2('userID'.$id,'pwd',"userName = '".$_POST['userName']."'",false,P_Android);
            if(strcmp($pwd[0]["pwd"],$_POST["pwd"]) == 0)
            {
                $userID = $db->select2('userID'.$id,'userID',"userName = '".$_POST['userName']."'",false,P_Android);
                $_SESSION['userID'] = $userID[0]['userID'];
                $_SESSION['userName'] = $_POST['userName'];
                //判断memory cache中是否存在userID，存在删除对应session
                $sessionID = mem::getInstance()->mcGet("mshd-".$_SESSION['userID']);
                if($sessionID !== false)
                {
                    mem::getInstance()->mcDel($sessionID);
                }
                mem::getInstance()->mcSet("mshd-".$_SESSION['userID'],session_id(),36000);//10小时失效
                SendOk2($_config['server']);
            }
       }
       else
       {
           SendError2(E_AUTH,"Login Fail");
       }
    }
    else 
    {
        SendError2(E_AUTH,"Accept LoginInfo Error !!!");
    }
?>
