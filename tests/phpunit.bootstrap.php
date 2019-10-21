<?php

require_once __DIR__ . '/../config/bootstrap.php';

function bootstrap()
{
    $kernel = new \App\Kernel('test', true);
    $kernel->boot();

    $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
    $application->setAutoExit(false);

    $run = function ($command) use ($application) {
        $exitCode = $application->run($command);
        if ($exitCode !== 0) {
            exit($exitCode);
        }
    };

    $run(new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'doctrine:database:drop',
        '--if-exists' => '1',
        '--force' => '1',
    ]));

    $run(new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'doctrine:database:create',
    ]));

    $run(new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'doctrine:migrations:migrate',
        '--no-interaction' => '1',
        '--quiet' => '1',
    ]));

    $run(new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'doctrine:fixtures:load',
        '--append' => '1',
        '--group' => ['testing'],
    ]));

    $kernel->shutdown();
}

bootstrap();
