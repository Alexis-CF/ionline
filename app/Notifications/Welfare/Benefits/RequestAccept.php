<?php

namespace App\Notifications\Welfare\Benefits;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Welfare\Benefits\Request;
use App\Models\Parameters\Parameter;

class RequestAccept extends Notification implements ShouldQueue
{
    use Queueable;

    protected $request;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
        $cc_mails = explode(', ', Parameter::get('welfare: beneficios','correos solicitudes'));
        $appUrl = config('app.url');

        return (new MailMessage)
                ->level('info')
                ->replyTo($cc_mails)
                ->subject('Se acepta solicitud de beneficio')
                ->line('Se acepta solicitud de beneficio: ' . $this->request->subsidy->name . '.')
                ->line('Una vez que se procese el pago, se informará a través de este medio.')
                ->action('Revisa tus solicitudes aquí', $appUrl . 'welfare/benefits/requests')
                ->cc($cc_mails)
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
