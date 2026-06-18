@extends('layouts.app')

@section('title', 'My Account | ' . \App\Models\Setting::get('site_name', 'Garikothay'))

@push('styles')
<style>
    .gk-account {
        background: #f8fafc;
        min-height: calc(100vh - 190px);
    }

    .gk-account-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .gk-account-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #e11d48, #be123c);
        color: #ffffff;
        padding: 1.5rem;
        box-shadow: 0 18px 38px rgba(190, 18, 60, 0.2);
    }

    .gk-account-avatar {
        display: grid;
        place-items: center;
        width: 4rem;
        height: 4rem;
        flex: 0 0 auto;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(255,255,255,0.18);
        color: #ffffff;
        font-size: 1.4rem;
        font-weight: 900;
        box-shadow: 0 0 0 4px rgba(255,255,255,0.28);
    }

    .gk-account-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gk-account-kicker {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        opacity: 0.78;
    }

    .gk-account-title {
        margin-top: 0.15rem;
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: clamp(1.45rem, 3vw, 2rem);
        font-weight: 900;
        line-height: 1.1;
        text-transform: uppercase;
    }

    .gk-account-email {
        margin-top: 0.25rem;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .gk-account-grid {
        display: grid;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    @media (min-width: 1024px) {
        .gk-account-grid {
            grid-template-columns: 260px minmax(0, 1fr);
        }
    }

    .gk-account-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
    }

    .gk-account-tabs {
        padding: 0.5rem;
    }

    .gk-account-tab {
        display: flex;
        width: 100%;
        align-items: center;
        gap: 0.75rem;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #374151;
        padding: 0.72rem 0.8rem;
        font-size: 0.9rem;
        font-weight: 700;
        text-align: left;
        cursor: pointer;
    }

    .gk-account-tab:hover {
        background: #f3f4f6;
        color: #e11d48;
    }

    .gk-account-tab.is-active {
        background: #e11d48;
        color: #ffffff;
        box-shadow: 0 10px 22px rgba(225, 29, 72, 0.18);
    }

    .gk-help-card {
        margin-top: 1rem;
        padding: 1rem;
    }

    .gk-help-card p {
        margin-top: 0.35rem;
        color: #6b7280;
        font-size: 0.78rem;
        line-height: 1.55;
    }

    .gk-stat-grid {
        display: grid;
        gap: 1rem;
    }

    @media (min-width: 640px) {
        .gk-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1280px) {
        .gk-stat-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    .gk-stat-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        padding: 1rem;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
    }

    .gk-stat-label {
        color: #6b7280;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .gk-stat-value {
        margin-top: 0.2rem;
        color: #111827;
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
    }

    .gk-stat-icon {
        display: grid;
        place-items: center;
        width: 2.6rem;
        height: 2.6rem;
        border-radius: 999px;
        background: #fee2e2;
        color: #e11d48;
        font-size: 1.25rem;
    }

    .gk-panel {
        display: none;
    }

    .gk-panel.is-active {
        display: block;
    }

    .gk-section-card {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
    }

    .gk-section-card + .gk-section-card {
        margin-top: 1.5rem;
    }

    .gk-section-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        border-bottom: 1px solid #e5e7eb;
        padding: 0.85rem 1.15rem;
    }

    .gk-section-card-title {
        font-family: 'Oswald', 'Inter', sans-serif;
        font-size: 1.12rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .gk-section-card-body {
        padding: 1rem;
        overflow-x: auto;
    }

    .gk-account-table {
        width: 100%;
        min-width: 680px;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .gk-account-table th {
        color: #6b7280;
        font-size: 0.72rem;
        font-weight: 900;
        letter-spacing: 0.05em;
        padding: 0.65rem 0.75rem;
        text-align: left;
        text-transform: uppercase;
    }

    .gk-account-table td {
        border-top: 1px solid #f1f5f9;
        padding: 0.85rem 0.75rem;
        vertical-align: middle;
    }

    .gk-account-table tr:hover td {
        background: #f8fafc;
    }

    .gk-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.25rem 0.6rem;
        font-size: 0.72rem;
        font-weight: 800;
    }

    .gk-badge-success { background: #dcfce7; color: #15803d; }
    .gk-badge-warning { background: #fef3c7; color: #b45309; }
    .gk-badge-danger { background: #ffe4e6; color: #be123c; }
    .gk-badge-info { background: #dbeafe; color: #1d4ed8; }
    .gk-badge-muted { background: #f3f4f6; color: #4b5563; }

    .gk-empty {
        padding: 3rem 1rem;
        text-align: center;
    }

    .gk-empty-icon {
        display: grid;
        place-items: center;
        width: 3.4rem;
        height: 3.4rem;
        margin: 0 auto 0.8rem;
        border-radius: 999px;
        background: #fee2e2;
        color: #e11d48;
        font-size: 1.5rem;
    }

    .gk-empty-title {
        color: #111827;
        font-weight: 900;
    }

    .gk-empty-text {
        max-width: 420px;
        margin: 0.35rem auto 0;
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.65;
    }

    .gk-account-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #111827;
        padding: 0.58rem 0.9rem;
        font-size: 0.82rem;
        font-weight: 800;
        text-decoration: none;
        transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    }

    .gk-account-btn:hover {
        border-color: #e11d48;
        color: #e11d48;
    }

    .gk-account-btn-primary {
        border-color: #e11d48;
        background: #e11d48;
        color: #ffffff;
    }

    .gk-account-btn-primary:hover {
        background: #be123c;
        color: #ffffff;
    }

    .gk-profile-grid {
        display: grid;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .gk-profile-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .gk-profile-field {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.9rem;
    }

    .gk-profile-field span {
        display: block;
        color: #6b7280;
        font-size: 0.72rem;
        font-weight: 900;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .gk-profile-field strong {
        display: block;
        margin-top: 0.25rem;
        color: #111827;
        font-weight: 800;
    }

    .gk-form-grid {
        display: grid;
        gap: 0.9rem;
    }

    @media (min-width: 768px) {
        .gk-form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    .gk-field label {
        display: block;
        margin-bottom: 0.35rem;
        color: #374151;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .gk-field input,
    .gk-field select,
    .gk-field textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #ffffff;
        padding: 0.68rem 0.8rem;
        color: #111827;
        font-size: 0.9rem;
        outline: 0;
    }

    .gk-field textarea {
        min-height: 92px;
        resize: vertical;
    }

    .gk-field input:focus,
    .gk-field select:focus,
    .gk-field textarea:focus {
        border-color: #e11d48;
        box-shadow: 0 0 0 3px rgba(225,29,72,0.12);
    }

    .gk-alert {
        border-radius: 12px;
        margin-bottom: 1rem;
        padding: 0.85rem 1rem;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .gk-alert-success {
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #15803d;
    }

    .gk-alert-danger {
        border: 1px solid #fecdd3;
        background: #fff1f2;
        color: #be123c;
    }

    @media (max-width: 640px) {
        .gk-account-hero {
            align-items: flex-start;
            flex-direction: column;
        }

        .gk-account-hero > .gk-account-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $ordersCount = $user->orders()->count();
    $serviceBookings = $serviceBookings ?? collect();
    $servicesCount = $serviceBookings->count();
    $spent = $user->total_spent;
    $defaultAddress = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();

    $statusClass = function ($order) {
        $color = $order->status->color();
        return match ($color) {
            'success' => 'gk-badge-success',
            'warning' => 'gk-badge-warning',
            'danger' => 'gk-badge-danger',
            'info', 'primary' => 'gk-badge-info',
            default => 'gk-badge-muted',
        };
    };

    $plainStatusClass = fn (?string $status) => match ($status) {
        'completed', 'active' => 'gk-badge-success',
        'confirmed' => 'gk-badge-info',
        'pending', 'paused' => 'gk-badge-warning',
        'cancelled', 'sold' => 'gk-badge-danger',
        default => 'gk-badge-muted',
    };
@endphp

<div class="gk-account" x-data="{ tab: 'dashboard' }">
    <div class="gk-account-container">
        @if(session('success'))
            <div class="gk-alert gk-alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="gk-alert gk-alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="gk-account-hero">
            <div style="display:flex; align-items:center; gap:1rem;">
                <div class="gk-account-avatar">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <div class="gk-account-kicker">Welcome back</div>
                    <h1 class="gk-account-title">{{ $user->name }}</h1>
                    <div class="gk-account-email">{{ $user->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="gk-account-btn">↪ Logout</button>
            </form>
        </div>

        <div class="gk-account-grid">
            <aside>
                <div class="gk-account-card gk-account-tabs">
                    @foreach([
                        ['id' => 'dashboard', 'icon' => '👤', 'label' => 'Dashboard'],
                        ['id' => 'orders', 'icon' => '📦', 'label' => 'My Orders'],
                        ['id' => 'services', 'icon' => '🔧', 'label' => 'My Services'],
                        ['id' => 'profile', 'icon' => '⚙', 'label' => 'Profile & Settings'],
                    ] as $item)
                        <button type="button" class="gk-account-tab" :class="{ 'is-active': tab === '{{ $item['id'] }}' }" @click="tab = '{{ $item['id'] }}'">
                            <span>{{ $item['icon'] }}</span>
                            <span>{{ $item['label'] }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="gk-account-card gk-help-card">
                    <strong>Need help?</strong>
                    <p>Contact our support team for any account, order, or service issue.</p>
                    <a href="{{ route('page.contact') }}" class="gk-account-btn" style="width:100%; margin-top:0.85rem;">Contact Support</a>
                </div>
            </aside>

            <main>
                <section class="gk-panel" :class="{ 'is-active': tab === 'dashboard' }">
                    <div class="gk-stat-grid">
                        <div class="gk-stat-card">
                            <div>
                                <div class="gk-stat-label">Orders</div>
                                <div class="gk-stat-value">{{ $ordersCount }}</div>
                            </div>
                            <div class="gk-stat-icon">📦</div>
                        </div>
                        <div class="gk-stat-card">
                            <div>
                                <div class="gk-stat-label">Services</div>
                                <div class="gk-stat-value">{{ $servicesCount }}</div>
                            </div>
                            <div class="gk-stat-icon">🔧</div>
                        </div>
                        <div class="gk-stat-card">
                            <div>
                                <div class="gk-stat-label">Total Spent</div>
                                <div class="gk-stat-value">৳{{ number_format($spent, 0) }}</div>
                            </div>
                            <div class="gk-stat-icon">৳</div>
                        </div>
                    </div>

                    <div class="gk-section-card" style="margin-top:1.5rem;">
                        <div class="gk-section-card-head">
                            <h2 class="gk-section-card-title">Recent Orders</h2>
                            <button type="button" class="gk-account-btn" @click="tab = 'orders'">View all</button>
                        </div>
                        <div class="gk-section-card-body">
                            @include('customer.partials.account-orders-table', ['orders' => $recentOrders, 'statusClass' => $statusClass])
                        </div>
                    </div>

                    <div class="gk-section-card">
                        <div class="gk-section-card-head">
                            <h2 class="gk-section-card-title">Recent Service Bookings</h2>
                            <button type="button" class="gk-account-btn" @click="tab = 'services'">View all</button>
                        </div>
                        <div class="gk-section-card-body">
                            @include('customer.partials.account-services-table', ['serviceBookings' => $serviceBookings->take(3), 'plainStatusClass' => $plainStatusClass])
                        </div>
                    </div>
                </section>

                <section class="gk-panel" :class="{ 'is-active': tab === 'orders' }">
                    <div class="gk-section-card">
                        <div class="gk-section-card-head">
                            <h2 class="gk-section-card-title">My Orders</h2>
                            <a href="{{ route('shop.index') }}" class="gk-account-btn gk-account-btn-primary">Shop Now</a>
                        </div>
                        <div class="gk-section-card-body">
                            @include('customer.partials.account-orders-table', ['orders' => $user->orders()->latest()->take(12)->get(), 'statusClass' => $statusClass])
                        </div>
                    </div>
                </section>

                <section class="gk-panel" :class="{ 'is-active': tab === 'services' }">
                    <div class="gk-section-card">
                        <div class="gk-section-card-head">
                            <h2 class="gk-section-card-title">My Service Bookings</h2>
                            <span class="gk-account-btn gk-account-btn-primary">Book Service</span>
                        </div>
                        <div class="gk-section-card-body">
                            <form method="POST" action="{{ route('customer.services.store') }}" class="gk-form-grid" style="margin-bottom:1.2rem;">
                                @csrf
                                <div class="gk-field">
                                    <label>Service Type</label>
                                    <select name="service_type" required>
                                        <option value="">Select service</option>
                                        <option value="Garage Service">Garage Service</option>
                                        <option value="Car Wash">Car Wash</option>
                                        <option value="Driver">Driver</option>
                                        <option value="GPS Installation">GPS Installation</option>
                                        <option value="Fuel Support">Fuel Support</option>
                                    </select>
                                </div>
                                <div class="gk-field">
                                    <label>Preferred Provider</label>
                                    <input type="text" name="provider" placeholder="Optional">
                                </div>
                                <div class="gk-field">
                                    <label>Preferred Date</label>
                                    <input type="date" name="booking_date">
                                </div>
                                <div class="gk-field">
                                    <label>Estimated Amount</label>
                                    <input type="number" name="amount" min="0" step="1" placeholder="৳">
                                </div>
                                <div class="gk-field" style="grid-column:1 / -1;">
                                    <label>Location</label>
                                    <input type="text" name="location" placeholder="Area, city">
                                </div>
                                <div class="gk-field" style="grid-column:1 / -1;">
                                    <label>Notes</label>
                                    <textarea name="notes" placeholder="Tell us what you need"></textarea>
                                </div>
                                <div style="grid-column:1 / -1;">
                                    <button type="submit" class="gk-account-btn gk-account-btn-primary">Submit Booking Request</button>
                                </div>
                            </form>
                            @include('customer.partials.account-services-table', ['serviceBookings' => $serviceBookings, 'plainStatusClass' => $plainStatusClass])
                        </div>
                    </div>
                </section>

                <section class="gk-panel" :class="{ 'is-active': tab === 'profile' }">
                    <div class="gk-section-card">
                        <div class="gk-section-card-head">
                            <h2 class="gk-section-card-title">Profile & Settings</h2>
                            <a href="{{ route('customer.profile') }}" class="gk-account-btn gk-account-btn-primary">Edit Profile</a>
                        </div>
                        <div class="gk-section-card-body">
                            <div class="gk-profile-grid">
                                <div class="gk-profile-field">
                                    <span>Full Name</span>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                                <div class="gk-profile-field">
                                    <span>Email</span>
                                    <strong>{{ $user->email }}</strong>
                                </div>
                                <div class="gk-profile-field">
                                    <span>Phone</span>
                                    <strong>{{ $user->phone ?: 'Not added yet' }}</strong>
                                </div>
                                <div class="gk-profile-field">
                                    <span>Default Address</span>
                                    <strong>
                                        @if($defaultAddress)
                                            {{ $defaultAddress->address_line_1 }}, {{ $defaultAddress->city }}
                                        @else
                                            Not added yet
                                        @endif
                                    </strong>
                                </div>
                            </div>
                            <div style="display:flex; flex-wrap:wrap; gap:0.75rem; margin-top:1rem;">
                                <a href="{{ route('customer.addresses') }}" class="gk-account-btn">Manage Addresses</a>
                                <a href="{{ route('wishlist.index') }}" class="gk-account-btn">Open Wishlist</a>
                                <a href="{{ route('customer.profile') }}" class="gk-account-btn">Change Password</a>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</div>
@endsection
