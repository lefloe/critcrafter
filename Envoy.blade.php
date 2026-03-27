@servers(['live' => 'root@207.154.220.72'])

@setup
    $path = '/var/www/critcrafter';
    $branch = 'main';
@endsetup

@story('deploy')
    pull
    dependencies
    migrate
    cache
    assets
@endstory

@task('pull', ['on' => 'live'])
    echo "--- Pulling {{ $branch }} ---"
    cd {{ $path }}
    git pull origin {{ $branch }}
@endtask

@task('dependencies', ['on' => 'live'])
    echo "--- Installing dependencies ---"
    cd {{ $path }}
    composer install --no-dev --optimize-autoloader --no-interaction
@endtask

@task('migrate', ['on' => 'live'])
    echo "--- Running migrations ---"
    cd {{ $path }}
    php artisan migrate --force
@endtask

@task('cache', ['on' => 'live'])
    echo "--- Clearing and caching config/routes/views ---"
    cd {{ $path }}
    php artisan optimize
@endtask

@task('assets', ['on' => 'live'])
    echo "--- Building frontend assets ---"
    cd {{ $path }}
    export NVM_DIR="$HOME/.nvm" && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    npm ci --prefer-offline
    npm run build
@endtask
