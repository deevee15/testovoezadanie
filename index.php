<head>
    <title>Тестовое задание</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
    <?if(empty($_GET['result'])){?>
    <form action="read.php" method="post">
        <p>Загрузка таблицы:</p>
        <input type="submit" value="Загрузить" name="upload">
    </form>
    <?}else if($_GET['result']=='uploaded'){?>
        <p class="text">Обработка файла прошла успешно</p>
        <meta http-equiv='Refresh' content='0; URL=/download.php'>
        <p>Если загрузка файла-отчета не произошла, то нажмите кнопку ниже:</p>
        <form action="download.php" method="post">
            <button name="download_summary">Загрузить файл-отчет</button>
        </form>
    <?}?>
</body>