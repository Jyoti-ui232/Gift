
@extends('layouts.app')

@section('content')
<div class="gb-privacy-page gb-refund-page">

    <section class="gb-privacy-hero">
        <span>GIFTBLOOM POLICIES</span>
        <h1>Cancellation &amp; Refund Policy</h1>
        <p>Important information about order cancellations, returns and refunds.</p>
        <small>Last updated: October 2026</small>
    </section>

    <div class="gb-privacy-layout">
        <main class="gb-privacy-content">

            <section id="refund-intro">
                <h2>1. Introduction</h2>
                <p>
                    At GiftBloom, we want you to have a positive shopping
                    experience. This policy explains how cancellation and
                    refund requests are reviewed. Eligibility depends on the
                    order status, product type and circumstances of the request.
                </p>
            </section>

            <section id="refund-cancellation">
                <h2>2. Order Cancellation</h2>
                <p>
                    Cancellation requests are subject to the processing and
                    dispatch status of your order. If you wish to cancel an
                    order, contact our support team as soon as possible and
                    provide your order number.
                </p>
                <p>
                    Once an order has been processed, customised or dispatched,
                    cancellation may no longer be possible. Our team will
                    review the request and inform you of the available options.
                </p>
            </section>

            <section id="refund-eligibility">
                <h2>3. Refund Eligibility</h2>
                <p>
                    Refund requests are reviewed according to the circumstances
                    of the order and the applicable policy. Eligibility may
                    depend on whether an order was cancelled successfully,
                    delivered incorrectly, received damaged or was otherwise
                    eligible for a refund.
                </p>
                <p>
                    Some products may have specific conditions. Please review
                    the product information and applicable terms before placing
                    your order.
                </p>
            </section>

            <section id="refund-damaged">
                <h2>4. Damaged, Defective or Incorrect Products</h2>
                <p>
                    If your order arrives damaged, defective or different from
                    what you ordered, contact our support team promptly after
                    delivery. Include your order number and a clear description
                    of the issue.
                </p>
                <p>
                    Photos of the product and packaging may be requested to
                    help us assess the issue. Our team will review the details
                    and explain the next steps.
                </p>
            </section>

            <section id="refund-request">
                <h2>5. How to Request a Cancellation or Refund</h2>
                <p>To help us review your request, please provide:</p>
                <ul>
                    <li>Your order number.</li>
                    <li>The contact details associated with the order.</li>
                    <li>The reason for your cancellation or refund request.</li>
                    <li>Relevant photos or supporting information, if applicable.</li>
                </ul>
                <p>
                    You can submit your query through our Contact Us page.
                    Please avoid sharing passwords or unnecessary sensitive
                    information.
                </p>
                <a href="{{ route('pages.contact') }}" class="gb-privacy-button">
                    Contact Support
                </a>
            </section>

            <section id="refund-review">
                <h2>6. Review and Approval</h2>
                <p>
                    Each request is reviewed based on the information provided
                    and the applicable policy. We may contact you if additional
                    details are needed. Submitting a request does not
                    automatically guarantee cancellation or a refund.
                </p>
            </section>

            <section id="refund-processing">
                <h2>7. Refund Processing Time</h2>
                <p>
                    If a refund is approved, the processing time may depend on
                    the payment method, payment provider and relevant financial
                    institution. Any available timeline will be communicated
                    during the refund process.
                </p>
                <p>
                    Please allow the relevant provider time to reflect the
                    approved refund in your account.
                </p>
            </section>

            <section id="refund-method">
                <h2>8. Refund Method</h2>
                <p>
                    Where possible and permitted, approved refunds will be
                    returned through the original payment method or another
                    method communicated by GiftBloom support.
                </p>
            </section>

            <section id="refund-delivery">
                <h2>9. Shipping and Delivery Charges</h2>
                <p>
                    The treatment of shipping or delivery charges depends on
                    the reason for the request and the applicable terms.
                    Please contact support for clarification about your order.
                </p>
            </section>

            <section id="refund-cod">
                <h2>10. Cash on Delivery Orders</h2>
                <p>
                    If an eligible Cash on Delivery order qualifies for a
                    refund, our support team will explain the available refund
                    arrangement and any information needed to process it.
                </p>
            </section>

            <section id="refund-fraud">
                <h2>11. Incorrect or Incomplete Requests</h2>
                <p>
                    Requests with missing or inaccurate information may take
                    longer to review. Please provide accurate order details
                    and respond if our support team asks for clarification.
                </p>
            </section>

            <section id="refund-contact">
                <h2>12. Contact Us</h2>
                <p>
                    For questions about cancellations, damaged products or
                    refund status, contact GiftBloom support and include your
                    order number so we can better understand your request.
                </p>
                <a href="{{ route('pages.contact') }}" class="gb-privacy-button">
                    Contact GiftBloom
                </a>
            </section>

            <p class="gb-privacy-note">
                Note: This is a starter policy template. Before publishing,
                confirm the actual cancellation eligibility, refund timelines,
                payment arrangements and product-specific conditions offered
                by GiftBloom.
            </p>

        </main>
    </div>
</div>
@endsection
