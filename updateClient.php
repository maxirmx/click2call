<?php
/**
 * updateClient.php Тестовый скрипт изменения клиента. 
 *
 * Параметры: token - токен, идентифицирующий клиента. new_token - новое значение токена (опционально).
 * login - новое значение логина (опционально). Логин будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner
 * password -  новое значение пароля(опционально). Пароль будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner
 *
 * Например:
 *
 * <code>
 * Адресная строка браузера (метод GET при программном вызове)
 * ----------------------------------------------------------------
 * http://192.168.1.1/click2call/updateClient.php?token=pupkin&new_token=tyupkin&login=0005&password=12345
 *
 * Результат
 * ----------------------------------------------------------------
 * Updating client pupkin ==> tyupkin 0005:12345: No error
 *
 * Thank you for your fish. 
 * Bye. 
 * </code>
 * 
 *
 * @see WDb::updateClient()	
 * @see show.php	
 * 
 * @category КНОПОМ
 * @package click2call
 * @subpackage tests
 */
 require('./click2call.php');
 
 $token = $_GET['token'];
 $new_token = $_GET['new_token'];
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
   $res = $rwd->updateClient($token, $new_token, $login, $secret);  
   print "Updating client $token ==> $new_token $login:$secret: " . $rwd->errorMessage($res) . "<br />";
 }
 print "<br />Thank you for your fish. <br />Bye.<br />";
?>
