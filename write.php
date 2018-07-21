<?php
//var_dump($_POST);
file_put_contents('profile.php', '' . PHP_EOL);
file_put_contents('profile.php', '' . PHP_EOL, FILE_APPEND);
file_put_contents('profile.php', '' . PHP_EOL, FILE_APPEND);
file_put_contents('profile.php', '' . PHP_EOL, FILE_APPEND);
file_put_contents('profile.php', '' . PHP_EOL, FILE_APPEND);
file_put_contents('profile.php', '' . PHP_EOL, FILE_APPEND);
file_put_contents('profile.php', '' . PHP_EOL, FILE_APPEND);
//if (!empty($_POST)) {
//    file_put_contents('profile.php', '' . PHP_EOL);
//    if (!empty($_POST['loginMinebet']))
//        file_put_contents('profile.php', $_POST['loginMinebet'] . PHP_EOL, FILE_APPEND);
//    if (!empty($_POST['passwordMinebet']))
//        file_put_contents('profile.php', $_POST['passwordMinebet'] . PHP_EOL, FILE_APPEND);
//    if (!empty($_POST['loginVodds']))
//        file_put_contents('profile.php', $_POST['loginVodds'] . PHP_EOL, FILE_APPEND);
//    if (!empty($_POST['passwordVodds']))
//        file_put_contents('profile.php', $_POST['passwordVodds'] . PHP_EOL, FILE_APPEND);
//    if (!empty($_POST['token']))
//        file_put_contents('profile.php', $_POST['token'] . PHP_EOL, FILE_APPEND);
//    if (!empty($_POST['id']))
//        file_put_contents('profile.php', $_POST['id'] . PHP_EOL, FILE_APPEND);
//}
function file2Array( $fileName ){
    $out = array();
    $rows = file( $fileName );
    foreach( $rows as $rowNr => $row )
        $out[$rowNr] = explode( "|", $row );
    return $out;
}

function array2File( $array, $fileName ){
    $fileString = "";
    foreach( $array as $row )
        $fileString .= implode( "|", $row );
    $fp = fopen( $fileName, "w" );
    fwrite( $fp, $fileString );
    fclose( $fp );
}

function deleteString($file,$strNum){
    // читаем файл в массив
    $fopen=file($file);
    // номер строки
    $need=$strNum;
    array_splice($fopen, $need, 1);
    $f=fopen($file,"w");
    for($i=0;$i<count($fopen);$i++)
    {
        fwrite($f,$fopen[$i]);
    }
    fclose($f);
}
//deleteString("profile.php",2);
$fileName = "profile.php";
$fileArray = file2Array( $fileName );
$fileArray[1][1] = $_POST['loginMinebet'];
$fileArray[2][2] = $_POST['passwordMinebet'];
$fileArray[3][3] = $_POST['loginVodds'];
$fileArray[4][4] = $_POST['passwordVodds'];
$fileArray[5][5] = $_POST['token'];
$fileArray[6][6] = $_POST['id'];

array2File( $fileArray, $fileName );
//deleteString("profile.php",3);