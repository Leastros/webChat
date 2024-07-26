<?php

/**
 * Model reprezentujici zpravy
 */
class MessageModel 
{
    public ?int $id = null;
    public ?ProfileModel $sender = null;
    public ?ProfileModel $recipient = null;
    public ?string $content = null;
    public $timestamp;

    public function GetDate() {
        return date("Y-m-d H:i:s", $this->timestamp);
    }

    public function validate(): bool {
        if (
            $this->sender === null ||
            $this->recipient === null ||
            $this->content=== null
            ) {
                return false;
        }

        if (!preg_match("/^[a-zA-Z0-9?!.,\- ]+$/", $this->content)) {
            return false;
        }
        /*
        */
        return true;
    }

}