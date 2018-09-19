<?php

// Функция findRate() отображает все курсы имеющиеся в БД в поля таблицы

function findRate() {
    $rates = R::findAll( 'rate' );
    foreach ($rates as $rate) {
        echo "<tr>";
        echo "<td class='charcode'>".$rate->charcode."</td>";
        echo "<td class='nominal'>".$rate->nominal."</td>";
        echo "<td class='name'>".$rate->name."</td>";
        echo "<td class='value'>".$rate->value."</td>";
        echo "<td class='date'>".$rate->date."</td>";
        echo "</tr>";
    }
}

//Функция loadCurrentRate() загружает в БД курсы текущего дня

function loadCurrentRate() {
    $currentDate = date("d/m/Y");
    $url = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=".$currentDate;
    $rates = simplexml_load_file($url);

    foreach ($rates->children() as $valute){
        $rate = R::dispense( 'rate' );

        $rate->uniquecode = (string)$valute['ID'];
        $rate->numcode = (int)$valute->NumCode;
        $rate->charcode = (string)$valute->CharCode;
        $rate->nominal = (int)$valute->Nominal;
        $rate->name = (string)$valute->Name;
        $rate->value = (float)str_replace(",",".",$valute->Value);
        $rate->date = $currentDate;
        
        R::store($rate);
    }
}

//Функция loadRate() загружает в БД курсы указанной даты

function loadRate($date) {
    $checkDate = R::findOne( 'rate', 'date = ?', [$date] );

    if($checkDate != NULL) {
        echo "По данной дате уже есть данные в БД!";
        return;
    }

    $url = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=".$date;
    $rates = simplexml_load_file($url);

    foreach ($rates->children() as $valute){
        $rate = R::dispense( 'rate' );

        $rate->uniquecode = (string)$valute['ID'];
        $rate->numcode = (int)$valute->NumCode;
        $rate->charcode = (string)$valute->CharCode;
        $rate->nominal = (int)$valute->Nominal;
        $rate->name = (string)$valute->Name;
        $rate->value = (float)str_replace(",",".",$valute->Value);
        $rate->date = $date;
        
        R::store($rate);
    }
}

/*
    Функция updateRate() проверяет наличие курсов на текущий день.
    Если в БД нет актуальных курсов, то вызывается функция loadCurrentRate(), которая их загружает в БД.
*/

function updateRate() {
    $currentDate = date("d/m/Y");
    $result = R::findOne( 'rate', 'date = ?', [$currentDate] );
    if($result == NULL) {
        loadCurrentRate();
    }
}

/* 
    Функция getLoadDate() производит захват данных с формы "Загрузить курсы по заданной дате".
    Полученные данные преобразуются в формат d/m/Y для последующей передачи в функцию loadRate()
*/ 

function getLoadDate() {
    $loadDate = strtotime($_GET['loadDate']);
    $loadDate = date("d/m/Y", $loadDate);
    loadRate($loadDate);
}

/* 
    Функция getValuteName() делает запрос в БД для получения названий всех валют.
    Полученные данные отоброжаются как элементы списка <select>.
*/ 

function getValuteName() {
    $valuteName = R::getAll('SELECT DISTINCT name, uniquecode FROM rate');
    for($i = 0; $i < count($valuteName); $i++) {
        echo "<option value=".$valuteName[$i]['uniquecode'].">".$valuteName[$i]['name']."</option>";
    }
}

function loadGraph() {

    if(empty($_POST["valuteName"]) || empty($_POST["firstDate"]) || empty($_POST["secondDate"])) {
        echo "Для построения графика, необоходимо заполнить все формы!";
    }

    $valuteName = $_POST["valuteName"];
    $firstDate = strtotime($_POST["firstDate"]);
    $firstDate = date("d/m/Y", $firstDate);
    $secondDate = strtotime($_POST["secondDate"]);
    $secondDate = date("d/m/Y", $secondDate);

    return plotGraph($valuteName, $firstDate, $secondDate);
}

function plotGraph($valuteName, $firstDate, $secondDate) {
    $url = "http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=".$firstDate."&date_req2=".$secondDate."&VAL_NM_RQ=".$valuteName;
    $data = simplexml_load_file($url);
    $dataPoints = array();
    foreach ($data->children() as $points) {
        array_push($dataPoints, array("y" => (float)str_replace(",",".",$points->Value), "label" => (string)$points['Date']));
    }
    return $dataPoints;
}
?>