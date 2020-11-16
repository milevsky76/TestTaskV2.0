<?php
    $srtError = $_SERVER['REDIRECT_STATUS'];
    $arrError = str_split($srtError);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ошибка <?=$srtError?></title>
</head>
<style>
    body{
        margin: 0;
        padding: 0;
        display: flex;
        align-items:center;
        justify-content: center;
        height: 100vh;
        background-color: #494949;
        color: white;
        font-size: 48px;
    }
    span{
        font-size: 160px;
    }
    span.blue{
        color: #007BFF;
    }
</style>
<body>
    ОШИБОЧКА <span class="blue"><?=$arrError[0];?></span><span><?=$arrError[1];?></span><span class="blue"><?=$arrError[2];?></span> ВЫШЛА
</body>
</html>