<?php
$app = new \lib\jf\app\JFApp(__DIR__.'/app.cnf.php', new lib\jf\app\JFAppContext($this->getRequestedApp(), $this));
$app->start();