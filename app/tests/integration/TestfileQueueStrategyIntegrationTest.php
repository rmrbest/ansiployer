<?php
namespace tests\unit;
use Ansiployer\Services\Queue\SimpleStringQueueMessage;
use Ansiployer\Services\Queue\TextfileQueueStrategy;
use PHPUnit_Framework_TestCase;

class TestfileQueueStrategyUnitTest extends PHPUnit_Framework_TestCase
{
    private $fileFixtures = [
        'busy_queue',
        'busy_long_first_line_queue'
    ];

    const FILE_FOLDER = __DIR__ . '/sandbox';

    public function setUp()
    {
        foreach($this->fileFixtures as $filename) {
            copy(__DIR__.'/file_fixtures/'.$filename, __DIR__.'/sandbox/'.$filename);
        }
        parent::setUp();
    }

    public function tearDown()
    {
        $files = glob(self::FILE_FOLDER.'/*');
        foreach($files as $file) {
            if ($file !== self::FILE_FOLDER.'/README.md') {
                unlink($file);
            }
        }
        parent::tearDown();
    }

    /**
     * method consume
     * when called
     * should removeFirstLineFromQueueFile
     */
    public function test_consume_called_removeFirstLineFromQueueFile()
    {
        $this->exerciseAndAssertConsume('busy');
    }

    /**
     * method consume
     * when calledOnAQueueWithVeryLongFirstLine
     * should removeFirstLineFromQueueFile
     */
    public function test_consume_calledOnAQueueWithVeryLongFirstLine_removeFirstLineFromQueueFile()
    {
        $this->exerciseAndAssertConsume('busy_long_first_line');
    }

    /**
     * method produce
     * when called
     * should addALineWithTheJsonVersionOfTheMessage
     */
    public function test_produce_called_addALineWithTheJsonVersionOfTheMessage()
    {
        $this->exerciseAndAssertProduce('busy', "first job\nsecond job\nthird job\nfourth job\nfifth job\n\"hola\"\n", 'hola');
    }

    /**
     * method produce
     * when calledOnAnNonExistentQueue
     * should createFileAndAddLine
     */
    public function test_produce_calledOnAnNonExistentQueue_createFileAndAddLine()
    {
        $this->exerciseAndAssertProduce('nonexistent', "\"hola\"\n", 'hola');

    }

    /**
     * method consume
     * when queueFileIsEmpty
     * should returnNull
     */
    public function test_consume_queueFileIsEmpty_returnNull()
    {
        $sut = new TextfileQueueStrategy(self::FILE_FOLDER);
        self::assertNull($sut->consume('empty'));
    }

    /**
     * method consume
     * when queueFileIsNotEmpty
     * should returnProducedMessage
     */
    public function test_consume_queueFileIsNotEmpty_returnProducedMessage()
    {
        $sut = new TextfileQueueStrategy(self::FILE_FOLDER);
        $queue = 'new';
        $message = 'el mensaje';
        $sut->produce($queue, new SimpleStringQueueMessage($message));
        $actual = $sut->consume($queue);
        self::assertEquals($message, $actual);
    }

    /**
     * @param $queue_name
     */
    private function exerciseAndAssertConsume($queue_name)
    {
        $sut = new TextfileQueueStrategy(self::FILE_FOLDER);
        $sut->consume($queue_name);
        $expected = "second job\nthird job\nfourth job\nfifth job\n";
        self::assertStringEqualsFile(self::FILE_FOLDER . '/' . $queue_name . TextfileQueueStrategy::QUEUEFILE_SUFIX, $expected);
    }

    /**
     * @param $queue_name
     * @param $expected
     * @param $message
     */
    private function exerciseAndAssertProduce($queue_name, $expected, $message)
    {
        $sut = new TextfileQueueStrategy(self::FILE_FOLDER);
        $sut->produce($queue_name, new SimpleStringQueueMessage($message));
        self::assertStringEqualsFile(self::FILE_FOLDER . '/' . $queue_name . TextfileQueueStrategy::QUEUEFILE_SUFIX, $expected);
    }
}