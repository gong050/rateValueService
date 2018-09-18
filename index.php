<?php
require 'scripts/dbconn.php';
include 'scripts/model.php';

updateRate();
?>
<html>
    <head>
        <title>Информационная система "Курс валют"</title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <script async src="libs/list.js"></script>
        <script async src="scripts/sort.js"></script>
    </head>
    <body onload="preload()">
        <div id="loader"></div>
        <div id="main">
            <div class="hello">
                <h1>Информационная система "Курс валют"</h1>
            </div>
            <div id="rate">
                <table class="rateTable">
                    <tr>
                        <th>Код</th>
                        <th>Единиц</th>
                        <th>
                            Валюта
                            <a class="sort" id="click" href="#" data-sort="name" onClick="change();">↑</a>
                        </th>
                        <th>Курс ЦБ РФ</th>
                        <th>Дата курса</th>
                    </tr>
                    <tbody class="list">
                    <?php findRate(); ?>
                </tbody>
                </table>
                <ul class="pagination"></ul>
            </div>
            <div id="options">
                <div class="block">
                    <form name="graph" action="" method="POST">
                        <h2>Показать динамику изменения курса:</h2>
                        <p>Название валюты: <input type="date" /></p>
                        <p>
                            Введите временной промежуток: </p>
                            <p>Начало: <input type="date" /> Конец: <input type="date" />
                        </p>
                        <p><input type="submit" value="Построить график"/></p>
                    </form>
                </div>
                <div class="block">
                    <form name="loadRate" action="" method="GET">
                        <h2>Загрузить курсы по заданной дате:</h2>
                        <p>Введите дату: <input type="date" /></p>
                        <p><input type="submit" value="Загрузить"/></p>
                    </form>
                </div>
            </div>
        </div>
    
        <script src="scripts/preloader.js"></script>
    </body>
</html>