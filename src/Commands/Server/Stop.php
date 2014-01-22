<?php

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Karma\Helpers\ServerHelper;
use ThinFrame\Pcntl\Constants\Signal;
use ThinFrame\Pcntl\Process;

/**
 * Class Stop
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Stop extends AbstractCommand
{
    use OutputDriverAwareTrait;

    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'stop';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'server stop' => 'Stop the HTTP server'
        ];
    }

    /**
     * This method will be called if this command is triggered
     *
     * @param ArgumentsContainer $arguments
     *
     * @return mixed
     */
    public function execute(ArgumentsContainer $arguments)
    {
        if (!ServerHelper::isRunning()) {
            $this->outputDriver->send(
                '[format foreground="white" background="red" effects="bold"] Server is not running [/format]' . PHP_EOL
            );

            return;
        }
        $process = new Process(ServerHelper::getServerPID());
        if ($process->sendSignal(new Signal(Signal::KILL))) {
            $this->outputDriver->send(
                '[format foreground="green" background="black" effects="bold"] Server will stop shortly [/format]' . PHP_EOL
            );
        } else {
            $this->outputDriver->send(
                '[format foreground="white" background="red" effects="bold"] The server is not responding [/format]' . PHP_EOL
            );
        }
    }
}