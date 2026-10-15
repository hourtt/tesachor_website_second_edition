import "./bootstrap";
import Alpine from "alpinejs";

// Import Swiper and AOS
import Swiper from "swiper/bundle";
import "swiper/css/bundle";
import AOS from "aos";
import "aos/dist/aos.css";
import "./image-cropper";
import "./destination-form";
window.Alpine = Alpine;
Alpine.start();

// Initialize AOS (Animate On Scroll)
AOS.init({
    duration: 800,
    easing: "ease-out-cubic",
    once: true,
    offset: 50,
});

// Make Swiper available globally if needed, or init directly in modules
window.Swiper = Swiper;

// Initialize Swiper on page load if element exists
document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector(".mySwiper")) {
        new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            speed: 1000,
            centeredSlides: false,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    }
});
