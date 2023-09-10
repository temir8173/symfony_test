<?php

class LocalAndLogos {

    function head() {
        if (AdminerHelper::isRemote()) {
            ?>
            <link rel="icon" type="image/png" sizes="16x16" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAgMAAABinRfyAAAADFBMVEUAAAD/////AAAATmF4UO+CAAAAAXRSTlMAQObYZgAAADdJREFUCNdjYIAA0RAgEbmUgYExNZKBNXRqKoP439QQBtnwyBQG+f+hIJYDA1ZC/C+QYA2FmgMAMPYNcu7RC50AAAAASUVORK5CYII=" />
            <?php
        } else {
            ?>
            <link rel="icon" type="image/png" sizes="16x16" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAAD1BMVEVMaXGjy4lDbyb/////AAAendBEAAAAAXRSTlMAQObYZgAAAEpJREFUeF51jcEJwDAMAwWeoN2gzgIm5w3c/WdqMTTQR/Q6hDjpF4NooG66SCgJKBLZ9JEQsus4qXzB3VnNkLawNjPag3u0uU+/PL5lDZ33sVhDAAAAAElFTkSuQmCC" />
            <?php
        }
        if (isset($_GET['db'])) {
            switch ($_GET['db']) {
                case 'v_1284_form':
                    ?>
                    <style>
                        h2 {
                            color: #4c4631;
                            background: url(//ht.kz/img/full_logo.png) #FFD953 no-repeat right;
                            background-size: 135px;
                            background-position-x: 98%;
                        }
                    </style>
                    <?php
                    break;
                case 'bronix':
                    ?>
                    <style>
                        h2 {
                            color: #FFF;
                            background: url(http://bronix.com/img/logo_white.svg) #311B92 no-repeat right;
                            background-size: 135px;
                            background-position-x: 98%;
                        }
                    </style>
                    <?php
                    break;
            }
        }
        if (file_exists("adminer.css")) {
            echo'<link rel="stylesheet" type="text/css" href="adminer.css">';
        }
        if (isset($_GET['import']) && AdminerHelper::isRemote()) {
            ?>
            <script<?= nonce() ?>>
                document.onreadystatechange = function () {
                    if (document.readyState === "interactive") {
                        const form = document.getElementById('form');
                        const formStyle = form.style;
                        formStyle.opacity = '0.3';
                        formStyle.pointerEvents = 'none';
                        const alertBlock = document.createElement('div');
                        alertBlock.innerHTML = 'Внимание! Вы пытаетесь сделать импорт в продакшн!<br/>'
                            + '<button id="unblockImport" style="margin-top: 10px;">'
                            + 'Всё хорошо. Я понимаю, что я делаю'
                            + '</button>';
                        alertBlock.style.padding = '10px 7px';
                        alertBlock.style.color = 'red';
                        alertBlock.style.fontWeight = 'bold';
                        form.parentNode.appendChild(alertBlock);

                        document.getElementById('unblockImport').addEventListener(
                            'click',
                            () => {
                                formStyle.opacity = '1';
                                formStyle.pointerEvents = '';
                                form.parentNode.removeChild(alertBlock);
                            }
                        );
                    }
                }
            </script>
            <?php
        }

        // инициализируем другие плагины
        (new TableHeaderScroll())->head();
        (new SqlFormatter())->head();
        return false;
    }

    function navigation() {
        ?>
        <style>
            .environment-notify {
                position: fixed;
                bottom: 10px;
                left: 10px;
                color: white !important;
                background: #c62828;
                padding: 5px 7px;
                border-radius: 5px;
                z-index: 10000;
            }
        </style>
        <?php
        if (AdminerHelper::isRemote()) {
            ?>
            <a class="environment-notify"
               title="Вы подключены к боевой базе"
               href="<?=$this->getLocalUrl()?>">PROD</a>
            <?php
        } else {
            ?>
            <style>
                .environment-notify {
                    background: #4ac628;
                }
            </style>
            <a class="environment-notify"
               title="Вы подключены к локальной базе"
               href="<?=$this->getRemoteUrl()?>">LOCAL</a>
            <?php
        }
    }

    public function getRemoteUrl(): string
    {
        return 'https://myradminer.kz' . $_SERVER['REQUEST_URI'];
    }

    public function getLocalUrl(): string
    {
        return 'https://myadminer.kz' . $_SERVER['REQUEST_URI'];
    }

    function name(): string
    {
        return 'Adminer ' . (
            AdminerHelper::isRemote() ?
                '<a style="color: #c62828;" href="'
                . $this->getLocalUrl() . '"'
                .' title="Вы подключены к боевой базе">PROD</a>'
                : '<a style="color: green;" href="'
                . $this->getRemoteUrl() . '"'
                . ' title="Вы подключены к локальной базе">LOCAL</a>'
            );
    }
}
return new LocalAndLogos();
