<?php declare(strict_types=1);

namespace BITAPP;

use \BITAPP\Core\Response;

/** @var string $balance */
?>
<!--suppress HtmlUnknownTarget -->
<form method="post" action="logout"><button type="submit" name="btnlogout">Logout</button></form>
<div>
    <span>Balance (BTC):</span>
    <span><b><?=$params['balance']?></b></span>
</div>
    <form method="post" action="withdrawal">
    <label>
        <?php
        if (Response::hasError('amount')) {
            ?>
            <div style="color:red"><?=Response::getError('amount')?></div>
            <?php
        }
        ?>
        <div>
            Withdrawal (satoshi):
        </div>
        <input type="text" value="0" name="amount">
    </label>
    <button type="submit" name="btnwithdrawal">Withdrawal</button>
    </form>