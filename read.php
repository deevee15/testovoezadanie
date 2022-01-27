<?
setlocale(LC_ALL, 'nl_NL');
if($_POST['upload']){
    $mysqli=new mysqli('localhost', 'root', 'password', 'table');
    $open=fopen('table.csv', 'r');
    while(!feof($open)){
        $array=fgetcsv($open, 1024);
        $count=count($array);
        if($count>1){
            if(preg_match("/^([а-яА-ЯЁёa-zA-Z0-9-.]+)$/i", $array[1])){
                $mysqli->query("SET NAMES 'cp1251'");
                $mysqli->query("INSERT INTO `ttable` (`code`, `name`) VALUES ('{$array[0]}', '{$array[1]}')");
            }
        }
    }
    fclose($open);
    $mysqli->close();
    echo "<meta http-equiv='Refresh' content='0; URL=/index.php?result=uploaded'>";
}
?>