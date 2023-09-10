# Настройка https
Если вы хотите, чтобы браузер не спрашивал каждый раз о сертификате, то установите его в систему (для windows):
0. Можете воспользоваться скриптом `import-certificate.ps1` через power-shell с правами админа в этой папке или установить вручную следуя по следующим шагам 
1. Откройте двойным нажатием private.ctr в этой папке
2. Установить сертификат...
3. Локальный компьютер -> Далее
4. Поместить все сертификаты в следующее хранилище -> Обзор...
5. Доверенные корневые центры сертификации -> Ок -> Далее -> Готово
6. Теперь локальные домены типа https://*.kz.lc будут открываться у вас без предупреждений браузера

Для Linux:
1. Проверьте существует ли директория /usr/local/share/ca-certificates/. Если нет, то создайте
2. Скопируйте файл private.ctr в данную директиву
    ```bash
    sudo cp ./ssl/private.crt /usr/local/share/ca-certificates/
    ```
3. Выполните команду
    ```bash
    sudo update-ca-certificates
    ```

# Обновление сертификатов или добавление нового домена в сертификат
Команды идущие дальше выполняются только если ключи, которые лежат в этой папке истекут
или если вам надо добавить новый домен в ssl.conf
Делал по этой инструкции https://gist.github.com/croxton/ebfb5f3ac143cd86542788f972434c96

1. Выполняем команды из этой папки
    ```bash
    openssl genrsa -out private.key 4096
    # выполняем следующую команду и жмем enter enter enter enter enter
    openssl req -new -sha256 \
        -out private.csr \
        -key private.key \
        -config ssl.conf
   
    openssl x509 -req \
        -sha256 \
        -days 3650 \
        -in private.csr \
        -signkey private.key \
        -out private.crt \
        -extensions req_ext \
        -extfile ssl.conf
   
    dc-restart nginx
    ```
1. После этого нужно выполнить шаги из раздела выше "Настройка https"
