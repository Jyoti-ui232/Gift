@extends('layouts.app')

@section('content')

<section class="occasion-section"> 
 
    <div class="occasion-container"> 
 
        <h2 class="occasion-title"> 
            Gifts For Every Occasion 
        </h2> 
 
        <div class="occasion-slider-wrapper"> 
 
            <button class="occasion-arrow occasion-left" type="button"> 
                <i class="fa-solid fa-chevron-left"></i> 
            </button> 
 
            <div class="occasion-slider"> 
 
                <div class="occasion-card"> 
                    <div class="occasion-image">
                        <img src="/assets/image/Occasion/birthday.jpg" alt="Birthday">
                    </div>

                    <div class="occasion-card-title"> 
                        Birthday 
                        <i class="fa-solid fa-chevron-right"></i> 
                    </div> 
                </div> 
 
                <div class="occasion-card"> 
                    <div class="occasion-image">
                        <img src="/assets/image/Occasion/anniversary.jpg" alt="Anniversary">
                    </div>

                    <div class="occasion-card-title"> 
                        Anniversary 
                        <i class="fa-solid fa-chevron-right"></i> 
                    </div> 
                </div> 
 
                <div class="occasion-card"> 
                    <div class="occasion-image">
                        <img src="/assets/image/Occasion/Congratulations.jpg" alt="Congratulations">
                    </div>

                    <div class="occasion-card-title"> 
                        Congratulations 
                        <i class="fa-solid fa-chevron-right"></i> 
                    </div> 
                </div> 
 
                <div class="occasion-card"> 
                    <div class="occasion-image">
                        <img src="/assets/image/Occasion/best-wishes.jpg" alt="Best Wishes">
                    </div>

                    <div class="occasion-card-title"> 
                        Best Wishes 
                        <i class="fa-solid fa-chevron-right"></i> 
                    </div> 
                </div> 
 
                <div class="occasion-card"> 
                    <div class="occasion-image">
                        <img src="/assets/image/Occasion/new-born.jpg" alt="New Born">
                    </div>

                    <div class="occasion-card-title"> 
                        New Born 
                        <i class="fa-solid fa-chevron-right"></i> 
                    </div> 
                </div> 
 
                <div class="occasion-card"> 
                    <div class="occasion-image">
                        <img src="/assets/image/Occasion/diwali.jpg" alt="Diwali">
                    </div>

                    <div class="occasion-card-title"> 
                        Diwali 
                        <i class="fa-solid fa-chevron-right"></i> 
                    </div> 
                </div> 
 
                <div class="occasion-card"> 
                    <div class="occasion-image">
                        <img src="/assets/image/Occasion/thank-you.jpg" alt="Thank You">
                    </div>

                    <div class="occasion-card-title"> 
                        Thank You 
                        <i class="fa-solid fa-chevron-right"></i> 
                    </div> 
                </div> 
 
                <div class="occasion-card"> 
                    <div class="occasion-image">
                        <img src="/assets/image/Occasion/boss-day.jpg" alt="Boss Day">
                    </div>

                    <div class="occasion-card-title"> 
                        Boss Day 
                        <i class="fa-solid fa-chevron-right"></i> 
                    </div> 
                </div> 
 
            </div> 
 
            <button class="occasion-arrow occasion-right" type="button"> 
                <i class="fa-solid fa-chevron-right"></i> 
            </button> 
 
        </div> 
 
    </div> 
 
</section>

<!-- banner section start -->
<div id="floralCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">

    <div class="carousel-inner">

        <div class="carousel-item active">
            <div class="floral-banner"><img src="/assets/image/banner.jpg" alt=""></div>
        </div>

        <div class="carousel-item">
            <div class="floral-banner">2</div>
        </div>

        <div class="carousel-item">
            <div class="floral-banner">3</div>
        </div>

        <div class="carousel-item">
            <div class="floral-banner">4</div>
        </div>

        <div class="carousel-item">
            <div class="floral-banner">5</div>
        </div>

    </div>

    <button class="carousel-control-prev"
            type="button"
            data-bs-target="#floralCarousel"
            data-bs-slide="prev">
            <i class="fa-solid fa-chevron-left"></i>
    </button>

    <button class="carousel-control-next"
            type="button"
            data-bs-target="#floralCarousel"
            data-bs-slide="next">
         <i class="fa-solid fa-chevron-right"></i>
    </button>

</div>
<!-- banner section end -->


<!-- bestseller section start -->

<!-- Shop By Bestsellers -->
<section class="bestseller-section">

    <div class="bestseller-container">

        <!-- Heading -->
        <h2 class="bestseller-title">
            Shop By Bestsellers
        </h2>

        <p class="bestseller-subtitle">
            Discover India's favourite gifting options, curated bestsellers that make every celebration extra special
        </p>


        <!-- Categories -->
        <div class="bestseller-categories">

            <div class="bestseller-category active"  data-category="flowers">
                <div class="category-circle"><img src="assets/image/flowers.jpg" alt=""></div>
                <span>Flowers</span>
            </div>

            <div class="bestseller-category"  data-category="cakes">
                <div class="category-circle"><img src="assets/image/cake.jpg" alt=""></div>
                <span>Cakes</span>
            </div>

            <div class="bestseller-category"  data-category="Personalised">
                <div class="category-circle"><img src="assets/image/hamper.jpg" alt=""></div>
                <span>Personalised</span>
            </div>

            <div class="bestseller-category"  data-category="Hampers">
                <div class="category-circle"><img src="assets/image/luxery.jpg" alt=""></div>
                <span>Hampers</span>
            </div>

            <div class="bestseller-category"  data-category="Chocolates">
                <div class="category-circle"><img src="assets/image/chocolates.jpg" alt=""></div>
                <span>Chocolates</span>
            </div>

        </div>


        <!-- Products -->
        <div class="bestseller-slider-wrapper">

            <button class="bestseller-arrow bestseller-left" type="button">
                <i class="fa-solid fa-chevron-left"></i>
            </button>


            <div class="bestseller-slider">

                <!-- Card 1 -->
                <div class="bestseller-card" data-category="flowers">

                    <div class="bestseller-card-image">
                        <div class="card-blob"></div>

                        <div class="card-image-content">
                            <img src="/assets/image/pink-floral.jpg" alt="Pretty in Pink Floral Vase">
                        </div>
                    </div>

                    <div class="bestseller-card-content">

                        <div class="product-name">
                            Pretty in Pink Floral Vase
                        </div>

                        <!-- <span class="personalised-badge">
                            PERSONALISED IT!
                        </span> -->

                        <div class="product-price">
                            ₹xxx
                            <del>₹xxx</del>
                            <span>15% OFF</span>
                        </div>

                        <div class="delivery-text">
                            <i class="fa-solid fa-truck"></i>
                            Earliest Delivery: 10th Oct
                        </div>

                    </div>

                </div>


                <!-- Card 2 -->
                <div class="bestseller-card" data-category="cakes">

                    <div class="bestseller-card-image">
                        <div class="card-blob"></div>

                        <div class="card-image-content">
                            <img src="/assets/image/cake.jpg" alt="Chocolates Truffle Delicious Cake...">
                        </div>
                    </div>

                    <div class="bestseller-card-content">

                        <div class="product-name">
                            Chocolates Truffle Delicious Cake...
                        </div>

                        <!-- <span class="personalised-badge">
                            PERSONALISED IT!
                        </span> -->

                        <div class="product-price">
                            ₹x,xxx
                            <del>₹x,xxx</del>
                            <span>12% OFF</span>
                        </div>

                        <div class="delivery-text">
                            <i class="fa-solid fa-truck"></i>
                            Tomorrow by 9 PM
                        </div>

                    </div>

                </div>


                <!-- Card 3 -->
                <div class="bestseller-card" data-category="Personalised">

                    <div class="bestseller-card-image">
                        <div class="card-blob"></div>

                        <div class="card-image-content">
                            <img src="/assets/image/lamp.jpg" alt="Timeless Glow LED Lamp">
                        </div>
                    </div>

                    <div class="bestseller-card-content">

                        <div class="product-name">
                            Timeless Glow LED Lamp
                        </div>

                        <span class="personalised-badge">
                            PERSONALISED IT!
                        </span>

                        <div class="product-price">
                            ₹x,xxx
                            <del>₹x,xxx</del>
                            <span>26% OFF</span>
                        </div>

                        <div class="same-day">
                            Same Day
                        </div>

                    </div>

                </div>


                <!-- Card 4 -->
                <div class="bestseller-card" data-category="Hampers">

                    <div class="bestseller-card-image">
                        <div class="card-blob"></div>

                        <div class="card-image-content">
                            <img src="/assets/image/hamper.jpg" alt="Ying & Yung Anniversary Hamper">
                        </div>
                    </div>

                    <div class="bestseller-card-content" >

                        <div class="product-name">
                            Ying & Yung Anniversary Hamper
                        </div>

                        <!-- <span class="personalised-badge">
                            PERSONALISED IT!
                        </span> -->

                        <div class="product-price">
                            ₹xxx
                            <del>₹xxx</del>
                            <span>15% OFF</span>
                        </div>

                        <div class="delivery-text">
                            <i class="fa-solid fa-truck"></i>
                            Earliest Delivery: 10th Oct
                        </div>

                    </div>

                </div>


                <!-- Card 5 -->
                <div class="bestseller-card" data-category="Chocolates">

                    <div class="bestseller-card-image">
                        <div class="card-blob"></div>

                        <div class="card-image-content">
                           <img src="/assets/image/chocolates.jpg" alt="Classic Favourites Chocolates">
                        </div>
                    </div>

                    <div class="bestseller-card-content">

                        <div class="product-name">
                            Classic Favourites Chocolates
                        </div>

                        <!-- <span class="personalised-badge">
                            PERSONALISED IT!
                        </span> -->

                        <div class="product-price">
                            ₹xxx
                            <del>₹xxx</del>
                            <span>33% OFF</span>
                        </div>

                        <div class="delivery-text">
                            <i class="fa-solid fa-truck"></i>
                            Earliest Delivery: 10th Oct
                        </div>

                    </div>

                </div>

            </div>


            <button class="bestseller-arrow bestseller-right" type="button">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

        </div>


        <!-- View All -->
        <button class="view-all-bestseller">
            View All Personalised
            <i class="fa-solid fa-chevron-right"></i>
        </button>

    </div>

</section>

 <!-- bestseller section end -->

<!-- Designer Floral Edit -->
<section class="designer-floral-section">

    <div class="designer-floral-overlay">

        <div class="designer-floral-heading">
            <h2>Designer Floral Edit</h2>
        </div>

        <div class="designer-floral-cards">

            <!-- Card 1 -->
            <div class="designer-floral-card">
                <div class="designer-floral-image">
                    <img src="{{ asset('assets/image/roses.jpg') }}" alt="Designer Floral Bouquet">
                </div>
                <div class="designer-floral-content">
                    <h3>Elegant Roses</h3>
                    <button>Order Now <i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="designer-floral-card">
                <div class="designer-floral-image">
                    <img src="/assets/image/blooms.jpg" alt="Designer Floral Bouquet">
                </div>
                <div class="designer-floral-content">
                    <h3>Premium Blooms</h3>
                    <button>Order Now <i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="designer-floral-card">
                <div class="designer-floral-image">
                    <img src="/assets/image/pastel.jpg" alt="Designer Floral Bouquet">
                </div>
                <div class="designer-floral-content">
                    <h3>Pastel Flowers</h3>
                    <button>Order Now <i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="designer-floral-card">
                <div class="designer-floral-image">
                    <img src="/assets/image/luxery.jpg" alt="Designer Floral Bouquet">
                </div>
                <div class="designer-floral-content">
                    <h3>Luxury Bouquet</h3>
                    <button>Order Now <i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

        </div>

    </div>

</section>

<!-- one banner section start -->

<div class="banner">
    <img src="/assets/image/banner.jpg" alt="">
</div>

<!-- one banner section end -->

<!-- cards section start -->
<section class="gift-card-section">
 <div id="parent-card">
  <div class="card">
    <div class="cards">
     <img src="/assets/image/flowers.jpg" alt="">
    </div>
    <h3 class="card-heading">Flowers</h3>
  </div>
  <div class="card">
    <div class="cards">
     <img src="/assets/image/cakes.jpg" alt="">
    </div>
    <h3 class="card-heading">Cakes</h3>
  </div>
  <div class="card">
    <div class="cards">
     <img src="/assets/image/hamper.jpg" alt="">
    </div>
    <h3 class="card-heading">Personalised</h3>
  </div>
  <div class="card">
    <div class="cards">
     <img src="/assets/image/plants.jpg" alt="">
    </div>
    <h3 class="card-heading">Plants</h3>
  </div>
  <div class="card">
    <div class="cards">
     <img src="/assets/image/hamper.jpg" alt="">
    </div>
    <h3 class="card-heading">Hampers</h3>
  </div>
 </div>
</section>
<!-- cards section end -->

<!-- favourite flower section start --> 
<section class="gift-category-section">
 <h2 class="gift-category-title">Pick their favourite Flower</h2>
    <div class="gift-category-container">

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <img src="{{ asset('assets/image/roses.jpg') }}" alt="Gift Hampers">
            </div>

            <h3 class="gift-category-heading">Roses</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Flowers"> -->
            </div>

            <h3 class="gift-category-heading">Orchids</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Birthday Gifts"> -->
            </div>

            <h3 class="gift-category-heading">Carnations</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Anniversary Gifts"> -->
            </div>

            <h3 class="gift-category-heading">Gerberas</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Special Gifts"> -->
            </div>

            <h3 class="gift-category-heading">Sunflowers</h3>
        </div>

    </div>

</section>
<!-- favourite flower section end -->

<!-- Cakes section start -->
<section class="gift-category-section1">
  <h2 class="gift-category-title">Freshly Baked Cakes</h2>
    <div class="gift-category-container">

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <img src="/assets/image/cakes.jpg" alt="Gift Hampers">
            </div>

            <h3 class="gift-category-heading">Chocolate</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Flowers"> -->
            </div>

            <h3 class="gift-category-heading">Butterscotch</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Birthday Gifts"> -->
            </div>

            <h3 class="gift-category-heading">Fresh Fruit</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <img src="/assets/image/chocolates.jpg" alt="Anniversary Gifts">
            </div>

            <h3 class="gift-category-heading">Combos</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Special Gifts"> -->
            </div>

            <h3 class="gift-category-heading">Pineapple</h3>
        </div>

    </div>

</section>
<!-- cakes section end  -->
<!-- every feeling start -->
<section class="gift-category-section2">
 <h2 class="gift-category-title">Gifts for Every Feeling</h2>
    <div class="gift-category-container">

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <img src="/assets/image/luxery.jpg" alt="Gift Hampers">
            </div>

            <h3 class="gift-category-title1">Love & Romance</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Flowers"> -->
            </div>

            <h3 class="gift-category-title1">Thinking of you</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Birthday Gifts"> -->
            </div>

            <h3 class="gift-category-title1">Miss You</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Anniversary Gifts"> -->
            </div>

            <h3 class="gift-category-title1">I am Sorry</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Special Gifts"> -->
            </div>

            <h3 class="gift-category-title1">Sympathy</h3>
        </div>

    </div>

</section>
<!-- every feeling end -->

<!-- plants section start -->
<section class="gift-category-section3">
 <h2 class="gift-category-title">Plants for Every Vibe</h2>
    <div class="gift-category-container">

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <img src="/assets/image/plants.jpg" alt="Gift Hampers">
            </div>

            <h3 class="gift-category-heading">Indoor Plants</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Flowers"> -->
            </div>

            <h3 class="gift-category-heading">Money Plants</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Birthday Gifts"> -->
            </div>

            <h3 class="gift-category-heading">Lucky Bamboo</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Anniversary Gifts"> -->
            </div>

            <h3 class="gift-category-heading">Peace Lily</h3>
        </div>

        <div class="gift-category-card">
            <div class="gift-category-overlay"></div>

            <div class="gift-category-inner">
                <!-- <img src="/assets/image/hampers.webp" alt="Special Gifts"> -->
            </div>

            <h3 class="gift-category-heading">All Plants</h3>
        </div>

    </div>

</section>
<!-- plants section end -->

<!-- video section start -->
<section class="gift-video-section">

    <h2 class="gift-video-title">Watch & Pick Your Favourite</h2>

    <div class="gift-video-container">

        <div class="gift-video-card">
            <video>
                <source src="/assets/image/video/video1.mp4" type="video/mp4">
            </video>

            <button class="gift-video-play" type="button">
                <i class="fa-solid fa-play"></i>
            </button>
        </div>

        <div class="gift-video-card">
            <video>
                <source src="/assets/videos/video2.mp4" type="video/mp4">
            </video>

            <button class="gift-video-play" type="button">
                <i class="fa-solid fa-play"></i>
            </button>
        </div>

        <div class="gift-video-card">
            <video>
                <source src="/assets/videos/video3.mp4" type="video/mp4">
            </video>

            <button class="gift-video-play" type="button">
                <i class="fa-solid fa-play"></i>
            </button>
        </div>

        <div class="gift-video-card">
            <video>
                <source src="/assets/videos/video4.mp4" type="video/mp4">
            </video>

            <button class="gift-video-play" type="button">
                <i class="fa-solid fa-play"></i>
            </button>
        </div>

        <div class="gift-video-card">
            <video>
                <source src="/assets/videos/video5.mp4" type="video/mp4">
            </video>

            <button class="gift-video-play" type="button">
                <i class="fa-solid fa-play"></i>
            </button>
        </div>

    </div>

</section>
<!-- video section end -->

@endsection