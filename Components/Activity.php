<?php
namespace Pingu\Activity\Components;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Pingu\Activity\Entities\Activity as ActivityModel;
use Pingu\User\Entities\User;

class Activity
{
    protected function shouldLogModel(Model $model)
    {
        return !in_array(get_class($model), config('activity.ignoreModels', []));
    }

    protected function getModelMessage(string $action, Model $model)
    {
        $friendly = $model::friendlyName();
        switch ($action) {
            case 'created':
                return $friendly.' has been created';
            
            case 'updated':
                return $friendly.' has been updated';

            case 'deleted':
                return $friendly.' has been deleted';

            case 'restored':
                return $friendly.' has been restored';

            default:
                return '';
        }
    }

    public function logModel(string $action, Model $model): ?ActivityModel
    {
        \Log::debug($model);
        \Log::debug($this->shouldLogModel($model));
        if ($this->shouldLogModel($model)) {
            return $this->log(
                $this->getModelMessage($action, $model),
                $action,
                json_encode($model->getAllOriginal()),
                json_encode($model->getAllAttributes()),
                get_class($model),
                $model->getKey()
            );
        }
        return null;
    }

    public function log(string $message, string $action = '', string $from = '', string $to = '', string $model = '', string $key = ''): ActivityModel
    {
        return ActivityModel::create([
            'action' => $action,
            'message' => $message,
            'object' => $model,
            'key' => $key,
            'user_id' => Auth::user() ? Auth::user()->id : null,
            'from' => $from,
            'to' => $to
        ]);
    }

    public function purge()
    {
        $time = new \Carbon\Carbon();
        $time->subSeconds(config('activity.lifetime'));
        $activities = ActivityModel::where('created_at','<=',$time)->delete();
        return sizeof($activities);
    }

}