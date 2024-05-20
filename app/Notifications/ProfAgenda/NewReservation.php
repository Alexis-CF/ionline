<?php

namespace App\Notifications\ProfAgenda;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Models\ProfAgenda\OpenHour;

class NewReservation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $openHour;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(OpenHour $openHour)
    {
        $this->openHour = $openHour;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->level('info')
            ->subject('UST - Cita con '. $this->openHour->activityType->name . ' el ' . $this->openHour->start_date->format('Y-m-d'))
            ->greeting('Hola ' . $notifiable->shortName)
            ->line('Se ha reservado una hora de: ' . $this->openHour->activityType->name . " - #" . $this->openHour->id)
            ->line('Con el profesional: ' . $this->openHour->profesional->shortName)
            ->line('La reserva se encuentra realizada para: ' . $this->openHour->start_date->format('Y-m-d'). ' a las: ' . $this->openHour->start_date->format('H:i'))
            ->line('Se solicita llegar puntual a su hora.')
            
            // ->action('Texto del boton', route('requirements.show', $this->sgr->id) )
            ->line(' Si no puede asistir, rogamos contactar a la Unidad de Salud del Trabajador para reagendar o cancelar su hora.')
            ->line('N° Telefono: 575767 / +57 2 405766')
            ->line('Correo electrónico: unidadstrabajador@gmail.com')
            ->line(new HtmlString('Este correo se genera de forma automática, <b>FAVOR NO RESPONDER</b>.'))
            ->salutation('Saludos cordiales.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
