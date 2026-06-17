@if($serviceBookings->count())
    <table class="gk-account-table">
        <thead>
            <tr>
                <th>Ref</th>
                <th>Service</th>
                <th>Provider</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($serviceBookings as $booking)
                <tr>
                    <td style="font-weight:800;">{{ $booking->reference }}</td>
                    <td>{{ $booking->service_type }}</td>
                    <td style="color:#6b7280;">{{ $booking->provider ?: 'Any trusted provider' }}</td>
                    <td>{{ $booking->booking_date?->format('M d, Y') ?? 'Flexible' }}</td>
                    <td>{{ $booking->amount ? '৳' . number_format((float) $booking->amount, 0) : '-' }}</td>
                    <td><span class="gk-badge {{ $plainStatusClass($booking->status) }}">{{ $booking->status_label }}</span></td>
                    <td style="text-align:right;">
                        @if(! in_array($booking->status, ['completed', 'cancelled'], true))
                            <form method="POST" action="{{ route('customer.services.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking request?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="gk-account-btn">Cancel</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="gk-empty">
        <div class="gk-empty-icon">🔧</div>
        <div class="gk-empty-title">No service bookings yet.</div>
        <p class="gk-empty-text">Submit a booking request above and it will appear here instantly.</p>
    </div>
@endif
