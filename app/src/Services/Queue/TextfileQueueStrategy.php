<?php
namespace Ansiployer\Services\Queue;

class TextfileQueueStrategy implements IQueueStrategy
{
    const QUEUEFILE_SUFIX = '_queue';
    private $assetsFolder;

    public function __construct(string $assetsFolder)
    {
        $this->assetsFolder = $assetsFolder;
    }

    public function produce(string $name, IQueueMessage $message)
    {
        $filename = $this->assetsFolder . '/' . $name . self::QUEUEFILE_SUFIX;
        $fp = $this->openAndGetLock($filename, 'a');
        fwrite($fp, json_encode($message->getBody())."\n");
        $this->unlockAndClose($fp);
    }

    public function consume(string $name)
    {
        $filename = $this->assetsFolder . '/' . $name . '_queue';
        $firstline = false;
        if (!file_exists($filename)) {
            return null;
        } else {
            $fp = $this->openAndGetLock($filename, 'c+');
            $offset = 0;
            $len = filesize($filename);
            while (($line = fgets($fp)) !== false) {
                if (!$firstline) {
                    $firstline = $line;
                    $offset = strlen($firstline);
                    continue;
                }
                $pos = ftell($fp);
                fseek($fp, $pos-strlen($line)-$offset);
                fwrite($fp, $line);
                fseek($fp, $pos);
            }
            fflush($fp);
            ftruncate($fp, $len-$offset);
            $this->unlockAndClose($fp);
            if (false === $firstline) {
                return null;
            } else {
                return json_decode(str_replace("\n", '', $firstline));
            }
        }
    }

    /**
     * @param $filename
     * @param $openMode
     * @return resource
     */
    private function openAndGetLock($filename, $openMode)
    {
        while (!($fp = fopen($filename, $openMode))) {
            sleep(1);
        }
        while (!flock($fp, LOCK_EX)) {
            sleep(1);
        }
        return $fp;
    }

    /**
     * @param $fp
     */
    private function unlockAndClose($fp)
    {
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}