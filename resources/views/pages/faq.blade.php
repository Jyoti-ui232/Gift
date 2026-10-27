
@extends('layouts.app')

@section('content')
<div class="gb-faq-page">

    <section class="gb-faq-hero">
        <span>WE'RE HERE TO HELP</span>
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about your GiftBloom experience.</p>
    </section>

    <section class="gb-faq-content container">
        <div class="gb-faq-heading">
            <span>NEED HELP?</span>
            <h2>How Can We Help You?</h2>
            <p>Browse the questions below to find the information you need.</p>
        </div>

        <div class="gb-faq-list">

            <details class="gb-faq-item">
                <summary>How can I place an order on GiftBloom?</summary>
                <p>Browse our products, select your favourite gift, add it to your cart and follow the checkout steps to place your order.</p>
            </details>

            <details class="gb-faq-item">
                <summary>How can I check my order status?</summary>
                <p>Visit the Track Order page and enter the requested order details to check your order status.</p>
            </details>

            <details class="gb-faq-item">
                <summary>What payment methods are available?</summary>
                <p>Available payment methods will be shown during checkout. Please check the options displayed when placing your order.</p>
            </details>

            <details class="gb-faq-item">
                <summary>Can I cancel my order?</summary>
                <p>Please read our Cancellation &amp; Refund policy for cancellation conditions and instructions.</p>
            </details>

            <details class="gb-faq-item">
                <summary>What is your delivery policy?</summary>
                <p>Delivery availability and estimated timelines may vary by product and delivery location. Check the delivery details during checkout.</p>
            </details>

            <details class="gb-faq-item">
                <summary>What should I do if my order arrives damaged?</summary>
                <p>Please contact our support team with your order details and a description of the issue so they can guide you.</p>
            </details>

            <details class="gb-faq-item">
                <summary>Can I change my delivery address?</summary>
                <p>Address changes may depend on your order status. Contact support as soon as possible to request a change.</p>
            </details>

            <details class="gb-faq-item">
                <summary>How can I contact GiftBloom?</summary>
                <p>Visit our Contact Us page to find our contact details and send us your query.</p>
                <a href="{{ route('pages.contact') }}" class="gb-faq-link">Contact Us</a>
            </details>

        </div>

        <div class="gb-faq-contact">
            <h2>Still Have Questions?</h2>
            <p>We're happy to help you with your gifting journey.</p>
            <a href="{{ route('pages.contact') }}" class="gb-faq-button">Get in Touch</a>
        </div>
    </section>

</div>
@endsection
