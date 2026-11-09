import "./bootstrap";
import Alpine from "alpinejs";
import registerRichEditor from "./rich-editor";
import "./image-cropper";
import "./destination-form";

window.Alpine = Alpine;

const savedLocale = (() => {
    try {
        const item = localStorage.getItem("locale");
        return item === "km" || item === "en" ? item : null;
    } catch (e) {
        return null;
    }
})();

const initialLocale = savedLocale || document.documentElement.lang || "en";
if (document.documentElement.lang !== initialLocale) {
    document.documentElement.lang = initialLocale;
    if (savedLocale) {
        fetch("/lang/" + savedLocale, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        }).catch(() => {});
    }
}

// Register Alpine global i18n store for reactive instant language switching with cross-fade
Alpine.store("i18n", {
    locale: initialLocale,
    activeButton: initialLocale,
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
        if (dict["landing"] && dict["landing"][key] !== undefined) return dict["landing"][key];
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

// Register rich editor component (engine is lazy-loaded on demand)
registerRichEditor(Alpine);
Alpine.start();

// Lazy load & initialize AOS only if data-aos elements exist on the page
let aosPromise = null;
const initAOS = async () => {
    if (!document.querySelector("[data-aos]")) return;

    if (!aosPromise) {
        aosPromise = Promise.all([
            import("aos"),
            import("aos/dist/aos.css"),
        ]).then(([aosModule]) => {
            const AOS = aosModule.default;
            AOS.init({
                duration: 800,
                easing: "ease-out-cubic",
                once: true,
                offset: 50,
            });
            window.AOS = AOS;
            return AOS;
        });
    } else {
        const AOS = await aosPromise;
        AOS.refresh();
    }
};

// Lazy load & initialize Swiper only if .mySwiper elements exist on the page
let swiperPromise = null;
const initSwiper = async () => {
    const swiperEl = document.querySelector(".mySwiper");
    if (!swiperEl) return;

    if (!swiperPromise) {
        swiperPromise = Promise.all([
            import("swiper/bundle"),
            import("swiper/css/bundle"),
        ]).then(([swiperModule]) => {
            const Swiper = swiperModule.default;
            window.Swiper = Swiper;
            return Swiper;
        });
    }

    const Swiper = await swiperPromise;
    new Swiper(swiperEl, {
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
};

// Global async getters for external/inline scripts if needed
window.loadSwiper = async () => {
    if (window.Swiper) return window.Swiper;
    if (!swiperPromise) {
        swiperPromise = Promise.all([
            import("swiper/bundle"),
            import("swiper/css/bundle"),
        ]).then(([swiperModule]) => {
            const Swiper = swiperModule.default;
            window.Swiper = Swiper;
            return Swiper;
        });
    }
    return swiperPromise;
};

window.loadAOS = async () => {
    if (window.AOS) return window.AOS;
    if (!aosPromise) {
        aosPromise = Promise.all([
            import("aos"),
            import("aos/dist/aos.css"),
        ]).then(([aosModule]) => {
            const AOS = aosModule.default;
            window.AOS = AOS;
            return AOS;
        });
    }
    return aosPromise;
};

// Initialize dynamic components on DOM ready and Livewire navigation
const initInteractiveComponents = () => {
    initAOS();
    initSwiper();
};

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initInteractiveComponents);
} else {
    initInteractiveComponents();
}

document.addEventListener("livewire:navigated", initInteractiveComponents);
