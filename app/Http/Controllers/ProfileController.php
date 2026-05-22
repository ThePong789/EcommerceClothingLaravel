<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('shippingAddresses', 'orders');
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'nullable|string',
            'last_name'  => 'nullable|string',
            'username'   => 'required|unique:users,username,' . $user->user_id . ',user_id',
        ]);

        $user->update($request->only('first_name', 'last_name', 'username'));
        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password changed.');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'full_name'    => 'required',
            'phone_number' => 'required',
            'address'      => 'required',
            'province'     => 'required',
        ]);

        Shipping::create(
            array_merge(['user_id' => Auth::id()],
            $request->only('full_name', 'phone_number', 'address', 'province', 'city', 'postal_code'))
        );
        return back()->with('success', 'Address added.');
    }

    public function deleteAddress($id)
    {
        Shipping::where('user_id', Auth::id())->findOrFail($id)->delete();
        return back()->with('success', 'Address deleted.');
    }
}
