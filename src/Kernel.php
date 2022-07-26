<?php


namespace ConsoleCommand\src;


use Exception;

class Kernel
{
    protected array $properties;
    protected string $commandName;
    protected array $arguments = [];
    protected array $params = [];
    protected array $commands = [];

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function run()
    {
        try {
            $this->registerCommands();

            if (!$this->hasCommandName()) {
                $this->printCommands();
            } else {
                $this->processParameters();
                $this->tryExecute();
            }
        } catch (Exception $e) {
            $this->print($e->getMessage());
        }
    }

    protected function print($message)
    {
        echo $message . PHP_EOL;
    }

    protected function printCommands()
    {
        foreach ($this->commands as $command) {
            $this->print($command::getName() . ' - ' . $command::getDescription());
        }
    }

    protected function processParameters()
    {
        $properties = $this->properties;

        $this->commandName = $properties[1];

        if ($this->hasParameters()) {
            for ($i = 2; $i < count($properties); $i++) {
                if ($this->isParameter($properties[$i])) {
                    $this->setParameter($properties[$i]);
                } else {
                    $this->setArgument($properties[$i]);
                }
            }
        }
    }

    protected function registerCommands()
    {
        $this->commands = require_once __DIR__ . '/../command.register.php';
    }

    /**
     * @throws \Exception
     */
    protected function tryExecute(): bool
    {
        foreach ($this->commands as $command) {
            if ($command::getName() === $this->commandName) {
                $commandClass = new $command($this->arguments, $this->params);
                $commandClass->execute();
                return true;
            }
        }

        throw new Exception('Command not found');
    }

    protected function hasCommandName(): bool
    {
        return count($this->properties) >= 2;
    }

    protected function hasParameters(): bool
    {
        return count($this->properties) > 2;
    }

    protected function isArgument($property): bool|int
    {
        return preg_match('/^{(.+)}$/ui', $property);
    }

    protected function isParameter($property): bool|int
    {
        return preg_match('/^\[(.+)=(.+)]$/ui', $property);
    }

    protected function setArgument($property)
    {
        if ($this->isArgument($property)) {
            preg_match('/^{(.+)}$/ui', $property, $matches);
            $this->arguments[] = $matches[1];
        } else {
            $this->arguments[] = $property;
        }
    }

    protected function setParameter($property)
    {
        preg_match('/^\[(.+)=(.+)]$/ui', $property, $matches);
        $this->params[$matches[1]][] = $matches[2];
    }
}
