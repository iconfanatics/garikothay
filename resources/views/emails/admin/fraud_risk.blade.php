<x-admin-transactional-email>
    <x-slot:purpose>
        🚨 Fraud Risk Detected
    </x-slot>

    <x-slot:greeting>
        Hello, {{ $notifiable->name ?? 'Admin' }}! 👋
    </x-slot>

    <p style="margin-top: 0;">This is an automated notification regarding <strong>Fraud Risk Detected</strong>.</p>
    
    <div style="background-color: #f1f5f9; padding: 20px; border-radius: 8px; margin: 24px 0;">
        <h3 style="margin-top: 0; color: #0f172a; font-size: 16px;">Details</h3>
        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
            @foreach($data as $key => $value)
            <tr>
                <td style="padding: 4px 0; color: #475569;">{{ ucwords(str_replace('_', ' ', $key)) }}:</td>
                <td style="padding: 4px 0; font-weight: 600; text-align: right; color: #0f172a;">{{ is_array($value) ? json_encode($value) : $value }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <x-slot:reference>
        Reference ID: {{ $data['reference_id'] ?? 'SYS-' . rand(1000, 9999) }}<br>
        Date & Time: {{ now()->timezone('Asia/Dhaka')->format('M d, Y h:i A') }} (GMT+6)
    </x-slot>

    <x-slot:actionRequired>
        Please review this event in the Admin Panel to ensure all necessary actions are taken.
    </x-slot>
</x-admin-transactional-email>