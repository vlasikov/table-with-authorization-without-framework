<?php
require("controller.php");
// 
class Route {

    /**
     * Получение данных из таблицы
     */
    static function getData() {
        $c = new Controller;
        echo $c->getData ();
    }

    /**
     * Добавление записи
     */
    static function addData(){
        $c = new Controller;
        echo $c->addData();
    }

    /**
     * Изменение записи
     */
    static function updateData(){
        $c = new Controller;
        echo $c->updateData();
    }

    /**
     * Авторизация
     */
    static function auth(){
        $c = new Controller;
        echo $c->auth();
    }
}