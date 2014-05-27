<?php

namespace Hautelook\SentryBundle\Sentry;

use Hautelook\SentryClient\Client as BaseClient;
use Symfony\Component\HttpKernel\Kernel;

class Client extends BaseClient
{
    public function __construct(array $config = array())
    {
        if (!array_key_exists('php_version', $config)) {
            $config['php_version'] = phpversion();
        }
        if (!array_key_exists('symfony_version', $config)) {
            $config['symfony_version'] = Kernel::VERSION;
        }
        $config['server_name'] = gethostname();

        parent::__construct($config);
    }
}
