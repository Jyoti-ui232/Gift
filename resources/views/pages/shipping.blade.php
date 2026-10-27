
@extends('layouts.app')

@section('content')
<div class="gb-privacy-page gb-shipping-page">

    <section class="gb-privacy-hero">
        <span>GIFTBLOOM POLICIES</span>
        <h1>Shipping &amp; Delivery</h1>
        <p>Everything you need to know about receiving your GiftBloom order.</p>
        <small>Last updated: October 2026</small>
    </section>

    <div class="gb-privacy-layout">
        <main class="gb-privacy-content">

            <section id="shipping-intro">
                <h2>1. Introduction</h2>
                <p>
                    At GiftBloom, we want your gifting experience to be smooth
                    and convenient. This policy explains how order processing,
                    delivery availability, tracking and delivery-related support
                    work on our website.
                </p>
            </section>

            <section id="shipping-coverage">
                <h2>2. Delivery Coverage</h2>
                <p>
                    Delivery availability depends on the product and the address
                    entered during checkout. Some products or locations may not
                    be serviceable. Please check the availability shown on the
                    website before placing your order.
                </p>
            </section>

            <section id="shipping-processing">
                <h2>3. Order Processing</h2>
                <p>
                    Orders are processed after the required order details have
                    been submitted and the order is confirmed. Processing time
                    may vary depending on product availability, order volume,
                    customisation requirements and other operational factors.
                </p>
                <p>
                    Please ensure that your contact number, delivery address
                    and other order details are correct when placing an order.
                    Incorrect or incomplete details may delay delivery.
                </p>
            </section>

            <section id="shipping-time">
                <h2>4. Estimated Delivery Time</h2>
                <p>
                    Estimated delivery dates, where provided, are indicative and
                    may vary according to the delivery location, product,
                    courier availability, weather, holidays and other
                    circumstances beyond our control.
                </p>
                <p>
                    If an estimated delivery date is displayed at checkout,
                    please refer to that information for your particular order.
                    We do not guarantee a specific delivery time unless
                    explicitly confirmed for the relevant product or service.
                </p>
            </section>

            <section id="shipping-charges">
                <h2>5. Shipping Charges</h2>
                <p>
                    Any applicable delivery or shipping charges will be displayed
                    on the website or during checkout before you complete your
                    order. Charges may vary depending on the product, location
                    or delivery service.
                </p>
            </section>

            <section id="shipping-tracking">
                <h2>6. Order Tracking</h2>
                <p>
                    You can check your order status through the Track Order page.
                    Enter the requested details to view the available tracking
                    information for your order.
                </p>
                <p>
                    Tracking updates may take time to appear after an order is
                    processed or handed over for delivery.
                </p>
                <a href="{{ route('order.track') }}" class="gb-privacy-button">
                    Track Your Order
                </a>
            </section>

            <section id="shipping-address">
                <h2>7. Delivery Address and Contact Details</h2>
                <p>
                    Customers are responsible for providing a complete and
                    accurate delivery address and a reachable contact number.
                    If you need to correct your details, contact our support
                    team as soon as possible.
                </p>
                <p>
                    Address changes may not be possible after an order has been
                    processed or dispatched. Any change request will depend on
                    the order status and delivery arrangements.
                </p>
            </section>

            <section id="shipping-delays">
                <h2>8. Delays and Delivery Attempts</h2>
                <p>
                    Deliveries may occasionally be delayed due to weather,
                    traffic, courier operations, incorrect addresses, recipient
                    unavailability, public holidays or other unexpected events.
                </p>
                <p>
                    If a delivery attempt is unsuccessful, the next steps will
                    depend on the courier's process and the available order
                    information. Please keep your phone reachable around the
                    expected delivery date.
                </p>
            </section>

            <section id="shipping-received">
                <h2>9. Receiving Your Order</h2>
                <p>
                    Please check the delivered package when you receive it.
                    If you notice visible damage, missing items or an incorrect
                    product, contact our support team promptly with your order
                    details and a description of the issue.
                </p>
                <p>
                    Photographs of the package or product may help us understand
                    and review the concern.
                </p>
            </section>

            <section id="shipping-undelivered">
                <h2>10. Undelivered or Returned Orders</h2>
                <p>
                    An order may be returned or marked undeliverable if the
                    address is incorrect, the recipient cannot be reached or
                    delivery cannot be completed. Please contact support for
                    guidance on the next steps for your specific order.
                </p>
            </section>

            <section id="shipping-gifts">
                <h2>11. Gifts and Special Occasions</h2>
                <p>
                    If you are sending a gift for a birthday, anniversary or
                    another special occasion, we recommend placing your order
                    with sufficient time in advance. Delivery estimates can
                    change, so please review the availability and estimated
                    date shown for the product.
                </p>
            </section>

            <section id="shipping-support">
                <h2>12. Delivery Support</h2>
                <p>
                    If you have questions about delivery, tracking or an order
                    that has not arrived as expected, contact our support team
                    with your order number and registered contact details.
                </p>
                <a href="{{ route('pages.contact') }}" class="gb-privacy-button">
                    Contact Us
                </a>
            </section>

            <p class="gb-privacy-note">
                Note: Delivery coverage, charges, timelines and courier
                arrangements depend on the actual services offered by GiftBloom.
                Confirm these details before publishing this policy.
            </p>

        </main>
    </div>
</div>
@endsection
