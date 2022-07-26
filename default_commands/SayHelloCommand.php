<?php


namespace ConsoleCommand\default_commands;


use ConsoleCommand\src\Command;

class SayHelloCommand extends Command
{
    public static function getName(): string
    {
        return 'say-hello';
    }

    public static function getDescription(): string
    {
        return 'render string "hello"';
    }

    public function execute()
    {
        $this->print('hello');
    }
}