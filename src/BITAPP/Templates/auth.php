<?php declare(strict_types=1);

namespace BITAPP;

use \BITAPP\Core\Response;

?>
<div class="">
    <form method="post" action="login">
        <label>
            <?php
            if (Response::hasError('login')) {
                ?>
                <div style="color:red"><?=Response::getError('login')?></div>
                <?php
            }
            ?>
            <div>Login:</div>
            <input name="login" value="<?php
            if (isset($params['login'])) {
                print $params['login'];
            }
            ?>">
        </label>
        <label>
            <?php
            if (Response::hasError('password')) {
                ?>
                <div style="color:red"><?=Response::getError('password')?></div>
                <?php
            }
            ?>
            <div>Password:</div>
            <input name="password" type="password">
        </label>
        <div>
            <button type="submit" name="btnlogin">Login</button>
        </div>
    </form>
</div>