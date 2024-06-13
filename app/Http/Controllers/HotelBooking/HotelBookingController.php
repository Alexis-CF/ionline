<?php

namespace App\Http\Controllers\HotelBooking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

use App\Models\ClCommune;
use App\Models\HotelBooking\Hotel;
use App\Models\HotelBooking\Room;
use App\Models\HotelBooking\RoomBookingConfiguration;
use App\Models\HotelBooking\RoomBooking;
use App\Models\HotelBooking\RoomBookingFile;
use Illuminate\Support\Facades\Storage;

use App\Notifications\HotelBooking\BookingConfirmation;
use App\Notifications\HotelBooking\BookingCancelation;

class HotelBookingController extends Controller
{
    public function index(Request $request){
        $hotels = Hotel::whereHas("rooms", function($q) {
                            $q->whereHas("bookingConfigurations", function($q) {
                                $q;
                            });
                        })
                        ->whereHas("rooms", function($q) {
                            $q->where('status', 1);
                        })
                        ->with([
                            'rooms' => function ($query) {
                                $query->with([
                                    'bookingConfigurations' => function ($query) {
                                        $query->where('end_date','>=',now());
                                    }
                                ]);
                            }
                        ])
                        ->get();
        // dd($hotels);
        $communes = ClCommune::whereIn('id',$hotels->pluck('commune_id')->toArray())->get();
        return view('hotel_booking.home',compact('communes','hotels','request'));
    }

    public function search_booking(Request $request){
        
        // $commune_id = $request->commune_id;
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $diff = $start_date->diffInDays($end_date);

        // se valida que no haya excedido el limite de reservas en el año
        $user_id = auth()->user()->id;
        $start_year = $start_date->year;

        $count = RoomBooking::where('user_id', $user_id)
                            ->whereYear('start_date', $start_year)
                            ->count();

        // Si el número de reservas del usuario en el año es menor o igual a 2, puedes proceder.
        if(!(auth()->user()->can('be god') || auth()->user()->can('welfare: hotel booking administrator'))){
            if ($count >= 2) {
                session()->flash('warning', 'Has excedido el límite de reservas para este año.');
                return redirect()->back();
            }
        }

        $hotels = Hotel::all();
        $communes = ClCommune::all();
        $count = 0;
        $found_rooms = [];

        //validación rango de fecha de búsqueda
        if($start_date->format('Y-m-d') < now()->format('Y-m-d')){
            session()->flash('warning', 'La fechas deben ser superior al día actual.');
            return view('hotel_booking.home',compact('communes','hotels','found_rooms', 'request'));
        }

        if($start_date >= $end_date){
            session()->flash('warning', 'La fecha de ingreso no puede ser igual o superior a la fecha de salida.');
            return view('hotel_booking.home',compact('communes','hotels','found_rooms', 'request'));
        }
        
        // encuentra todas las configuraciones que tengan disponibilidad 
        $bookingConfigurations = RoomBookingConfiguration::where('end_date','>=',now())
                                        ->whereHas("room", function($q) {
                                            $q->where('status', 1);
                                        })
                                        ->get();

        foreach($bookingConfigurations as $bookingConfiguration){

            // Si lo que se intenta reservar está en un día no configurado, no permitirá la reserva
            $flag_not_configurated = 0;
            foreach (CarbonPeriod::create($start_date, '1 day', $end_date) as $day) {
                if(( $bookingConfiguration->sunday && $day->dayOfWeek==0) || ($bookingConfiguration->monday && $day->dayOfWeek==1) || 
                    ($bookingConfiguration->tuesday && $day->dayOfWeek==2) || ($bookingConfiguration->wednesday && $day->dayOfWeek==3) || 
                    ($bookingConfiguration->thursday && $day->dayOfWeek==4) || ($bookingConfiguration->friday && $day->dayOfWeek==5) ||
                    ($bookingConfiguration->saturday && $day->dayOfWeek==6)){
                        
                }else{
                    $flag_not_configurated = 1;
                }
            }

            $room_capacity = $bookingConfiguration->room->single_bed + ($bookingConfiguration->room->double_bed*2);
            $max_days_alowed = $bookingConfiguration->room->max_days_avaliable;
            
            if($start_date >= $bookingConfiguration->start_date && $start_date <= $bookingConfiguration->end_date 
            && $end_date >= $bookingConfiguration->start_date && $end_date <= $bookingConfiguration->end_date
            // && $guest_number <= $room_capacity
            && $diff <= $max_days_alowed
            && $flag_not_configurated == 0){

                $roomBookings = RoomBooking::where('room_id',$bookingConfiguration->room_id)
                                        ->whereIn('status',['Confirmado','Bloqueado'])
                                        ->get();

                // se verifica si dia actuales son compatible con días reservados/bloqueados
                $flag = 0;
                foreach($roomBookings as $key => $roomBooking){
                    
                    if($start_date <= $roomBooking->start_date && $end_date >= $roomBooking->end_date){
                        $flag = 1;
                        continue; //se rompe la busqueda en esta configuración
                    }
                    if($start_date > $roomBooking->start_date && $start_date < $roomBooking->end_date){
                        $flag = 1;
                        continue; //se rompe la busqueda en esta configuración
                    }
                    if($end_date > $roomBooking->start_date && $end_date < $roomBooking->end_date){
                        $flag = 1;
                        continue; //se rompe la busqueda en esta configuración
                    }
                    
                    if($start_date->format('Y-m-d') == $roomBooking->start_date->format('Y-m-d') && $end_date->format('Y-m-d') == $roomBooking->end_date->format('Y-m-d')){
                        $flag = 1;
                        continue; //se rompe la busqueda en esta configuración
                    }
                }

                // No hubo interrupciones, por ende se agrega habitación al array
                if($flag == 0){
                    array_push($found_rooms, $bookingConfiguration->room);
                }
            }
        }
        
        return view('hotel_booking.home',compact('communes','hotels','found_rooms', 'request'));
    }

    public function my_bookings(){
        $roomBookings = RoomBooking::where('user_id',auth()->user()->id)->paginate(50);
        return view('hotel_booking.my_bookings',compact('roomBookings'));
    }

    public function booking_admin(Request $request){
        $room = null;
        $roomBookings = null;

        // Validar si el parámetro 'status' existe en la solicitud
        $statuses = $request->has('status') ? $request->status : ['Reservado'];

        if($request->room_id){
            $room = Room::find($request->room_id);
            $roomBookings = RoomBooking::where('room_id',$request->room_id)
                                        ->whereIn('status',$statuses)
                                        ->paginate(50);
        }
        
        return view('hotel_booking.bookings_admin',compact('roomBookings','room'));
    }

    public function booking_cancelation(Request $request, RoomBooking $roomBooking){
        $roomBooking->status = "Cancelado";
        $roomBooking->cancelation_observation = $request->cancelation_observation;
        $roomBooking->save();

        if($roomBooking->user){
            if($roomBooking->user->email != null){
                // Utilizando Notify 
                $roomBooking->user->notify(new BookingCancelation($roomBooking));
            } 
        }

        session()->flash('success', 'Se modificó el estado de la reserva.');
        return redirect()->back();
    }

    public function booking_confirmation(RoomBooking $roomBooking){
        // verifica si tiene asociado archivos
        if($roomBooking->payment_type == "Transferencia"){
            if(count($roomBooking->files)==0){
                session()->flash('warning', 'No es posible confirmar, el funcionario debe subir primero el comprobante de transferencia.');
                return redirect()->back();
            }
        }

        $roomBooking->status = "Confirmado";
        $roomBooking->save();

        if($roomBooking->user){
            if($roomBooking->user->email != null){
                // Utilizando Notify 
                $roomBooking->user->notify(new BookingConfirmation($roomBooking));
            } 
        }

        session()->flash('success', 'Se modificó el estado de la reserva.');
        return redirect()->back();
    }

    public function download(RoomBookingFile $file)
    {
        if(Storage::disk('gcs')->exists($file->file)){
            return Storage::disk('gcs')->response($file->file, mb_convert_encoding($file->name,'ASCII'));
        }else{
            return redirect()->back()->with('warning', 'El archivo no se ha encontrado.');
        }  
    }
}
