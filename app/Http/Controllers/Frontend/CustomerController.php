<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Address;
use App\Models\ServiceBooking;
use App\Models\UserListing;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Rules\BdPhone;

class CustomerController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function dashboard(): View
    {
        $user = auth()->user();
        return view('customer.dashboard', [
            'recentOrders' => $this->orderRepository->getByUser($user->id, 5),
            'serviceBookings' => $user->serviceBookings()->latest()->get(),
        ]);
    }

    public function storeServiceBooking(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_type' => ['required', 'string', 'max:80'],
            'provider' => ['nullable', 'string', 'max:120'],
            'booking_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:180'],
            'amount' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        auth()->user()->serviceBookings()->create([
            ...$validated,
            'reference' => $this->nextReference(ServiceBooking::class, 'SVC'),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Service booking request submitted.');
    }

    public function cancelServiceBooking(ServiceBooking $serviceBooking): RedirectResponse
    {
        abort_if($serviceBooking->user_id !== auth()->id(), 403);

        $serviceBooking->update(['status' => 'cancelled']);

        return back()->with('success', 'Service booking cancelled.');
    }

    public function storeListing(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'type' => ['required', 'in:product,garage,driver,carwash,rental'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'location' => ['nullable', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1200'],
        ]);

        auth()->user()->listings()->create([
            ...$validated,
            'reference' => $this->nextReference(UserListing::class, 'LST'),
            'status' => 'active',
        ]);

        return back()->with('success', 'Listing published.');
    }

    public function updateListing(Request $request, UserListing $listing): RedirectResponse
    {
        abort_if($listing->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:active,paused,sold'],
        ]);

        $listing->update($validated);

        return back()->with('success', 'Listing status updated.');
    }

    public function destroyListing(UserListing $listing): RedirectResponse
    {
        abort_if($listing->user_id !== auth()->id(), 403);

        $listing->delete();

        return back()->with('success', 'Listing removed.');
    }



    public function orderShow(string $orderNumber): View
    {
        $order = auth()->user()->orders()
            ->with(['items.product', 'items.variant', 'payment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('customer.order-show', compact('order'));
    }

    public function profile(): View
    {
        return view('customer.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        auth()->user()->update(['password' => Hash::make($request->validated('password'))]);
        return back()->with('success', 'Password changed successfully.');
    }

    public function addresses(): View
    {
        return view('customer.addresses', ['addresses' => auth()->user()->addresses]);
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => 'required|string',
            'full_name' => 'required|string|max:255',
            'phone' => ['required', 'string', new BdPhone()],
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        auth()->user()->addresses()->create($validated);

        return back()->with('success', 'Address added successfully.');
    }

    public function updateAddress(Request $request, Address $address): RedirectResponse
    {
        abort_if($address->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'label' => 'required|string',
            'full_name' => 'required|string|max:255',
            'phone' => ['required', 'string', new BdPhone()],
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        $address->update($validated);

        return back()->with('success', 'Address updated successfully.');
    }

    public function destroyAddress(Address $address): RedirectResponse
    {
        abort_if($address->user_id !== auth()->id(), 403);
        $address->delete();
        return back()->with('success', 'Address removed successfully.');
    }

    private function nextReference(string $modelClass, string $prefix): string
    {
        do {
            $reference = $prefix . '-' . now()->format('ymd') . '-' . Str::upper(Str::random(4));
        } while ($modelClass::query()->where('reference', $reference)->exists());

        return $reference;
    }
}
