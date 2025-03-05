@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h4 class="fw-bold mb-0">Purchase Support Time</h4>
                    </div>

                    <div class="card-body p-4">
                        <!-- Important Information Card -->
                        <div class="alert alert-info border-0 shadow-sm mb-4 position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 h-100 d-flex align-items-center ps-3">
                                <i class="bi bi-info-circle-fill text-primary fs-1 opacity-25"></i>
                            </div>
                            <div class="ps-5">
                                <h5 class="alert-heading fw-bold">Important Information</h5>
                                <p class="mb-2">Before purchasing support time, please note:</p>
                                <ul class="mb-0">
                                    <li class="mb-1">Support time is billed in whole hours.</li>
                                    <li class="mb-1">The minimum purchase is 1 hour.</li>
                                    <li class="mb-1"><strong>This purchase is non-refundable.</strong> Please ensure you need the support time before proceeding.</li>
                                    <li class="mb-1">Support time expires 12 months from the date of purchase.</li>
                                    <li class="mb-1">Our support team is available Monday to Friday, 9 AM to 5 PM GMT.</li>
                                    <li>Emergency support outside these hours may be subject to additional charges.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Purchase Form -->
                        <form method="POST" action="{{ route('support.purchase.initiate') }}">
                            @csrf
                            <!-- Support Time Selection -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="fw-bold mb-3">Support Details</h5>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label fw-medium">Quantity (hours)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-clock"></i></span>
                                        <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity', 1) }}" required autofocus min="1">
                                        @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="support_type" class="form-label fw-medium">Type of Support</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-tag"></i></span>
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

                                <div class="col-12 mb-3">
                                    <label for="details" class="form-label fw-medium">Additional Details (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-file-text"></i></span>
                                        <textarea id="details" name="details" class="form-control @error('details') is-invalid @enderror" rows="3" placeholder="Please provide any additional information about your support needs">{{ old('details') }}</textarea>
                                        @error('details')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Price Summary Card -->
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body p-3">
                                    <h5 class="fw-bold mb-3">Order Summary</h5>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Price per hour:</span>
                                        <span>£{{ number_format($unitPrice, 2) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Total Price:</span>
                                        <span class="fs-4 fw-bold text-primary" id="totalPrice">£{{ number_format($unitPrice, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
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

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4" id="purchaseButton" disabled>
                                    <i class="bi bi-credit-card me-2"></i>
                                    Purchase Support Time
                                </button>
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
                const quantity = parseInt(quantityInput.value) || 1;

                // Ensure minimum quantity is 1
                if (quantity < 1) {
                    quantityInput.value = 1;
                }

                const totalPrice = (parseInt(quantityInput.value) || 1) * unitPrice;
                totalPriceElement.textContent = formatCurrency(totalPrice);
            }

            function updatePurchaseButtonState() {
                purchaseButton.disabled = !termsCheckbox.checked;
            }

            quantityInput.addEventListener('input', updateTotalPrice);
            termsCheckbox.addEventListener('change', updatePurchaseButtonState);

            // Initialize
            updateTotalPrice();
            updatePurchaseButtonState();
        });
    </script>
@endpush
