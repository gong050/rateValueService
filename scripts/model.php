<?php
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
        $rate->value = (int)$valute->Value;
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
        $rate->value = (int)$valute->Value;
        $rate->date = $currentDate;
        
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
?>