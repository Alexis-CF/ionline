<?php

namespace App\Notifications\Welfare\Benefits;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Welfare\Benefits\Request;
use App\Models\Parameters\Parameter;

class RequestCreate extends Notification implements ShouldQueue
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
        // si es de HAH, solo se devuelve data de ese establecimiento. Si es SST, se devuelve SST y HETG. Si no es ninguna de los 2, se devuelve esa info.
        $applicant = $this->request->applicant;
        $establishments = [$applicant->establishment_id];
        if($applicant->establishment_id == 41){
            $establishments = [41];
        }elseif($applicant->establishment_id == 38){
            $establishments = [1,38];
        }

        $cc_mails = explode(', ',Parameter::where('module', 'welfare: beneficios')
                                            ->where('parameter', 'correos solicitudes')
                                            ->whereIn('establishment_id', $establishments)
                                            ->first()
                                            ->value);
        $appUrl = config('app.url');

        return (new MailMessage)
                ->level('info')
                ->replyTo($cc_mails)
                ->subject('Creación solicitud de beneficio')
                ->line('Se informa creación de solicitud de beneficio: ' . $this->request->subsidy->name . '.')
                ->line('La solicitud se encuentra en proceso de revisión')
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
