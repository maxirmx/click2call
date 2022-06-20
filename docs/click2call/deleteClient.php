<?php
/**
 * deleteClient.php Тестовый скрипт удаления клиента.
 *
 * Параметры: token - токен, идентифицирующий клиента.
 *
 * Например:
 *
 * <code>
 * Адресная строка браузера (метод GET при программном вызове)
 * ----------------------------------------------------------------
 * http://192.168.1.1/click2call/deleteClient.php?token=pupkin
 *
 * Результат
 * ----------------------------------------------------------------
 * Deleting client pupkin : No error
 *
 * Thank you for your fish. 
 * Bye. 
 * </code>
 *
 * @see WDb::deleteClient()	
 * @see show.php	
 * 
 * @category КНОПОМ
 * @package click2call
 * @subpackage tests
 */
 require('./click2call.php');
 
 $token = $_GET['token'];
 
 $rwd = new WDb(true); 
 $res = $rwd->Connect();      

 if (res != WDb::RWD_OK)
 {
   print "A call to connect has failed: " . $rwd->errorMessage($res) . " (" . $res . ")<br />";
 }
 else
 {
   $res = $rwd->deleteClient($token);  
   print "Deleting client $token : " . $rwd->errorMessage($res) . "<br />";
 }
 print "<br />Thank you for your fish. <br />Bye.<br />";
?>
