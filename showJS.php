<?php
 require('./click2call.php');
 header("Cache-Control: no-cache, must-revalidate"); 
 header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
 header("Content-Type: text/html; charset=utf-8");


 print  "<!DOCTYPE html>\n";
 print  "<html lang=\"ru\">\n";
 print  " <head>\n"; 
 print  "  <script src=\"http://yui.yahooapis.com/3.10.3/build/yui/yui-min.js\"></script>\n";
 print  " </head>\n";
 print  " <body>\n";
 print  "  <div class=\"example yui3-skin-sam\">\n";

 print  "    <div id=\"click2call\">\n";
 print  "      <ul>\n";
 print  "        <li><a href=\"#tab_clients\">Clients</a></li>\n";
 print  "        <li><a href=\"#tab_widgetsByToken\">Widgets by token</a></li>\n";
 print  "        <li><a href=\"#tab_widgetsByURL\">Widgets by URL</a></li>\n";
 print  "        <li><a href=\"#tab_status\">Status</a></li>\n";
 print  "      </ul>\n";
 print  "      <div>\n";
 print  "        <div id=\"tab_clients\">\n";
 print  "          <br />\n";
 print  "          <div id=\"clients\"></div>\n";
 print  "          <br />\n";
 print  "        </div>\n";
 print  "        <div id=\"tab_widgetsByToken\">\n";
 print  "          <br />\n";
 print  "          <div id=\"widgetsByToken\"></div>\n";
 print  "          <br />\n";
 print  "        </div>\n";
 print  "        <div id=\"tab_widgetsByURL\">\n";
 print  "          <br />\n";
 print  "          <div id=\"widgetsByURL\"></div>\n";
 print  "          <br />\n";
 print  "        </div>\n";
 print  "        <div id=\"tab_status\">\n";
 print  "          <br />\n";
 print  "          <div id=\"status\"></div>\n";
 print  "          <br />\n";
 print  "        </div>\n";
 print  "      </div>\n";
 print  "    </div>\n";

 print  "    <script type=\"text/javascript\">\n";
 print  "      YUI().use('tabview', 'datatable', function(Y) {\n";
 print  "         var tabview = new Y.TabView({srcNode:'#click2call'});\n";
 print  "         tabview.render();\n";

 print  "         var status = new Y.DataTable({\n";
 print  "           columns: [\"Module\", \"Version\", \"Error\"],\n";

 $rwd = new WDb();
 print  "        data   : [ \n";
 print  "{ Module: \"PHP\", Version: \"".PHP_VERSION."\", Error: \"".$rwd->errorMessage(WDb::RWD_OK)." (". WDb::RWD_OK .")\" },\n";
 print  "{ Module: \"PDO\", Version: \"" . phpversion('PDO') ."\", Error: \"".$rwd->errorMessage(WDb::RWD_OK)." (". WDb::RWD_OK .")\" },\n";
 $res = $rwd->Connect();      
 if ($res == WDb::RWD_OK)
 {
   print  "{ Module: \"SQLite\", Version: \"".$rwd->showSQLiteVersion()."\", Error: \"".$rwd->errorMessage($res)." (". $res .")\" },\n";
   print  "{ Module: \"Database schema\", Version: \"".$rwd->showDatabaseVersion()."\", Error: \"".$rwd->errorMessage($res)." (". $res .")\" },\n";
 }
 else
 {
   print  "{ Module: \"SQLite\", Version: \"Unknown\", Error: \"".$rwd->errorMessage($res)." (". $res .")\" },\n";
   print  "{ Module: \"Database schema\", Version: \"Unknown\", Error: \"".$rwd->errorMessage($res)." (". $res .")\" },\n";
 }
 print  "{ Module: \"Database script\", Version: \"".$rwd->showScriptVersion()."\", Error: \"".$rwd->errorMessage(0)." (". 0 .")\" } \n]\n";
 print "      });\n";

 if ($res == WDb::RWD_OK) { $r = $rwd->showClients(); }
 else                     { $r = array();             }
 $s = count($r);

 print "      var clients = new Y.DataTable({\n";
 print "        columns: [\"id\", \"token\", \"login\", \"password\"],\n";
 print "        data   : [\n";
 for ($k = 0; $k < $s-1; $k++)  
 { 
   $c = $r[$k]; 
   print "            { id: \"".$c[0]."\", token: \"".$c[1]."\", login: \"".$c[2]."\" , password: \"secret\" },\n";
 }
 if ($s>0) 
 { 
   $c = $r[$s-1];
   print "            { id: \"".$c[0]."\", token: \"".$c[1]."\", login: \"".$c[2]."\" , password: \"secret\" }\n";
 }
 print "        ]\n";
 print "      });\n";

// Widgets by Token -----------------------------------------------

 if ($res == WDb::RWD_OK) { $r = $rwd->showWidgetsByToken();  }
 else                     { $r = array();                     }
 $s = count($r);

 print "      var widgetsByToken = new Y.DataTable({\n";
 print "        columns: [\"id\", \"client\", \"callee\"],\n";
 print "        data   : [\n";
 for ($k = 0; $k < $s-1; $k++)  
 { 
   $c = $r[$k]; 
   print "            { id: \"".$c[0]."\", client: \"".$c[1]." (id:".$c[3].")\", callee: \"".$c[2]."\" },\n";
 }
 if ($s>0) 
 { 
   $c = $r[$s-1];
   print "            { id: \"".$c[0]."\", client: \"".$c[1]." (id:".$c[3].")\", callee: \"".$c[2]."\" }\n";
 }
 print "        ]\n";
 print "      });\n";

// Widgets by URL -----------------------------------------------

 if ($res == WDb::RWD_OK) { $r = $rwd->showWidgetsByURL();    }
 else                     { $r = array();                     }
 $s = count($r);

 print "      var widgetsByURL = new Y.DataTable({\n";
 print "        columns: [\"id\", \"client\", \"URL\", \"callee\"],\n";
 print "        data   : [\n";
 for ($k = 0; $k < $s-1; $k++)  
 { 
   $c = $r[$k]; 
   print "            { id: \"".$c[0]."\", client: \"".$c[1]." (id:".$c[4].")\", URL: \"".$c[2]."\", callee: \"".$c[3]."\" },\n";
 }
 if ($s>0) 
 { 
   $c = $r[$s-1];
   print "            { id: \"".$c[0]."\", client: \"".$c[1]." (id:".$c[4].")\", URL: \"".$c[2]."\", callee: \"".$c[3]."\" }\n";
 }
 print "        ]\n";
 print "      });\n";


 print "      clients.render(\"#clients\");\n";
 print "      widgetsByToken.render(\"#widgetsByToken\");\n";
 print "      widgetsByURL.render(\"#widgetsByURL\");\n";
 print "      status.render(\"#status\");\n";
 print "     });\n";
 print "    </script>\n";
 print "  </div>\n";
 print " </body>\n";
 print "</html>\n";
?>
