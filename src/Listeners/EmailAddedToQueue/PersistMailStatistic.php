<?php

namespace BitsOfLove\MailStats\Listeners\EmailAddedToQueue;

use BitsOfLove\MailStats\Events\EmailAddedToQueue;
use BitsOfLove\MailStats\Entities\MailStatistic;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PersistMailStatistic
{
    /**
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EmailAddedToQueue $event
     * @return void
     */
    public function handle(EmailAddedToQueue $event)
    {
        // retrieve the data from the event object
        $data = $event->getMail();
        $response = $event->getResponse();

        $tags = $data->getTags();

        if (count($tags) > 1) {
            $tags = json_encode($tags);
        } else {
            $tags = reset($tags);
        }

        $l = MailStatistic::create([
            'recipient' => $data->getTo()->getEmail(),
            'tag' => $tags,
            'status' => 'queued', // default state
            'project_id' => $data->getProject()->id,
            'service_message_id' => $response->http_response_body->id,
            'category_id' => $data->getCategory() ? $data->getCategory()->id : null,
        ]);
    }
}
