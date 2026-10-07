import './bootstrap';
import Alpine from 'alpinejs';

// Import Swiper and AOS
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import AOS from 'aos';
import 'aos/dist/aos.css';

window.Alpine = Alpine;
Alpine.start();

// Initialize AOS (Animate On Scroll)
AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true,
    offset: 50,
});

// Make Swiper available globally if needed, or init directly in modules
window.Swiper = Swiper;