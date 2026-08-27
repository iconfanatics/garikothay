<x-transactional-email>
    <x-slot:purpose>👋 Welcome to Garikothay</x-slot:purpose>
    <x-slot:greeting>Hello, {{ $user->name }}! 👋</x-slot:greeting>

    We're excited to have you on board. {{ config('app.name') }} is your one-stop destination for everything you need.

    You can now log in to your account to:
    - Track your orders
    - Update your profile and addresses
    - Save your favorite products

    <x-slot:reference>
        Reference ID: ACCT-{{ $user->id }}
        Status: Account Created
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('d M Y, h:i A') }} (GMT+6)
    </x-slot:reference>

    <x-slot:cta>
        <x-mail::button :url="route('login')">
            Manage Account
        </x-mail::button>
    </x-slot:cta>

    <x-slot:nextStep>
        Explore our wide range of products and services.
    </x-slot:nextStep>
</x-transactional-email>
