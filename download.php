<?
    header('Content-type: text/csv; charset=utf-8');
	header("Content-Disposition: attachment; filename=summary.csv");
    $mysqli=new mysqli('localhost', 'root', 'password', 'table');
	$output = fopen("php://output", "w");
	fputcsv($output, array('code', 'name', 'error'));
    $open=fopen('table.csv', 'r');
    while(!feof($open)){
        $array=fgetcsv($open, 1024);
        $count=count($array);
        if($count>1){
            if(!preg_match("/^([а-яА-ЯЁёa-zA-Z0-9-.]+)$/i", $array[1])){
                $p = "/[A-Za-z]|[А-Яа-я]|-|\.|[0-9]/"; 
                $r = ""; 
                $result = preg_replace($p,$r,$array[1]);
                $array[2]='Undefined symbol '.$result.' in field Name';
            }
            fputcsv($output, $array);
        }
    }
    fclose($open);
 	fclose($output);
    exit;
?>