<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogAllActivity
{
    use LogsActivity;
    public static function bootLogAllActivity()
    {
        static::updating(function ($model) {
           if ($model->skipLog) {
                // Desliga o log temporariamente
                $model->disableLogging();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['updated_at', 'created_at','password','remember_token','id']) // Ignorar campos automáticos
            ->useLogName(class_basename($this)); // Nome do log baseado na classe
    }
}
