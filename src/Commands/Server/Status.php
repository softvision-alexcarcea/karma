<?php

namespace ThinFrame\Karma\Commands\Server;

use ThinFrame\CommandLine\ArgumentsContainer;
use ThinFrame\CommandLine\Commands\AbstractCommand;
use ThinFrame\CommandLine\DependencyInjection\OutputDriverAwareTrait;
use ThinFrame\CommandLine\IO\OutputDriverInterface;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Karma\Helpers\ServerHelper;

/**
 * Class Status
 *
 * @package ThinFrame\Karma\Commands\Server
 * @since   0.2
 */
class Status extends AbstractCommand
{
    use OutputDriverAwareTrait;
    use DispatcherAwareTrait;

    /**
     * Get the argument the will trigger this command
     *
     * @return string
     */
    public function getArgument()
    {
        return 'status';
    }

    /**
     * Get the descriptions for this command
     *
     * @return string[]
     */
    public function getDescriptions()
    {
        return [
            'server status' => 'Check HTTP server status'
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
        if (ServerHelper::isRunning()) {
            $this->outputDriver->send(
                '[format background="black" foreground="green" effects="bold"] The server is running [/format]'
                . PHP_EOL
            );
        } else {
            $this->outputDriver->send(
                '[format background="black" foreground="red" effects="bold"] The server is not running [/format]'
                . PHP_EOL
            );
        }
    }
}