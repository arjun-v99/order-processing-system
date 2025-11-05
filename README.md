# Set-Up
1. Make sure you have latest version of PHP, Laravel and Composer installed in your system
2. Set up a MySQL Database for to be used for the project
3. Update the .env file with your DB_HOST, DB_NAME, DB_USER and DB_PASS values
4. Run the command `composer install` in your project directory.
5. Run the command `php artisan migrate:fresh --seed`. this will create the tables and populate the tables with pre-filled values.
6. Run the command `php artisan queue:work` to start the queue worker.
7. Run the command `php artisan serve` to start the server. The server will start at `http://127.0.0.1:8000/` if no errors occured.
8. Before we begin with our app make sure you have logged in. Send a POST request to `http://127.0.0.1:8000/api/login` with `email` and `password` as request data.
9. If you are using postman you can write a script to automatically save the token and use it for subsequent request.
10. Access `/api/dashboard` with GET method for dashboard data.
11. Access `api/order/{your_order_id_here}` wih POST method for processing the orders.

# Note
1. Make sure your HTTP request's `Accept` header value is `application/json` to receive proper json responses