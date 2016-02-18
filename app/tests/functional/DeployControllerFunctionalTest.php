<?php
namespace tests\functional;

use Ansiployer\Services\Queue\TextfileQueueStrategy;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;

class DeployControllerFunctionalTest extends PHPUnit_Framework_TestCase
{
    const URL = 'http://localhost:3000/deploy';
    const TEST_QUEUE = 'testQ';
    /** @var  Client */
    private $httpClient;



    public function setUp()
    {
        $this->httpClient = new Client();
        try {
            $this->getRequest('/');
        } catch (ConnectException $e) {
            self::markTestSkipped('The webserver is not running. You should execute the docker container to make this tests work');
        }
        parent::setUp();
    }

    public function tearDown()
    {
        shell_exec('sudo docker exec deployment rm /assets/'.self::TEST_QUEUE.TextfileQueueStrategy::QUEUEFILE_SUFIX);
        parent::tearDown();
    }

    /**
     * method make
     * when calledWithProperRequest
     * should returnOKAndStatus200
     */
    public function test_make_calledWithProperRequest_returnOKAndStatus200()
    {
        $branch = 'master';
        $response = $this->postRequest('/', self::TEST_QUEUE, $branch);
        $body = (string)$response->getBody();
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('{"status":"QUEUED"}', $body);
        self::assertStringEqualsFileInContainer('/assets/'. self::TEST_QUEUE .TextfileQueueStrategy::QUEUEFILE_SUFIX, "\"master\"\n");
    }

    private function getRequest($method) : ResponseInterface
    {
        return $this->httpClient->get(self::URL.$method);
    }

    private function postRequest($method, $environment, $branch) : ResponseInterface
    {
        return $this->httpClient->post(self::URL.$method, [
            'form_params' => [
                'environment' => $environment,
                'version' => $branch
            ]
        ]);
    }

    private static function assertStringEqualsFileInContainer(string $file, string $string)
    {
        $file_contents = shell_exec('sudo docker exec deployment cat '.$file);
        self::assertEquals($string, $file_contents);
    }

}