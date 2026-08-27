<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
**Every Journey Deserves Trust.**<br><br>
Thank you for choosing Garikothay.com.<br>
We appreciate your trust and look forward to serving you again.<br><br>
— Team Garikothay.com<br><br>
Garikothay.com<br>
Dhaka, Bangladesh<br><br>
© {{ date('Y') }} Garikothay.com. All Rights Reserved.
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
