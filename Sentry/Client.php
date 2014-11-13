<?php

namespace Hautelook\SentryBundle\Sentry;

use Hautelook\SentryClient\Client as BaseClient;
use Symfony\Component\HttpKernel\Kernel;

class Client extends BaseClient
{
    public function __construct(array $config = array())
    {
        // ignore the previous params cached by the container
        $config['command']['params']['tags']['php_version'] = phpversion();
        $config['command']['params']['tags']['symfony_version'] = Kernel::VERSION;
        $config['command']['params']['server_name'] = gethostname();

        parent::__construct($config);
    }
}
