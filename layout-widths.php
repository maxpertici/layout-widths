<?php
/*
 * Plugin Name:       Layout Widths
 * Plugin URI:        https://maxpertici.fr
 * Description:       Add support for custom layout widths in the block editor.
 * Version:           0.1.0
 * Author:            @maxpertici
 * Author URI:        https://maxpertici.fr
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://maxpertici.fr/#layout-widths
 * Text Domain:       layout-widths
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) or die();

use MXP\LayoutWidths\Core\App ;

require __DIR__ . '/vendor/autoload.php';

$app = App::instance();
$app->createFromFile( __FILE__ );
$app->load();
