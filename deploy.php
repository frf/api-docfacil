<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/slack.php';

// Project name
set('application', 'api-docfacil');

// Project repository
set('repository', 'git@github.com:frf/api-docfacil.git');

set('slack_webhook', 'https://hooks.slack.com/services/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');
set('slack_title', 'Application: {{application}}');
set('slack_text', '_{{user}}_ deploying branch: `{{branch}}` to *https://{{hostname}}*');
set('slack_success_text', 'Deploy *{{hostname}}* successful');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', ['storage']);

// Writable dirs by web server
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

// Hosts
host('api.docfacil.me')
    ->hostname('api.docfacil.me')
    ->user('deployer')
    ->set('deploy_path', '/var/www/html');

// Tasks
task('generate:swagger', function () {
    run('cd {{release_path}} && php artisan l5-swagger:generate');
});

task('build', function () {
    run('cd {{release_path}} && build');
});

// Tasks
task('reload:php-fpm', function () {
    run('sudo /etc/init.d/php7.4-fpm restart'); // Using SysV Init scripts
});

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:clear',
    'artisan:optimize:clear',
    'deploy:symlink',
    'artisan:migrate',
    'deploy:unlock',
    'cleanup',
]);

before('deploy', 'slack:notify');
after('success', 'slack:notify:success');
before('deploy', 'slack:notify');

after('deploy', 'success');
after('success', 'slack:notify:success');
after('deploy:failed', 'deploy:unlock');
after('deploy:failed', 'slack:notify:failure');
after('deploy', 'generate:swagger');


