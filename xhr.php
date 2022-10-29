<?php
  require('./click2call.php');
  header("Cache-Control: no-cache, must-revalidate"); 
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
  header('Content-type: application/json');

  $rwd = new WDb();
  $response = array(array("error" => WDb::RWD_E_INVALID_COMMAND, "errMsg" => $rwd->errorMessage(WDb::RWD_E_INVALID_COMMAND)));
  $res = $rwd->Connect();      
  if ($res == WDb::RWD_OK) 
  {
     if (isset($_GET['load']))
     {
       if ($_GET['load'] == 'clients')             { $response = $rwd->showClients(true);        } 
       else if ($_GET['load'] == 'widgetsByToken') { $response = $rwd->showWidgetsByToken(true); }
       else if ($_GET['load'] == 'widgetsByURL')   { $response = $rwd->showWidgetsByURL(true);   }
       if ($response == WDb::RWD_ERROR) 
       {
         $response = array(array("error" => $response, "errMsg" => $rwd->errorMessage($response)));
       }  
     }
     if (isset($_GET['delete']))
     {
       if ($_GET['delete'] == 'client')                               { $res = $rwd->deleteClient($_GET['token']); } 
       else if ($_GET['delete'] == 'widget' && isset($_GET['URL']))   { $res = $rwd->Delete($_GET['URL'], WDb::KeyTypeURL); }
       else if ($_GET['delete'] == 'widget')                          { $res = $rwd->Delete($_GET['token'], WDb::KeyTypeClientToken); }
       else                                                           { $res = WDb::RWD_E_INVALID_COMMAND; }
       $response = array(array("error" => $res, "errMsg" => $rwd->errorMessage($res)));
     }   
     if (isset($_GET['insert']))
     {
       if ($_GET['insert'] == 'client')                               
       { 
          $res = $rwd->insertClient($_GET['token'], $_GET['login'], $_GET['password'], true); 
          if ($res<0) $response = array(array("error" => $res, "errMsg" => $rwd->errorMessage($res)));
          else        $response = array(array("error" => 0, "errMsg" => $rwd->errorMessage(0), "id" => $res));
       } 
       else if ($_GET['insert'] == 'widget' && isset($_GET['URL']))
       { 
         $res = $rwd->Insert($_GET['token'], $_GET['URL'], $_GET['callee'], $_GET['login'], $_GET['password'], true); 
       }
       else if ($_GET['insert'] == 'widget') 
       { 
         $res = $rwd->Insert($_GET['token'], NULL, $_GET['callee'], $_GET['login'], $_GET['password'], true); 
       }
       else                                                           { $res = WDb::RWD_E_INVALID_COMMAND; }
       if ($res<0) $response = array(array("error" => $res, "errMsg" => $rwd->errorMessage($res)));
       else        $response = array(array("error" => 0, "errMsg" => $rwd->errorMessage(0), "id" => $res[0], "client_id" => $res[1]));
     }   
     if (isset($_GET['update']))
     {
       if ($_GET['update'] == 'client')                               { $res = $rwd->updateClient($_GET['token'], $_GET['new_token'], $_GET['login'], $_GET['password']); } 
       else if ($_GET['update'] == 'widget' && isset($_GET['URL']))   { $res = $rwd->Update($_GET['URL'], WDb::KeyTypeURL, $_GET['callee']); }
       else if ($_GET['update'] == 'widget') { $res = $rwd->Update($_GET['token'], WDb::KeyTypeClientToken, $_GET['callee']); }
       else                                                           { $res = WDb::RWD_E_INVALID_COMMAND; }
       $response = array(array("error" => $res, "errMsg" => $rwd->errorMessage($res)));
     }   
  }
  else
  {
    $response = array(array("error" => $res, "errMsg" => $rwd->errorMessage($res)));
  }

  echo json_encode($response);
?>