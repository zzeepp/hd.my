<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0px;
            padding: 0px;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        div {
            flex-basis: 500px;
            padding: 15px;
        }

        form {
            display: block;
        }

        label, input {
            display: block;
            margin: auto;
        }

        label, h1 {
            text-align: center;
        }

        input {
            margin-bottom: 20px;
            padding: 3px 5px;
            border: 1px solid #0077aa;
            box-shadow: 0px 5px 10px 2px rgba(122, 191, 236, 0.2);
        }

        input[type=submit] {
            background: #ffffff;
            padding: 5px 10px;

        }
    </style>
</head>
<body>
<div>

    <?php if(!empty($_SESSION['res']['answer']))
    {
        echo '<p style="color: red;text-align: center">' . $_SESSION['res']['answer'] . '</p>';

        unset($_SESSION['res']);
    } ?>

    <h1>Авторизация</h1>
    <form
            action="<?= PATH . $adminPath ?>/login"
            method="POST"
    >
        <label
                for="login"
        >Логин</label>
        <input
                type="text"
                name="login"
                id="login"
                autocomplete="off"
        >
        <label
                for="password"
        >Пароль</label>
        <input
                type="password"
                name="password"
                id="password"
                autocomplete="current-password"
        >
        <input
                type="submit"
                value="Войти"
        >
    </form>
</div>
<script src="<?= PATH . ADMIN_TEMPLATE ?>js/frameworkfunctions.js"></script>
<script>
    let form = document.querySelector('form');

    if (form)
    {
        form.addEventListener('submit', e =>
        {
            if (e.isTrusted)
            {
                e.preventDefault();

                Ajax({
                         data: {
                             ajax: 'token'
                         }
                     })
                    .then(
                        ress =>
                        {
                            if (ress) form.insertAdjacentHTML('beforeend', '<input type="hidden" name="token" value="' + ress + '">');

                            form.submit();
                        })
            }
        });
    }
</script>
</body>
</html>
