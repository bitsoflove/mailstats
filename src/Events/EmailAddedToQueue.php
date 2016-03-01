<?php

namespace BitsOfLove\MailStats\Events;

use App\Events\Event;
use BitsOfLove\MailStats\SendMail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EmailAddedToQueue extends Event
{
    use SerializesModels;

    /**
     * @var SendMail
     */
    protected $mail;

    /**
     * @var \StdClass
     */
    protected $response;

    /**
     * Create a new event instance.
     *
     * @param SendMail $mail
     * @param $response
     */
    public function __construct(SendMail $mail, $response)
    {
        $this->mail = $mail;
        $this->response = $response;
    }

    /**
     * @return SendMail
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @return \StdClass
     */
    public function getResponse()
    {
        return $this->response;
    }


    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
