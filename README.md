# Jennifer - A Simple PHP Framework

Jennifer is a simple PHP framework that implements MVC pattern.

# Configuration
<pre>
composer require ngodinhloc/jennifer
</pre>
# Usage
## Single Point Entry
#### index.php
<pre>
use Jennifer\Http\Response;
use Jennifer\Http\Router;
use Jennifer\Sys\System;

try {
    $system = new System([DOC_ROOT . "/config/env.ini"]);
    $system->setRouter(new Router([DOC_ROOT . "/config/routes.ini"]))->loadView()->renderView();
} catch (Exception $exception) {
    (new Response())->error($exception->getMessage(), $exception->getCode());
}
</pre>

#### api/index.php
<pre>
use Jennifer\Api\Api;
use Jennifer\Http\Response;
use Jennifer\Sys\System;
use thedaysoflife\Api\ServiceMapper;

try {
    $system = new System([DOC_ROOT . "/config/env.ini"]);
    $system->setApi(new Api(new ServiceMapper()))->runAPI();
} catch (Exception $exception) {
    (new Response())->error($exception->getMessage(), $exception->getCode());
}
</pre>

#### controllers/index.php
<pre>
use Jennifer\Http\Response;
use Jennifer\Http\Router;
use Jennifer\Sys\System;

try {
    $system = new System([DOC_ROOT . "/config/env.ini"]);
    $system->setRouter(new Router([DOC_ROOT . "/config/routes.ini"]))->loadController()->runController();
} catch (Exception $exception) {
    (new Response())->error($exception->getMessage(), $exception->getCode());
}
</pre>

For example of implementation and usage, please take a look at Thedaysoflife project https://github.com/ngodinhloc/thedaysoflife.com which was developed using Jennifer framework