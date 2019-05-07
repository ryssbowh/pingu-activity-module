<?php
namespace Pingu\Activity\Components;

use Illuminate\Database\Eloquent\Model;

use Pingu\Activity\Entities\Activity as ActivityModel;
use Auth;

class Activity
{

	public function log(string $action, Model $model)
	{
		ActivityModel::create([
			'action' => $action,
			'object' => get_class($model),
			'key' => $model->getKey(),
			'user_id' => Auth::user() ? Auth::user()->id : null,
			'from' => json_encode($model->getOriginal()),
			'to' => json_encode($model->getAttributes())
		]);
	}

	public function purge()
	{
		$time = new \Carbon\Carbon();
		$time->subSeconds(config('activity.lifetime'));
		$activities = ActivityModel::where('created_at','<=',$time)->delete();
	}

}