<?php
namespace Ansiployer\Services\Queue;

class JsonSerializableString implements \JsonSerializable
{
    private $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}

class SimpleStringQueueMessage implements IQueueMessage
{
    private $body;
    public function __construct(string $body)
    {
        $this->setBody(new JsonSerializableString($body));
    }

    public function setBody(\JsonSerializable $data)
    {
        $this->body = $data;
    }

    public function getBody() : \JsonSerializable
    {
        return $this->body;
    }
}