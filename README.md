HautelookSentryBundle
=====================

Installation
------------

I recommend to only add the bundle in the production environment

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        ...

        if (in_array($this->getEnvironment(), array('prod'))) {
            $bundles[] = new Hautelook\SentryBundle\HautelookSentryBundle();
        }

        ...
    }
}
```

Example configuration
---------------------

```yaml
hautelook_sentry:
    client_options:
        dsn: %sentry.dsn%

        # you probabled do not want to log 404 exceptions
        ignored_exceptions:
            Symfony\Component\HttpKernel\Exception\NotFoundHttpException: true

        # add customs data/tags
        command:
            params:
                tags: {}

    error_handler: true # enables exception, error, fatal errors capture

    # this will remove delpoyments dates etc from stack traces files
    # without this sentry will fail to group exceptions happening on different capistrano deployments
    files_base_path: %kernel.root_dir%/../

```

Add capistrano revision to exceptions
-------------------------------------

```yaml
imports:
    - { resource: revision.php }

hautelook_sentry:
    client_options:
        command:
            params:
                tags:
                    revision: %revision%
```

```php
<?php
// app/config/revision.php

$revision = 'unknown';
if (file_exists($revisionFile = __DIR__ . '/../../REVISION')) {
    $revision = file_get_contents($revisionFile);
}

$container->setParameter('revision', $revision);
```

Config reference
----------------

```yaml
# Default configuration for extension with alias: "hautelook_sentry"
hautelook_sentry:
    error_handler:
        enabled:              false
        exception:            true
        error:                true
        fatal_error:          true
    files_base_path:      ~
    client_options:       # Required
        public_key:           ~ # Required
        secret_key:           ~ # Required
        project_id:           ~ # Required
        protocol:             https
        host:                 app.getsentry.com
        path:                 /
        port:                 ~
        dsn:                  ~
        exception_factory:    ~
        request:
            options:              [] # guzzle request options
        curl:
            options:              [] # guzzle curl options
        command:
            params:
                level:                ~
                logger:               ~
                platform:             ~
                tags:                 []
                extra:                []
                server_name:          localhost.localdomain
        ignored_exceptions:   []
```
