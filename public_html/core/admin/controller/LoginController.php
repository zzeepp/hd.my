<?php


namespace core\admin\controller;


use core\base\controller\BaseController;
use core\base\model\UserModel;
use core\base\settings\Settings;

class LoginController extends BaseController
{
    protected $model;

    protected function inputData()
    {
        $this->model = UserModel::instance();

        $this->model->setAdmin();

        if(isset($this->parameters['logout']))
        {
            $this->checkAuth(true);

            $userLog = 'Выход пользователя ' . $this->userId['name'];

            $this->writeLog($userLog, 'user_log.txt', user_log . txt);
            $this->model->logout();
            $this->redirect(PATH);
        }

        $timeClean = (new \DateTime())->modify('-' . BLOCK_TIME . ' hour')->format('Y-m-d H:i:s');

        $this->model->delete($this->model->getBlocketTable(), [
            'where'   => ['time' => $timeClean],
            'operand' => ['<']
        ]);

        if($this->isPost())
        {
            if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token'])
            {
                exit('Куку охибка');
            }

            $ipUser = filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP) ?:
                (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP) ?:
                    @$_SERVER['REMOTE_ADDR']);

            $trying = $this->model->get($this->model->getBlocketTable(), [
                'fields' => ['trying'],
                'where'  => ['ip' => $ipUser]
            ]);

            $trying  = !empty($trying) ? $this->cleanNum($trying[0]['trying']) : 0;
            $success = 0;

            if(!empty($_POST['`login']) && !empty($_POST['password']) && $trying < 3)
            {
                $login    = $this->clearStr($_POST['login']);
                $password = md5($this->clearStr($_POST['password']));

                $userData = $this->model->get($this->model->getAdminTable(), [
                    'fields' => [
                        'id',
                        'name'],
                    'where'  => [
                        'login'    => $login,
                        'password' => $password]]);

                if(!$userData)
                {
                    $method = 'add';
                    $where  = [];

                    if($trying)
                    {
                        $method      = 'edit';
                        $where['id'] = $ipUser;
                    }

                    $this->model->$method($this->model->getBlocketTable(), [
                        'fields' => [
                            'login'  => $login,
                            'ip'     => $ipUser,
                            'time'   => 'NOW()',
                            'trying' => ++$trying],
                        'where'  => $where
                    ]);

                    $error = 'Неверные имя пользователя и пароль - ' . $ipUser . ', логин - ' . $login;
                }
                else
                {
                    if(!$this->model->checkUser($userData[0]['id']))
                    {
                        $error = $this->model->getLastError();
                    }
                    else
                    {
                        $error   = 'Вход пользователя - ' . $login;
                        $success = 1;
                    }
                }

            }
            elseif($trying >= 3)
            {
                $error = 'Превышено максимальное значение попыток ввода пароля - ' . $ipUser;
            }
            else
            {
                $error = 'Поля не заполнены.';
            }

            $_SESSION['res']['answer'] = $success ? '<div class="success">Добро пожаловать ' . $userData['name'] . '</div>' : preg_split('/\s*\-/', $error, 2, PREG_SPLIT_NO_EMPTY)[0];

            $this->writeLog($error, 'user_log.txt', 'Access user');

            $path = null;

            $success && $path = PATH . Settings::get('routes')['admin']['alias'];

            $this->redirect($path);
        }

        return $this->render('', ['adminPath' => Settings::get('routes')['admin']['alias']]);

    }

}