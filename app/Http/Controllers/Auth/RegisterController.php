<?php

namespace App\Http\Controllers\Auth;

use Aminkhoshzahmat\CountryCode\Enums\CountryType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected string $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(): View
    {
        return view('auth.register', ['countries' => $this->getCountryList()]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'billing_address' => ['required', 'string', 'max:255'],
            'billing_city' => ['required', 'string', 'max:255'],
            'billing_state' => ['required', 'string', 'max:255'],
            'billing_country' => ['required', 'string', 'max:2'],
            'billing_zip_code' => ['required', 'string', 'max:20'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'billing_address' => $data['billing_address'],
            'billing_city' => $data['billing_city'],
            'billing_state' => $data['billing_state'],
            'billing_country' => $data['billing_country'],
            'billing_zip_code' => $data['billing_zip_code'],
        ]);

        return $user;
    }

    private function getCountryList(): array
    {
        $countries = [];
        foreach (CountryType::cases() as $country) {
            $countries[$country->getCode()] = $country->getName();
        }
        asort($countries); // Sort countries alphabetically by name

        return $countries;
    }
}
