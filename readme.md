## Laravel MailChimp
Implementation of the RESTful API on the Laravel framework to interact with the [MailChimp lists](https://developer.mailchimp.com/documentation/mailchimp/reference/overview/). 

The following features implemented:
- Receive, add, update and delete the list;
- Receive, add, update and delete members in the list;

It is assumed that changes in MailChimp will be made only through this API, and therefore the data should not be extracted from MailChimp with every API call, but should be stored locally wherever practicable.

### How to launch
Begin with docker
```
docker-compose up --build
```
Set up MailChimp Api Key in .env file
```
MAILCHIMP_API_KEY={Your api key}
```
Run composer
```
composer install
```
Init laravel
```
docker-compose exec php-cli php -r "file_exists('.env') || copy('.env.example', '.env');" 
docker-compose exec php-cli php artisan key:generate
```
Start migrations
```
docker-compose exec php-cli php artisan migrate
```
Run synchronization command to get data from MailChimp
```
docker-compose exec php-cli php artisan mailchimp:synchronize
```
Setup cron to run laravel scheduler for regular synchronization
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
Also start laravel queue worker
```
docker-compose exec php-cli php artisan queue:work
```
You can make requests using prepared postman collection (import to Postman the postman_collection.json)
