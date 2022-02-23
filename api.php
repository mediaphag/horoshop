<?php

class Application
{
    /**
     * @var [string]callable
     */
    private $commands = [];

    /**
     * @throws Exception
     */
    public function executeCommand(string $command): string
    {
        $args = explode(' ', $command);
        $name = $args[0];

        if (!array_key_exists($name,  $this->commands)) {
            throw new Exception('Command not exist');
        }

        return $this->commands[$name]($args);
    }

    /**
     * @throws Exception
     */
    public function addCommand(string $name, callable $executor): self
    {
        if (array_key_exists($name, $this->commands)) {
            throw new Exception("Command $name already exist");
        }

        $this->commands[$name] = $executor;

        return $this;
    }
}

class DogCommand
{
    public $dogs = [
        'shiba-inu' => ['woof! woof!', 'woof! woof!'],
        'mops' => ['woof! woof!', '**whining piteously'],
        'dachshund' => ['woof! woof!', 'woof! woof!'],
        'plush-labrador' => ['**no reaction', '**no reaction'],
        'rubber-dachshund' => ['beep! beep!', '**no reaction']
    ];

    public function execute(array $args): string
    {
        if (count($args) !== 2) {
            throw new Exception('Unexpected quantity of command');
        }
        $dog = $args[0];
        $action = $args[1];

        if ($action !== 'sound' && $action !== 'hunt') {
            throw new Exception('Unexpected argument');
        }

        return $this->dogs[$dog][($action == 'sound') ? 0 : 1];
    }
}

$application = new Application();

$dogCommand = new DogCommand();

foreach ($dogCommand->dogs as $dog => $value) {
    $application->addCommand($dog, [$dogCommand, 'execute']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = json_decode(file_get_contents('php://input'), true);
    $command = $json['command'];
    $outputJson = [];
    try {
        $outputJson['output'] = $application->executeCommand($command);
    } catch(Exception $e) {
        $outputJson['output'] = $e->getMessage();
    }

    header('Content-Type: application/json', true, 200);
    echo json_encode($outputJson);
}

