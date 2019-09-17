<?php
// show databases;
// use ppr;
// mysql> CREATE TABLE beejee (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, username VARCHAR(20), email VARCHAR(200), task VARCHAR(200));
// DESCRIBE beejee;
// INSERT INTO beejee (username, email, task) VALUES ('Smith', 'mail', 'asdsad');
// SELECT * FROM beejee;
// TRUNCATE TABLE beejee;

class ModelTasks {
    const ADMIN_LOGIN = "admin";
    const ADMIN_PSW = "202cb962ac59075b964b07152d234b70";//"123";

    const DB_HOST="localhost";
    const DB_DATABASE="ppr";
    const DB_USERNAME="homestead";
    const DB_PASSWORD="secret";
    const DB_TABLE="beejee";

    /**
     * Получение всех записей
     * @return array
     */
    public function all() {
        $conn = $this->connectDB();
        $sql = "SELECT * FROM beejee";
        $result = $conn->query($sql);
        $conn->close();

        $res = array();
        while($row = $result->fetch_assoc()) {
            $res[] = $row;
        }

        return $res;
    }

    /**
     * Получение страницы для таблицы
     * @param $sidx, $sord, $lim1, $lim2
     * @return array
     */
    public function getData($sidx, $sord, $lim1, $lim2) {
        $conn = $this->connectDB();
        $sql = "SELECT * FROM ".self::DB_TABLE." ORDER BY ".$sidx." ".$sord." LIMIT ".$lim1.", ".$lim2;
        $result = $conn->query($sql);
        $conn->close();

        $res = array();
        while($row = $result->fetch_assoc()) {
            $res[] = $row;
        }

        return $res;
    }

    /**
     * Добавление записи в таблицу
     * @return boolean
     */
    public function add($data) {
        $name = $data['name'];
        $email = $data['email'];
        $task = $data['task'];
        $done = $data['done'];
        $edit = $data['edit'];

        $conn = $this->connectDB();
        $sql = "INSERT INTO ".self::DB_TABLE." (username, email, task, done, edit)
                VALUES ('".$name."', '".$email."', '".$task."', '".$done."', '".$edit."')";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    /**
     * Изменение записи
     * @return boolean
     */
    public function update($data) {
        $id = $data['id'];
        $name = $data['name'];
        $email = $data['email'];
        $task = $data['task'];
        $done = $data['done'];
        $edit = $data['edit'];

        $conn = $this->connectDB();
        $sql = "UPDATE ".self::DB_TABLE." SET username='".$name."', email='".$email."', task='".$task."', done='".$done."', edit='".$edit."' WHERE id=".$id;
        // var_dump($sql); die();
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    /**
     * Подключение к ДБ
     * @return connect
     */
    private function connectDB() {
        // Create connection
        $conn = new mysqli(self::DB_HOST, self::DB_USERNAME, self::DB_PASSWORD, self::DB_DATABASE);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}