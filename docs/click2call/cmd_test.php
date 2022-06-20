<?php
 require('./click2call.php');

 $rwd = new WDb(true);
 $res = $rwd->Connect();      print "Connect: " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Insert("maxirmx", "www.rbc.ru", "0600", "0015", "12345");  print "maxirmx@www.rbc.ru ==> 0600 : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Update("www.rbc.ru", WDb::KeyTypeURL, "0601");  print "maxirmx@www.rbc.ru = Update => 0601 : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Delete("www.rbc.ru", WDb::KeyTypeURL);  print "maxirmx@www.rbc.ru = Delete = : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Insert("maxirmx", "www.rbc.ru", "0600", "0015", "12345");  print "maxirmx@www.rbc.ru ==> 0600 : " . $rwd->errorMessage($res) . "\n";
 $res = $rwd->Insert("pupkin", "www.rbc.ru", "0700", "0015", "12345");  print "pupkin@www.rbc.ru ==> 0700 : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Insert("maxirmx", NULL, "0900", "0015", "12345");  print "maxirmx@NULL ==> 0900 : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Delete("maxirmx", WDb::KeyTypeClientToken);  print "maxirmx@NULL = Delete = : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Insert("maxirmx", NULL, "0901", "0015", "12345");  print "maxirmx@NULL ==> 0900 : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Insert("hryupkin", NULL, "0901", NULL, "12345");  print "hryupkin@NULL ==> 0901 : " . $rwd->errorMessage($res) . "\n";

// $res = $rwd->insertClient("hryupkin", "0008", "12345");  print "Inserting client hryupkin 0008:12345 : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Login("hryupkin", WDb::KeyTypeClientToken);
// if (is_numeric($res) && $res<0)  {print "hryupkin -- Login? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "hryupkin == Login ==> : " . $res[0] . ":" . $res[1] . "\n";}


//
// $res = $rwd->insertClient("shurupkin", "0009", "12345");  print "Inserting client shurupkin 0009:12345 : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->Insert("shurupkin", NULL, "1900");  print "shurupkin@NULL ==> 1900 : " . $rwd->errorMessage($res) . "\n";


// $res = $rwd->Login("shurupkin", WDb::KeyTypeClientToken);
// if (is_numeric($res) && $res<0)  {print "shurupkin -- Login? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "shurupkin == Login ==> : " . $res[0] . ":" . $res[1] . "\n";}

// $res = $rwd->updateClient("shurupkin", "shurupkin2", "1009", "54321");  print "Updating client shurupkin shurupkin2 1009:54321 : " . $rwd->errorMessage($res) . "\n";

// $res = $rwd->Login("shurupkin2", WDb::KeyTypeClientToken);
// if (is_numeric($res) && $res<0)  {print "shurupkin2 -- Login? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "shurupkin2 == Login ==> : " . $res[0] . ":" . $res[1] . "\n";}

// $res = $rwd->updateClient("shurupkin", NULL, "2009", "54321");  print "Updating client shurupkin 2009:54321 : " . $rwd->errorMessage($res) . "\n";
// $res = $rwd->updateClient("shurupkin2", NULL, "2009", NULL);  print "Updating client shurupkin2 2009:54321 : " . $rwd->errorMessage($res) . "\n";

// $res = $rwd->Login("pupkin", WDb::KeyTypeClientToken);
// if (is_numeric($res) && $res<0)  {print "shurupkin2 -- Login? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "shurupkin2 == Login ==> : " . $res[0] . ":" . $res[1] . "\n";}


 $res = $rwd->Delete("pupkin", WDb::KeyTypeClientToken);  print "pupkin@NULL = Delete = : " . $rwd->errorMessage($res) . "\n";
 $res = $rwd->Insert("pupkin", NULL, "0700", "0015", "12345");  print "pupkin@NULL ==> 0700 : " . $rwd->errorMessage($res) . "\n";
 $res = $rwd->deleteClient("pupkin");  print "Deleting client pupkin : " . $rwd->errorMessage($res) . "\n";
 $res = $rwd->Insert("pupkin", NULL, "0700", "10015", "+12345");  print "pupkin@NULL ==> 0700 : " . $rwd->errorMessage($res) . "\n";

// $res = $rwd->deleteClient("<qwerty>");  print "Deleting client <qwerty> : " . $rwd->errorMessage($res) . "\n";



// $res = $rwd->Login("www.rbc.ru", WDb::KeyTypeURL);
// if (is_numeric($res) && $res<0)  {print "www.rbc.ru -- Login? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "www.rbc.ru == Login ==> : " . $res[0] . ":" . $res[1] . "\n";}
 

// $res = $rwd->Login("maxirmx", WDb::KeyTypeClientToken);
// if (is_numeric($res) && $res<0)  {print "maxirmx -- Login? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "maxirmx == Login ==> : " . $res[0] . ":" . $res[1] . "\n";}

// $res = $rwd->Login("www.samsonov.net", WDb::KeyTypeURL);
// if (is_numeric($res) && $res<0)  {print "www.samsonov.net -- Login? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "www.samsonov.net == Login ==> : " . $res[0] . ":" . $res[1] . "\n";}

// $res = $rwd->Login("pupkin", WDb::KeyTypeClientToken);
// if (is_numeric($res) && $res<0)  {print "pupkin -- Login? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "pupkin == Login ==> : " . $res[0] . ":" . $res[1] . "\n";}


//
// $res = $rwd->Route("www.rbc.ru", WDb::KeyTypeURL);
// if (is_numeric($res) && $res<0)  {print "www.rbc.ru -- Route? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "www.rbc.ru == Route ==> : " . $res . "\n";}
 

// $res = $rwd->Route("maxirmx", WDb::KeyTypeClientToken);
// if (is_numeric($res) && $res<0)  {print "maxirmx -- Route? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "maxirmx == Route ==> : " . $res . "\n";}

// $res = $rwd->Route("www.samsonov.net", WDb::KeyTypeURL);
// if (is_numeric($res) && $res<0)  {print "www.samsonov.net -- Route? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "www.samsonov.net == Route ==> : " . $res . "\n";}

// $res = $rwd->Route("pupkin", WDb::KeyTypeClientToken);
// if (is_numeric($res) && $res<0)  {print "pupkin -- Route? : " . $rwd->errorMessage($res) . "\n";}
// else                             {print "pupkin == Route ==> : " . $res . "\n";}

// $rwd->showAll(); 
?>
