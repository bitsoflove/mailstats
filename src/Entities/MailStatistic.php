<?php

namespace BitsOfLove\MailStats\Entities;

use BitsOfLove\MailStats\Entities\Category;
use BitsOfLove\MailStats\Entities\Project;
use Illuminate\Database\Eloquent\Model;

class MailStatistic extends Model
{

    protected $fillable = [
        'project_id',
        'category_id',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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

    /**
     * Order by created at
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }

    /**
     * Add a group by scope to the query
     *
     * @param Builder $query
     * @param string $column
     * @return Builder
     */
    public function scopeGroupedBy($query, $column = "service_message_id")
    {
        return $query->groupBy($column);
    }

    /**
     *
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNewestGrouped($query)
    {
        // @todo: find a better way of doing this
        $newest = \DB::select("select max(id) as id from mail_statistics group by service_message_id");
        $newest = collect($newest)->pluck('id');

        return $query->whereIn('id', $newest->toArray())->newest();
    }
}
