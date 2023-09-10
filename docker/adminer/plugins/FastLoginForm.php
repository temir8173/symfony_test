<?php

class FastLoginForm {

    /**
     * Выводит быстрые параметры для быстрой авторизации в базу
     * @return null
     */
    function loginForm() {
        $isRemote = AdminerHelper::isRemote();
        ?>
        <div style="padding-top: 5px">
            Быстрый вход:
            <?php
            if ($isRemote) {
                ?>
                <button class="btn-fill-auth-data" data-env="remoteMysql"
                        type="button">Заполнить форму</button>
                <button class="btn-fill-auth-data" data-env="remoteClickhouse"
                        type="button">Заполнить форму (clickhouse)</button>
                <?php
            } else {
                ?>
                <button class="btn-fill-auth-data" data-env="localMysql"
                        type="button">Заполнить форму и войти</button>
                <button class="btn-fill-auth-data" data-env="localClickhouse"
                        type="button">Заполнить форму и войти (clickhouse)</button>
                <?php
            }
            ?>
        </div>
        <script<?= nonce() ?>>
            const environments = {
                'localMysql': {
                    'server': 'db',
                    'username': 'remote',
                    'password': '123',
                },
                'localClickhouse': {
                    'driver': 'clickhouse',
                    'server': 'clickhouse:8123',
                    'username': 'default',
                    'password': '',
                },
                'remoteMysql': {
                    'server': '<?= $_ENV['WEBSERVER_REMOTE_DB_HOST'] ?>',
                    'username': 'remote',
                },
                'remoteClickhouse': {
                    'driver': 'clickhouse',
                    'server': '<?= $_ENV['WEBSERVER_REMOTE_CLICKHOUSE_HOST'] ?>:8712',
                    'username': 'ht',
                },
            };
            function fillAuthData(environment) {
                const env = environments[environment];
                document.querySelector('select[name="auth[driver]"]').value = env.driver || 'server';
                document.querySelector('input[name="auth[server]"]').value = env.server;
                document.querySelector('input[name="auth[username]"]').value = env.username;
                document.querySelector('input[name="auth[permanent]"]').checked = true;
                if (typeof env.password !== 'undefined') {
                    document.querySelector('input[name="auth[password]"]').value = env.password;
                    document.querySelector('#content form').submit();
                }
            }
            Array.from(document.getElementsByClassName('btn-fill-auth-data')).forEach(function(element) {
                element.addEventListener('click', () => fillAuthData(element.dataset.env));
            });
        </script>
        <?php
    }
}
return new FastLoginForm();
