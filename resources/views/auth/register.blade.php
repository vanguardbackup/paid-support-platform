@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_address" class="col-md-4 col-form-label text-md-end">{{ __('Billing Address') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_address" type="text" class="form-control @error('billing_address') is-invalid @enderror" name="billing_address" value="{{ old('billing_address') }}" required autocomplete="address-line1">

                                    @error('billing_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_country" class="col-md-4 col-form-label text-md-end">{{ __('Country') }}</label>

                                <div class="col-md-6">
                                    <select id="billing_country" class="form-select @error('billing_country') is-invalid @enderror" name="billing_country" required autocomplete="country-name">
                                        <option value="">Select a country</option>
                                        @foreach ($countries as $code => $name)
                                            <option value="{{ $code }}" {{ old('billing_country') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>

                                    @error('billing_country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_city" class="col-md-4 col-form-label text-md-end">{{ __('City') }}</label>

                                <div class="col-md-6">
                                    <select id="billing_city" class="form-select @error('billing_city') is-invalid @enderror" name="billing_city" required autocomplete="address-level2">
                                        <option value="">Select a city</option>
                                    </select>

                                    @error('billing_city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_state" class="col-md-4 col-form-label text-md-end">{{ __('State') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_state" type="text" class="form-control @error('billing_state') is-invalid @enderror" name="billing_state" value="{{ old('billing_state') }}" required autocomplete="address-level1">

                                    @error('billing_state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_zip_code" class="col-md-4 col-form-label text-md-end">{{ __('Zip Code') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_zip_code" type="text" class="form-control @error('billing_zip_code') is-invalid @enderror" name="billing_zip_code" value="{{ old('billing_zip_code') }}" required autocomplete="postal-code">

                                    @error('billing_zip_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-vanguard">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countrySelect = document.getElementById('billing_country');
            const citySelect = document.getElementById('billing_city');

            countrySelect.addEventListener('change', function() {
                const selectedCountry = this.value;
                citySelect.innerHTML = '<option value="">Select a city</option>';

                if (selectedCountry) {
                    fetch(`/api/cities/${selectedCountry}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (Array.isArray(data)) {
                                data.forEach(city => {
                                    const option = document.createElement('option');
                                    option.value = city;
                                    option.textContent = city;
                                    citySelect.appendChild(option);
                                });
                            } else if (data.message) {
                                throw new Error(data.message);
                            } else {
                                throw new Error('Unexpected data format');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching cities:', error);
                            citySelect.innerHTML = '<option value="">Error loading cities: ' + error.message + '</option>';
                        });
                }
            });
        });
    </script>
@endpush
