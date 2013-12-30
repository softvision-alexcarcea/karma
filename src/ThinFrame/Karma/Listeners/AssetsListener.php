<?php
namespace ThinFrame\Karma\Listeners;

use React\EventLoop\LoopInterface;
use React\Stream\Stream;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Applications\DependencyInjection\ApplicationAwareInterface;
use ThinFrame\Events\ListenerInterface;
use ThinFrame\Http\Utils\MimeTypeGuesser;
use ThinFrame\Server\Events\HttpRequestEvent;

/**
 * Class AssetsListener
 *
 * @package ThinFrame\Karma\Listeners
 * @since   0.2
 */
class AssetsListener implements ListenerInterface, ApplicationAwareInterface
{
    /**
     * @var AbstractApplication
     */
    private $application;

    /**
     * @var LoopInterface
     */
    private $serverLoop;

    /**
     * Constructor
     *
     * @param LoopInterface $serverLoop
     */
    public function __construct(LoopInterface $serverLoop)
    {
        $this->serverLoop = $serverLoop;
    }

    /**
     * Attach application to current instance
     *
     * @param AbstractApplication $application
     *
     * @return mixed
     */
    public function setApplication(AbstractApplication $application)
    {
        $this->application = $application;
    }


    /**
     * Get event mappings ["event"=>["method"=>"methodName","priority"=>1]]
     *
     * @return array
     */
    public function getEventMappings()
    {
        return [
            HttpRequestEvent::EVENT_ID => [
                'method' => 'onRequest'
            ]
        ];
    }

    /**
     * Handle http request
     *
     * @param HttpRequestEvent $event
     */
    public function onRequest(HttpRequestEvent $event)
    {
        $filePath = $event->getRequest()->getPath();
        foreach ($this->application->getMetadata() as $name => $metadata) {
            /* @var $metadata \PhpCollection\Map */
            if ($metadata->containsKey('assets')) {
                $assumedFilePath = $metadata->get('application_path')->get() . DIRECTORY_SEPARATOR . $metadata->get(
                        'assets'
                    )->get() . DIRECTORY_SEPARATOR . $filePath;
                $assumedFilePath = realpath($assumedFilePath);
                if (file_exists($assumedFilePath) && is_file($assumedFilePath)) {
                    $event->stopPropagation();
                    $fileStream = new Stream(fopen($assumedFilePath, 'r'), $this->serverLoop);

                    $event->getResponse()->getHeaders()->set(
                        'Content-Type',
                        MimeTypeGuesser::getMimeType($assumedFilePath)
                    );
                    $event->getResponse()->dispatchHeaders();
                    $fileStream->pipe($event->getResponse()->getReactResponse());

                    $fileStream->on('end', 'gc_collect_cycles');

                    gc_collect_cycles();
                }
            }
        }
    }
}
