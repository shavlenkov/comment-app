# comment-app

### About project
This project is a comment app where you can leave comments and reply to them. Also, when creating a comment, you can attach a file or image to it.

### Requirements for launching the project
To start the project, you will need:

1. PHP >= **8.2.6**
2. Laravel >= **10**
3. Composer >= **2.3.7**
4. npm >= **8.5.1**
5. Docker >= **20.10.18**
6. Docker Compose >= **1.29.2**

### How to launch the project?
1. Clone a repository:

   `git clone https://github.com/shavlenkov/comment-app.git`
2. Go to the comment-app folder:

   `cd comment-app`
3. Make an .env file from the .env.example file:

   `cp .env.example .env`
4. Make the necessary configuration changes to the .env file:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
```
RECAPTCHA_SECRET=
VITE_APP_RECAPTCHA_SITEKEY=
```
5. Install all dependencies using Composer:

   `composer install`
6. Install all dependencies using npm:

   `npm install`
7. Сompile the project with the command:

   `npm run build`
8. Run containers using Docker Compose:

   `docker-compose up -d`
9. Connect to the container:

   `docker exec -it comment-app_laravel.test_1 bash`
    1. Give the correct access rights to the bootstrap folder:

       `chmod -R 777 ./bootstrap ./storage`
    2. Generate App Key:

       `php artisan key:generate`
    3. Run migrations and seeders:

       `php artisan migrate --seed`
    4. Create symbolic link:
        
       `php artisan storage:link`
10. Open a browser and go to the address:
   [http://localhost:8000/](http://localhost:8000/ "http://localhost:8000/")
