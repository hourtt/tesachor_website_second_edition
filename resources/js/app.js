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

// Register Alpine global i18n store for reactive instant language switching with cross-fade
Alpine.store("i18n", {
    locale: document.documentElement.lang || "en",
    activeButton: document.documentElement.lang || "en",
    isSwitching: false,
    t(key) {
        if (!window.translations || !window.translations[this.locale]) {
            return key;
        }
        const dict = window.translations[this.locale];
        if (dict[key] !== undefined && typeof dict[key] !== "object") return dict[key];

        const dotIndex = key.lastIndexOf(".");
        if (dotIndex !== -1) {
            const file = key.substring(0, dotIndex);
            const subKey = key.substring(dotIndex + 1);
            if (dict[file] && dict[file][subKey] !== undefined) {
                return dict[file][subKey];
            }
        }

        const parts = key.split(".");
        let cur = dict;
        let found = true;
        for (const p of parts) {
            if (cur && typeof cur === "object" && p in cur) {
                cur = cur[p];
            } else {
                found = false;
                break;
            }
        }
        if (found && cur !== undefined && typeof cur !== "object") {
            return cur;
        }

        if (dict.landing && dict.landing[key] !== undefined) return dict.landing[key];
        if (dict["landing/landing"] && dict["landing/landing"][key] !== undefined) return dict["landing/landing"][key];
        if (dict.navbar && dict.navbar[key] !== undefined) return dict.navbar[key];

        return key;
    },
    switchLang(code) {
        if (code === this.activeButton) return;
        this.activeButton = code;
        this.isSwitching = true;
        document.body.classList.add("lang-switching");

        setTimeout(() => {
            this.locale = code;
            document.documentElement.lang = code;
            try {
                localStorage.setItem("locale", code);
            } catch (e) {}
            this.isSwitching = false;
            document.body.classList.remove("lang-switching");
        }, 160);

        fetch("/lang/" + code, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        }).catch(() => {});
    }
});

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
