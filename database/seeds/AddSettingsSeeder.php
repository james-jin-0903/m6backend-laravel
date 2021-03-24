<?php
use Illuminate\Database\Seeder;
use App\AppsSettings;

class AddSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $settingsItApp = array(
        array( 'field' => 'wo_request_type', 'value' => 'Appointment', ),
        array( 'field' => 'wo_request_type', 'value' => 'Meeting', ),
        array( 'field' => 'wo_request_type', 'value' => 'Reminder', ),
        array( 'field' => 'wo_request_type', 'value' => 'Request', ),
        array( 'field' => 'wo_request_type', 'value' => 'Task', ),
      );

      $general = AppsSettings::where([ ['field', '=', 'wo_request_type'], ['value', '=', 'General'] ])->first();

      foreach($settingsItApp as $setting){
        AppsSettings::create([
            'field' => $setting['field'],
            'value' => $setting['value'],
            'app_type'=> 'm6works',
            'parent_id' => $general['id']
        ]);
    }
    }
}
