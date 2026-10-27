import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    const slider = document.getElementById('categorySlider');
    const leftArrow = document.getElementById('leftArrow');
    const rightArrow = document.getElementById('rightArrow');

    // Agar category slider page par nahi hai
    if (!slider || !leftArrow || !rightArrow) {
        return;
    }

    function updateArrows() {

        // Left arrow
        if (slider.scrollLeft > 5) {
            leftArrow.style.display = 'flex';
        } else {
            leftArrow.style.display = 'none';
        }

        // Right arrow
        if (
            slider.scrollLeft + slider.clientWidth
            >= slider.scrollWidth - 5
        ) {
            rightArrow.style.display = 'none';
        } else {
            rightArrow.style.display = 'flex';
        }
    }


    function scrollCategories(direction) {

        const scrollAmount = window.innerWidth <= 600
            ? 250
            : 400;

        slider.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });

        setTimeout(updateArrows, 450);
    }


    // Left arrow click
    leftArrow.addEventListener('click', () => {
        scrollCategories(-1);
    });


    // Right arrow click
    rightArrow.addEventListener('click', () => {
        scrollCategories(1);
    });


    // User manually scroll kare
    slider.addEventListener('scroll', updateArrows);


    // Window resize hone par arrows update
    window.addEventListener('resize', updateArrows);


    // Initial state
    updateArrows();

});


// =========================================================
// DELIVERY LOCATION POPUP
// =========================================================

const deliveryLocation = document.getElementById('deliveryLocation');
const locationOverlay = document.getElementById('locationOverlay');
const locationClose = document.getElementById('locationClose');
const locationConfirm = document.getElementById('locationConfirm');
const clearPincode = document.getElementById('clearPincode');
const locationPincode = document.getElementById('locationPincode');

if (
    deliveryLocation &&
    locationOverlay &&
    locationClose &&
    locationConfirm
) {

    // Open popup
    deliveryLocation.addEventListener('click', () => {
        locationOverlay.classList.add('active');
    });


    // Close popup
    locationClose.addEventListener('click', () => {
        locationOverlay.classList.remove('active');
    });


    // Click outside popup = close
    locationOverlay.addEventListener('click', (event) => {

        if (event.target === locationOverlay) {
            locationOverlay.classList.remove('active');
        }

    });


    // Clear pincode
    if (clearPincode && locationPincode) {

        clearPincode.addEventListener('click', () => {
            locationPincode.value = '';
            locationPincode.focus();
        });

    }


    // Confirm
    locationConfirm.addEventListener('click', () => {

        const pincode = locationPincode.value.trim();

        if (pincode.length !== 6 || !/^\d{6}$/.test(pincode)) {

            alert('Please enter a valid 6 digit pincode.');

            return;
        }

        locationOverlay.classList.remove('active');

        console.log('Selected Pincode:', pincode);

    });

}




document.addEventListener("DOMContentLoaded", function () {

    const slider = document.querySelector(".occasion-slider");
    const leftArrow = document.querySelector(".occasion-left");
    const rightArrow = document.querySelector(".occasion-right");

    if (!slider || !leftArrow || !rightArrow) {
        return;
    }

    // Starting position par left arrow hidden
    leftArrow.style.display = "none";

    // Right arrow visible
    rightArrow.style.display = "flex";

    rightArrow.addEventListener("click", function () {
        slider.scrollBy({
            left: 300,
            behavior: "smooth"
        });
    });

    leftArrow.addEventListener("click", function () {
        slider.scrollBy({
            left: -300,
            behavior: "smooth"
        });
    });

    slider.addEventListener("scroll", function () {

        if (slider.scrollLeft > 5) {
            leftArrow.style.display = "flex";
        } else {
            leftArrow.style.display = "none";
        }

    });

});




// =========================================
// Designer Floral Edit - Looping Slider
// =========================================

document.addEventListener("DOMContentLoaded", function () {

    const slider = document.querySelector(".floral-slider");
    const slides = document.querySelectorAll(".floral-slide");
    const leftArrow = document.querySelector(".floral-left");
    const rightArrow = document.querySelector(".floral-right");

    if (!slider || !slides.length || !leftArrow || !rightArrow) {
        return;
    }

    let currentSlide = 0;

    function moveSlider() {

        const slideWidth = slides[0].offsetWidth;
        const gap = 22;

        slider.scrollTo({
            left: currentSlide * (slideWidth + gap),
            behavior: "smooth"
        });
    }

    // RIGHT → 1 > 2 > 3 > 4 > 5 > 1
    rightArrow.addEventListener("click", function () {

        currentSlide++;

        if (currentSlide >= slides.length) {
            currentSlide = 0;
        }

        moveSlider();
    });


    // LEFT ← 1 > 5 > 4 > 3 > 2 > 1
    leftArrow.addEventListener("click", function () {

        currentSlide--;

        if (currentSlide < 0) {
            currentSlide = slides.length - 1;
        }

        moveSlider();
    });

});


document.addEventListener("DOMContentLoaded", function () {

    const slider = document.querySelector(".bestseller-slider");
    const leftArrow = document.querySelector(".bestseller-left");
    const rightArrow = document.querySelector(".bestseller-right");

    if (!slider || !leftArrow || !rightArrow) {
        return;
    }

    rightArrow.addEventListener("click", function () {
        slider.scrollBy({
            left: 300,
            behavior: "smooth"
        });
    });

    leftArrow.addEventListener("click", function () {
        slider.scrollBy({
            left: -300,
            behavior: "smooth"
        });
    });

});


document.addEventListener("DOMContentLoaded", function () {

    const categories = document.querySelectorAll(".bestseller-category");
    const cards = document.querySelectorAll(".bestseller-card");

    categories.forEach(category => {

        category.addEventListener("click", function () {

            const selectedCategory = this.dataset.category;

            // Active category change
            categories.forEach(item => {
                item.classList.remove("active");
            });

            this.classList.add("active");

            // Cards filter
            cards.forEach(card => {

                if (card.dataset.category === selectedCategory) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }

            });

        });

    });

});

document.addEventListener("DOMContentLoaded", function () {
    const menuBtn = document.getElementById("mobileMenuBtn");
    const navbar = document.querySelector(".navigation");

    if (menuBtn && navbar) {
        menuBtn.addEventListener("click", function () {
            const isOpen = navbar.classList.toggle("mobile-nav-open");
            menuBtn.setAttribute("aria-expanded", String(isOpen));
            menuBtn.setAttribute("aria-label", isOpen ? "Close menu" : "Open menu");

            const icon = menuBtn.querySelector("i");
            if (icon) {
                icon.classList.toggle("fa-bars", !isOpen);
                icon.classList.toggle("fa-xmark", isOpen);
            }
        });
    }
});


document.querySelectorAll('.gift-video-card').forEach(card => {

    const video = card.querySelector('video');
    const button = card.querySelector('.gift-video-play');
    const icon = button.querySelector('i');

    button.addEventListener('click', function () {

        if (video.paused) {
            video.play();
            icon.classList.remove('fa-play');
            icon.classList.add('fa-pause');
        } else {
            video.pause();
            icon.classList.remove('fa-pause');
            icon.classList.add('fa-play');
        }

    });

});



// PRODUCT LISTING PAGE 

document.addEventListener('DOMContentLoaded', function () {

    // 1. CATEGORY SLIDER
    window.moveCategories = function (direction) {
        const track = document.getElementById('categoryTrack');

        if (!track) return;

        track.scrollBy({
            left: direction * 250,
            behavior: 'smooth'
        });
    };


    // 2. PRODUCT FILTER ELEMENTS
    const cards = document.querySelectorAll('.product-card');
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    const applyButton = document.getElementById('applyPriceFilter');
    const resetButton = document.getElementById('resetFilters');

    const checkboxes = document.querySelectorAll(
        '.product-filters input[type="checkbox"]'
    );


    // 3. APPLY ALL FILTERS
    function applyFilters() {

        const minPrice = minPriceInput && minPriceInput.value !== ''
            ? Number(minPriceInput.value)
            : 0;

        const maxPrice = maxPriceInput && maxPriceInput.value !== ''
            ? Number(maxPriceInput.value)
            : Infinity;

        const selectedTypes = Array.from(checkboxes)
            .filter(function (checkbox) {
                return checkbox.checked &&
                    checkbox.value !== 'same-day';
            })
            .map(function (checkbox) {
                return checkbox.value;
            });

        const sameDaySelected = Array.from(checkboxes).some(
            function (checkbox) {
                return checkbox.checked &&
                    checkbox.value === 'same-day';
            }
        );

        cards.forEach(function (card) {

            const price = Number(card.dataset.price);
            const category = card.dataset.category;
            const delivery = card.dataset.delivery;

            const priceMatch =
                price >= minPrice && price <= maxPrice;

            const categoryMatch =
                selectedTypes.length === 0 ||
                selectedTypes.includes(category);

            const deliveryMatch =
                !sameDaySelected || delivery === 'same-day';

            const isVisible =
                priceMatch && categoryMatch && deliveryMatch;

            card.style.display = isVisible ? '' : 'none';
        });
    }


    // 4. APPLY FILTER BUTTON
    if (applyButton) {
        applyButton.addEventListener('click', function () {

            const min = minPriceInput.value;
            const max = maxPriceInput.value;

            if (
                min !== '' &&
                max !== '' &&
                Number(min) > Number(max)
            ) {
                alert('Minimum price maximum price se zyada nahi ho sakta.');
                return;
            }

            applyFilters();
        });
    }


    // 5. CHECKBOX FILTERS
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', applyFilters);
    });


    // 6. RESET ALL FILTERS
    if (resetButton) {
        resetButton.addEventListener('click', function () {

            if (minPriceInput) minPriceInput.value = '';
            if (maxPriceInput) maxPriceInput.value = '';

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            applyFilters();
        });
    }


   
    

 // 7. PRODUCT IMAGE SLIDER
    // Listen on the card so hover still works when a stretched details link sits above the gallery.
    const galleries = document.querySelectorAll('.product-gallery');
    galleries.forEach(function (gallery) {
        const images = gallery.querySelectorAll('img');
        const dots = gallery.querySelectorAll('.slider-dots span');
        const card = gallery.closest('.product-card') || gallery;

        if (images.length <= 1) return;

        let currentIndex = 0;
        let timer = null;

        function showImage(index) {
            images.forEach(function (image, i) {
                image.classList.toggle('active', i === index);
            });

            dots.forEach(function (dot, i) {
                dot.classList.toggle('active', i === index);
            });

            currentIndex = index;
        }

        function startSlider() {
            if (timer !== null) return;

            timer = setInterval(function () {
                showImage((currentIndex + 1) % images.length);
            }, 1500);
        }

        function stopSlider() {
            if (timer !== null) {
                clearInterval(timer);
                timer = null;
            }
            showImage(0);
        }

        card.addEventListener('mouseenter', startSlider);
        card.addEventListener('mouseleave', stopSlider);

        gallery.addEventListener('touchstart', function () {
            startSlider();
        }, { passive: true });

        showImage(0);
    });


    // 8. WISHLIST / DOT CONTROLS MUST NOT BE BLOCKED BY CARD LINK
    document.querySelectorAll('.product-card .wishlist-btn').forEach(function (wishlistBtn) {
        wishlistBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            if (wishlistBtn.dataset.busy === '1') {
                return;
            }

            const wishlistUrl = wishlistBtn.dataset.wishlistUrl;
            const loginUrl = wishlistBtn.dataset.loginUrl || '/login';
            const productName = wishlistBtn.dataset.productName || 'product';

            if (!wishlistUrl) {
                alert('Please log in to add items to your wishlist.');
                window.location.href = loginUrl;
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                alert('Unable to update wishlist. Please refresh the page and try again.');
                return;
            }

            wishlistBtn.dataset.busy = '1';
            wishlistBtn.disabled = true;

            fetch(wishlistUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify({}),
            })
                .then(function (response) {
                    if (response.status === 401 || response.status === 419) {
                        alert('Please log in to add items to your wishlist.');
                        window.location.href = loginUrl;
                        return null;
                    }

                    if (!response.ok) {
                        throw new Error('Wishlist request failed');
                    }

                    return response.json();
                })
                .then(function (data) {
                    if (!data || !data.success) {
                        return;
                    }

                    const isWishlisted = data.action === 'added';
                    wishlistBtn.dataset.wishlisted = isWishlisted ? '1' : '0';
                    wishlistBtn.setAttribute('aria-pressed', isWishlisted ? 'true' : 'false');
                    wishlistBtn.setAttribute(
                        'aria-label',
                        (isWishlisted ? 'Remove ' : 'Add ') +
                            productName +
                            (isWishlisted ? ' from wishlist' : ' to wishlist')
                    );
                    wishlistBtn.textContent = isWishlisted ? '♥' : '♡';
                })
                .catch(function () {
                    alert('Unable to update wishlist. Please try again.');
                })
                .finally(function () {
                    wishlistBtn.dataset.busy = '0';
                    wishlistBtn.disabled = false;
                });
        });
    });

    document.querySelectorAll('.product-card .slider-dots').forEach(function (dots) {
        dots.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
        });
    });

});
