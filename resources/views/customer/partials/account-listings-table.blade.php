@if($userListings->count())
    <table class="gk-account-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Price</th>
                <th>Views</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($userListings as $listing)
                <tr>
                    <td>
                        <div style="font-weight:800;">{{ $listing->title }}</div>
                        <div style="color:#6b7280; font-size:0.78rem;">{{ $listing->reference }}@if($listing->location) · {{ $listing->location }}@endif</div>
                    </td>
                    <td><span class="gk-badge gk-badge-muted">{{ $listing->type_label }}</span></td>
                    <td>{{ $listing->price ? '৳' . number_format((float) $listing->price, 0) : '-' }}</td>
                    <td>{{ number_format($listing->views) }}</td>
                    <td><span class="gk-badge {{ $plainStatusClass($listing->status) }}">{{ $listing->status_label }}</span></td>
                    <td style="text-align:right;">
                        <div style="display:flex; justify-content:flex-end; gap:0.4rem;">
                            <form method="POST" action="{{ route('customer.listings.update', $listing) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $listing->status === 'active' ? 'paused' : 'active' }}">
                                <button type="submit" class="gk-account-btn">{{ $listing->status === 'active' ? 'Pause' : 'Activate' }}</button>
                            </form>
                            <form method="POST" action="{{ route('customer.listings.destroy', $listing) }}" onsubmit="return confirm('Delete this listing?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="gk-account-btn" style="color:#be123c;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="gk-empty">
        <div class="gk-empty-icon">🏪</div>
        <div class="gk-empty-title">You have not listed anything yet.</div>
        <p class="gk-empty-text">Publish your first product, garage, driver, rental, or car wash listing using the form above.</p>
    </div>
@endif
