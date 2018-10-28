<?php declare(strict_types=1);
/**
 * Project Index file Doc Comment
 *
 * PHP version 7.1
 *
 * @category File
 * @package  MyPackage
 * @author   Display Name <daniil.kamenskiy@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://matbea.net/
 */

require __DIR__ . '/../vendor/autoload.php';

try {
    $btcapp = new \BITAPP\Core\Application();
    $btcapp->process();
} catch (\Exception $ex) {
    /** @noinspection ForgottenDebugOutputInspection */
    error_log('Error: "' . $ex->getMessage() . '" in ' . $ex->getFile() . ':' . $ex->getLine());
    //HUERAGA - vozmozhno 404/500 tut vivesti, ne uveren, chto tak horosho, to est tolko cherez Sapi?
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    die;
}
