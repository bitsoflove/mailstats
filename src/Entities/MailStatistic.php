<?php

namespace BitsOfLove\MailStats\Entities;

use BitsOfLove\MailStats\Entities\Project;
use Illuminate\Database\Eloquent\Model;

class MailStatistic extends Model
{

    protected $fillable = [
        'project_id',
        'service_message_id',
        'recipient',
        'tag',
        'status',
    ];

    /**
     * Reference to the statistic's project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Service id scope
     *
     * @param Builder $query
     * @param string $id
     * @return Builder
     */
    public function scopeService($query, $id)
    {
        return $query->where("service_message_id", $id);
    }

    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }
}