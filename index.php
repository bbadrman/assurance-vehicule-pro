<?php

use App\Kernel;

require_once dirname(__DIR__).'/assurance-vehicule-pro.fr/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
