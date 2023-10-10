<?php
// To be run via cron every 24 hours
// /path/to/artisan pullStaff >> /path/to/logfile 2>&1

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Staff;
use Illuminate\Support\Facades\File;

class PullStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pullStaff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull staff data from API and update table/csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Initial assumption here is if the cron is handling running
        // the command every 24h, then can just pull from api and update
        // table/csv, otherwise would use laravel scheduler. See README.MD for more details. 

        // Change limit to 100 (10 pages, with 10 entries each, means they have about 100 entries
        // max), easier to just set limit to 100 for single page to grab all data.  If they added
        // cap to limit and we wanted all data, then would just use a loop to iterate through each page
        
        $maxcount = 100;
        $url = "https://61f07509732d93001778ea7d.mockapi.io/api/v1/user/users?page=1&limit=" . $maxcount;
        $json = json_decode(file_get_contents($url), true);

        foreach ($json as $payload) {

            // Pop into database, update where we already have value, otherwise insert
            Staff::updateOrInsert(
                ['id' => $payload['id']], 
                [
                    'createdAt' => $payload['createdAt'],
                    'first_name' => $payload['first_name'],
                    'last_name' => $payload['last_name'],
                    'address' => $payload['address'],
                    'job_title' => $payload['job_title']
                ]
            );

        }

        // Pull all of our data and put into CSV
        $staffData = Staff::all();
        $csvFilePath = storage_path('app/staff.csv');
        File::put($csvFilePath, "id,createdAt,first_name,last_name,address,job_title\n");
        $file = fopen($csvFilePath, 'w');
        foreach ($staffData as $staffEntry) {
            fputcsv($file, $staffEntry->toArray());
        }
        fclose($file);

        $this->info("Staff data imported, updated, and saved to staff.csv successfully!");
        return 0;
    }
}