<?php

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginAtuser extends Plugin {

    protected $aInherits=array(
        'module' => array('ModuleText'),
    );

    // Активация плагина
    public function Activate() {
        return true;
    }

    // Деактивация плагина
    public function Deactivate(){       
    	return true;
    }
    // Инициализация плагина
    public function Init() {
    }
}
?>
