<x-mail::message>
    # Status: {{ ucfirst($serviceRequest->status) }}

    Hello {{ $serviceRequest->name }},
    @if ($serviceRequest->reply)
        We have an update on your service request:

        {{ $serviceRequest->reply }}

        Thank you for your patience.
    @else
        Your service request status has been updated to {{ ucfirst($serviceRequest->status) }}.

        We will keep you informed with more details soon.
    @endif

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
