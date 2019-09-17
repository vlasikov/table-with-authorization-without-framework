<?php
require("model.php");

class Controller {

    /**
     * Формирование страницы таблицы
     * @return array
     */
    public function getData() {
        $rows = $_POST['rows'];
        $page = $_POST['page'];
        $sidx = $_POST['sidx'];
        $sord = $_POST['sord'];

        $model = new ModelTasks;
        $result = $model->all();
        $count = count($result);

        $start = ($page-1)*$rows;
        $result = $model->getData($sidx, $sord, $start, 3);

        if( $count > 0 && $rows > 0) {
            $totalPage = ceil($count/$rows);
        } else {
            $totalPage = 0;
        }
        $data = array("total" => $totalPage, "page" => $page, "records" => $count, "rows" => $result);
        $resJs = json_encode($data);
        return $resJs;
    }

    /**
     * Добавление записи в таблицу
     * @return boolean
     */
    public function addData() {
        $model = new ModelTasks;
        return $model->add($_POST);
    }

    /**
     * Изменение записи
     * @return boolean
     */
    public function updateData() {
        $model = new ModelTasks;
        return $model->update($_POST);
    }

    /**
     * Авторизация
     * @return array
     */
    public function auth() {
        $login = $_POST['login'];
        $psw = $_POST['psw'];

        if (($login == ModelTasks::ADMIN_LOGIN) && ($psw == ModelTasks::ADMIN_PSW))
            return '{"auth":"ok"}';

        return '{"auth":"not_ok"}';
    }
}
