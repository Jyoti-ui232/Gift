@extends('layouts.app')

@section('content')
<section class="gb-contact">
    <div class="container">

        <div class="text-center mb-5">
            <span class="eyebrow">We're here for you</span>
            <h1>Let's Talk <span style="color:#7d8531;">Gifts!</span></h1>
            <p class="intro">
                Need help choosing the perfect gift or have a question about your order?
                Our team is happy to help make every gifting moment special.
            </p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon">✉</div>
                    <h5>Email Us</h5>
                    <p>support@giftbloom.com</p>
                    <p>Send us your queries anytime.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon">☎</div>
                    <h5>Call Us</h5>
                    <p>+91 99999 99999</p>
                    <p>Monday–Saturday, 9 AM–6 PM</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon">♡</div>
                    <h5>Customer Care</h5>
                    <p>We're here to help with orders, delivery and gifting questions.</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">Send Us a Message</h3>
                        <p class="text-muted mb-0">Fill in the details below and tell us how we can help.</p>
                    </div>

                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="contact-name">Your Name</label>
                                <input class="form-control" id="contact-name" type="text"
                                       placeholder="Enter your name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="contact-email">Email Address</label>
                                <input class="form-control" id="contact-email" type="email"
                                       placeholder="Enter your email" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="contact-subject">Subject</label>
                                <input class="form-control" id="contact-subject" type="text"
                                       placeholder="What is this regarding?" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold" for="contact-message">Your Message</label>
                                <textarea class="form-control" id="contact-message" rows="5"
                                          placeholder="Write your message here..." required></textarea>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-contact w-100" type="button" disabled>
                                    Send Message
                                </button>
                                <small class="text-muted d-block mt-2 text-center">
                                    Contact form submission will be enabled later.
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection