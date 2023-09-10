<?php

class AdminerHelper
{
    /**
     * Получает глобальный объект админера
     * @return mixed
     */
    public static function getAdminer() {
        global $adminer;
        return $adminer;
    }

    /**
     * Это удаленная версия админера и с ней можно цепляться к любому IP
     * @return bool
     */
    public static function isRemote(): bool {
        return $_SERVER['HTTP_HOST'] === 'myradminer.kz';
    }

    /**
     * Это локальная версия админера и с ней можно цепляться только к хосту с названием db
     * @return bool
     */
    public static function isLocal(): bool {
        return !self::isRemote();
    }
}
