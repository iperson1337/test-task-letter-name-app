@component('mail::message')
# Ваша заявка на буквенное имя была отклонена.
<h4>По причине:</h4>
<p>{{ $application->comment }}</p>
@endcomponent
