<?php

namespace Hautelook\SentryBundle\Sentry;

use Hautelook\SentryClient\Client as BaseClient;
use Symfony\Component\HttpKernel\Kernel;

class Client extends BaseClient
{
    public function __construct(array $config = array())
    {
        if (!isset($config['command']['params']['tags']['php_version'])) {
            $config['command']['params']['tags']['php_version'] = phpversion();
        }
        if (!isset($config['command']['params']['tags']['symfony_version'])) {
            $config['command']['params']['tags']['symfony_version'] = Kernel::VERSION;
        }
        if (!isset($config['command']['params']['server_name'])) {
            $config['command']['params']['server_name'] = gethostname();
        }

        parent::__construct($config);
    }
}
