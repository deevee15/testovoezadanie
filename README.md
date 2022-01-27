Создаем таблицу "ttable" в существующей БД:

```
CREATE TABLE `ttable` (
  `code` int(11) NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

```
Создаем страницу index.php с простой формой, где только кнопка "Загрузить", выполняющая загрузку данных из .csv таблицы в БД. Загрузка выполняется в файле "read.php". Со следующим кодом:

```
if($_POST['upload']){ - проверка, нажата ли кнопка
    $mysqli=new mysqli('localhost', 'root', 'password', 'table'); - подключение к БД
    $open=fopen('table.csv', 'r'); - открываем таблицу
    while(!feof($open)){ - создаем цикл 
        $array=fgetcsv($open, 1024); - каждый столбец таблицы записываем в массив
        $count=count($array); - количество строк в таблице
        if($count>1){
            if(preg_match("/^([а-яА-ЯЁёa-zA-Z0-9-.]+)$/i", $array[1])){ - проверка на соответствие заданным условиям: латиница, кириллица, цифры и символы ".", "-"
                $mysqli->query("SET NAMES 'cp1251'"); - устанавливаем набор символов cp1251 для корреткного отображения кириллицы в БД
                $mysqli->query("INSERT INTO `ttable` (`code`, `name`) VALUES ('{$array[0]}', '{$array[1]}')"); - вносим все строки таблицы в БД
            }
        }
    }
    fclose($open);
    $mysqli->close();
    echo "<meta http-equiv='Refresh' content='0; URL=/index.php?result=uploaded'>"; - перенаправление на главную страницу с GET-запросом, указывающим на успешную загрузку данных
}

```
В index.php делаем проверку GET-запроса "result" и, в случае, если он равен "uploaded", то выполняем загрузку файла-отчёта путём открытия файла download.php со следующим кодом:

    header("Content-Type: text/csv; charset=utf-8");
	header("Content-Disposition: attachment; filename=summary.csv"); - указываем название файла, с которым будем работать
    $mysqli=new mysqli('localhost', 'root', 'password', 'table'); - подключаемся к БД
	$output = fopen("php://output", "w"); - открываем файл summary.csv
	fputcsv($output, array('code', 'name', 'error')); - форматируем строку в виде CSV и записываем её в файловый указатель
    $open=fopen('table.csv', 'r'); - открываем исходный файл с данными
    while(!feof($open)){
        $array=fgetcsv($open, 1024);
        $count=count($array);
        if($count>1){
            if(!preg_match("/^([а-яА-ЯЁёa-zA-Z0-9-.]+)$/i", $array[1])){ - если строка "имя" не соответствует требованиям
                $p = "/[A-Za-z]|[А-Яа-я]|-|\.|[0-9]/"; 
                $r = ""; 
                $result = preg_replace($p,$r,$array[1]); - выводим символы, из-за которых строка не была записана в БД
                $array[2]='Undefined symbol '.$result.' in field Name'; 
            }
            fputcsv($output, $array); - записываем данные в новый файл summary.csv
        }
    }
    fclose($open);
 	fclose($output);
    exit;
В итоге получается готовая страница с загрузкой данных из таблицы в БД, работающая по следующему алгоритму:
1.Заходим на страницу и нажимаем кнопку "Загрузить". Происходит загрузка данных .csv таблицы в БД.
2.По прошествии загрузки данных в БД открывается страница с текстом "Обработка файла прошла успешно" и загружается файл-отчёт, где еще есть столбец "error", в котором напротив строк с недопустимыми символами пишется "Недопустимый символ "%s" в поле Название".
