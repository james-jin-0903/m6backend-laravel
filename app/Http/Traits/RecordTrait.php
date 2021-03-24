<?php

namespace App\Http\Traits;

use App\RecordsCount;
use Carbon\Carbon;

trait RecordTrait {
    public function getLatestRecordNumber($appId, $prefix) {

        $res = RecordsCount::where([ ['app_id', '=', $appId], ['year', '=', Carbon::now()->year] ])->get();
        $count = !count($res) ? str_pad(1, 8, '0', STR_PAD_LEFT) : str_pad($res[0]-> count + 1, 8, '0', STR_PAD_LEFT);
        
        return $prefix . '#' . $count . '-' . Carbon::now()->year;
    }
}