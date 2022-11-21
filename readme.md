
## Installation

**Download the code**
```sh
$ git clone https://gitlab.iut-clermont.uca.fr/php-symfony-2019/dealabs-2020/goncalves-nicolas.git
$ cd goncalves-nicolas
```

**Setup the evironment**
```sh
$ cp .env .env.local
```

change the database url according to your environment  
If you are using docker take this one:
```
DATABASE_URL=mysql://dealabs:dealabs@mysql:3306/dealabs?serverVersion=5.7
```

Then you can change the docker ports to your liking (**Warning: In our case these variables in the .env file overwrite those in the .env.local file**). Example:
```
DOCKER_APACHE_PORT=8081
DOCKER_MAILCATCHER_1_PORT=1081
DOCKER_MAILCATCHER_2_PORT=1026
DOCKER_MYSQL_PORT=3310
DOCKER_PHPMYADMIN_PORT=8090
```

Finally, you can add the MAILER_DSN variable in your .env.local. Example :
```
MAILER_DSN=smtp://192.168.1.17:1026
```

**Start the project (with docker)**  
If you are using docker now is the time to start the project.  
```sh
$ docker-compose up
```

You must run the following commands in the docker container (except for yarn & composer).  
To do so you have to run a bash inside it:  
```sh
docker-compose exec php bash
```

**Install dependencies**
```sh
$ composer install
$ yarn install
```

**Create the database**
```
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
```

Fill the database with some data
```
$ php bin/console doctrine:fixtures:load
```

**Build assets**
```sh
$ yarn dev
# or
$ yarn build
```

**Start the project (without docker)**  
You can serve the project using php built-in server:  
```sh
$ php -S 0.0.0.0:8080 -t public
```
or use the server of your choice like LAMP/WAMP/XAMP/MAMP
