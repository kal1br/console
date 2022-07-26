# Console app

## Install
```` bash
$ composer require kal1br/console
````

## Usage

### Commands registration:

1) Create a folder and command classes in it (with namespace Command)
```` php
<?php
namespace Command;

class TestCommand extends \ConsoleCommand\src\Command
{

    public static function getName(): string
    {
        return 'test_command';
    }

    public static function getDescription(): string
    {
        return 'some description';
    }

    public function execute()
    {
        $this->print('print test');
    }
}
````
2) Create command.register.php file in project root (the folder key must contain the path to the folder with commands)
```` php
<?php

use Command\TestCommand;

return [
    'folder' => 'commands',
    'commands' => [
        TestCommand::class,
    ],
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