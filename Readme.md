# Air Pressure Drop API (APD-API)

## WIP

This Project is still in progress. I'm working on a complete refactoring with a clean architecture and more features.

## Description

This small API allow you to calculate the air drop pressure in air duct network. You can add multiple duct sections and configure each section with its own characteristics. Air characteristics are configurable too.
You must have an account, and create a project to add duct networks and perform duct sections calculation in there.
This project is a PHP fork of an other project of mine developed in Python : [Air pressure drop calculator](https://github.com/LudovicLeBris/Air-pressure-drop-calculator)

## Aeraulic method of calculation

The calculations methods used in this software is strongly inspired by [COSTIC methods](https://www.costic.com).  
The formula used for the linear air pressure drop calculation is the [Colebrook equation](https://www.engineeringtoolbox.com/colebrook-equation-d_1031.html).

## PHP version used

* PHP 8.2

## Main dependencies

* Symfony 6.4
* beberlei/assert
* lexik/jwt-authentication-bundle
* nelmio/cors-bundle
* zircote/swagger-php

## Database

* MySQL CE

## How to launch

The app run into a docker container.
Docker and Docker compose must be installed on host.
Launch the container with ```docker compose up -d``` and wait for the end of the build.
If is the first time you launch the app, the database must be initiated.
An script will create, migrate and populate the database : ```docker exec www bash docker.sh```.

An user is already registered with this credentials :
```json
{
    "email": "john.doe@demo.fr",
    "password": "demoDem0!"
}
```

## Api doc

OpenApi documentation : [https://aeraulic.com/api/V1] or when project is deployed on host [http://localhost:8080]
