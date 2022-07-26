<?php


namespace ConsoleCommand\src;


use Exception;

class Kernel
{
    const FILE_CONFIG = 'command.register.php';
    const FOLDER_DEFAULT = 'commands';

    protected array $properties;
    protected string $commandName;
    protected array $config = [];
    protected array $arguments = [];
    protected array $params = [];
    protected array $commands = [];

    public function __construct($properties)
    {
        $this->properties = $properties;
        $this->setConfig();
        $this->autoload($this->config['folder']);
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

    protected function setConfig()
    {
        $path = __DIR__ . '/../../../../' . self::FILE_CONFIG;

        if (file_exists($path)) {
            $this->config = require_once $path;
        } else {
            $this->config['folder'] = self::FOLDER_DEFAULT;
        }

        $defaultCommands = require_once __DIR__ . '/../' . self::FILE_CONFIG;

        $this->config['commands'] = array_merge($this->config['commands'], $defaultCommands);
    }

    protected function print($message)
    {
        echo $message . PHP_EOL;
    }

    protected function autoload($folder)
    {
        spl_autoload_register(function ($class) use ($folder) {

            $prefix = 'Command\\';

            $base_dir = __DIR__ . '/../../../../' . $folder . '/';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relative_class = substr($class, $len);

            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });
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
        $this->commands = $this->config['commands'];
    }

    /**
     * @throws \Exception
     */
    protected function tryExecute(): bool
    {
        foreach ($this->commands as $command) {
            if ($command::getName() === $this->commandName) {
                $commandClass = new $command($this->arguments, $this->params);
                $commandClass->run();
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
