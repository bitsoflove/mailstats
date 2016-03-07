<?php

namespace BitsOfLove\MailStats\Entities;

use BitsOfLove\MailStats\Entities\MailStatistic;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = [
        "name",
        "human_name",
        "recipient_email",
        "recipient_name",
        "sender_email",
        "sender_name",
        "reply_to_email",
        "reply_to_name",
    ];

    /**
     * Reference to the projects mail statistics information
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mailStatistics()
    {
        return $this->hasMany(MailStatistic::class);
    }
}
