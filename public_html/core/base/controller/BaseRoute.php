<?php


namespace core\base\controller;


class BaseRoute
{
    use Singleton, BaseMethod;

    public static function routeDirection()
    {
        if (self::instance()->isAjax())
        {
            exit((new BaseAjax())->route());
        }

        RouteController::Instance()->route();
    }

}