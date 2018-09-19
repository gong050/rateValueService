<?php
    require 'scripts/dbconn.php';
    include 'scripts/model.php';

    updateRate();
    getLoadDate();
    $dataPoints = loadGraph();
?>
<html>
    <head>
        <script>
            window.onload = function () {
                var chart = new CanvasJS.Chart("chartContainer", {
                    title: {
                        text: "Динамика изменения курса"
                    },
                    axisY: {
                        title: "Значение курса"
                    },
                    data: [{
                        type: "spline",
                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart.render();
                showPage();
            }
        </script>
        <title>Информационная система "Курс валют"</title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    </head>
    <body>     
        <div id="loader"></div>
        <div id="main">
            <div class="hello">
                <h1>Информационная система "Курс валют"</h1>
            </div>
            <div id="rate">
                <div id="search">
                    <h2>Поиск по ключевым словам:</h2>
                    <input type="text" class="search" placeholder="Поиск" />
                </div>
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
            <div id="chartContainer"></div>
            <div id="options">
                <div class="block">
                    <form name="graph" action="index.php" method="POST">
                        <h2>Показать динамику изменения курса:</h2>
                        <p>
                            Название валюты: <br> 
                            <select name="valuteName">
                            <?php getValuteName(); ?>
                            </select>
                        </p>
                        <p>
                            Введите временной промежуток: </p>
                            <p>
                                Начало: <input type="date" name="firstDate" /><br> 
                                Конец: <input type="date" name="secondDate"/>
                            </p>
                        </p>
                        <p><input type="submit" value="Построить график"/></p>
                    </form>
                </div>
                <div class="block">
                    <form name="loadRate" action="index.php" method="GET">
                        <h2>Загрузить курсы по заданной дате:</h2>
                        <p>Введите дату: <input type="date" name="loadDate" /></p>
                        <p><input type="submit" value="Загрузить"/></p>
                    </form>
                </div>
            </div>
        </div>
        <script src="libs/list.js"></script>
        <script src="scripts/sort.js"></script> 
        <script src="scripts/preloader.js"></script>
        <script src="libs/canvasjs.min.js"></script>
    </body>
</html>