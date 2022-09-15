# Task management sample

### Installation

first you should download application from github by this command :

``git clone https://github.com/drsoft28/task-managment-sample.git ``

then  task-managment-sample run commands:

` composer install `
`cp .env.example .env`
`php artisan key:generate`

you should fill correct information about you database in .env  file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskmng
DB_USERNAME=root
DB_PASSWORD=
```

then run command 
`php artisan migrate`

now you can run application by command

`php artisan serve`