<?php
/**
 * delete.php Тестовый скрипт удаления widget'а.
 *
 * Параметры: token - токен, идентифицирующий widget, URL, идентифицирующий widget. Оба параметра опциональны, но 
 * хотя бы один должен быть задан. Наличие значение для URL означает удаление widget'а, идентифицируемого по URL.
 * Отсуствие - удаление widget'а, идентифицируемого по токену.
 *
 * Например:
 *
 * <code>
 * Адресная строка браузера (метод GET при программном вызове)
 * ----------------------------------------------------------------
 * http://192.168.1.1/click2call/delete.php?token=pupkin
 *
 * Результат
 * ----------------------------------------------------------------
 * Deleting pupkin@: No error
 * 
 * Thank you for your fish. 
 * Bye. 
 * </code>
 *
 * @see WDb::Delete()	
 * @see show.php	
 * 
 * @category КНОПОМ
 * @package click2call
 * @subpackage tests
 */

 require('./click2call.php');
 
 $token = $_GET['token'];
 $URL = $_GET['URL'];
 
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
     $res = $rwd->Delete($URL, WDb::KeyTypeURL);  
     print "Deleting @$URL: " . $rwd->errorMessage($res) . "<br />";
   }
   else
   {
     $res = $rwd->Delete($token, WDb::KeyTypeClientToken);  
     print "Deleting $token@: " . $rwd->errorMessage($res) . "<br />";
   }
 }
 print "<br />Thank you for your fish. <br />Bye.<br />";
?>
