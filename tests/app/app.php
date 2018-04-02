<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */
require_once __DIR__.'/../../vendor/autoload.php';

use Silex\Application;

$app = new Application();

require __DIR__.'/controllers.php';

return $app;
