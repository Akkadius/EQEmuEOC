# EQEmuEOC

EQEmu EOC - Rapid Development Platform

This platform is designed to help EQEmu Server developers develop content rapidly and mangage their server efficiently.

Tools Documentation: http://wiki.eqemulator.org/p?EQEmu_Operations_Center_for_Development#tools

**Update 1/5/2019** This project is now considered a Legacy project with a Legacy PHP codebase

# Index
[EOC Code and Project Structure](https://github.com/Akkadius/EQEmuEOC/wiki/EOC-Code-and-Project-Structure)

# Dev Environment (Docker)

Assuming you have docker and relatively know what it is, we now have a Docker setup that can get you up and going with relative ease

Clone this project and run the following command

```
cp env-example .env
```

Initialize your Database instance first before bringing everything up, wait for it to initialize and exit. If you end up waiting more than a few minutes its likely already initialized and running so just Ctrl + C out of it

``` 
docker-compose up mariadb
```

Once MariaDB is initialized. Bring up the env with the following comand

```
docker-compose up
``` 

This will build your images and get your environment ready to go

Once you have setup your config below and sourced in a development database you can browse to http://localhost

# Config

```
cp includes/config.example.php includes/config.php
```

# Seeding a Database

Once this is done you'll need to seed a database if you're not going to point to an existing one

First - jump into the workspace container via

```
docker-compose exec workspace bash
```

Then run the following command

``` 
make seed-peq-db
```
