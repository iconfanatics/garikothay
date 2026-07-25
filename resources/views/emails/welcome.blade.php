<x-mail::message>
# Welcome to {{ config('app.name') }}, {{ $user->name }}!

We're excited to have you on board. {{ config('app.name') }} is your one-stop destination for everything you need.

You can now log in to your account to:
- Track your orders
- Update your profile and addresses
- Save your favorite products

<x-mail::button :url="route('login')">
Visit Your Account
</x-mail::button>

If you have any questions, feel free to reply to this email.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
