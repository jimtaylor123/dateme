# set up

This guide assumes the following set up: 

* Mac OS
* gitdocker
* docker compose
* make
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
   3. Run all php unit tests with the following command `composer test`
   
Interact with system
1. Get status
2. Create user
3. Login as user
4. 