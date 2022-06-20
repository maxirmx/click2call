<?php
/**
 * insert.php Тестовый скрипт добавления widget'а.
 * 
 * Добавляет в систему новый widget и, при необходимости, клиента. 
 * Параметры: token - токен клиента, для которого создается widget. 
 * Если клиент с таким токеном отсуствует, то он будет создан.
 * URL - URL, для которого создается widget. NULL означает, 
 * что требуется создание widget'а, идентифицируемого по токену. Не NULL означает 
 * создание widget'а, идентифицируемого по URL.
 * callee - значение вызываемого номера для widget'а. Номер понимается 
 * в широком смысле - это может быть телефонный номер, SIP identity или любой 
 * другой идентификатор, который может быть использован для маршрутизации вызова.  
 * login - логин для создаваемого клиента. Логин будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner.
 * password - пароль для создаваемого клиента. Пароль будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner.
 *
 * Например:
 *
 * <code>
 * Адресная строка браузера (метод GET при программном вызове)
 * ----------------------------------------------------------------
 * http://192.168.1.1/click2call/insert.php?token=pupkin&callee=12345&login=0004&password=secret
 *
 * Результат
 * ----------------------------------------------------------------
 * Inserting pupkin@ ==> 12345: No error
 *
 * Thank you for your fish. 
 * Bye. 
 * </code>
 *
 * @see WDb::Insert()	
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
 $login = $_GET['login'];
 $secret = $_GET['password'];

 
 $rwd = new WDb();
 $res = $rwd->Connect();      

 if (res != WDb::RWD_OK)
 {
   print "A call to connect has failed: " . $rwd->errorMessage($res) . " (" . $res . ")<br />";
 }
 else
 {
   $res = $rwd->Insert($token, $URL, $callee, $login, $secret);  
   print "Inserting $token@$URL ==> $callee: " . $rwd->errorMessage($res) . "<br />";
 }
 print "<br />Thank you for your fish. <br />Bye.<br />";
?>
