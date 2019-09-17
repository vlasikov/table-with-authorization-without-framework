<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>Подсистема отображения отчетов</title>
 
<link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui-1.12.1.custom.css" />

<script src="js/jquery-1.11.0.min.js" type="text/javascript"></script>

<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="js/site.js" type="text/javascript"></script>
<script src="js/md5.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="css/site.css" />



<script type="text/javascript">
    /**
     * Обновление таблицы с задачами
     */
    function updateTable(rows=3, page=1, sidx="username", sord="desc") {
        $.ajax({
            type:'post',
            url:'/getData.php',
            data:{
                rows:       rows,
                page:       page,
                sidx:       sidx,
                sord:       sord,
            },
            dataType: 'JSON',
            success: function (rsp) {
                arrTask = rsp.rows;
                let i = 0;
                for(let k in arrTask) {
                    if (i==0)
                        $("#row"+i+"-col5").html('<input type="radio" name="dzen" id="radio_'+i+'" value="1" checked>');
                    else
                        $("#row"+i+"-col5").html('<input type="radio" name="dzen" id="radio_'+i+'" value="1">');
                    $("#row"+i+"-col0").html('<xmp>'+arrTask[k]["username"]+'</xmp>');
                    $("#row"+i+"-col1").html('<xmp>'+arrTask[k]["email"]+'</xmp>');
                    $("#row"+i+"-col2").html('<xmp>'+arrTask[k]["task"]+'</xmp>');
                    // выполнено
                    if (arrTask[k]["done"]==1)
                        $("#row"+i+"-col3").html('<input type="checkbox" id="checkbox_'+i+'" onchange="toggleCheckbox(this)" checked />');
                    else
                        $("#row"+i+"-col3").html('<input type="checkbox" id="checkbox_'+i+'" onchange="toggleCheckbox(this)"/>');
                    // отредактировано
                    if (arrTask[k]["edit"]==1)
                        $("#row"+i+"-col4").html("да");
                    else
                        $("#row"+i+"-col4").html("нет");
                    i++;
                }
                for (i; i<3; i++){
                    $("#row"+i+"-col5").html("");
                    $("#row"+i+"-col0").html("");
                    $("#row"+i+"-col1").html("");
                    $("#row"+i+"-col2").html("");
                    $("#row"+i+"-col4").html("");
                }
                
                $("#pages").html('страница '+rsp.page+' из '+rsp.total);
                $("#records").html('записей '+rsp.records);
                pageMax = rsp.total;
                page = rsp.page;
                updateElement(rows, page, sidx, sord);;
            },
            error: function(data, errorThrown)
            {
                alert('updateTable failed :'+errorThrown);
            }
        });
    }
</script>

<style>
    .simple-little-table tr td {
        word-break: break-all;
        vertical-align: middle;
    }
</style>

</head>
<body>
    <br>
    <header>
        <div class="auth" id="auth">
            <!-- <form class="form-inline"> -->
            <table border="0" width="75%" align="center"><tr align="right"><td>
            </td><td width="180px">
                <div class="form-group">
                    <input type="email" class="form-control form-control-sm" id="inp-login" placeholder="Имя">
                </div>
            </td><td width="180px">
                <div class="form-group">
                    <input type="password" class="form-control form-control-sm" id="inp-psw" placeholder="Пароль">
                </div>
            </td><td width="80px">
                <div class="form-group">
                    <button type="submit" class="btn btn-secondary btn-sm hidden" id="btn-signin" onClick = "handlerButtonSignIn()">Вход</button>
                    <button type="submit" class="btn btn-secondary btn-sm hidden" id="btn-exit" onClick = "handlerButtonExit()">Выйти</button>
                </div>
            </td><td width="80px">
                <div class="form-group">
                    <span id="user-name"></span>
                </div>
            </td></tr></table> 
            <!-- </form> -->
        </div>              
    </header>
    <br>
    <div class="table">
        <br>
        <div>           
            <table class="simple-little-table" border="1" width="100%" align="center" >
                    <tr align="center" bgcolor="#AAAAAA">
                        <td width="40px"></td>
                        <td width="250px">
                            Имя пользователя
                            <input type="radio" name="sord" id="username_desc" value="1" onclick="handlerRadioSord(this)" checked>
                            &uarr; &darr;
                            <input type="radio" name="sord" id="username_asc"  value="1" onclick="handlerRadioSord(this)">
                        </td>
                        <td width="140px">
                            Email
                            <input type="radio" name="sord" id="email_desc" value="1" onclick="handlerRadioSord(this)">
                            &uarr; &darr;
                            <input type="radio" name="sord" id="email_asc"  value="1" onclick="handlerRadioSord(this)">
                        </td>
                        <td width="300px">
                            Текст задачи
                            <input type="radio" name="sord" id="task_desc" value="1" onclick="handlerRadioSord(this)">
                            &uarr; &darr;
                            <input type="radio" name="sord" id="task_asc"  value="1" onclick="handlerRadioSord(this)">
                        </td>
                        <td>
                            Выполнено
                            <input type="radio" name="sord" id="done_desc" value="1" onclick="handlerRadioSord(this)">
                            &uarr; &darr;
                            <input type="radio" name="sord" id="done_asc"  value="1" onclick="handlerRadioSord(this)">
                        </td>
                        <td>
                            Отредактировано
                            <input type="radio" name="sord" id="edit_desc" value="1" onclick="handlerRadioSord(this)">
                            &uarr; &darr;
                            <input type="radio" name="sord" id="edit_asc"  value="1" onclick="handlerRadioSord(this)">
                        </td>
                    </tr>
                <?php
                for($i=0; $i<3; $i++){
                    echo 
                    '<tr align="right" height="80px" valign="middle">
                        <td><div id="row'.$i.'-col5"></div></td>
                        <td><div id="row'.$i.'-col0"></div></td>
                        <td><div id="row'.$i.'-col1"></div></td>
                        <td><div id="row'.$i.'-col2"></div></td>
                        <td><div id="row'.$i.'-col3"></div></td>
                        <td><div id="row'.$i.'-col4"></div></td>
                    </tr>';
                }
                ?>
                    <tr align="center" bgcolor="#AAAAAA" height="30px">
                        <td colspan="3">
                            <form action="#openModalAddTask"  style="float:left; padding: 0px 10px 0px">
                                <button type="submit" class="btn btn-secondary btn-sm" id="btnAdd">Добавить</button>
                            </form>
                            <form action="javascript:handlerButtonEdit()" style="float:left;">
                                <button type="submit" class="btn btn-secondary btn-sm" id="btnEdit">Редактировать</button>
                            </form>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-secondary btn-sm" id="btn_back" onClick = "paginationBtn(this)"><<</button>
                            <span id="pages"></span>
                            <button type="submit" class="btn btn-secondary btn-sm" id="btn_next" onClick = "paginationBtn(this)">>></button>
                        </td>
                        <td colspan="2">
                            <span id="records"></span>
                        </td>
                    </tr>
            </table>
        <div>
        <br>
        <!-- модальное окно "Добавить задачу" -->
        <div id="openModalAddTask" class="modalDialog">
            <div>
                <a href="#close" title="Закрыть" class="close">X</a>
                <h2>Добавить задачу</h2>
                <input type="email" class="form-control form-control-sm" id="inp-name-add" placeholder="Имя пользователя">
                <input type="email" class="form-control form-control-sm" id="inp-email-add" placeholder="E-mail">
                <input type="email" class="form-control form-control-sm" id="inp-task-add" placeholder="Задача">
                <br>
                <button type="submit" class="btn btn-secondary btn-sm" id="btn-save-add" onClick = "handlerButtonAddSaveMod()">Сохранить</button>
            </div>
        </div>
        <!-- модальное окно "Редактировать задачу" -->
        <div id="openModalEditTask" class="modalDialog">
            <div>
                <a href="#close" title="Закрыть" class="close">X</a>
                <h2>Редактировать задачу</h2>
                <input type="email" class="form-control form-control-sm" id="inp-name-edit" placeholder="Имя пользователя">
                <input type="email" class="form-control form-control-sm" id="inp-email-edit" placeholder="E-mail">
                <input type="email" class="form-control form-control-sm" id="inp-task-edit" placeholder="Задача">
                <br>
                <button type="submit" class="btn btn-secondary btn-sm" id="btn-save-edit" onClick = "handlerButtonEditSaveMod()">Сохранить</button>
            </div>
        </div>
    </div>
</body>
</html>

<script type="text/javascript">
    $( document ).ready(function() {
        document.location.href = '?#';
        // загружаем данные
        updateTable();
    });
</script>

