<?php

class EnvironmentCredentials
{

    function permanentLogin() {
        // key used for permanent login
        return 'cBE-%9xj"nb@qa=u{i"iN=JwI&byG@x!i:CsK;ZASs{"~QrV@mee,Z*a^F5Q';
    }

    function headers() {
        // локально база называется ht.kz, нужен редирект когда меняем окружение PROD/LOCAL
        $redirect = false;
        $server = $_GET['server'] ?? '';
        if (AdminerHelper::isRemote()) {
            if ($server === 'db') {
                $_GET['server'] = getenv('WEBSERVER_REMOTE_DB_HOST');
                $redirect = true;
            }
            if ($_GET['db'] === 'ht.kz') {
                $_GET['db'] = 'v_1284_form';
                $redirect = true;
            }
        } else {
            // для локального клиента отключаем блокировку по количеству неправильных вводов пароля
            @unlink(get_temp_dir() . "/adminer.invalid");

            if ($server === getenv('WEBSERVER_REMOTE_DB_HOST')) {
                $_GET['server'] = 'db';
                $redirect = true;
            }
            if ($_GET['db'] === 'v_1284_form') {
                $_GET['db'] = 'ht.kz';
                $redirect = true;
            }
        }
        $server = $_GET['server'] ?? '';

        if ($server && in_array($server, ['db', 'mysql', 'postgres']) && AdminerHelper::isLocal()) {
            ?>
            С локальным админером можно подключаться только к серверу "db"<br/>
            Это сервер, который находится в вашем докер контейнере<br/><br/>
            Чтобы подключиться к любому серверу используйте
            <a href="https://myradminer.kz" target="_blank">https://myradminer.kz</a><br/><br/>
            <button onclick="history.back()">Вернуться назад</button>
            <?php
            exit;
        }

        if ($redirect) {
            header('Location: ' . $_SERVER['DOCUMENT_URI'] . '?' . http_build_query($_GET));
            exit;
        }
    }

    function login() {
        return true;
    }

    function credentials()
    {
        if (AdminerHelper::isRemote()) {
            // если в удаленном админере, и цепляемся к серверу с хостом db,
            // то прокинем подключение к удаленному тоннелю
            $serverName = $_GET['server'] === 'db' ?
                getenv('WEBSERVER_REMOTE_DB_HOST') . ':' . getenv('WEBSERVER_REMOTE_DB_PORT')
                : $_GET['server'];
            return [$serverName, $_GET['username'], get_password()];
        }
        if (isset($_GET['clickhouse']) && explode(':', $_GET['clickhouse'])[0] === 'clickhouse') {
            return ['clickhouse:8123', 'default', ''];
        }
        return ['db', 'root', '123'];
    }
}

return new EnvironmentCredentials();
