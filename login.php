<?php
/**
 * login.php Интерфейсный скрипт. 
 *
 * Формирует XML документ с информацией о параметрах регистрации widget'а на сервере flashphoner'а 
 * и далее на Asterisk.
 * Cкрипт предназначен для задания в качестве параметра auto_login_url при настройке flashphoner'а,
 * см. {@link http://docs.flashphoner.com/display/FPN/Configuring документацию на flashphoner}. 
 * Параметры: token - токен, переданный widget'ом, pageURL URL, переданный widget'ом. Оба параметра опциональны, но 
 * хотя бы один должен быть задан. 
 *
 * Например:
 *
 * <code>
 * Адресная строка браузера (метод GET при программном вызове)
 * ----------------------------------------------------------------
 * http://192.168.1.1/click2call/login.php?token=test
 *
 * Результат
 * ----------------------------------------------------------------
 * <root registered="false" login="0004" authenticationName="0004" 
 *       password="secret" outboundProxy="81.222.88.130" port="" 
 *       domain="81.222.88.130" visibleName="OmegaClient"/> 
 * </code>
 *
 * В случае отсуствия widget'a для заданного токена или URL, внутренней ошибки или неправильных параметров 
 * в качестве ответа  формируется XML документ 
 * <code>
 * <root registered="false" login="0000" authenticationName="0000" 
 *       password="BadPassword" outboundProxy="81.222.88.130" port="" 
 *       domain="81.222.88.130" visibleName="Not a client"/>  
 * </code>
 * 
 * @see WDb::Login()	
 *
 * @category КНОПОМ
 * @package click2call
 * @subpackage interface
 * 
 * @todo При формировании тегов outboundProxy, port, domain  используются статически заданные значения, "прибитые" к текущей
 * конфигурации сервера. Для поля visibleName используется значение 'OmegaClient', не имеющее особого смысла. 
 * Для поля authenticationName используется то же значение, что и для поля login. По хорошему outboundProxy, port, domain должны 
 * задаваться ini-файлом. Поля для значений visibleName, authenticationName должны использоваться дополнительные поля в таблице Clients
 *
 */

 require('./click2call.php');
 
 $token = $_GET['token'];
 $URL = $_GET['pageUrl'];
 
 $rwd = new WDb();
 $rwd->Connect();      
 $res = WDb::RWD_ERROR;

 if ($URL !== NULL)               { $res = $rwd->Login($URL, WDb::KeyTypeURL);           }

 if (is_numeric($res) && $res<0)  
 { 
   $res = $rwd->Login($token, WDb::KeyTypeClientToken); 
 } 

 if (is_numeric($res) && $res<0)  
 {
     print '<root registered="false" login="0000" authenticationName="0000" password="BadPassword" ' .
           'outboundProxy="81.222.88.130" port="" domain="81.222.88.130" visibleName="Not a client"/>'; 
 }
 else
 {
     print '<root registered="false" login="' . $res[0] . '" authenticationName="' . $res[0] . '" password="' . $res[1] .
           '" outboundProxy="81.222.88.130" port="" domain="81.222.88.130" visibleName="OmegaClient"/>'; 
 }
?>
