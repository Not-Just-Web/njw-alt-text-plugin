<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Bootstrap
 */

/**
 * First we need to load the composer autoloader, so we can use WP Mock.
 */
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/bootstrap-wp-mock.php';

require_once __DIR__ . '/njw-alt-text-plugin.php';
