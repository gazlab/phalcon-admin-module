<?php

namespace Gazlab;

use Phalcon\Mvc\Model\Behavior;
use Phalcon\Mvc\Model\BehaviorInterface;

class LogActivities extends Behavior implements BehaviorInterface
{
    public function notify($eventType, $model)
    {
        switch ($eventType) {

            case 'afterCreate':
            case 'afterUpdate':
            case 'afterDelete':

                $di = \Phalcon\DI::getDefault();
                $session = $di->get('session');
                $flash = $di->get('flash');

                if ($session->has('uId')) {
                    $logActivity = new \Gazlab\Admin\Models\LogActivity();
                    $logActivity->user_id = $session->get('uId');
                    $logActivity->event_type = $eventType;
                    $logActivity->table_name = $model->getSource();
                    $logActivity->row_id = $model->id;
                    if (!$logActivity->save()) {
                        $flash->warning('Something wrong when insert log activity');
                    }
                }
                break;

            default:
                /* ignore the rest of events */
        }
    }
}
