<?php


namespace ConsoleCommand\default_commands;


use ConsoleCommand\src\Command;

class NameCommand extends Command
{

    public static function getName(): string
    {
        return 'command_name';
    }

    public static function getDescription(): string
    {
        return 'prints all entered arguments and options';
    }

    public function execute()
    {
        $this->print('');
        $this->print('Called command: ' . static::getName());
        $this->print('');
        $this->print('Arguments:');
        foreach ($this->arguments as $argument) {
            $this->print('  -  ' . $argument);
        }
        $this->print('Options:');
        foreach ($this->params as $key => $param) {
            $this->print('  -  ' . $key);
            foreach ($param as $item) {
                $this->print('        -  ' . $item);
            }
        }
    }
}