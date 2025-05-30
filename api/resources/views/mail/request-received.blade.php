<x-mail::message>
    # Service Request Received

    Hello {{ $serviceRequest->name }},

    We have received your request for the service: {{ $serviceRequest->service->name }}.

    Here are the details you provided:
    {{ $serviceRequest->details }}

    Our team is currently reviewing your request and will get back to you as soon as possible.

    Thanks
    {{ config('app.name') }}
</x-mail::message>
