<?php


namespace core\base\settings;

use core\base\controller\Singleton;

class Settings
{
    use Singleton;

    private $routes = [
        'admin'    => [
            'alias'  => 'admin',
            'path'   => 'core/admin/controller/',
            'hrUrl'  => false,
            'routes' => []
        ],
        'settings' => [
            'path' => 'core/base/settings/'
        ],
        'plugins'  => [
            'path'  => 'core/plugins/',
            'hrUrl' => false,
            'dir'   => ''
        ],
        'user'     => [
            'path'   => 'core/user/controller/',
            'hrUrl'  => true,
            'routes' => []
        ],
        'default'  => [
            'controller'   => 'IndexController',
            'inputMethod'  => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];

    /**
     * массив шаблонов
     * ключ - имя шалона
     * значение - поля, выводимые в этом шаблоне
     */
    private $templateArr = [
        'text'         => ['name'],
        'textarea'     => [
            'keywords',
            'content'],
        'radio'        => ['visible'],
        'checkboxlist' => ['filters'],
        'select'       => [
            'menu_position',
            'parent_id'],
        'img'          => [
            'img',
            'main_img'],
        'gallery_img'  => [
            'gallery_img',
            'new_gallery_img']
    ];

    private $fileTemplates = [
        'img',
        'gallery_img'];

    private $defaultTable = 'goods';

    private $formTemplates = PATH . 'core/admin/view/include/form_templates/';

    private $projectTables = [
        'articles' => ['name' => 'Статьи'],
        'pages'    => ['name' => 'Страницы'],
        'goods'    => [
            'name' => 'Товары',
            'img'  => 'pages.png'],
        'filters'  => ['name' => 'Фильтры']
    ];

    private $translate = [
        'name'     => [
            'Название',
            'Не более 100 символов'],
        'keywords' => [
            'Ключевые слова',
            'Не более 70 символов'],
        'content'  => []
    ];

    private $radio = [
        'visible' => [
            'Нет',
            'Да',
            'default' => 'Да']
    ];

    private $rootItems = [
        'name'   => 'Корневая',
        'tables' => [
            'articles',
            'filters',
            'goods']
    ];

    private $manyToMany = [
     //   'goods_filters' => [
      //      'goods',
      //      'filters']
        //'type' => 'child' || 'root'

    ];

    private $blockNeedle = [
        'vg-rows'    => [],
        'vg-img'     => [
            'img',
            'main_img'],
        'vg-content' => ['content']
    ];


    /***
     * массив проверяемых полей
     * ключ - поле для проверки
     * значение - функции для проверки
     */
    private $validations = [
        'name'        => [
            'empty' => true,
            'trim'  => true],
        'price'       => ['int' => true],
        'login'       => [
            'empty' => true,
            'trim'  => true],
        'password'    => [
            'crypt' => true,
            'empty' => true],
        'keywords'    => [
            'count' => 70,
            'trim'  => true],
        'description' => [
            'count' => 160,
            'trim'  => true]

    ];

    private $expansion = 'core/admin/expansion/';
    private $messages  = 'core/base/messages/';

    static public function get($property)
    {
        return self::instance()->$property;
    }

    public function clueProperties($class)
    {
        $baseProperties = [];

        foreach($this as $name => $item)
        {
            $property = $class::get($name);

            if(is_array($property) && is_array($item))
            {
                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
                continue;
            }

            if(!$property) $baseProperties[$name] = $this->$name;
        }
        return $baseProperties;
    }

    public function arrayMergeRecursive()
    {
        $arrays = func_get_args();

        $base = array_shift($arrays); //вернуть первый элемент массива

        foreach($arrays as $array)
        {
            foreach($array as $key => $value)
            {
                if(is_array($value) && is_array($base[$key]))
                {
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                }
                else
                {
                    if(is_int($key)) // если ключ числовой
                    {
                        //проверяем наличие значения в массиве
                        if(!in_array($value, $base)) array_push($base, $value);

                        continue;
                    }

                    $base[$key] = $value; //присваиваем новое значение
                }
            }
        }
        return $base;
    }
}