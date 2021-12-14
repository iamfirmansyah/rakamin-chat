## Rakamin Chat

Installation Requirtments : 

- Laravel 7x
- MYSQL
- PHP 7.4 or Upper
- XAMPP

Package Use :

- JWT

## Feature
1. Users can send a message to another user.
2. Users can list all messages in a conversation between them and another user.
3. Users can reply to a conversation they are involved with.
4. User can list all their conversations (if user A has been chatting with user C & D, the list
for A will shows A-C & A-D)
5. Unit Test for Authentication

## Documentation

## Installing Rakamin on local

- [clone this GitHub Link](https://github.com/iamfirmansyah/rakamin-chat.git)
- [download composer](https://getcomposer.org/)
- Run composer install on base dir project 
```bash
  composer install
```
- Run php artisan serve
```bash
  php artisan serve
```
- Create Database MYSQL name 'rakamin-chat'
- Run php artisan migrate
```bash
  php artisan migrate
```
- Run php artisan db:seed
```bash
  php artisan db:seed
```
- Run unit test
```bash
  composer test
```
