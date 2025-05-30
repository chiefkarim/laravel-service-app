<x-mail::message>
    # Status: {{ ucfirst($serviceRequest->status) }}

    @if ($serviceRequest->reply)
        Hello,

        We have an update on your service request:

        {{ $serviceRequest->reply }}

        Thank you for your patience.
    @else
        Hello,

        Your service request status has been updated to **{{ ucfirst($serviceRequest->status) }}**.

        We will keep you informed with more details soon.
    @endif

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
