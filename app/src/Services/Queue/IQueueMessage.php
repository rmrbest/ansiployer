<?php
namespace Ansiployer\Services\Queue;

interface IQueueMessage
{
    public function setBody(\JsonSerializable $data);
    public function getBody() : \JsonSerializable;
}