@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Purchase Support Time</div>

                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <h5 class="alert-heading">Important Information</h5>
                            <p class="mb-0">Before purchasing support time, please note:</p>
                            <ul class="mb-0">
                                <li>Support time is billed in whole hours.</li>
                                <li>The minimum purchase is 1 hour.</li>
                                <li><strong>This purchase is non-refundable.</strong> Please ensure you need the support time before proceeding.</li>
                                <li>Support time expires 12 months from the date of purchase.</li>
                                <li>Our support team is available Monday to Friday, 9 AM to 5 PM GMT.</li>
                                <li>Emergency support outside these hours may be subject to additional charges.</li>
                            </ul>
                        </div>

                        <form method="POST" action="{{ route('support.purchase.initiate') }}">
                            @csrf
                            <div class="form-group row mb-3">
                                <label for="quantity" class="col-md-4 col-form-label text-md-right">Quantity (hours)</label>
                                <div class="col-md-6">
                                    <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity', 1) }}" required autofocus min="1">
                                    @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="support_type" class="col-md-4 col-form-label text-md-right">Type of Support</label>
                                <div class="col-md-6">
                                    <select id="support_type" name="support_type" class="form-select @error('support_type') is-invalid @enderror" required>
                                        <option value="">Select support type</option>
                                        <option value="technical" {{ old('support_type') == 'technical' ? 'selected' : '' }}>Technical Issues</option>
                                        <option value="install" {{ old('support_type') == 'install' ? 'selected' : '' }}>Install Vanguard</option>
                                        <option value="other" {{ old('support_type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('support_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="details" class="col-md-4 col-form-label text-md-right">Additional Details (Optional)</label>
                                <div class="col-md-6">
                                    <textarea id="details" name="details" class="form-control @error('details') is-invalid @enderror" rows="3">{{ old('details') }}</textarea>
                                    @error('details')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-md-4 col-form-label text-md-right">Price per hour</label>
                                <div class="col-md-6">
                                    <p class="form-control-plaintext">£{{ number_format($unitPrice, 2) }}</p>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-md-4 col-form-label text-md-right">Total Price</label>
                                <div class="col-md-6">
                                    <p class="form-control-plaintext font-weight-bold" id="totalPrice">£{{ number_format($unitPrice, 2) }}</p>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" required {{ old('terms') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="terms">
                                            I confirm that I have read and agree to the <a href="{{ route('terms') }}" target="_blank">Terms of Service</a> and understand that this purchase is non-refundable.
                                        </label>
                                        @error('terms')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-vanguard" id="purchaseButton" disabled>
                                        Purchase Support Time
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
            const quantityInput = document.getElementById('quantity');
            const totalPriceElement = document.getElementById('totalPrice');
            const termsCheckbox = document.getElementById('terms');
            const purchaseButton = document.getElementById('purchaseButton');
            const unitPrice = {{ $unitPrice }};

            function formatCurrency(amount) {
                const formattedNumber = new Intl.NumberFormat('en-GB', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(amount);
                return '£' + formattedNumber;
            }

            function updateTotalPrice() {
                const quantity = parseInt(quantityInput.value);
                const totalPrice = quantity * unitPrice;
                totalPriceElement.textContent = formatCurrency(totalPrice);
            }

            function updatePurchaseButtonState() {
                purchaseButton.disabled = !termsCheckbox.checked;
            }

            quantityInput.addEventListener('input', updateTotalPrice);
            termsCheckbox.addEventListener('change', updatePurchaseButtonState);

            updateTotalPrice();
            updatePurchaseButtonState();
        });
    </script>
@endpush
