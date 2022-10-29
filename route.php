<?php
/**
 * route.php Интерфейсный скрипт. 
 *
 * Формирует XML документ с информацией о номере, по которому widget должен произвети вызов. 
 * Номер понимается в широком смысле - это может быть телефонный номер, SIP identity или любой другой идентификатор, 
 * который может быть использован для маршрутизации вызова.  
 * Cкрипт предназначен для задания в качестве параметра get_callee_url при настройке flashphoner'а,
 * см. {@link http://docs.flashphoner.com/display/FPN/Configuring документацию на flashphoner}. 
 * 
 * Параметры: token - токен, переданный widget'ом, pageURL URL, переданный widget'ом. Оба параметра опциональны, но 
 * хотя бы один должен быть задан. 
 *
 * Например:
 *
 * <code>
 * Адресная строка браузера (метод GET при программном вызове)
 * ----------------------------------------------------------------
 * http://192.168.1.1/click2call/route.php?token=test
 *
 * Результат
 * ----------------------------------------------------------------
 * <callee account="600"/> 
 * </code>
 *
 * В случае отсуствия widget'a для заданного токена или URL, внутренней ошибки или неправильных параметров 
 * в качестве ответа  формируется XML документ <callee account="600"/> 
 * 
 *
 * @see WDb::Route()	
 *
 * @category КНОПОМ
 * @package click2call
 * @subpackage interface
 */

 require('./click2call.php');
 
 $token = $_GET['token'];
 $URL = $_GET['pageUrl'];
 
 $rwd = new WDb();
 $rwd->Connect();      
 $res = WDb::RWD_ERROR;

 if ($URL !== NULL)               
 { 
   $res = $rwd->Route($URL, WDb::KeyTypeURL);           
 }

 if (is_numeric($res) && $res<0)  
 { 
   $res = $rwd->Route($token, WDb::KeyTypeClientToken); 
 }

 if (is_numeric($res) && $res<0)  { print '<callee account="000"/>'; }
 else                             { print '<callee account="' . $res . '"/>'; }

?>
