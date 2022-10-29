<?php
/**
 * update.php Тестовый скрипт изменения widget'а. 
 *
 * Изменяет значение вызываемого номера для widget'а. 
 * Параметры: token - токен, идентифицирующий widget, URL, идентифицирующий widget. Оба параметра опциональны, но 
 * хотя бы один должен быть задан. Наличие значение для URL означает изменение widget'а, идентифицируемого по URL.
 * Отсуствие - изменение widget'а, идентифицируемого по токену. callee - новое значение вызываемого номера для widget'а. 
 * Номер понимается в широком смысле - это может быть телефонный номер, SIP identity или любой другой идентификатор, 
 * который может быть использован для маршрутизации вызова.
 *
 * Например:
 *
 * <code>
 * Адресная строка браузера (метод GET при программном вызове)
 * ----------------------------------------------------------------
 * http://192.168.1.1/click2call/update.php?token=pupkin&callee=12345
 *
 * Результат
 * ----------------------------------------------------------------
 * Updating pupkin@ ==> 12345 No error
 *
 * Thank you for your fish. 
 * Bye. 
 * </code>
 * 
 *
 * @see WDb::Update()	
 * @see show.php	
 * 
 * @category КНОПОМ
 * @package click2call
 * @subpackage tests
 */

 require('./click2call.php');
 
 $token = $_GET['token'];
 $URL = $_GET['URL'];
 $callee = $_GET['callee'];
 
 $rwd = new WDb();
 $res = $rwd->Connect();      

 if (res != WDb::RWD_OK)
 {
   print "A call to connect has failed: " . $rwd->errorMessage($res) . " (" . $res . ")<br />";
 }
 else
 {
   if ($URL !== NULL) 
   {
     $res = $rwd->Update($URL, WDb::KeyTypeURL, $callee);  
     print "Updating @$URL ==> $callee: " . $rwd->errorMessage($res) . "<br />";
   }
   else
   {
     $res = $rwd->Update($token, WDb::KeyTypeClientToken, $callee);  
     print "Updating $token@ ==> $callee " . $rwd->errorMessage($res) . "<br />";
   }
 }
 print "<br />Thank you for your fish. <br />Bye.<br />";
?>
