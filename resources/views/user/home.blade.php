@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        <p>Your current support time balance: <strong>{{ trans_choice(':count hour|:count hours', $user->support_time_balance, ['count' => $user->support_time_balance]) }}</strong></p>
                        <a href="{{ route('support.purchase') }}" class="btn btn-vanguard">Purchase Support Time</a>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">{{ __('Support Time Purchase History') }}</div>

                    <div class="card-body">
                        @if ($supportTimePurchases->reject->isExpired()->isEmpty())
                            <p>You don't have any active support time purchases.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Expires</th>
                                        <th>Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($supportTimePurchases->reject->isExpired() as $purchase)
                                        <tr>
                                            <td>{{ $purchase->created_at->toFormattedDateString() }}</td>
                                            <td>{{ trans_choice(':count hour|:count hours', $purchase->quantity, ['count' => $purchase->quantity]) }}</td>
                                            <td>{{ $purchase->formatted_support_type }}</td>
                                            <td>{{ $purchase->formatted_amount }}</td>
                                            <td>{{ $purchase->created_at->addYear()->toFormattedDateString() }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#purchaseModal{{ $purchase->id }}">
                                                    View
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal for each purchase -->
                                        <div class="modal fade" id="purchaseModal{{ $purchase->id }}" tabindex="-1" aria-labelledby="purchaseModalLabel{{ $purchase->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="purchaseModalLabel{{ $purchase->id }}">Purchase Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Date:</strong> {{ $purchase->created_at->format('F j, Y g:i A') }}</p>
                                                        <p><strong>Amount:</strong> {{ trans_choice(':count hour|:count hours', $purchase->quantity, ['count' => $purchase->quantity]) }}</p>
                                                        <p><strong>Type:</strong> {{ $purchase->formatted_support_type }}</p>
                                                        <p><strong>Price:</strong> {{ $purchase->formatted_amount }}</p>
                                                        <p><strong>Expires:</strong> {{ $purchase->created_at->addYear()->format('F j, Y') }}</p>
                                                        <p><strong>Details:</strong> {{ $purchase->details ?: 'No additional details provided.' }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

                <div class="card">
                    <div class="card-header">{{ __('Frequently Asked Questions') }}</div>

                    <div class="card-body">
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        How do I purchase support time?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        To purchase support time, click on the "Purchase Support Time" button above. You'll be taken to a page where you can select the amount of time you'd like to purchase and complete the transaction.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        What can I use my support time for?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Support time can be used for technical assistance, troubleshooting, and guidance related to our products and services. It's billed in 15-minute increments.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        How long is my support time valid?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Your purchased support time is valid for 12 months from the date of purchase. Any unused time will expire after this period.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        How will we communicate during support sessions?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We offer multiple communication options for support sessions, including:
                                        <ul>
                                            <li>Video calls via Zoom, Google Meet or Microsoft Teams</li>
                                            <li>Screen sharing for more complex issues</li>
                                            <li>Email for non-urgent queries or follow-ups</li>
                                        </ul>
                                        The most appropriate method will be agreed upon when scheduling your support session.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        What if I need urgent support?
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        While we strive to provide timely support, please note that as an open-source project, we don't offer 24/7 or emergency support. For urgent issues, we recommend checking our documentation first. If you still need assistance, you can request an expedited session, subject to supporter availability and potentially at a higher rate.
                                    </div>
                                </div>
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
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush
