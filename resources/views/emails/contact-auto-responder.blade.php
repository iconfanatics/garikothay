<x-transactional-email>
    <x-slot:purpose>✉️ Support Ticket Received</x-slot:purpose>
    <x-slot:greeting>Hello, {{ $customerName }}! 👋</x-slot:greeting>

    Thank you for reaching out to us. This is an automated response to confirm that we have received your message regarding **"{{ $messageSubject }}"**.

    Our support team will review your message and get back to you within 24 hours. If you have any additional information to add, simply reply to this email.

    <x-slot:reference>
        Reference ID: {{ $ticketNumber }}
        Status: Open
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('d M Y, h:i A') }} (GMT+6)
    </x-slot:reference>

    <x-slot:nextStep>
        We'll keep you updated as your request is processed.
    </x-slot:nextStep>
</x-transactional-email>
