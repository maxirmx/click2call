<?php
/**
 * show.php Тестовый скрипт вывода информации о widget'ax. 
 *
 * При обращении по URL выводит список widget'ов в формате <token>@<URL> ==> <вызываемый номер>
 * Например:
 *
 * <code>
 * Адресная строка браузера (метод GET при программном вызове)
 * ----------------------------------------------------------------
 * http://192.168.1.1/click2call/show.php
 *
 * Результат
 * ----------------------------------------------------------------
 * Running database schema version 1.00.01
 * Running database script version 1.00.02
 *
 * Widgets by token: 
 * newoffice@ ==> 600
 * oldoffice@ ==> 700
 *
 * Widgets by URL: 
 * newoffice@http://192.168.1.1/fph2/click2call-test-1.html ==> 800
 *
 * Thank you for your fish. 
 * Bye.
 * </code>
 * 
 * @see WDb::showClients()	
 * @see WDb::showWidgetsByToken()	
 * @see WDb::showWidgetsByURL()
 *
 * @category КНОПОМ
 * @package click2call
 * @subpackage tests
 */

 require('./click2call.php');
 header("Content-Type: text/html; charset=utf-8");
 
 $rwd = new WDb(true);
 
 $res = $rwd->Connect();      
 if ($res != WDb::RWD_OK) 
 {
    print "Database connection failed: " . $rwd->errorMessage($res) . "<br />";
 }
 else
 { 
    print "Running database schema version " . $rwd->showDatabaseVersion() . "<br />" ;
    print "Running database script version " . $rwd->showScriptVersion() . "<br />" ;
   
    $r = $rwd->showWidgetsByToken();  
    if ($r == WDb::RWD_ERROR)
    {
      print ("<br />A call to showWidgetsByToken has failed.<br /><br />");
    }
    else
    {
      $size = count($r);
      if ($size > 0) 
      {
        print ("<br />Widgets by token: <br />");
        for ($key = 0; $key < $size; $key++)  {  $c = $r[$key]; print "$c[1]@ ==> $c[2]<br />";  }
        print ("<br />");
      }
      else
      {
        print ("No widgets by token has been found.<br /><br />");
      }
    }

    $r = $rwd->showWidgetsByURL();  
    if ($r == WDb::RWD_ERROR)
    {
      print ("<br />A call to showWidgetsByURL has failed.<br /><br />");
    }
    else
    {
      $size = count($r);
      if ($size > 0) 
      {
        print ("Widgets by URL: <br />");
        for ($key = 0; $key < $size; $key++) { $c = $r[$key]; print "$c[1]@$c[2] ==> $c[3]<br />"; }
        print ("<br />");
      }
      else
      {
        print ("No widgets by URL has been found.<br /><br />");
      }
    }
  }
  
  print "Thank you for your fish. <br />Bye.<br />";
?>
