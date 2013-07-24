<?php
/**
 * InstallHandler class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Tobias Munk <schmunk@usrbin.de>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package Crisu83.YiiComposer;
 */

namespace Crisu83\YiiComposer;

defined('YII_DEBUG') or define('YII_DEBUG', true);

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

use Composer\Script\CommandEvent;

/**
 * InstallHandler is called by Composer after it installs/updates the current package.
 * Ported from https://github.com/yiisoft/yii2-composer/ and adapted to Yii.
 */
class InstallHandler
{
    const PARAM_WRITABLE = 'yii-install-writable';
    const PARAM_EXECUTABLE = 'yii-install-executable';

    /**
     * Sets the correct permissions of files and directories.
     * @param CommandEvent $event
     */
    public static function setPermissions($event)
    {
        $options = array_merge(
            array(
                self::PARAM_WRITABLE => array(),
                self::PARAM_EXECUTABLE => array(),
            ),
            $event->getComposer()->getPackage()->getExtra()
        );

        foreach ((array)$options[self::PARAM_WRITABLE] as $path) {
            echo "Setting writable: $path ...";
            if (is_dir($path)) {
                chmod($path, 0777);
                echo "done\n";
            } else {
                echo "The directory was not found: " . getcwd() . DIRECTORY_SEPARATOR . $path;
                return;
            }
        }

        foreach ((array)$options[self::PARAM_EXECUTABLE] as $path) {
            echo "Setting executable: $path ...";
            if (is_file($path)) {
                chmod($path, 0755);
                echo "done\n";
            } else {
                echo "\n\tThe file was not found: " . getcwd() . DIRECTORY_SEPARATOR . $path . "\n";
                return;
            }
        }
    }
}