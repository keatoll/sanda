<div id = login>
<div id = 'errormsg' ><?=$params['errormsg']?></div>
<h2>Здравствуйте!</h2>
<form id = 'auth_form' action="" method="post">
    <table>
<? if (!$user_authorized=CheckUserRights()) {?>
        <tr><td>Email:</td></tr>
        <tr><td><input type="text" name="login" value = '<?=$params[params]['login']?>'/></td></tr>
        <tr><td>Пароль:</td></tr>
        <tr><td ><input type="password" name="password" value = '<?=$params[params]['password']?>'/></td></tr>
        <tr><td><input type="hidden" name="from_url" value = '<?= rawurlencode($_SERVER[REQUEST_URI])?>'/></td></tr>
        <tr><td><input type="submit" name='btn_login' value="Войти" /></td></tr>
        <tr><td><input type="submit" name='btn_remember' value="Вспомнить пароль" /></td></tr>
        <tr><td id =><a href = '/register'>Зарегистрироваться</a></td></tr>
<? } else {?>
        <tr><td><?=$_SESSION['user_email']?></td>
        </tr>
        <tr><td><input type="submit" name='btn_logout' value="Выйти" /></td>
        </tr>
        <tr><td><a href = '/myaccount'>Личный кабинет</a></td>
        </tr>
<?}?>
    </table>
</form>
</div>