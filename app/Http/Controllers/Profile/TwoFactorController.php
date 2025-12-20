<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function index()
    {

        $user = auth()->user();

        $has_two_factor = !is_null($user->two_factor_confirmed_at);

        if($has_two_factor) {
            $recovery_codes = $user->recoveryCodes();
            $qr_code_svg = null;
        } elseif(session('status') === 'two-factor-authentication-enabled') {
            $qr_code_svg = $user->twoFactorQrCodeSvg();
            $recovery_codes = null;
        } else {
            $qr_code_svg = null;
            $recovery_codes = null;
        }

        return inertia('Account/TwoFactor', [
            'has_two_factor' => $has_two_factor,
            'recovery_codes' => $recovery_codes,
            'qr_code_svg' => $qr_code_svg,
        ]);

    }
    public function store(Request $request)
    {
    }

    public function destroy(User $two_factor)
    {
    }
}
