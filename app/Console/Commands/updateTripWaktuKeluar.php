<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trip;

class updateTripWaktuKeluar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trip:updatewaktukeluar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update waktu_keluar pada tabel trip setiap jam 12 malam';

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
        $trips = Trip::where('waktu_keluar', null)->get();

        foreach($trips as $trip){
          $trip->waktu_keluar = date('Y-m-d H:i:s', strtotime($trip->waktu_masuk . '+ 1 minute'));
          $trip->save();
        }

        $this->info('trip waktu keluar updated successfully');
        return 0;
    }
}
