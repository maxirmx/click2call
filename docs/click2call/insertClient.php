<?php
/**
 * insertClient.php Тестовый скрипт добавления клиента.
 * 
 * Добавляет в систему новыого клиента. 
 * Параметры: token - токен клиента. login - логин для создаваемого клиента. Логин будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner.
 * password - пароль для создаваемого клиента. Пароль будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner.
 *
 * Например:
 *
 * <code>
 * Адресная строка браузера (метод GET при программном вызове)
 * ----------------------------------------------------------------
 * http://192.168.1.1/click2call/insertClient.php?token=pupkin&login=0004&password=secret
 *
 * Результат
 * ----------------------------------------------------------------
 * Inserting client pupkin 0004:secret: No error
 *
 * Thank you for your fish. 
 * Bye. 
 * </code>
 *
 * @see WDb::insertClient()	
 * @see show.php	
 * 
 * @category КНОПОМ
 * @package click2call
 * @subpackage tests
 */
 require('./click2call.php');
 
 $token = $_GET['token'];
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
  $res = $rwd->insertClient($token, $login, $secret);  
  print "Inserting client $token  $login:$secret: " . $rwd->errorMessage($res) . "<br />";
 }
 print "<br />Thank you for your fish. <br />Bye.<br />";
?>
