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
    ->user('docfacil')
    ->set('deploy_path', '/var/www/docfacil');

task('build', function () {
    run('cd {{release_path}} && build');
});
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


