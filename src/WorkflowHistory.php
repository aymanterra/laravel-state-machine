<?php

namespace troojaan\SM;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WorkflowHistory
 * @package App
 * @mixin /Eloquent
 */
class WorkflowHistory extends Model
{
    /**
     * @var string
     */
    protected $table = 'workflow_history';

    /**
     * @var array
     */
    protected $fillable = [
        'model_id',
        'model_type',
        'user_id',
        'workflow_name',
        'comment',
        'current_state'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'current_state' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

}
