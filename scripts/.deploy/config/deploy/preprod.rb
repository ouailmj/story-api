set :stage, :dev
set :symfony_env, "dev"

set :branch, 'master' # your staging branch
set :deploy_to, '/datadisc/htdocs/instantapp/preprod' # path on production server

after 'deploy:updated', 'deploy:migrate' # Comment this if you don't want update the database
after 'deploy:finished', 'assets:install' # Comment this if you don't want update the database

set :controllers_to_clear, ['app_*']
set :composer_install_flags, '--prefer-dist --no-interaction --optimize-autoloader'


server '51.136.31.137', user: 'multiinfo', port: 22, roles: %w{app db web} # edit IP / Port and SSH user of your production server
SSHKit.config.command_map[:composer] = "php #{shared_path.join("composer.phar")}"

namespace :assets do
  task :install do
    on "multiinfo@51.136.31.137" do
      invoke 'symfony:console', 'ckeditor:install'
      invoke 'symfony:console', 'assets:install', ' web'
      execute "cd #{fetch(:deploy_to)}/current && yarn"
      execute "cd #{fetch(:deploy_to)}/current && yarn run encore production"
    end
  end
end
