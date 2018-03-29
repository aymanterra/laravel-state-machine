<?php
/**
 * Created by PhpStorm.
 * User: Kareem Adel
 * Date: 26-Mar-18
 * Time: 2:30 PM
 */

namespace troojaan\SM;


use Illuminate\Support\Facades\Auth;

/**
 * Class workflowBase
 * @package App
 */
abstract class WorkflowBase
{
    /**
     * @param $order
     * @param $event
     * @return mixed
     */
    abstract protected function handler($object, $event);

    /**
     * @param $object
     * @param $event
     * @param $workflowName
     * @return mixed
     */
    public function fire($object, $event, $workflowName)
    {
        $result = $this->handler($object, $event);
        return $result;

    }

    /**
     * @param $object
     * @param $event
     * @param $workflowName
     */
    public function addHistory($object, $event, $workflowName)
    {
        $user = Auth::user();

        $object->workflow_history()->create([
            'user_id' => $user->id,
            'current_state' => $object->state, //$event->getConfig()['to'],
            'comment' => request('comment') ?: '',
            'workflow_name' => $workflowName,
        ]);
    }
}