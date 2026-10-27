@extends('layouts.app')

@section('content')
<div class="gb-privacy-page">

    <section class="gb-privacy-hero">
        <span>GIFTBLOOM POLICIES</span>
        <h1>Privacy Policy</h1>
        <p>Your privacy matters to us. Learn how we handle your information.</p>
        <small>Last updated: October 2026</small>
    </section>

    <div class="container gb-privacy-layout">
        <main class="gb-privacy-content">

            <section id="privacy-intro">
                <h2>1. Introduction</h2>
                <p>
                    Welcome to GiftBloom. This Privacy Policy explains how GiftBloom
                    collects, uses, stores and protects information when you visit
                    our website, create an account or place an order.
                </p>
                <p>
                    By using our website, you acknowledge that you have read this
                    policy. Please do not use our website if you do not agree with
                    the practices described here.
                </p>
            </section>

            <section id="privacy-consent">
                <h2>2. Your Consent</h2>
                <p>
                    When you provide information to us or use our website, your
                    information may be processed as described in this policy and
                    subject to applicable law. Please provide only information
                    that you are authorised to share.
                </p>
            </section>

            <section id="privacy-collect">
                <h2>3. Information We Collect</h2>
                <p>Depending on how you use GiftBloom, information may include:</p>
                <ul>
                    <li><strong>Personal details:</strong> Name, email address and phone number.</li>
                    <li><strong>Order details:</strong> Products purchased, order number, delivery address and order history.</li>
                    <li><strong>Account details:</strong> Information needed to create and manage your account.</li>
                    <li><strong>Technical information:</strong> Browser, device and website usage information, where collected.</li>
                    <li><strong>Support messages:</strong> Information you provide when contacting customer support.</li>
                </ul>
                <p>We aim to collect only information needed for relevant website services and operations.</p>
            </section>

            <section id="privacy-use">
                <h2>4. How We Use Your Information</h2>
                <p>Information may be used to:</p>
                <ul>
                    <li>Process orders and arrange delivery.</li>
                    <li>Manage accounts, addresses and order history.</li>
                    <li>Respond to enquiries and provide customer support.</li>
                    <li>Send order updates and important service notifications.</li>
                    <li>Maintain website functionality and help prevent fraud or misuse.</li>
                    <li>Meet applicable legal and record-keeping obligations.</li>
                </ul>
                <p>Promotional communications, where sent, should include an appropriate way to opt out.</p>
            </section>

            <section id="privacy-sharing">
                <h2>5. Sharing of Information</h2>
                <p>
                    Information may be shared with relevant service providers when
                    necessary to operate the website, process payments, deliver
                    orders, host systems or provide customer support.
                </p>
                <p>
                    Information may also be disclosed where required by law or
                    necessary to protect the website, customers or our legal rights.
                    We do not intend to sell customers' personal information.
                </p>
            </section>

            <section id="privacy-security">
                <h2>6. Data Security</h2>
                <p>
                    We aim to use reasonable technical and organisational measures
                    to protect personal information from unauthorised access,
                    alteration, loss or disclosure.
                </p>
                <p>
                    No method of online transmission or storage can be guaranteed
                    completely secure. Customers should also protect their account
                    credentials and avoid sharing passwords with others.
                </p>
            </section>

            <section id="privacy-cookies">
                <h2>7. Cookies and Website Usage</h2>
                <p>
                    Our website may use cookies or similar technologies to support
                    essential functionality, remember preferences and understand
                    website usage, where these features are enabled.
                </p>
                <p>
                    You can manage cookies through your browser settings. Disabling
                    certain cookies may affect some website features.
                </p>
            </section>

            <section id="privacy-retention">
                <h2>8. Data Retention</h2>
                <p>
                    Information may be retained for as long as reasonably necessary
                    to provide services, maintain records, resolve disputes,
                    prevent misuse and comply with applicable legal obligations.
                    Retention periods may vary according to the type of information
                    and its purpose.
                </p>
            </section>

            <section id="privacy-rights">
                <h2>9. Your Privacy Rights</h2>
                <p>
                    Subject to applicable law, you may request access to, correction
                    of or deletion of your personal information. You may also raise
                    concerns about how your information is handled.
                </p>
                <p>
                    Some information may need to be retained where legally required
                    or necessary for legitimate business purposes. We may need to
                    verify your identity before processing a request.
                </p>
            </section>

            <section id="privacy-third-party">
                <h2>10. Third-Party Websites</h2>
                <p>
                    Our website may contain links to external websites or services.
                    Their privacy practices are governed by their own policies.
                    Please review those policies before sharing personal information.
                </p>
            </section>

            <section id="privacy-changes">
                <h2>11. Changes to This Policy</h2>
                <p>
                    We may update this Privacy Policy when our services or legal
                    requirements change. The updated version will be published on
                    this page along with a revised update date.
                </p>
            </section>

            <section id="privacy-contact">
                <h2>12. Contact Us</h2>
                <p>
                    If you have questions, privacy concerns or a request relating
                    to your personal information, please contact GiftBloom through
                    our Contact Us page.
                </p>
                <a href="{{ route('pages.contact') }}" class="gb-privacy-button">
                    Contact GiftBloom
                </a>
            </section>

            <p class="gb-privacy-note">
                Note: This is a starter policy template. Review it against your
                actual data collection, payment, analytics and customer-support
                practices before publishing it as your final legal policy.
            </p>

        </main>
    </div>
</div>
@endsection
