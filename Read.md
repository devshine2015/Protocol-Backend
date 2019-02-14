#Basic project setup

#Clone the repository
#for now we have personal repo, We can create new repo to access code 

#Switch to the repo folder
cd bridgit_chrome_extension_backend

#Install all the dependencies using composer
composer install

#Copy the example env file and make the required configuration changes in the .env file
cp .env.example .env

#Generate a new application key
php artisan key:generate

Run the database migrations
php artisan migrate

#Code Overview

laravel-passport - For authentication using Web Tokens
laravel-socialite - For authentication using tokens for social login
laravel-yajra-datatable - To create table and manage list data for web
laravel-cors - For handling Cross-Origin Resource Sharing (CORS)

#Folders

app - Contains all the Eloquent models
app/Http/Controllers/Api - Contains all the api controllers
app/Http/Controllers/Admin - Contains all the admin(web) controllers
app/Http/Middleware - Contains the auth middleware
config - Contains all the application configuration files
database/migrations - Contains all the database migrations
database/seeds - Contains the database seeder
resources/views/ - Contains view pages for the email and all the web pages of admin panel
public/js - Contains all the external js and js created which used for web
public/css - Contains all the external css and admin.css which used for web
routes - Contains all the api routes defined in api.php file and web path in web.php file
Events and Listenres - Event is for send notification and rewards point after some action
Interface and repositery - Containe combine logic for share module for web and api both(For access coman function for both)


#Environment variables

.env - Environment variables can be set in this file

#Testing API on Local

php artisan serve

#The api can now be accessed at
http://localhost:8000/api/

#Testing API on live
https://bridgit.io/app/api/


## How to build and run extension?
```
# Run this line if it's the first time you try to build
1. npm i

2. npm run build

3. dist folder should be created

4. Open chrome://extensions/ and ON Developer Mode

5. Load your dist folder by clicking Load unpacked or drag and drop