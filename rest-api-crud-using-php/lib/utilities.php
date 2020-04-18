<?php
//require_once('adodb/adodb.inc.php');
//require_once('GTO_include_legacy.php');
global $Checkpoints;
$Checkpoints = NULL;
 
function simpleLog($msg) {
    $logDir  = "../log/";
//  date_default_timezone_set("UTC");
    date_default_timezone_set("America/Chicago");
    $datenow = time();
    $wkday   = date("w",$datenow);// w is day of week 0-Su 1-mo 2-tu ...
    $timenow = date("m/d (w) h:i:s",$datenow);
    // make sure the log directory exists.
    if(is_dir($logDir)){
       // delete log files older than 2 days old
       for ($i = 0; $i < 7; $i++){
          $fn = $logDir."simpleLog".$i.".log";
          if(file_exists($fn)){
             $fage = filemtime($fn);
             $daysOld = ($datenow - $fage)/86400;
             if($daysOld > 2){ unlink($fn); }
          }
       }
    }else{ // if the log directory doesn't exist, create it. 
       mkdir($logDir);
    }
    // file name will be one of 7 based on the week day of the system clock.
    $fn = $logDir."simpleLog".$wkday.".log";
    $fp = fopen($fn,"a+");
    fwrite($fp,$timenow.": $msg\n");
    fclose($fp);
}
 
function sanitizeInput($input) {
    // Mondo-bizaro line wrapping voodoo and MySQL safe strings.
    $return = preg_replace("/[\r\n]?[\r\n]/", "\n", $input);
    // Not necessary with PDO lib
    // $return = mysql_real_escape_string($return);
    // Remove any non-ASCII characters.  This can happen if cut and paste from Outlook generated email with rich
    // text formatting (Word) on.
    $return = preg_replace("/[^\x01-\x7F]/", "",$return);
    return $return;
} // Function sanitizeInput



class Timer {
    // To use:
   // Start timer
    // $timer = null;
    // if ($this->timer_enable) {
    //     $timer = new Timer();
    // }
    // End timer
    // if ($this->timer_enable) {
    //     unset($timer);
    // }
 
    private $time = null;
    public function __construct() {
        logMessage('Initializing timer...');
        $this->starttime = microtime(true);
        $this->lasttime = microtime(true);
    }   
    
    public function timeIt($comment) {
        $curTime = microtime(true);
        logMessage($comment.'- since last check: '.sprintf( '%0.3f', ($curTime - $this->lasttime)).' seconds'
                   .' - total: '.sprintf( '%0.3f', ($curTime - $this->starttime)).' seconds.');
        $this->lasttime = microtime(true);
    }
 
    public function __destruct() {
        $curTime = microtime(true);
        logMessage('End - since last check: '.sprintf( '%0.3f', ($curTime - $this->lasttime)).' seconds'
                   .' - total: '.sprintf( '%0.3f', ($curTime - $this->starttime)).' seconds.');
    }              
}
 
?>
