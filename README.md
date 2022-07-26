# Console app

## Install
```` bash
$ composer require kal1br/console
````

## Usage

### Commands registration:

Create command.register.php file in project root
```` php
<?php

use ConsoleCommand\default_commands\NameCommand;
use ConsoleCommand\default_commands\SayHelloCommand;

return [
    SayHelloCommand::class,
    NameCommand::class
];
````

### Execute commands

Commands are entered in the format:
```` bash
$ php ./vendor/kal1br/console/app.php {arg1,arg2} [param={value1,value2}]
````
List available commands:
```` bash
$ php ./vendor/kal1br/console/app.php
````