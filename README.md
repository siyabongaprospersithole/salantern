
## Login System using Laravel

The application runs on Laravel as backend, Vuejs as the Frontend, Bootstrap as CSS Framework and Custom CSS styles
Commands to run the app:

## Tech / Framework used
#### Built with:
- [Laravel](https://laravel.com) 
- [VueJS](https://vuejs.org)
- [Bootstrap 4](https://getbootstrap.com)
- [VueSimpleSpinner](https://dzwillia.github.io/vue-simple-spinner) 
- Axios
- MariaDB 10.1.38

#### My machine specification
- PHP 8
- npm v6.9.0
- Composer v7.18.1


## Getting started
```bash
# clone this repository
git https://github.com/siyabongaprospersithole/salantern.git

# change into project directory
cd app

# install dependencies
composer install

# copy .env.example file and rename it to .env
cp .env.example .env

# modify the database configuration in .env file as you prefer

# generate application key
php artisan key:generate

# open .env file
Replace QUEUE_CONNECTION=sync with QUEUE_CONNECTION=database

# config mysql
Replace mysql details on .env file

# queue table
php artisan queue:table

# clear cache
php artisan config:cache

# run migration
php artisan migrate

# install node_modules
npm install

# compile assets
npm run dev || npm run watch

# Send emails

php artisan queue:listen

# run in development mode
php artisan serve  #localhost:8000
```

