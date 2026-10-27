
@extends('layouts.app')

@section('content')
<div class="gb-about-page">

    {{--About Hero Banner --}}
    <section class="gb-about-hero">
        <div class="gb-about-hero-content">
            <span>THE ART OF GIFTING</span>
            <h1>We Create<br>Special Moments</h1>
            <p>Every gift has a story. Every moment deserves to be remembered.</p>
        </div>
    </section>

    {{-- Our Story --}}
    <section class="gb-about-story gb-about-section">
        <span class="gb-about-label">OUR JOURNEY</span>
        <h2>How It All Started</h2>
        <p>
            GiftBloom began with a simple thought: every celebration becomes
            more meaningful when a gift comes from the heart. Our journey is
            about helping people express love, gratitude and happiness through
            thoughtful gifting.
        </p>
    </section>

    {{-- Image and Text --}}
    <section class="gb-about-delivery">
        <div class="gb-about-delivery-image gb-image-placeholder">
            <span>Gifting Experience Image</span>
        </div>
        <div class="gb-about-delivery-text">
            <span class="gb-about-label">SPREADING HAPPINESS</span>
            <h2>Delivering Love,<br>One Gift at a Time</h2>
            <p>
                From birthdays and anniversaries to festivals and little
                surprises, we help you make every occasion special.
            </p>
        </div>
    </section>

    {{-- Features --}}
    <section class="gb-about-section gb-about-features">
        <span class="gb-about-label">THE GIFTBLOOM EXPERIENCE</span>
        <h2>The Perfect Surprise</h2>

        <div class="gb-about-feature-grid">
            <div class="gb-about-feature">
                <div class="gb-feature-placeholder gb-image-placeholder">
                    <span>Feature Image 1</span>
                </div>
                <h3>Thoughtful Gifts</h3>
                <p>Discover gifts for every person and every occasion.</p>
            </div>

            <div class="gb-about-feature">
                <div class="gb-feature-placeholder gb-image-placeholder">
                    <span>Feature Image 2</span>
                </div>
                <h3>Convenient Shopping</h3>
                <p>Find something special with a simple shopping experience.</p>
            </div>

            <div class="gb-about-feature">
                <div class="gb-feature-placeholder gb-image-placeholder">
                    <span>Feature Image 3</span>
                </div>
                <h3>Personal Touch</h3>
                <p>Make every celebration more memorable with meaningful gifts.</p>
            </div>
        </div>
    </section>

    {{-- Vision and Mission --}}
    <section class="gb-about-vision-mission">
        <div class="gb-about-vm-card">
            <div class="gb-vm-image gb-image-placeholder">
                <span>Vision Image</span>
            </div>
            <div class="gb-vm-text">
                <h2>Our Vision</h2>
                <p>To make thoughtful gifting a joyful part of every celebration.</p>
            </div>
        </div>

        <div class="gb-about-vm-card">
            <div class="gb-vm-image gb-image-placeholder">
                <span>Mission Image</span>
            </div>
            <div class="gb-vm-text">
                <h2>Our Mission</h2>
                <p>To help people share love and happiness through convenient, meaningful gifting.</p>
            </div>
        </div>
    </section>

    {{-- Milestones --}}
    <section class="gb-about-section gb-about-milestones">
        <span class="gb-about-label">OUR STORY IN PROGRESS</span>
        <h2>Our Journey & Milestones</h2>

        <div class="gb-milestone-grid">
            <div class="gb-milestone">
                <div class="gb-milestone-year">01</div>
                <h3>A Thought Begins</h3>
                <p>The idea of making gifting more special.</p>
            </div>
            <div class="gb-milestone">
                <div class="gb-milestone-year">02</div>
                <h3>Growing Together</h3>
                <p>Building a collection for different occasions.</p>
            </div>
            <div class="gb-milestone">
                <div class="gb-milestone-year">03</div>
                <h3>Creating Memories</h3>
                <p>Helping customers celebrate meaningful moments.</p>
            </div>
            <div class="gb-milestone">
                <div class="gb-milestone-year">04</div>
                <h3>Looking Ahead</h3>
                <p>Making every gifting experience even better.</p>
            </div>
        </div>
    </section>

    {{-- Achievements --}}
    <section class="gb-about-section gb-about-awards">
        <span class="gb-about-label">WHAT MATTERS TO US</span>
        <h2>Our Commitments</h2>

        <div class="gb-awards-grid">
            <div class="gb-award">Thoughtful Selection</div>
            <div class="gb-award">Customer Happiness</div>
            <div class="gb-award">Easy Shopping</div>
            <div class="gb-award">Special Occasions</div>
            <div class="gb-award">Meaningful Gifting</div>
            <div class="gb-award">Continuous Improvement</div>
        </div>
    </section>

    {{-- Team --}}
    <section class="gb-about-section gb-about-team">
        <span class="gb-about-label">THE PEOPLE BEHIND GIFTBLOOM</span>
        <h2>Meet Our Team</h2>
        <p class="gb-about-section-intro">
            The people and passion behind every gifting experience.
        </p>

        <div class="gb-about-team-grid">
            <div class="gb-team-card">
                <div class="gb-team-image gb-image-placeholder"><span>Team Image 1</span></div>
                <h3>Team Member</h3>
                <p>Founder / Leadership</p>
            </div>
            <div class="gb-team-card">
                <div class="gb-team-image gb-image-placeholder"><span>Team Image 2</span></div>
                <h3>Team Member</h3>
                <p>Creative Team</p>
            </div>
            <div class="gb-team-card">
                <div class="gb-team-image gb-image-placeholder"><span>Team Image 3</span></div>
                <h3>Team Member</h3>
                <p>Customer Experience</p>
            </div>
            <div class="gb-team-card">
                <div class="gb-team-image gb-image-placeholder"><span>Team Image 4</span></div>
                <h3>Team Member</h3>
                <p>Operations</p>
            </div>
        </div>
    </section>

    {{-- Statistics --}}
    <section class="gb-about-stats">
        <div><h2>Gifting</h2><p>Made Meaningful</p></div>
        <div><h2>Every</h2><p>Special Occasion</p></div>
        <div><h2>One</h2><p>Thoughtful Experience</p></div>
        <div><h2>Always</h2><p>Growing With You</p></div>
    </section>

</div>
@endsection
