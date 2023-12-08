# Air Pressure Drop API

## WIP

This Project is still in progress. I'm working on a complete refactoring with a clean architecture and more features.

## Description

This small API allow you to calculate the air drop pressure in air duct network. You can add multiple duct sections and configure each section with its own characteristics. Air characteristics are configurable too.

## Aeraulic method of calculation

he calculations methods used in this software is strongly inspired by [COSTIC methods](https://www.costic.com).  
The formula used for the linear air pressure drop calculation is the [Colebrook equation](https://www.engineeringtoolbox.com/colebrook-equation-d_1031.html).

## PHP version used

* PHP 8.2

## Dependencies

* Symfony 6.4

## Database

* MariaDB

## Endpoints

TODO

## Requests and responses

Json format

## Values required for this API

* Flow rate in the duct section (unit: cubic metre per hour - m³/h)
* Shape of the duct section (circular or rectangular)
* Material of the duct section (galvanised steel per example)
* Diameter* of the duct section (if shape is circular) (unit: millimeter - mm)
* Width and height of the duct section (if shape is rectangular) (unit: millimeter - mm)
* Length of the duct section (unit: meter - m)
* List of singularities** present in the duct section and there number
* Additional air pressure drop (optionnal) (unit: Pascal - Pa)
* Temperature in the duct section (optionnal, set to 20°C by default)
* Altitude of the installation (optionnal, set to 0m below sea by default)

## How to launch

TODO
