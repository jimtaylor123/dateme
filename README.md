# Date-me  ❤️

By: James Taylor
For: Muzmatch
Date: 01/12/2020

## General info

I have adapted a [boilerplate slim php api](https://github.com/maurobonfietti/rest-api-slim-php), since I consider it to have some good patterns in it such as repository, service and invokable classes for the controller routes.
I am not ashamed to have used this template, since I consider it a good use of time, I was able to concentrate my efforts on fulfilling the brief, and I have done a considerable amount of customization. One example is updating the docker file to use the gd extension for image processing.
## How to set up

> NB: If you have any issues setting this up please contact me and I will be happy to help: jim@jimtaylor.space

This guide assumes the following set up: 

* Mac OS
* git
* docker
* docker compose
* make (for windows see below)
* There are no services running on the following ports:
  * 8081
  * 63790
  * 33060
  * 8888

> If you are on a windows machine 'make' will not work, so for each make command below see the corresponding command in the Makefile and copy into your terminal. For example, instead of `make up` you can type `docker-compose up -d --build` into your command line.

1. Git clone https://github.com/jimtaylor123/rest-api-slim-php.git date-me
2. cd date-me
3. make up
4. make db
5. make php
6. composer install
7. cp .env.example .env
8. Set the 'secret-key' in your .env file to a random 32 character string, you can get one here: http://www.unit-conversion.info/texttools/random-string-generator/, for example SECRET_KEY='dq8ceH5NaQVYYdSbXpy2xQ4OI1FbDEmZ'
9. You can now 
   1. view the api welcome page at http://localhost:8081/
   2.  connect to the db 
       1.  on http://localhost:8888/ using username 'root' and no password, or ...
       2.  you can use a programme like sequel pro or tables plus using the same username anbd password and host localhost port 33060
   3. Run all php unit tests with the following command `composer test`. After each test run `composer restart-db` to reset the db back to it's original state.

## Comments 

I have tried to fulfil the requirements given, but there are still some rough corners due to lack of time.

* There are no tests for swipes. 
* I would like to have cropped the centre of the images, not just 100x100.
* I would like to have created a make or bash script for set up.
* The format of the json responses leaves something to be desired, I would have preferred to have coded a cull json-api standard response.
* I did not have time to create a swagger file

### Auth

The boilerplate initially used an auth package, but after reading the brief I have commented this out and replaced it with my own code for producing the jwt. 
