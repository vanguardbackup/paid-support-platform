<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vanguard Paid Support - Expert Assistance for Your Backup Needs</title>
    <meta name="description" content="Get expert support for Vanguard directly from its creators. Fast, efficient, and tailored to your needs.">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600,700" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --bs-font-sans-serif: 'Poppins', sans-serif;
            --bs-primary: #000;
            --bs-primary-rgb: 0, 0, 0;
        }

        body {
            font-family: var(--bs-font-sans-serif);
            background-color: #fff;
            color: #212529;
            line-height: 1.6;
        }

        .bg-black {
            background-color: #000;
        }

        .bg-light-gray {
            background-color: #f8f9fa;
        }

        .text-black {
            color: #000 !important;
        }

        /* Custom button styles */
        .btn-black {
            background-color: #000;
            color: #fff;
            border: 2px solid #000;
            border-radius: 0.375rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-black:hover {
            background-color: #333;
            color: #fff;
            border-color: #333;
        }

        .btn-outline-black {
            background-color: transparent;
            color: #000;
            border: 2px solid #000;
            border-radius: 0.375rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-black:hover {
            background-color: #000;
            color: #fff;
        }

        /* Card styles */
        .support-card {
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .support-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-color: #000;
        }

        .support-card-header {
            background-color: #000;
            color: #fff;
            padding: 1.5rem;
        }

        .support-card-body {
            padding: 2rem;
        }

        /* Custom list styles */
        .feature-list {
            list-style: none;
            padding-left: 0;
        }

        .feature-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list .icon {
            margin-right: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        /* Step process */
        .step-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #000;
            color: #fff;
            font-weight: 600;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .step-content {
            flex-grow: 1;
        }

        .step-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        /* Hero section */
        .hero-section {
            padding: 5rem 0;
            background-color: #000;
            color: #fff;
            position: relative;
        }

        .hero-pattern {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            opacity: 0.05;
            background-image: radial-gradient(#fff 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Navbar customization */
        .navbar {
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
        }

        /* Pricing display */
        .price-display {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .price-unit {
            font-size: 1rem;
            color: #6c757d;
            align-self: flex-end;
            margin-bottom: 0.5rem;
            margin-left: 0.25rem;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            Vanguard <span class="text-secondary fs-6">/ Support</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="https://vanguardbackup.com">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://docs.vanguardbackup.com">Documentation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/vanguardbackup/vanguard">GitHub</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-pattern"></div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">Expert Vanguard Support</h1>
                <p class="lead fs-4 opacity-75 mb-4">Direct assistance from the team that built Vanguard. Resolve issues faster and optimize your backup strategy.</p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ route('register') }}" class="btn btn-outline-black btn-lg">Get Started</a>
                    <a href="#pricing" class="btn btn-black btn-lg">View Pricing</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container py-4">
        <div class="row g-5 align-items-stretch">
            <!-- Pricing Card -->
            <div class="col-lg-6">
                <div class="support-card h-100" id="pricing">
                    <div class="support-card-header">
                        <h2 class="h3 mb-0 fw-bold">Premium Support</h2>
                    </div>
                    <div class="support-card-body d-flex flex-column">
                        <div class="mb-4">
                            <div class="d-flex align-items-end mb-3">
                                <span class="price-display">£30</span>
                                <span class="price-unit">per hour</span>
                            </div>
                            <p class="text-muted">Direct assistance from Vanguard's creators</p>
                        </div>

                        <ul class="feature-list mb-4">
                            <li>
                                <div class="icon text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </div>
                                <span>One-on-one personalized assistance</span>
                            </li>
                            <li>
                                <div class="icon text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </div>
                                <span>Rapid problem diagnosis and resolution</span>
                            </li>
                            <li>
                                <div class="icon text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </div>
                                <span>Expert advice on best practices</span>
                            </li>
                            <li>
                                <div class="icon text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </div>
                                <span>Priority response within 24 hours</span>
                            </li>
                            <li>
                                <div class="icon text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </div>
                                <span>Custom implementation guidance</span>
                            </li>
                        </ul>

                        <div class="mt-auto text-center">
                            <a href="{{ route('login') }}" class="btn btn-black w-100 py-3">Get Support Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How It Works Card -->
            <div class="col-lg-6">
                <div class="support-card h-100">
                    <div class="support-card-header">
                        <h2 class="h3 mb-0 fw-bold">How It Works</h2>
                    </div>
                    <div class="support-card-body">
                        <div class="step-row">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3 class="h5 fw-bold mb-2">Create an account</h3>
                                <p class="text-muted mb-0">Quick and easy sign-up process that takes less than a minute to complete.</p>
                            </div>
                        </div>

                        <div class="step-row">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3 class="h5 fw-bold mb-2">Purchase support hours</h3>
                                <p class="text-muted mb-0">Choose how many hours you need with flexible payment options.</p>
                            </div>
                        </div>

                        <div class="step-row">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3 class="h5 fw-bold mb-2">Schedule your session</h3>
                                <p class="text-muted mb-0">Book a time that works for you using our simple scheduling system.</p>
                            </div>
                        </div>

                        <div class="step-row">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h3 class="h5 fw-bold mb-2">Get expert help</h3>
                                <p class="text-muted mb-0">Connect with our team via your preferred method and solve your issues efficiently.</p>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('register') }}" class="btn btn-outline-black py-3 px-4">Create Your Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="h2 fw-bold mb-3">Frequently Asked Questions</h2>
            <p class="text-muted mx-auto" style="max-width: 700px;">Everything you need to know about our paid support services</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="faqAccordion">
                    <!-- FAQ Item 1 -->
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                How does the billing work?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                <p>We charge £30 per hour for support, billed in 15-minute increments. You purchase support credits in advance, and we deduct from your balance as support is provided. Any unused time remains in your account for future use with no expiration.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                What types of support do you provide?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                <p>We provide assistance with installation, configuration, troubleshooting, optimization, and best practices for Vanguard backup solutions. This includes help with database backups, file backups, cloud storage configurations, and automated scheduling.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                How quickly will I receive support?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                <p>After purchasing support hours, you'll receive a response within 24 hours to schedule your session. For urgent issues, we offer priority support with faster response times when available.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                What communication methods do you use?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                <p>We offer support via video call (Zoom or Google Meet), screen sharing, email, or ticketing system based on your preference. For complex issues, screen sharing is recommended for faster resolution.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Can I get a refund if my issue isn't resolved?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light">
                                <p>We're committed to resolving your issues and will continue working until a solution is found. If for any reason we're unable to resolve your issue, we'll provide a refund for the unresolved portion of the support time.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action -->
<section class="py-5 bg-black text-white text-center">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="h1 fw-bold mb-4">Ready to get expert support?</h2>
                <p class="lead mb-5">Join the growing community of satisfied Vanguard users who trust our support team to keep their backups running smoothly.</p>
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-outline-white btn-lg px-4 py-3" style="border-color: white; color: white;">Sign Up Now</a>
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 py-3 text-black">Login</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-light py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0 text-muted">&copy; {{ date('Y') }} Vanguard. All rights reserved.</p>
            </div>
            <div class="col-md-6">
                <nav class="nav justify-content-center justify-content-md-end">
                    <a href="https://vanguardbackup.com" class="nav-link px-2 text-muted">Home</a>
                    <a href="https://docs.vanguardbackup.com" class="nav-link px-2 text-muted">Documentation</a>
                    <a href="https://github.com/vanguardbackup/vanguard" class="nav-link px-2 text-muted">GitHub</a>
                    <a href="#" class="nav-link px-2 text-muted">Privacy</a>
                    <a href="#" class="nav-link px-2 text-muted">Terms</a>
                </nav>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
