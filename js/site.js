var arrTask = [];
var rows = 3,
    page = 1,
    pageMax = 1,
    sidx = "username",
    sord = "desc";

/**
 * Обработка кнопки авторизации
 * @return
 */
function handlerButtonSignIn() {
    if (!validateLogin()) {
        return;
    }
};

/**
 * Валидация и проверка логина пароля
 * @return mixed 
 */
function validateLogin() {
    let login = document.getElementById('inp-login').value;
    let psw = document.getElementById('inp-psw').value;

    let hashPsw = md5(psw);

    if (!(login.length && psw.length)) {
        alert('Заполните поля');
        return false;
    }

    $.ajax({
        type: 'post',
        url: '/auth.php',
        data: {
            login: login,
            psw: hashPsw,
        },
        dataType: 'JSON',
        success: function(rsp) {

            console.log("validateLogin() success " + rsp);
            if (rsp.auth == "ok") {
                // document.location.href = '/?#';
                // обновляем только куки с именем 'user'
                document.cookie = "user=admin";
                updateTable(rows, page, sidx, sord);
            } else {
                alert('Неверный логин-пароль');
            }
        },
        error: function(data, errorThrown) {
            alert('validateLogin :' + errorThrown);
        }
    });

    return true;
}

/**
 * Разлогинивание
 */
function handlerButtonExit() {
    // обновляем только куки с именем 'user'
    delete_cookie("user");
    updateElement();
};

/**
 * Сохранить новую запись в таблице
 */
function handlerButtonAddSaveMod() {
    let email = document.getElementById('inp-email-add').value;
    if (!validateEmail(email)) {
        return;
    }
    $.ajax({
        type: 'post',
        url: '/addData.php',
        data: {
            name: document.getElementById('inp-name-add').value,
            email: document.getElementById('inp-email-add').value,
            task: document.getElementById('inp-task-add').value,
            done: 0,
            edit: 0,
        },
        dataType: 'JSON',
        success: function(rsp) {
            console.log("handlerButtonAddSaveMod() success ");
            alert("Задача добавлена");
            // document.location.replace("/");
            document.location.href = '/?#';
            updateTable(rows, page, sidx, sord);
        },
        error: function(data, errorThrown) {
            alert('handlerButtonAddSaveMod :' + errorThrown);
        }
    });
};

/**
 * Сохранить редактируюмую запись
 */
function handlerButtonEditSaveMod() {
    if (getUserName() != "admin") {
        alert('Доступно админу');
        document.location.href = '/?#close';
        updateTable(rows, page, sidx, sord);
        return;
    }
    console.log("handlerButtonEditSaveMod");
    let email = document.getElementById('inp-email-edit').value;
    if (!validateEmail(email)) {
        return;
    }
    let radioId = getRadioCheckedNumber();
    let id = arrTask[radioId]["id"];
    $.ajax({
        type: 'post',
        url: '/updateData.php',
        data: {
            id: id,
            name: document.getElementById('inp-name-edit').value,
            email: document.getElementById('inp-email-edit').value,
            task: document.getElementById('inp-task-edit').value,
            done: arrTask[radioId]["done"],
            edit: 1,
        },
        dataType: 'JSON',
        success: function(rsp) {
            console.log("handlerButtonAddSaveMod() success ");
            alert("Задача сохранена");
            document.location.href = '/?#';
            updateTable(rows, page, sidx, sord);
        },
        error: function(data, errorThrown) {
            alert('handlerButtonEditSaveMod :' + errorThrown);
        }
    });
}

/**
 * Валидация email
 * @return boolean  
 */
function validateEmail(email) {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    var address = email;
    if (reg.test(address) == false) {
        alert('Введите корректный e-mail');
        return false;
    }
    return true;
}

/**
 * Вызов окна редактирования записи
 */
function handlerButtonEdit() {
    let radioId = getRadioCheckedNumber();

    if (radioId != -1) {
        $("#inp-name-edit").val(arrTask[radioId]["username"]);
        $("#inp-email-edit").val(arrTask[radioId]["email"]);
        $("#inp-task-edit").val(arrTask[radioId]["task"]);
        document.location.href = '?#openModalEditTask';
    }
}

/**
 * Получение номера строки с checked
 * @return int  
 */
function getRadioCheckedNumber() {
    let radioId = -1;
    for (let i = 0; i < 3; i++) {
        if ($("#radio_" + i).prop('checked'))
            radioId = i;
    }
    return radioId;
}

/**
 * Сортировка колонок
 */
function handlerRadioSord(obj) {
    let radioSordId = $(obj).attr('id');
    let substringArray = radioSordId.split("_");
    sidx = substringArray[0];
    sord = substringArray[1];

    updateTable(rows, page, sidx, sord)
}

/**
 * Обработка кнопок пагинации
 */
function paginationBtn(obj) {
    let id = $(obj).attr('id');
    if (id == "btn_back")
        page--;
    if (id == "btn_next")
        page++;

    if (page < 1)
        page = 1;

    if (page > pageMax)
        page = pageMax;

    updateTable(rows, page, sidx, sord)
}

/**
 * Обработка чекбокса "Выполнено"
 */
function toggleCheckbox(obj) {
    if (getUserName() != "admin") {
        alert('Доступно админу');
        document.location.href = '/?#close';
        updateTable(rows, page, sidx, sord);
        return;
    }

    let done = 0;
    if (obj.checked) {
        console.log($(obj).attr('id'));
        done = 1;
    }
    let checkboxId = $(obj).attr('id');
    let substringArray = checkboxId.split("_");
    checkboxId = substringArray[1];

    $.ajax({
        type: 'post',
        url: '/updateData.php',
        data: {
            id: arrTask[checkboxId]["id"],
            name: arrTask[checkboxId]["username"],
            email: arrTask[checkboxId]["email"],
            task: arrTask[checkboxId]["task"],
            done: done,
            edit: 1,
        },
        dataType: 'JSON',
        success: function(rsp) {
            updateTable(rows, page, sidx, sord);
        },
        error: function(data, errorThrown) {
            alert('toggleCheckbox :' + errorThrown);
        }
    });
}

/**
 * Удаление куки
 */
function delete_cookie(cookie_name) {
    var cookie_date = new Date(); // Текущая дата и время
    cookie_date.setTime(cookie_date.getTime() - 1);
    document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}

/**
 * проверяет существование кука и если он сущетвует возвращает его значение.
 * @return mixed  
 */
function getCookie(name) {
    var pattern = "(?:; )?" + name + "=([^;]*);?";
    var regexp = new RegExp(pattern);

    if (regexp.test(document.cookie))
        return decodeURIComponent(RegExp["$1"]);

    return false;
}

/**
 * Получаем логин из куки
 * @return string  
 */
function getUserName() {
    let name;
    name = getCookie("user");
    if (!name)
        name = "guest";
    return name;
}

/**
 * Обновляем элементы на странице
 */
function updateElement() {
    let btnSignin = document.getElementById('btn-signin');
    let btnExit = document.getElementById('btn-exit');
    let inpLogin = document.getElementById('inp-login');
    let inpPsw = document.getElementById('inp-psw');

    let user = getCookie("user");
    if (user == 'admin') {
        // кнопка Вход
        if (!btnSignin.classList.contains('hidden'))
            btnSignin.classList.add("hidden");
        if (btnSignin.classList.contains('visible'))
            btnSignin.classList.remove("visible");

        // login
        if (!inpLogin.classList.contains('hidden'))
            inpLogin.classList.add("hidden");
        if (inpLogin.classList.contains('visible'))
            inpLogin.classList.remove("visible");

        // psw
        if (!inpPsw.classList.contains('hidden'))
            inpPsw.classList.add("hidden");
        if (inpPsw.classList.contains('visible'))
            inpPsw.classList.remove("visible");

        // кнопка Выход
        if (btnExit.classList.contains('hidden'))
            btnExit.classList.remove("hidden");
        if (!btnExit.classList.contains('visible'))
            btnExit.classList.add("visible");

        // кнопка Редактировать, скрываем, если нет задач
        if (arrTask.length) {
            if (btnEdit.classList.contains('hide'))
                btnEdit.classList.remove("hide");
        } else {
            if (!btnEdit.classList.contains('hide'))
                btnEdit.classList.add("hide");
        }

        $('#user-name').html("<b>" + "Админ" + "</b>");

    } else {
        // кнопка Выход
        if (!btnExit.classList.contains('hidden'))
            btnExit.classList.add("hidden");
        if (btnExit.classList.contains('visible'))
            btnExit.classList.remove("visible");

        // кнопка Редактировать
        if (!btnEdit.classList.contains('hide'))
            btnEdit.classList.add("hide");

        // кнопка Вход
        if (btnSignin.classList.contains('hidden'))
            btnSignin.classList.remove("hidden");
        if (!btnSignin.classList.contains('visible'))
            btnSignin.classList.add("visible");

        // login
        if (inpLogin.classList.contains('hidden'))
            inpLogin.classList.remove("hidden");
        if (!inpLogin.classList.contains('visible'))
            inpLogin.classList.add("visible");

        // psw
        if (inpPsw.classList.contains('hidden'))
            inpPsw.classList.remove("hidden");
        if (!inpPsw.classList.contains('visible'))
            inpPsw.classList.add("visible");

        $('#user-name').html("<b>" + "Гость" + "</b>");

    }
};