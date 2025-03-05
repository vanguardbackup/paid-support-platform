@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row">
            <!-- Support Time Summary Card -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="d-flex flex-column align-items-center">
                            <div class="mb-3">
                                <i class="bi bi-clock-history text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Support Balance</h4>
                            <div class="d-inline-block bg-light rounded-pill px-4 py-2 mb-3">
                                <span class="h2 fw-bold mb-0 text-primary">{{ $user->support_time_balance }}</span>
                                <span class="text-muted">{{ trans_choice('hour|hours', $user->support_time_balance) }}</span>
                            </div>
                            <a href="{{ route('support.purchase') }}" class="btn btn-primary btn-lg px-4 py-2 rounded-pill fw-bold">
                                Purchase Support Time
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <h4 class="fw-bold">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-plus me-3 text-primary" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h5 class="mb-1 fw-bold">Schedule Support</h5>
                                                <p class="mb-0 small text-muted">Book a time with our support team</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="stretched-link"></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-text me-3 text-primary" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h5 class="mb-1 fw-bold">Documentation</h5>
                                                <p class="mb-0 small text-muted">Access guides and resources</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="stretched-link"></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-chat-square-dots me-3 text-primary" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h5 class="mb-1 fw-bold">Contact Support</h5>
                                                <p class="mb-0 small text-muted">Submit a new support request</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="stretched-link"></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-question-circle me-3 text-primary" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h5 class="mb-1 fw-bold">View FAQ</h5>
                                                <p class="mb-0 small text-muted">Find answers to common questions</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="stretched-link" data-bs-toggle="collapse" data-bs-target="#faqSection" aria-expanded="false"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Support Purchases Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h4 class="fw-bold mb-0">Active Support Purchases</h4>
                <button class="btn btn-sm btn-outline-primary rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#activePurchasesCollapse" aria-expanded="true">
                    <i class="bi bi-chevron-up"></i>
                </button>
            </div>
            <div class="collapse show" id="activePurchasesCollapse">
                <div class="card-body p-0">
                    @if ($supportTimePurchases->reject->isExpired()->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-clock text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">No Active Purchases</h5>
                            <p class="text-muted mb-0">You don't have any active support time purchases.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Type</th>
                                    <th class="border-0">Price</th>
                                    <th class="border-0">Expires</th>
                                    <th class="border-0 text-end">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($supportTimePurchases->reject->isExpired() as $purchase)
                                    <tr>
                                        <td>{{ $purchase->created_at->toFormattedDateString() }}</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ trans_choice(':count hour|:count hours', $purchase->quantity, ['count' => $purchase->quantity]) }}
                                            </span>
                                        </td>
                                        <td>{{ $purchase->formatted_support_type }}</td>
                                        <td>{{ $purchase->formatted_amount }}</td>
                                        <td>
                                            @php
                                                $expiryDate = $purchase->created_at->addYear();
                                                $daysLeft = now()->diffInDays($expiryDate, false);
                                            @endphp

                                            @if ($daysLeft < 30)
                                                <span class="badge bg-warning text-dark">
                                                    Expires in {{ $daysLeft }} days
                                                </span>
                                            @else
                                                {{ $expiryDate->toFormattedDateString() }}
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#purchaseModal{{ $purchase->id }}">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal for each purchase -->
                                    <div class="modal fade" id="purchaseModal{{ $purchase->id }}" tabindex="-1" aria-labelledby="purchaseModalLabel{{ $purchase->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title fw-bold" id="purchaseModalLabel{{ $purchase->id }}">Purchase Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                                        <span class="text-muted">Date</span>
                                                        <span class="fw-bold">{{ $purchase->created_at->format('F j, Y g:i A') }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                                        <span class="text-muted">Amount</span>
                                                        <span class="fw-bold">{{ trans_choice(':count hour|:count hours', $purchase->quantity, ['count' => $purchase->quantity]) }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                                        <span class="text-muted">Type</span>
                                                        <span class="fw-bold">{{ $purchase->formatted_support_type }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                                        <span class="text-muted">Price</span>
                                                        <span class="fw-bold">{{ $purchase->formatted_amount }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                                        <span class="text-muted">Expires</span>
                                                        <span class="fw-bold">{{ $purchase->created_at->addYear()->format('F j, Y') }}</span>
                                                    </div>
                                                    <div class="mb-0">
                                                        <p class="text-muted mb-2">Details</p>
                                                        <p class="mb-0">{{ $purchase->details ?: 'No additional details provided.' }}</p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-primary rounded-pill" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="card border-0 shadow-sm mb-4 collapse" id="faqSection">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h4 class="fw-bold mb-0">Frequently Asked Questions</h4>
                <button class="btn btn-sm btn-outline-primary rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#faqSection" aria-expanded="false">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="accordion accordion-flush" id="faqAccordion">
                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed bg-transparent fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                How do I purchase support time?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body pt-0">
                                To purchase support time, click on the "Purchase Support Time" button above. You'll be taken to a page where you can select the amount of time you'd like to purchase and complete the transaction.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed bg-transparent fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                What can I use my support time for?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body pt-0">
                                Support time can be used for technical assistance, troubleshooting, and guidance related to our products and services. It's billed in 15-minute increments.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed bg-transparent fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                How long is my support time valid?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body pt-0">
                                Your purchased support time is valid for 12 months from the date of purchase. Any unused time will expire after this period.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 border-bottom">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed bg-transparent fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                How will we communicate during support sessions?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body pt-0">
                                We offer multiple communication options for support sessions, including:
                                <ul class="mb-0 mt-2">
                                    <li>Video calls via Zoom, Google Meet or Microsoft Teams</li>
                                    <li>Screen sharing for more complex issues</li>
                                    <li>Email for non-urgent queries or follow-ups</li>
                                </ul>
                                <p class="mt-2 mb-0">The most appropriate method will be agreed upon when scheduling your support session.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed bg-transparent fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                What if I need urgent support?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body pt-0">
                                While we strive to provide timely support, please note that as an open-source project, we don't offer 24/7 or emergency support. For urgent issues, we recommend checking our documentation first. If you still need assistance, you can request an expedited session, subject to supporter availability and potentially at a higher rate.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize all tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle collapse toggle buttons to change icon
            const collapseButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');
            collapseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const target = document.querySelector(this.getAttribute('data-bs-target'));
                    if (target.classList.contains('show')) {
                        this.querySelector('i').classList.replace('bi-chevron-up', 'bi-chevron-down');
                    } else {
                        this.querySelector('i').classList.replace('bi-chevron-down', 'bi-chevron-up');
                    }
                });
            });
        });
    </script>
@endpush
