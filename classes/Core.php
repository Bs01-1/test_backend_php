<?php

namespace classes;

use controllers\Index;

class Core
{
    public static function Init()
    {
        DataBase::Init();

        require_once ABSOLUTE_PATH.'controllers/Index.php';
        Index::Init();
    }

    public static function getModel(string $model): false|object
    {
        $model_path = ABSOLUTE_PATH.'models/'.$model.'.php';
        if (!file_exists($model_path))
            return false;
        require_once $model_path;
        $Model = '\models\\'.$model;
        return new $Model();
    }

    public static function getTemplate(string $name, array|null $data = null): string
    {
        $template_path = ABSOLUTE_PATH.'templates/'.$name.'.template.php';
        if (!file_exists($template_path))
            return '';
        if ($data !== null)
            extract($data);
        ob_start();
        require $template_path;
        return ob_get_clean();
    }

    public static function getBlock(string $name, array|null $data = null): string
    {
        return self::getTemplate('blocks/'.$name, $data);
    }

    public static function prePrint($data, $exit = true)
    {
        echo "<pre>"; print_r($data); echo "</pre>";
        if ($exit) exit();
    }
}