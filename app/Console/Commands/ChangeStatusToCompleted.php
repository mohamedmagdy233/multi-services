<?php

namespace App\Console\Commands;

use App\Models\BusReservation;
use App\Models\BusTime;
use App\Models\RoomReservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChangeStatusToCompleted extends Command
{

//    to run php artisan :ChangeStatusToCompleted
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ChangeStatusToCompleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' this is for if the time or date for transportation or Residence has passed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->updateRoomReservations();
        $this->updateBusReservations('departure_date', 'departure_bus_time_id', 0);
        $this->updateBusReservations('return_date', 'return_bus_time_id', 1);
    }

    private function updateRoomReservations()
    {
        $roomReservations = RoomReservation::where('process', 1)
            ->where('to', '<', Carbon::now()->format('Y-m-d'))
            ->get();

        foreach ($roomReservations as $reservation) {
            $reservation->update(['process' => 2]);
        }
    }

    private function updateBusReservations($dateField, $timeField, $isDeparture)
    {
        $busReservations = BusReservation::where('process', 1)
            ->where($dateField, '<=', Carbon::now()->format('Y-m-d'))
            ->where('is_departure', $isDeparture)
            ->get();

        $busTimes = BusTime::whereIn('id', $busReservations->pluck($timeField))->get();
        foreach ($busTimes as $busTime) {
            if ($busTime->to_time < Carbon::now()->format('H:i:s')) {
                foreach ($busReservations as $busReservation) {
                    if ($busReservation->$timeField == $busTime->id) {
                        $busReservation->update(['process' => 2]);
                    }
                }
            }
        }
    }






}
