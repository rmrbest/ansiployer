<?php
namespace tests\integration;

use PHPUnit_Framework_TestCase;

class IntegrationTestBase extends PHPUnit_Framework_TestCase
{
    const FILE_FOLDER = __DIR__ . '/sandbox';

    public function setUp()
    {
        $filefixtures = $this->getFileFixtures();
        foreach ($filefixtures as $filename) {
            copy(__DIR__ . '/file_fixtures/' . $filename, IntegrationTestBase::FILE_FOLDER . '/' . $filename);
        }

        $folderfixtures = $this->getFolderFixtures();
        foreach ($folderfixtures as $folder) {
            $origin = __DIR__ . '/file_fixtures/' .$folder;
            $destination = IntegrationTestBase::FILE_FOLDER . '/' . $folder;
            shell_exec("cp -r $origin $destination");
        }
        parent::setUp();
    }

    public function tearDown()
    {
        $dirs = glob(IntegrationTestBase::FILE_FOLDER . '/*', GLOB_ONLYDIR);
        foreach ($dirs as $folder) {
            shell_exec("rm -rf $folder");
        }

        $files = glob(IntegrationTestBase::FILE_FOLDER . '/*');
        foreach ($files as $file) {
            if ($file !== IntegrationTestBase::FILE_FOLDER . '/README.md') {
                unlink($file);
            }
        }
        parent::tearDown();
    }

    protected function getFileFixtures()
    {
        return [];
    }

    protected function getFolderFixtures()
    {
        return [];
    }
}