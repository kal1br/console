<?php


namespace ConsoleCommand\src;


abstract class Command
{
    protected array $arguments = [];
    protected array $params = [];

    abstract public static function getName(): string;
    abstract public static function getDescription(): string;
    abstract public function execute();

    public function __construct($arguments, $params)
    {
        $this->arguments = $arguments;
        $this->params = $params;
    }

    public function run()
    {
        if ($this->isHelp()) {
            $this->print(static::getDescription());
        } else {
            $this->execute();
        }
    }

    protected function isHelp(): bool
    {
        return in_array('help', $this->arguments);
    }

    protected function print($message)
    {
        echo $message . PHP_EOL;
    }
}
