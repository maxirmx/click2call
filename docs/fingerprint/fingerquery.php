<?php
/**
 * fingerquery.php
 * 
 */

 require('./fingercore.php');

 $finger = $_GET['finger'];

 $rwd = new WDb();
 $res = $rwd->Connect();      

 if (res != WDb::RWD_OK)
 {
   print "A call to connect has failed: " . $rwd->errorMessage($res) . " (" . $res . ")<br />";
 }
 else
 {
   $res = $rwd->Query($finger);
   print "<br/>" . $res . "<br />";
 }
 print "<br />Спасибо за интерес.";
?>
