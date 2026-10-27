@extends('layouts.app')

@section('content')
<div class="gb-privacy-page gb-terms-page">

    <section class="gb-privacy-hero">
        <span>GIFTBLOOM POLICIES</span>
        <h1>Terms &amp; Conditions</h1>
        <p>Please read these terms before using the GiftBloom website.</p>
        <small>Last updated: October 2026</small>
    </section>

    <div class="gb-privacy-layout">
        <main class="gb-privacy-content">

            <section id="terms-intro">
                <h2>1. Introduction</h2>
                <p>Welcome to GiftBloom. These Terms &amp; Conditions explain the general rules for accessing and using our website and services. By using this website, you agree to these terms. If you do not agree, please discontinue using the website.</p>
            </section>

            <section id="terms-eligibility">
                <h2>2. Eligibility</h2>
                <p>You must be legally capable of entering into a binding agreement to place an order on this website. If you are using the website on behalf of another person, you confirm that you are authorised to do so.</p>
            </section>

            <section id="terms-account">
                <h2>3. Account and Information</h2>
                <p>You are responsible for providing accurate and up-to-date information when creating an account or placing an order. Please keep your login details secure and notify us if you believe your account has been accessed without permission.</p>
            </section>

            <section id="terms-products">
                <h2>4. Products and Availability</h2>
                <p>We aim to display product descriptions, images and prices as accurately as possible. Actual product appearance, colour or packaging may vary slightly due to screen settings, availability or other factors.</p>
                <p>All products are subject to availability. We reserve the right to update product details, discontinue items or correct errors on the website.</p>
            </section>

            <section id="terms-pricing">
                <h2>5. Pricing and Payments</h2>
                <p>Product prices and applicable charges will be displayed on the website or during checkout. Please review your order total before confirming your purchase.</p>
                <p>Available payment methods will be shown during checkout. An order may be subject to verification before it is processed.</p>
            </section>

            <section id="terms-orders">
                <h2>6. Orders and Confirmation</h2>
                <p>After submitting an order, you should review any confirmation or order details provided by the website. Submission of an order does not necessarily mean that it has been finally accepted or dispatched.</p>
                <p>If an order cannot be fulfilled due to availability, pricing errors or other valid reasons, we may contact you regarding cancellation or the available next steps.</p>
            </section>

            <section id="terms-delivery">
                <h2>7. Shipping and Delivery</h2>
                <p>Delivery availability and estimated timelines may vary by product and delivery location. Any estimated delivery date is subject to operational conditions and circumstances beyond our reasonable control.</p>
                <p>For more information, please read our <a href="{{ route('pages.shipping') }}">Shipping &amp; Delivery Policy</a>.</p>
            </section>

            <section id="terms-cancellation">
                <h2>8. Cancellation and Refunds</h2>
                <p>Order cancellation and refund requests are handled according to the applicable cancellation and refund policy. Eligibility may depend on the order status, product type and circumstances of the request.</p>
                <p>Please read our <a href="{{ route('pages.refund') }}">Cancellation &amp; Refund Policy</a> before submitting a request.</p>
            </section>

            <section id="terms-user">
                <h2>9. Acceptable Use</h2>
                <p>When using GiftBloom, you agree not to:</p>
                <ul>
                    <li>Use the website for unlawful or fraudulent activities.</li>
                    <li>Submit false, misleading or unauthorised information.</li>
                    <li>Attempt to disrupt the website, its security or its operation.</li>
                    <li>Copy or misuse website content for unauthorised commercial purposes.</li>
                    <li>Interfere with another user's access to the website.</li>
                </ul>
            </section>

            <section id="terms-intellectual">
                <h2>10. Intellectual Property</h2>
                <p>Unless otherwise stated, website content such as text, graphics, logos, design elements and other materials may be protected by applicable intellectual property laws. You may not reproduce or commercially use such content without the necessary permission.</p>
            </section>

            <section id="terms-thirdparty">
                <h2>11. Third-Party Services and Links</h2>
                <p>The website may contain links to third-party websites or use external services. These services may have their own terms and privacy policies. GiftBloom is not responsible for third-party content or practices outside its control.</p>
            </section>

            <section id="terms-liability">
                <h2>12. Limitation of Liability</h2>
                <p>To the extent permitted by applicable law, GiftBloom shall not be liable for losses arising from temporary website unavailability, technical interruptions or circumstances beyond its reasonable control. Nothing in these terms excludes liability that cannot legally be excluded.</p>
            </section>

            <section id="terms-privacy">
                <h2>13. Privacy</h2>
                <p>Your use of this website is also subject to our <a href="{{ route('pages.privacy') }}">Privacy Policy</a>, which explains how personal information may be handled.</p>
            </section>

            <section id="terms-changes">
                <h2>14. Changes to These Terms</h2>
                <p>We may update these Terms &amp; Conditions when necessary. Changes will be published on this page along with an updated date where appropriate. Continued use of the website after changes are published may indicate acceptance of the revised terms, subject to applicable law.</p>
            </section>

            <section id="terms-law">
                <h2>15. Governing Law</h2>
                <p>These terms are subject to applicable laws of India. Any dispute will be handled by the appropriate forum as determined under applicable law.</p>
            </section>

            <section id="terms-contact">
                <h2>16. Contact Us</h2>
                <p>If you have questions about these Terms &amp; Conditions, please contact our team through the Contact Us page.</p>
                <a href="{{ route('pages.contact') }}" class="gb-privacy-button">Contact GiftBloom</a>
            </section>

            <p class="gb-privacy-note">Note: This is a starter Terms &amp; Conditions template. Review it against your actual business practices and applicable legal requirements before publishing it as your final policy.</p>

        </main>
    </div>
</div>
@endsection
