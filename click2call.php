<?php
/**
 * Click2call. Интерфейс к базе данных услуги КНОПОМ 
 *
 * Reference to external document with database model
 * 
 * @category КНОПОМ
 * @package click2call
 * @subpackage main
 * @version 1.00.03
 * @author  Максим Самсонов <m.samsonov@ieee.org>
 * @copyright  2013 Максим Самсонов, его родственники и знакомые
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 */


/**
 * Class C2Cbase
 *
 */
 abstract class C2CBase
 {
/**
 * @static   
 * Database handler
 */
   protected static $dbh = NULL;

/**
 * Флаг отладочного режима
 */
   protected $dbg = false;

/**
 * Флаг поддержки FOREIGN KEYS
 */
   protected $fk = false;

/**
 * __construct Конструктор  
 *
 * @param bool $dbg  флаг отладочного режима 
 */
   function __construct($dbg)
   {
     $this->dbg = $dbg;
   }   // C2CBase::__construct

/**
 * debugOutput  Вывод отладочного сообщения
 *
 * @param string $s  Отладочное сообщение
 * @return void
 */
   protected function debugOutput($s)
   {
     if ($this->dbg)  { print "$s." . (PHP_SAPI==="cli" ? PHP_EOL: '<br/>'); }  
   }         // C2CBase::debugOutput

/**
 * pdoError  Вывод сообщения об исключении PDO в отладочном режиме
 *
 * @param object $e  {@link PDO::PDOException}
 * @return void
 */
   protected function pdoError($e)
   {
       $this->debugOutput('PDO Exception: ' . $e->getMessage());
   }         // C2CBase::pdoError

/**
 * doSearch() {@link PDO::query()}/{@link PDO::fetch()} wrapper.
 *
 * @param string $s   SQL запрос для поиска
 * @param bool $array Признак поиска одного записи, если false, или всех записей, если true
 * @param bool $assoc Признак поиска возврата нумерованного массива, если false, или ассоциативного , если true
 * @return mixed false в случае ошибки или отсуствия подходящих записей или результат поиска с учетом значения параметра $array
 */
   protected function doSearch($s, $array = false, $assoc = false)
   {
     $this->debugOutput("Executing SQL: $s");
     $q = self::$dbh->query($s);
     $r = $q->fetchAll($assoc ? PDO::FETCH_ASSOC : PDO::FETCH_NUM);
     $q->closeCursor();
     if ($r === false) { return false; }
     else              
     { 
       if ($array === false) { return count($r) == 0 ? false: $r[0][0]; }
       else                  { return $r;    } 
     }
   }  // C2CBase::doSearch

/**
 * doExec() {@link PDO::exec()} wrapper.
 *
 * Необходима для тотального добавления вывода отладочных сообщений в отладочном же режиме
 * @param string $s  SQL запрос для выполнения
 * @return int  количество измененных\удаленных или вставленных записей, как описано в документации на {@link PDO::exec()}
 */
   protected function doExec($s)
   {
     $this->debugOutput("Executing SQL: $s");
     $r = self::$dbh->exec($s);
     return $r;
   }   // C2CBase::doExec
 }

/**
 * Class Widget 
 * @ignore
 *
 */
 abstract class Widget extends C2CBase
 {
   protected $key;
   protected $not_found;

/**
 * factory() Widget factory  
 *
 * @param bool $dbg  флаг отладочного режима 
 * @param string $key ключ widget'а (токен или URL) 
 * @param int $key_type тип ключа - {@link WDb::KeyTypeClientToken} или {@link WDb::KeyTypeURL}, 
 * определяющий какой класс создавать 
 */
   public static function factory($dbg, $key, $key_type)
   {
       if ($key_type == WDb::KeyTypeClientToken) { return new WidgetByToken($dbg, $key); }
       elseif ($key_type == WDb::KeyTypeURL)     { return new WidgetByURL($dbg, $key);   }
       else                                      { return false;                         }
   }   // Widget::factory

/**
 * __construct Конструктор  
 *
 * @param bool $dbg  флаг отладочного режима 
 * @param string $key ключ widget'а (токен или URL) 
 */
   function __construct($dbg, $key)
   {
     parent::__construct($dbg);
     $this->key = $key;
   }   // Widget::__construct


/**
 * @ignore
 *
 */
   abstract protected function get_search_query();
/**
 * @ignore
 *
 */
   abstract protected function get_search_login_query();
/**
 * @ignore
 *
 */
   abstract protected function get_search_id_query();
/**
 * @ignore
 *
 */
   abstract protected function get_update_query($callee);
/**
 * @ignore
 *
 */
   abstract protected function get_insert_query($callee, $client_id);
/**
 * @ignore
 *
 */
   abstract protected function get_delete_query();

/**
 * @ignore
 *
 */
   public function search()
   { 
     $r = $this->doSearch($this->get_search_query()); 
     if ($r === false) { return $this->not_found; }
     else              { return $r;               }
   }   // Widget::search

/**
 * @ignore
 *
 */
   public function searchLogin()
   { 
     $r = $this->doSearch($this->get_search_login_query(), true); 
     if ($r === false) { return $this->not_found; }
     else              { return $r;               }
   }   // Widget::searchLogin

/**
 * @ignore
 *
 */
   public function delete()
   { 
     $r = $this->doExec($this->get_delete_query()); 
     if ($r == 0) { return $this->not_found; }       
     else         { return WDb::RWD_OK;         }
   }   // Widget::delete

/**
 * @ignore
 *
 */
   public function update($callee)
   { 
     $r = $this->doExec($this->get_update_query($callee)); 
     if ($r == 0) { return $this->not_found; }       
     else         { return WDb::RWD_OK;         }
   }   // Widget::update

/**
 * @ignore
 *
 */
   public function insert($callee, $client_id)
   { 
     $r1 = $this->doSearch($this->get_search_id_query()); 
     if ($r1 !== false)  { return WDb::RWD_E_W_ALREADY_EXISTS; }
     $this->doExec($this->get_insert_query($callee, $client_id));
     $r1 = $this->doSearch($this->get_search_id_query()); 
     if ($r1 === false) { return WDb::RWD_ERROR; }
     return $r1;
   }   // Widget::insert
 }

/**
 * Class WidgetByToken
 * @ignore
 *
 */
 class WidgetByToken extends Widget 
 {
/**
 * __construct Конструктор  
 *
 * @param bool $dbg  флаг отладочного режима 
 * @param string $key токен, идентифицирующий widget 
 */
   function __construct($dbg, $key)
   {
     parent::__construct($dbg, $key);
     $this->not_found = WDb::RWD_E_W_NO_TOKEN; 
   }
   
/**
 * @ignore
 *
 */
   function get_search_query()
   { return "SELECT callee FROM WidgetsByToken, Clients WHERE Clients.token = '$this->key' AND Clients.id = WidgetsByToken.client_id"; }
   
/**
 * @ignore
 *
 */
   function get_search_login_query()
   { return "SELECT login, secret FROM WidgetsByToken, Clients WHERE Clients.token = '$this->key' AND Clients.id = WidgetsByToken.client_id"; }

/**
 * @ignore
 *
 */
   function get_search_id_query()
   { return "SELECT WidgetsByToken.id FROM WidgetsByToken, Clients WHERE Clients.token = '$this->key' AND Clients.id = WidgetsByToken.client_id"; }

/**
 * @ignore
 *
 */
   function get_delete_query()
   { return "DELETE FROM WidgetsByToken WHERE client_id IN (SELECT id FROM Clients WHERE token = '$this->key')"; }

/**
 * @ignore
 *
 */
   function get_update_query($callee)
   { return "UPDATE WidgetsByToken SET callee = '$callee' WHERE client_id IN (SELECT id FROM Clients WHERE token = '$this->key')"; }

/**
 * @ignore
 *
 */
   function get_insert_query($callee, $client_id)  
   { return "INSERT INTO WidgetsByToken (callee, client_id) VALUES ('$callee', $client_id)"; }

 }

/**
 * Class WidgetByURL
 * @ignore
 *
 */
 class WidgetByURL extends Widget 
 {
/**
 * __construct Конструктор  
 *
 * @param bool $dbg  флаг отладочного режима 
 * @param string $key URL, идентифицирующий widget 
 */
   function __construct($dbg, $key)
   {
     parent::__construct($dbg, $key);
     $this->not_found = WDb::RWD_E_W_NO_URL; 
   }
/**
 * @ignore
 *
 */
   function get_search_query()  { return "SELECT callee FROM WidgetsByURL WHERE URL = '$this->key'"; }
/**
 * @ignore
 *
 */
   function get_search_login_query()  
   { 
     return "SELECT login, secret FROM WidgetsByURL, Clients WHERE URL = '$this->key' AND Clients.id = WidgetsByURL.client_id";
   }
/**
 * @ignore
 *
 */
   function get_search_id_query()  { return "SELECT id FROM WidgetsByURL WHERE URL = '$this->key'"; }
/**
 * @ignore
 *
 */
   function get_delete_query()  { return "DELETE FROM WidgetsByURL WHERE URL = '$this->key'";        }
/**
 * @ignore
 *
 */
   function get_update_query($callee)  { return "UPDATE WidgetsByURL SET callee = '$callee' WHERE URL = '$this->key'"; }
/**
 * @ignore
 *
 */
   function get_insert_query($callee, $client_id)  
   { return "INSERT INTO WidgetsByURL (callee, URL, client_id) VALUES ('$callee', '$this->key', $client_id)"; }
 }


/**
 * Class WDb  Интерфейс к базе данных услуги КНОПОМ
 *
 * Все операции с базой данный должны производиться с использованием данного класса. 
 * Примеры использования можно посмотреть в тестовых скриптах:
 * @see show.php, insert.php, update.php, delete.php, insertClient.php, updateClient.php, deleteClient.php	
 * @todo после обновления php не работают качкадные удаления и constraints. Все дописано "руками", что явно не оптимально.
 *  
 */
 class WDb extends C2CBase
 {
/**
 * Тип ключа для идентификации widget'а - токен клиента   
 */
   const KeyTypeClientToken = 0;
/**
 * Тип ключа для идентификации widget'а - URL   
 */
   const KeyTypeURL = 1;
/**
 * Нет ошибки   
 */
   const RWD_OK = 0;
/**
 * Код ошибки: Такой widget уже есть в системе    
 */
   const RWD_E_W_ALREADY_EXISTS = -1;
/**
 * Код ошибки: Неправильно задан токен    
 */
   const RWD_E_INVALID_TOKEN    = -2;
/**
 * Код ошибки: Неправильно задан вызываемый номер    
 */
   const RWD_E_INVALID_CALLEE   = -3;
/**
 * Код ошибки: Неправильно задан тип ключа    
 */
   const RWD_E_INVALID_KEY_TYPE = -5;
/**
 * Код ошибки: не задан токен    
 */
   const RWD_E_NO_TOKEN         = -6;
/**
 * Код ошибки: Не задан URL    
 */
   const RWD_E_W_NO_URL         = -7;
/**
 * Код ошибки: Неправильно задан логин    
 */
   const RWD_E_INVALID_LOGIN    = -8;
/**
 * Код ошибки: Неправильно задан пароль    
 */
   const RWD_E_INVALID_SECRET   = -9;
/**
 * Код ошибки: Такой клиент уже есть в системе    
 */
   const RWD_E_C_ALREADY_EXISTS = -10;
/**
 * Код ошибки: Не найден клиент с таким токеном    
 */
   const RWD_E_W_NO_TOKEN       = -11;
/**
 * Код ошибки: Ошибка формата ответа сервера
 *
 * Используется только внутри клиентских приложений. Здесь приведено для контроля целостности кодов ошибок.    
 */
   const RWD_E_INVALID_RESPONSE = -125;       // Used by JavaScript only
/**
 * Код ошибки: Неизвестная команда    
 */
   const RWD_E_INVALID_COMMAND  = -126;
/**
 * Код ошибки: Неспецифицированная ошибка    
 */
   const RWD_ERROR = -127;

/**
 * __construct Конструктор  
 *
 * @param bool $dbg  флаг отладочного режима для инициализации экземпляра класса
 */
   function __construct($dbg = false)
   {
     $this->dbg = $dbg;
   }   // WDb::__construct

/**
 * errorMessage Текстовое сообщение, соотвествующее коду ошибки  
 *
 * @param int $c  код ошибки
 * @return string текстовое сообщение, соответствующее коду ошибки
 */
   public function errorMessage($c)
   { 
     $msg = array (
                     WDb::RWD_ERROR              => 'Unspecified error',
                     WDb::RWD_E_INVALID_COMMAND  => 'Invalid command',
                     WDb::RWD_E_INVALID_RESPONSE => 'Invalid server response',
                     WDb::RWD_E_W_NO_TOKEN       => 'Widget for specified token was not found',
                     WDb::RWD_E_C_ALREADY_EXISTS => 'Client already exists',
                     WDb::RWD_E_INVALID_SECRET   => 'Invalid password',
                     WDb::RWD_E_INVALID_LOGIN    => 'Invalid login',
                     WDb::RWD_E_W_NO_URL         => 'Widget for specified URL was not found',
                     WDb::RWD_E_NO_TOKEN         => 'Client token was not found',
                     WDb::RWD_E_INVALID_KEY_TYPE => 'Invalid key type',
                     WDb::RWD_E_INVALID_CALLEE   => 'Invalid callee',
                     WDb::RWD_E_INVALID_TOKEN    => 'Invalid token',
                     WDb::RWD_E_W_ALREADY_EXISTS => 'Widget already exists',
                     WDb::RWD_OK                 => 'No error'
                   );
     return $msg[$c]; 
   }         // WDb::errorMessage

/**
 * queryTable  проверка наличия таблицы в схеме БД
 *
 * Функция не обрабатывает исключения. Обработчик должен быть реализован в вызывающем коде.
 *
 * @param string $s  имя таблицы
 * @return bool true, если таблица есть в схеме БД, false в противном случае
 */
   private function queryTable($s)
   { 
       $r = $this->doSearch("SELECT name FROM sqlite_master WHERE type = 'table' AND name = '$s'");
       if ($r === false) { $this->debugOutput("Query table '$s': does not exist");      }
       else              { $this->debugOutput("Query table '$s': exists");  $r = true;  }
       return $r;
   }         // WDb::queryTable

/**
 * queryAllTables проверка наличия всех таблиц в базе данных 
 *
 * Используется только для отладки. 
 * Функция не обрабатывает исключения. Обработчик должен быть реализован в вызывающем коде.
 *  
 * @return void
 */
   private function queryAllTables()
   {
     $this->queryTable("Version");
     $this->queryTable("Clients");
     $this->queryTable("WidgetsByToken");
     $this->queryTable("WidgetsByURL");
   }         // WDb::queryAllTables

/**
 * Connect Создание соединения с базой данных.
 *
 * Функция создает соединение с базой данных. 
 *
 * Если база отсуствует и в ini-файле указана необходимость создания базы (секция [database], параметр [create] установлен в true),
 * база создается или при необходимости происходит обновление схемы базы данных до последней версии.
 *
 * Расположение файла базы данных задается ini-файлом: секция [database], параметр [path]. В случае отсуствия ini-файла
 * или указанного параметра значение по умолчанию  - '/usr/local/click2call/db'. 
 *
 * @return int код ошибки, {@link WDb::RWD_OK} означает остуствие ошибки.
 */
   public function Connect()
   {
     try 
     {
        if (self::$dbh == NULL)
        {
          $ini = parse_ini_file("click2call.ini", true);
          if ($ini && $ini["database"]["path"] != NULL)  { $db =  'sqlite:' . $ini["database"]["path"]; }
          else                                           { $db =  'sqlite:/usr/local/click2call/db';    }

          $this->debugOutput("Opening database file at: '$db'");
          self::$dbh = new PDO($db);
          $this->doExec ("PRAGMA foreign_keys = ON");
          $this->fk = ($this->doSearch("PRAGMA foreign_keys") == 1);

          if ($ini && $ini["database"]["create"]) 
          { 
            $this->queryAllTables();

            if ($this->doExec( <<< __SQL__
                  CREATE TABLE IF NOT EXISTS Clients  (
                       id     INTEGER PRIMARY KEY,                             
                       token  CHAR[256] NOT NULL ON CONFLICT ABORT UNIQUE ON CONFLICT ABORT
                  )
__SQL__
                ) === false) { return WDb::RWD_ERROR; }
            if ($this->doExec( <<< __SQL__
                  CREATE TABLE IF NOT EXISTS WidgetsByToken  (
                       id INTEGER PRIMARY KEY,                             
                       callee CHAR[256] NOT NULL ON CONFLICT ABORT,
                       client_id INTEGER REFERENCES Clients(id) ON DELETE CASCADE UNIQUE ON CONFLICT ABORT
                  )
__SQL__
                )  === false) { return WDb::RWD_ERROR; }
            if ($this->doExec( <<< __SQL__
                  CREATE TABLE IF NOT EXISTS WidgetsByURL  (
                       id INTEGER PRIMARY KEY,                             
                       URL CHAR[256] NOT NULL ON CONFLICT ABORT UNIQUE ON CONFLICT ABORT,
                       callee CHAR[256] NOT NULL ON CONFLICT ABORT,
                       client_id INTEGER REFERENCES Clients(id) ON DELETE CASCADE,
                       UNIQUE (URL, client_id) ON CONFLICT ABORT 
                  )
__SQL__
                )  === false) { return WDb::RWD_ERROR; }

/**
 * Признаком необходимости обновления схемы базы данных от версии 1.00.00 до версии 1.00.01 служит отсуствие таблицы 'Version'.
 * Обновлении выполняются следующие операции 
 *   (1) Добавляются поля 'login' и 'secret' к таблице 'Clients',
 *   (2) Добавляется таблица 'Version'.
 *
 * Значения по умолчанию для полей 'login' и 'secret' задаются как '0004' и соотвествующий пароль для обратной совместимости с
 * версией 1.00.00 (демо), которая как-то неожиданно оказалась в промышленной эксплуатации.
 */
            if ($this->queryTable('Version') === false) 
            { 
               $this->debugOutput("Table Version does not exist");  
               if ($this->doExec( <<< __SQL__
                   ALTER TABLE Clients  
                       ADD COLUMN login  CHAR[16] NOT NULL ON CONFLICT ABORT DEFAULT ('0004')
__SQL__
                  )  === false) { return WDb::RWD_ERROR; }
               if ($this->doExec( <<< __SQL__
                   ALTER TABLE Clients  
                       ADD COLUMN secret CHAR[16] NOT NULL ON CONFLICT ABORT DEFAULT ('MwPfBFaglL')
__SQL__
                  )  === false) { return WDb::RWD_ERROR; }
               if ($this->doExec( <<< __SQL__
                  CREATE TABLE Version  (
                       id INTEGER PRIMARY KEY,                             
                       version CHAR[256] NOT NULL ON CONFLICT ABORT UNIQUE ON CONFLICT ABORT
                     )
__SQL__
                  )  === false) { return WDb::RWD_ERROR; }

                 if ($this->doExec("INSERT INTO Version (version) VALUES ('1.00.01')") !=1)  { return WDb::RWD_ERROR; }
            }
          }
        }
     }   
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return WDb::RWD_OK;
   }    // WDb::Connect

/**
 * _searchClient() внутренняя функция поиска клиента по токену
 * Предполагается, что токен уникален. 
 * Если это ограничение целостности нарушено будет найден какой-нибудь подходящий клиент. 
 *
 * @param string $token токен для поиска клиента 
 * @return int | bool  идентификатор клиента (поле id таблицы  'Clients') или false в случае ошибки или отсуствия клиента
 * с заданным токеном
 */
   private function _searchClient($token)  
   { 
     return $this->doSearch("SELECT id FROM Clients WHERE token = '$token'"); 
   } 

/**
 * _insertClient() внутренняя функция создания клиента.
 *
 * @param string $token  
 * @param string $login  
 * @param string $secret
 * @return int
 */
   private function _insertClient($token, $login, $secret)  
   { 
     $r = $this->doExec("INSERT INTO Clients (token, login, secret) VALUES ('$token', '$login', '$secret')"); 
     return $r;
   }

/**
 * Insert() создает widget и, при необходимости, клиента.
 *
 * @param string $i_client_token токен клиента, для которого создается widget. 
 * Если клиент с таким токеном отсуствует, то он будет создан.
 * @param string $i_URL URL, для которого создается widget. NULL означает, 
 * что требуется создание widget'а, идентифицируемого по токену. Не NULL означает 
 * создание widget'а, идентифицируемого по URL.
 * @param string $i_callee значение вызываемого номера для widget'а. Номер понимается 
 * в широком смысле - это может быть телефонный номер, SIP identity или любой 
 * другой идентификатор, который может быть использован для маршрутизации вызова.  
 * @param string $i_login логин для создаваемого клиента. Логин будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner
 * @param string $i_secret пароль для создаваемого клиента. Пароль будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner
 * @param bool $i_return_ids  значение true означает, что в случае успешного завершения 
 * функция должна возвращать массив (идентификатор widget'а, идентификатор клиента). В качестве 
 * идентифкаторов используются значения PRIMARY KEY соотвествующих таблиц базы данных, 
 * не имеющие прикладного смыслаю. Значение false означает, что в случае успешного завершения 
 * функция должна возвращать значение {@link WDb::RWD_OK}.
 * @return int|array код ошибки, или, в случае успешного завершения, массив (идентификатор widget'а, 
 * идентификатор клиента) либо {@link WDb::RWD_OK} в зависимости от значения параметра $i_return_ids.
 *
 */
   public function Insert($i_client_token, $i_URL, $i_callee, $i_login = NULL, $i_secret = NULL, $i_return_ids = false)
   {
     try 
     {
       if ($i_client_token === NULL) { return WDb:: RWD_E_INVALID_TOKEN;  }
       if ($i_callee === NULL)       { return WDb:: RWD_E_INVALID_CALLEE; }

       $client_id = $this->_searchClient($i_client_token);
       if ($client_id === false) 
       {
         if ($i_login === NULL)        { return WDb:: RWD_E_INVALID_LOGIN;  }
         if ($i_secret === NULL)       { return WDb:: RWD_E_INVALID_SECRET; }
         $this->_insertClient($i_client_token, $i_login, $i_secret); 
         $client_id = $this->_searchClient($i_client_token);
       }

       if ($client_id === false) { return WDb::RWD_ERROR; }

       if ($i_URL === NULL)  { $w = Widget::factory($this->dbg, $i_client_token, WDb::KeyTypeClientToken); }
       else                  { $w = Widget::factory($this->dbg, $i_URL, WDb::KeyTypeURL);                  }
       
       $res = $w->insert($i_callee, $client_id);       
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     if ($res<0)             return $res;
     else if ($i_return_ids) return array ($res, $client_id);
     else                    return WDb::RWD_OK;
   }    // WDb::Insert

/**
 * Update() обновляет значение вызываемого номера для widget'а. 
 *
 * @param string $u_key ключ для поиска widget'а - токен или URL в зависимости от значения $d_key_type
 * @param int $u_key_type тип ключа. Допустимые значения - {@link WDb::KeyTypeClientToken}, {@link WDb::KeyTypeClientToken}
 * @param string $u_callee новое значение вызываемого номера для widget'а. Номер понимается в широком смысле - 
 * это может быть телефонный номер, SIP identity или любой другой идентификатор, который может быть использован 
 * для маршрутизации вызова.  
 * @return int код ошибки, {@link WDb::RWD_OK} означает остуствие ошибки.
 */
   public function Update($u_key, $u_key_type, $u_callee)
   {
     try 
     {
       if ($u_callee === NULL)       { return WDb:: RWD_E_INVALID_CALLEE; }
       if (($w = Widget::factory($this->dbg, $u_key, $u_key_type)) === false) { return WDb::RWD_INVALIFD_KEY_TYPE; }
       $res = $w->update($u_callee);
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return $res;
   }    // WDb::Update

/**
 * Delete() удаляет widget. 
 *
 * Удаление widget'а не означает удаления клиента, с которым widget связан.
 *
 * @param string $d_key ключ для поиска widget'а - токен или URL в зависимости от значения $d_key_type
 * @param int $d_key_type тип ключа. Допустимые значения - {@link WDb::KeyTypeClientToken}, {@link WDb::KeyTypeClientToken}
 * @return int код ошибки, {@link WDb::RWD_OK} означает остуствие ошибки.
 */
   public function Delete($d_key, $d_key_type)
   {
     try 
     {
       if (($w = Widget::factory($this->dbg, $d_key, $d_key_type)) === false) { return WDb::RWD_INVALIFD_KEY_TYPE; }
       $res = $w->delete();
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return $res;
   }    // WDb::Delete

/**
 * Route() возвращает номер, который должен быть вызван widget'ом. 
 *
 * Номер понимается в широком смысле - это может быть телефонный номер, SIP identity или любой другой идентификатор, 
 * который может быть использован для маршрутизации вызова.  
 *
 * @param string $r_key ключ для поиска widget'а - токен или URL в зависимости от значения $r_key_type
 * @param int $r_key_type тип ключа. Допустимые значения - {@link WDb::KeyTypeClientToken}, {@link WDb::KeyTypeClientToken}
 * @return string|int номер или код ошибки
 * @todo Схема базы данных допускает размещение нескольких widget'ов на одной странице. В то же время текущая реализация клиента 
 * в виде отдельного окна браузера делает эту возможность практически неосуществимой. Поэтому методы {@link WDb::Login()} и 
 * {@link WDb::Route()} реализованы в предположении, что по одному URL может быть не более одного widget'а. При появлении другой 
 * реализации клиента может быть необходима доработка.
 */
   public function Route($r_key, $r_key_type)
   {
     try 
     {
       if (($w = Widget::factory($this->dbg, $r_key, $r_key_type)) === false) { return WDb::RWD_INVALIFD_KEY_TYPE; }
       $res = $w->search();
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return $res;
   }    // WDb::Route

/**
 * Login() возвращает имя пользователя и пароль, используемые widget'ом для регистрации на Asterisk через Flashphoner. 
 *
 * @param string $l_key ключ для поиска widget'а - токен или URL в зависимости от значения $l_key_type
 * @param int $l_key_type тип ключа. Допустимые значения - {@link WDb::KeyTypeClientToken}, {@link WDb::KeyTypeClientToken}
 * @return array|int нумерованный массив (логин, пароль) или код ошибки
 * @todo Схема базы данных допускает размещение нескольких widget'ов на одной странице. В то же время текущая реализация клиента 
 * в виде отдельного окна браузера делает эту возможность практически неосуществимой. Поэтому методы {@link WDb::Login()} и 
 * {@link WDb::Route()} реализованы в предположении, что по одному URL может быть не более одного widget'а. При появлении другой 
 * реализации клиента может быть необходима доработка.
 */
   public function Login($l_key, $l_key_type)
   {
     try 
     {
       if (($w = Widget::factory($this->dbg, $l_key, $l_key_type)) === false) { return WDb::RWD_INVALIFD_KEY_TYPE; }
       $res = $w->searchLogin();
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return $res;
   }    // WDb::Login

/**
 * insertClient() создает нового клиента. 
 *
 * @param string $ic_token токен создаваемого клиента
 * @param string $ic_login логина создаваемого клиента. Логин будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner
 * @param string $ic_secret пароль создаваемого клиента. Пароль будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner
 * @param bool $ic_return_id  значение true означает, что в случае успешного завершения 
 * функция должна возвращать идентификатор клиента. В качестве идентифкатора используется 
 * значение PRIMARY KEY соотвествующей таблицы базы данных, не имеющее прикладного смыслаю. 
 * Значение false означает, что в случае успешного завершения функция должна возвращать 
 * значение {@link WDb::RWD_OK}.
 * @return int код ошибки, или, в случае успешного завершения, идентификатор созданного клиента
 * либо {@link WDb::RWD_OK} в зависимости от значения параметра $iс_return_id.
 *
 */
   public function insertClient($ic_token, $ic_login, $ic_secret, $ic_return_id = false)
   {
     try 
     {
       if ($ic_token === NULL)        { return WDb:: RWD_E_INVALID_TOKEN;  }
       if ($ic_login === NULL)        { return WDb:: RWD_E_INVALID_LOGIN;  }
       if ($ic_secret === NULL)       { return WDb:: RWD_E_INVALID_SECRET; }

       $res = $this->_insertClient($ic_token, $ic_login, $ic_secret); 
       if ($res==1 && $ic_return_id) $client_id = $this->_searchClient($ic_token);

     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return ($res==1) ? ($ic_return_id ? $client_id : WDb::RWD_OK) : WDb::RWD_E_C_ALREADY_EXISTS; 
   }

/**
 * updateClient() изменяет параметры клиента. 
 *
 * @param string $uc_token токен изменяемого клиента
 * @param string $uc_new_token новое значение токена, NULL, если токен не изменяется
 * @param string $uc_login новое значение логина, NULL, если логин не изменяется. Логин будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner
 * @param string $uc_secret новое значение пароля, NULL, если пароль не изменяется. Пароль будет использоваться 
 * всеми widget'ами создаваемого клиента при регистрации на Asterisk через Flashphoner
 * @return int код ошибки, {@link WDb::RWD_OK} означает остуствие ошибки.
 */
   public function updateClient($uc_token, $uc_new_token, $uc_login, $uc_secret)
   {
     try 
     {
       if ($uc_token === NULL)         { return WDb:: RWD_E_INVALID_TOKEN;  }
       $client_id = $this->_searchClient($uc_token);
       if ($client_id === false)       { return WDb::RWD_E_NO_TOKEN; }

       $res = ($uc_new_token !== NULL) ? $this->doExec("UPDATE Clients SET token = '$uc_new_token' WHERE id = '$client_id'") : 1;
       $res += ($uc_login !== NULL) ? $this->doExec("UPDATE Clients SET login = '$uc_login' WHERE id = '$client_id'") : 1;
       $res += ($uc_secret !== NULL) ? $this->doExec("UPDATE Clients SET secret = '$uc_secret' WHERE id = '$client_id'") : 1;
       
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return ($res==3) ? WDb::RWD_OK: WDb::RWD_ERROR; 
   }

/**
 * deleteClient() удаляет клиента и все связанные с ним widget'ы. 
 *
 * @param string $dc_token токен удаляемого клиента
 * @return int код ошибки, {@link WDb::RWD_OK} означает остуствие ошибки.
 */
   public function deleteClient($dc_token)
   {
     try 
     {
       if ($dc_token === NULL)        { return WDb:: RWD_E_INVALID_TOKEN;  }
       if (!$this->fk)
       { 
         $res = $this->doExec("DELETE FROM WidgetsByToken WHERE client_id IN (SELECT id FROM Clients WHERE token = '$dc_token')");
         $res = $this->doExec("DELETE FROM WidgetsByURL WHERE client_id IN (SELECT id FROM Clients WHERE token = '$dc_token')");
       }
       $res = $this->doExec("DELETE FROM Clients WHERE token = '$dc_token'");
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return ($res==1) ? WDb::RWD_OK: WDb::RWD_E_NO_TOKEN; 
   }

/**
 * showDatabaseVersion() возвращает версию схемы базы данных. 
 *
 * Версия берется из таблицы Version. Если таблица Version отсуствует в схеме, подразумевается версия 1.00.00.
 *
 * @return string|int версия схемы базы данных или код ошибки
 */
   public function showDatabaseVersion()
   {
     try 
     {
         $r = $this->doSearch("SELECT version FROM Version ORDER BY id DESC LIMIT 1"); 
         if ($r === false) { return "1.00.00"; }
         else              { return $r;        }
     }         
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
   }    // WDb::showDatabaseVersion


/**
 * showSQLiteVersion() возвращает версию клиентской части SQLite. 
 *
 * @return string|int версия клиентской части PDO или код ошибки
 */
   public function showSQLiteVersion()
   {
     try 
     {
         return self::$dbh->getAttribute(PDO::ATTR_CLIENT_VERSION);
     }         
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
   }

/**
 * showScriptVersion() возвращает версию скрипта для взаимодействия с базой данных. 
 *
 * @return string версия скрипта
 */
   public function showScriptVersion()
   {
     return "1.00.03"; 
   }    // WDb::showScriptVersion

/**
 * showClients() возвращает массив с информацией о клиентах. 
 *
 * Информация ("запись") о клиенте включает идентификатор (PRIMARY KEY базы данных, прикладного смысла не несет),
 * токен, логин и пароль, используемые widget'ом для регистрации на Asterisk через Flashphoner.
 *
 * @param bool $assoc  если true, то каждая запись о клиенте представляется ассоциативным массивом, 
 * в противном случае - нумерованным массивом. Поля в записе имеют следующие ключи или индексы:
 * идентификатор - "id"/0, токен - "token"/1, логин - "login"/2, пароль - "secret"/3. 
 * @return array|int массив записей о клиентах или код ошибки
 */
   public function showClients($assoc = false)
   {
     try 
     {
       $r = $this->doSearch("SELECT id, token, login, secret FROM Clients ORDER BY id ASC", true, $assoc);
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return $r;
   }    // WDb::showClients

/**
 * showWidgetsByToken() возвращает массив с информацией о widget'ах, идентифицируемых токенами. 
 *
 * Информация ("запись") о widget'e включает идентификатор (PRIMARY KEY базы данных, прикладного смысла не несет),
 * токен, вызывемый номер и идентификатор клиента (FOREIGN KEY к таблице клиентов).
 *
 * @param bool $assoc  если true, то каждая запись о клиенте представляется ассоциативным массивом, 
 * в противном случае - нумерованным массивом. Поля в записе имеют следующие ключи или индексы:
 * идентификатор - "id"/0, токен - "token"/1, вызываемый номер - "callee"/2, идентификатор клиента - "client_id"/3. 
 * @return array|int массив записей о widget'ах или код ошибки
 */
   public function showWidgetsByToken($assoc = false)
   {
     try 
     {
       $r = $this->doSearch('SELECT WidgetsByToken.id, token, callee, client_id FROM Clients, WidgetsByToken ' . 
                             'WHERE Clients.id = WidgetsByToken.client_id', true, $assoc);
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return $r;
   }    // WDb::showWidgetsByToken

/**
 * showWidgetsByURL() возвращает массив с информацией о widget'ах, идентифицируемых токенами и URL. 
 *
 * Информация ("запись") о widget'e включает идентификатор (PRIMARY KEY базы данных, прикладного смысла не несет),
 * токен, URL, вызывемый номер и идентификатор клиента (FOREIGN KEY к таблице клиентов).
 *
 * @param bool $assoc  если true, то каждая запись о клиенте представляется ассоциативным массивом, 
 * в противном случае - нумерованным массивом. Поля в записе имеют следующие ключи или индексы:
 * идентификатор - "id"/0, токен - "token"/1, URL - "URL"/2, вызываемый номер - "callee"/3, 
 * идентификатор клиента - "client_id"/4. 
 * @return array|int массив записей о widget'ах или код ошибки
 */
   public function showWidgetsByURL($assoc = false)
   {
     try 
     {
       $r = $this->doSearch('SELECT WidgetsByURL.id, token, URL, callee, client_id FROM Clients, WidgetsByURL ' .
                            'WHERE Clients.id = WidgetsByURL.client_id', true, $assoc);
     }
     catch (PDOException $e)
     {
       $this->pdoError($e);
       return WDb::RWD_ERROR;
     }
     return $r;
   }    // WDb::showWidgetsByURL

 }      // Class WDb
?>
