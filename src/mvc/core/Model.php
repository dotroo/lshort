<?php

namespace MVC\Core;

abstract class Model
{
    /**
     * Получает запись из БД
     */
    public abstract function getDataById(int $id);
    /**
     * Добавляет или обновляет запись в БД
     */
    public abstract function saveToDb($data);
}