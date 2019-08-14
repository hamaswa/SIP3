<?php
#$file = 'monkey.gif';
#$file=($_POST["id"]);
#$file='/var/spool/asterisk/monitor/2017/11/15/out-98355493-4788-20171115-161243-1510733563.16590.wav';
#echo $file;
$value=($_GET['id']);

//echo $value;
#$str = "internal-58-88-20171002-172211-1506936131.792.wav";
$value2=(explode("-",$value));
#print_r($array);
$year=substr($value2[3],0,4);
$month=substr($value2[3],-4,2);
$day=substr($value2[3],-2,2);
$file="/var/spool/asterisk/monitor/".$year."/".$month."/".$day. "/".$value;
//echo $file;

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

else {
    echo "file not found";

echo "<a href='" . $_SERVER["HTTP_REFERER"] . "'> Back </a>";
}