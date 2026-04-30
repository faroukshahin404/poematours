(() => {
    const destinationToggle = document.querySelector("[data-destination-toggle]");
    const destinationMenu = document.querySelector("[data-destination-menu]");
    const destinationClose = document.querySelector("[data-destination-close]");
    const siteHeader = document.querySelector("[data-site-header]");
    const categoryButtons = document.querySelectorAll(".mega-menu__category");
    const previewTitle = document.querySelector("[data-preview-target-title]");
    const previewImage = document.querySelector("[data-preview-target-image]");

    const mobileToggle = document.querySelector("[data-mobile-menu-toggle]");
    const mobileMenu = document.querySelector("[data-mobile-menu]");
    const mobileClose = document.querySelector("[data-mobile-menu-close]");
    const mobileBackdrop = document.querySelector("[data-mobile-menu-backdrop]");
    const mobileDestinationToggle = document.querySelector("[data-mobile-destination-toggle]");
    const mobileDestinationList = document.querySelector("[data-mobile-destination-list]");

    if (siteHeader) {
        const isHomePage = document.body.classList.contains("is-homepage");
        const stickySubnav = document.querySelector("[data-sticky-subnav]");
        const headerScrollThreshold = 48;
        let previousScrollY = window.scrollY;

        const syncHeaderState = () => {
            const currentScrollY = window.scrollY;

            if (isHomePage) {
                const isScrolled = currentScrollY > headerScrollThreshold;
                siteHeader.classList.toggle("site-header--overlay", !isScrolled);
                siteHeader.classList.toggle("site-header--scrolled", isScrolled);
                siteHeader.classList.remove("site-header--solid");
                siteHeader.classList.remove("site-header--hidden");
                previousScrollY = currentScrollY;
                return;
            }

            siteHeader.classList.remove("site-header--overlay");
            siteHeader.classList.add("site-header--scrolled");
            siteHeader.classList.remove("site-header--solid");

            if (stickySubnav) {
                const isScrollingDown = currentScrollY > previousScrollY;
                const passedHideThreshold = currentScrollY > 120;
                siteHeader.classList.toggle("site-header--hidden", isScrollingDown && passedHideThreshold);
            } else {
                siteHeader.classList.remove("site-header--hidden");
            }

            previousScrollY = currentScrollY;
        };

        requestAnimationFrame(() => {
            siteHeader.classList.remove("site-header--preload");
        });

        window.addEventListener("scroll", syncHeaderState, { passive: true });
        syncHeaderState();
    }

    if (destinationToggle && destinationMenu) {
        const openDestinationMenu = () => {
            destinationMenu.classList.add("is-open");
            destinationMenu.setAttribute("aria-hidden", "false");
            destinationToggle.setAttribute("aria-expanded", "true");
        };

        const closeDestinationMenu = () => {
            destinationMenu.classList.remove("is-open");
            destinationMenu.setAttribute("aria-hidden", "true");
            destinationToggle.setAttribute("aria-expanded", "false");
        };

        destinationToggle.addEventListener("click", () => {
            const isOpen = destinationMenu.classList.contains("is-open");
            if (isOpen) {
                closeDestinationMenu();
                return;
            }
            openDestinationMenu();
        });

        if (destinationClose) {
            destinationClose.addEventListener("click", closeDestinationMenu);
        }

        document.addEventListener("click", (event) => {
            const clickedInsideMenu = destinationMenu.contains(event.target);
            const clickedToggle = destinationToggle.contains(event.target);
            if (!clickedInsideMenu && !clickedToggle) {
                closeDestinationMenu();
            }
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                closeDestinationMenu();
                closeMobileMenu();
            }
        });
    }

    categoryButtons.forEach((button) => {
        button.addEventListener("click", () => {
            categoryButtons.forEach((item) => item.classList.remove("is-active"));
            button.classList.add("is-active");

            if (previewTitle) {
                previewTitle.textContent = button.dataset.previewLabel || "Destination";
            }

            if (previewImage && button.dataset.previewSrc) {
                previewImage.src = button.dataset.previewSrc;
            }
        });
    });

    const openMobileMenu = () => {
        if (!mobileMenu || !mobileToggle) {
            return;
        }

        mobileMenu.classList.add("is-open");
        mobileMenu.setAttribute("aria-hidden", "false");
        mobileToggle.setAttribute("aria-expanded", "true");
        if (mobileBackdrop) {
            mobileBackdrop.classList.add("is-open");
            mobileBackdrop.setAttribute("aria-hidden", "false");
        }
        document.body.classList.add("mobile-menu-open");
        document.body.style.overflow = "hidden";
    };

    const closeMobileMenu = () => {
        if (!mobileMenu || !mobileToggle) {
            return;
        }

        mobileMenu.classList.remove("is-open");
        mobileMenu.setAttribute("aria-hidden", "true");
        mobileToggle.setAttribute("aria-expanded", "false");
        if (mobileBackdrop) {
            mobileBackdrop.classList.remove("is-open");
            mobileBackdrop.setAttribute("aria-hidden", "true");
        }
        document.body.classList.remove("mobile-menu-open");
        document.body.style.overflow = "";
    };

    if (mobileToggle) {
        mobileToggle.addEventListener("click", openMobileMenu);
    }

    if (mobileClose) {
        mobileClose.addEventListener("click", closeMobileMenu);
    }

    if (mobileBackdrop) {
        mobileBackdrop.addEventListener("click", closeMobileMenu);
    }

    if (mobileDestinationToggle) {
        mobileDestinationToggle.addEventListener("click", () => {
            if (!mobileDestinationList) {
                return;
            }

            const isExpanded = mobileDestinationToggle.getAttribute("aria-expanded") === "true";
            mobileDestinationToggle.setAttribute("aria-expanded", String(!isExpanded));
            mobileDestinationList.classList.toggle("is-open", !isExpanded);
            mobileDestinationList.setAttribute("aria-hidden", String(isExpanded));
        });
    }

    const heroSlider = document.querySelector("[data-hero-slider]");
    if (heroSlider) {
        const slides = Array.from(heroSlider.querySelectorAll("[data-hero-slide]"));
        const prevButton = document.querySelector("[data-hero-prev]");
        const nextButton = document.querySelector("[data-hero-next]");
        let activeIndex = 0;
        let autoplayTimer = null;
        const autoplayDelay = 5000;

        const renderHeroSlide = () => {
            slides.forEach((slide, index) => {
                slide.classList.toggle("is-active", index === activeIndex);
            });
        };

        const goToHeroSlide = (index) => {
            if (!slides.length) {
                return;
            }
            activeIndex = (index + slides.length) % slides.length;
            renderHeroSlide();
        };

        const startAutoplay = () => {
            if (slides.length <= 1) {
                return;
            }
            clearInterval(autoplayTimer);
            autoplayTimer = window.setInterval(() => {
                goToHeroSlide(activeIndex + 1);
            }, autoplayDelay);
        };

        if (prevButton) {
            prevButton.addEventListener("click", () => {
                goToHeroSlide(activeIndex - 1);
                startAutoplay();
            });
        }

        if (nextButton) {
            nextButton.addEventListener("click", () => {
                goToHeroSlide(activeIndex + 1);
                startAutoplay();
            });
        }

        heroSlider.addEventListener("mouseenter", () => clearInterval(autoplayTimer));
        heroSlider.addEventListener("mouseleave", startAutoplay);

        renderHeroSlide();
        startAutoplay();
    }

    const priceRangeInput = document.querySelector("[data-price-range]");
    if (priceRangeInput) {
        const outputId = priceRangeInput.dataset.priceOutputId;
        const outputElement = outputId ? document.getElementById(outputId) : null;

        const updatePriceRangeLabel = () => {
            if (!outputElement) {
                return;
            }

            const formattedValue = Number(priceRangeInput.value).toLocaleString();
            outputElement.textContent = `$${formattedValue}`;
        };

        priceRangeInput.addEventListener("input", updatePriceRangeLabel);
        updatePriceRangeLabel();
    }

    const packageFiltersToggle = document.querySelector("[data-packages-filters-toggle]");
    const packageFiltersPanel = document.querySelector("[data-packages-filters-panel]");
    if (packageFiltersToggle && packageFiltersPanel) {
        const mobileMediaQuery = window.matchMedia("(max-width: 768px)");

        const syncPackagesFiltersState = () => {
            const isMobile = mobileMediaQuery.matches;
            if (isMobile) {
                packageFiltersPanel.classList.remove("is-open");
                packageFiltersPanel.setAttribute("aria-hidden", "true");
                packageFiltersToggle.setAttribute("aria-expanded", "false");
                return;
            }

            packageFiltersPanel.classList.add("is-open");
            packageFiltersPanel.setAttribute("aria-hidden", "false");
            packageFiltersToggle.setAttribute("aria-expanded", "true");
        };

        packageFiltersToggle.addEventListener("click", () => {
            if (!mobileMediaQuery.matches) {
                return;
            }

            const isExpanded = packageFiltersToggle.getAttribute("aria-expanded") === "true";
            packageFiltersToggle.setAttribute("aria-expanded", String(!isExpanded));
            packageFiltersPanel.classList.toggle("is-open", !isExpanded);
            packageFiltersPanel.setAttribute("aria-hidden", String(isExpanded));
        });

        if (typeof mobileMediaQuery.addEventListener === "function") {
            mobileMediaQuery.addEventListener("change", syncPackagesFiltersState);
        } else {
            mobileMediaQuery.addListener(syncPackagesFiltersState);
        }

        syncPackagesFiltersState();
    }

    const gallerySwiper = document.querySelector("[data-gallery-swiper]");
    if (gallerySwiper) {
        const slides = Array.from(gallerySwiper.querySelectorAll("[data-gallery-slide]"));
        const thumbs = Array.from(gallerySwiper.querySelectorAll("[data-gallery-thumb]"));
        const counter = gallerySwiper.querySelector("[data-gallery-counter]");
        const prevButton = gallerySwiper.querySelector("[data-gallery-prev]");
        const nextButton = gallerySwiper.querySelector("[data-gallery-next]");
        let activeIndex = 0;

        const renderGallerySlide = () => {
            slides.forEach((slide, index) => {
                slide.classList.toggle("is-active", index === activeIndex);
            });

            thumbs.forEach((thumb, index) => {
                thumb.classList.toggle("is-active", index === activeIndex);
            });

            if (counter) {
                counter.textContent = `${activeIndex + 1} / ${slides.length}`;
            }
        };

        const goToSlide = (index) => {
            if (slides.length === 0) {
                return;
            }

            activeIndex = (index + slides.length) % slides.length;
            renderGallerySlide();
        };

        if (prevButton) {
            prevButton.addEventListener("click", () => goToSlide(activeIndex - 1));
        }

        if (nextButton) {
            nextButton.addEventListener("click", () => goToSlide(activeIndex + 1));
        }

        thumbs.forEach((thumb) => {
            thumb.addEventListener("click", () => {
                const index = Number(thumb.dataset.index || 0);
                goToSlide(index);
            });
        });

        renderGallerySlide();
    }

    const lightbox = document.querySelector("[data-image-lightbox]");
    if (lightbox) {
        const triggers = Array.from(document.querySelectorAll("[data-lightbox-trigger]"));
        const lightboxImage = lightbox.querySelector("[data-lightbox-image]");
        const closeButton = lightbox.querySelector("[data-lightbox-close]");
        const prevButton = lightbox.querySelector("[data-lightbox-prev]");
        const nextButton = lightbox.querySelector("[data-lightbox-next]");
        const zoomIn = lightbox.querySelector("[data-lightbox-zoom-in]");
        const zoomOut = lightbox.querySelector("[data-lightbox-zoom-out]");
        let currentIndex = 0;
        let zoomLevel = 1;

        const renderLightbox = () => {
            const current = triggers[currentIndex];
            if (!current || !lightboxImage) {
                return;
            }

            lightboxImage.src = current.dataset.src || "";
            lightboxImage.style.transform = `scale(${zoomLevel})`;
        };

        const openLightbox = (index) => {
            currentIndex = index;
            zoomLevel = 1;
            renderLightbox();
            lightbox.classList.add("is-open");
            lightbox.setAttribute("aria-hidden", "false");
        };

        const closeLightbox = () => {
            lightbox.classList.remove("is-open");
            lightbox.setAttribute("aria-hidden", "true");
        };

        triggers.forEach((trigger, index) => {
            trigger.addEventListener("click", () => openLightbox(index));
        });

        if (closeButton) {
            closeButton.addEventListener("click", closeLightbox);
        }

        if (prevButton) {
            prevButton.addEventListener("click", () => {
                currentIndex = (currentIndex - 1 + triggers.length) % triggers.length;
                renderLightbox();
            });
        }

        if (nextButton) {
            nextButton.addEventListener("click", () => {
                currentIndex = (currentIndex + 1) % triggers.length;
                renderLightbox();
            });
        }

        if (zoomIn) {
            zoomIn.addEventListener("click", () => {
                zoomLevel = Math.min(zoomLevel + 0.2, 2.4);
                renderLightbox();
            });
        }

        if (zoomOut) {
            zoomOut.addEventListener("click", () => {
                zoomLevel = Math.max(zoomLevel - 0.2, 1);
                renderLightbox();
            });
        }
    }

    const mapZoomContainer = document.querySelector("[data-map-zoom]");
    if (mapZoomContainer) {
        const mapImage = mapZoomContainer.querySelector("[data-map-image]");
        const mapZoomIn = mapZoomContainer.querySelector("[data-map-zoom-in]");
        const mapZoomOut = mapZoomContainer.querySelector("[data-map-zoom-out]");
        let mapScale = 1;
        const pointers = new Map();
        let startDistance = 0;
        let startScale = 1;

        const renderMapScale = () => {
            if (mapImage) {
                mapImage.style.transform = `scale(${mapScale})`;
            }
        };

        if (mapZoomIn) {
            mapZoomIn.addEventListener("click", () => {
                mapScale = Math.min(mapScale + 0.2, 2.2);
                renderMapScale();
            });
        }

        if (mapZoomOut) {
            mapZoomOut.addEventListener("click", () => {
                mapScale = Math.max(mapScale - 0.2, 1);
                renderMapScale();
            });
        }

        const getDistance = (a, b) => {
            const dx = a.clientX - b.clientX;
            const dy = a.clientY - b.clientY;
            return Math.hypot(dx, dy);
        };

        mapZoomContainer.addEventListener("pointerdown", (event) => {
            pointers.set(event.pointerId, event);
            if (pointers.size === 2) {
                const [first, second] = Array.from(pointers.values());
                startDistance = getDistance(first, second);
                startScale = mapScale;
            }
        });

        mapZoomContainer.addEventListener("pointermove", (event) => {
            if (!pointers.has(event.pointerId)) {
                return;
            }

            pointers.set(event.pointerId, event);
            if (pointers.size === 2) {
                const [first, second] = Array.from(pointers.values());
                const currentDistance = getDistance(first, second);
                if (startDistance > 0) {
                    const nextScale = startScale * (currentDistance / startDistance);
                    mapScale = Math.min(Math.max(nextScale, 1), 3);
                    renderMapScale();
                }
            }
        });

        const clearPointer = (event) => {
            pointers.delete(event.pointerId);
            if (pointers.size < 2) {
                startDistance = 0;
                startScale = mapScale;
            }
        };

        mapZoomContainer.addEventListener("pointerup", clearPointer);
        mapZoomContainer.addEventListener("pointercancel", clearPointer);
        mapZoomContainer.addEventListener("pointerleave", clearPointer);
    }

    const itineraryHotelModal = document.querySelector("[data-itinerary-hotel-modal]");
    const itineraryHotelGallery = document.querySelector("[data-itinerary-hotel-gallery]");
    if (itineraryHotelModal && itineraryHotelGallery) {
        const hotelButtons = Array.from(document.querySelectorAll("[data-itinerary-hotel-open]"));
        const modalCloseButtons = itineraryHotelModal.querySelectorAll("[data-itinerary-hotel-close]");
        const modalTitle = itineraryHotelModal.querySelector("[data-itinerary-hotel-title]");
        const modalDescription = itineraryHotelModal.querySelector("[data-itinerary-hotel-description]");
        const mainImage = itineraryHotelModal.querySelector("[data-itinerary-hotel-main-image]");
        const thumbsContainer = itineraryHotelModal.querySelector("[data-itinerary-hotel-thumbs]");
        const openMainImageButton = itineraryHotelModal.querySelector("[data-itinerary-hotel-main-open]");

        const galleryImage = itineraryHotelGallery.querySelector("[data-itinerary-gallery-image]");
        const galleryCloseButton = itineraryHotelGallery.querySelector("[data-itinerary-gallery-close]");
        const galleryPrevButton = itineraryHotelGallery.querySelector("[data-itinerary-gallery-prev]");
        const galleryNextButton = itineraryHotelGallery.querySelector("[data-itinerary-gallery-next]");

        let activeHotelGallery = [];
        let activeHotelImageIndex = 0;

        const renderHotelModalImage = () => {
            const activeSrc = activeHotelGallery[activeHotelImageIndex];
            if (!activeSrc) {
                return;
            }
            if (mainImage) {
                mainImage.src = activeSrc;
            }

            const thumbButtons = thumbsContainer ? Array.from(thumbsContainer.querySelectorAll(".itinerary-hotel-modal__thumb")) : [];
            thumbButtons.forEach((thumb, index) => {
                thumb.classList.toggle("is-active", index === activeHotelImageIndex);
            });
        };

        const renderFullscreenGalleryImage = () => {
            const activeSrc = activeHotelGallery[activeHotelImageIndex];
            if (galleryImage && activeSrc) {
                galleryImage.src = activeSrc;
            }
        };

        const openHotelModal = (button) => {
            try {
                activeHotelGallery = JSON.parse(button.dataset.hotelGallery || "[]");
            } catch (_error) {
                activeHotelGallery = [];
            }
            if (activeHotelGallery.length === 0) {
                activeHotelGallery = [button.dataset.hotelFallback || ""];
            }
            activeHotelImageIndex = 0;

            if (modalTitle) {
                modalTitle.textContent = button.dataset.hotelName || "Hotel";
            }
            if (modalDescription) {
                modalDescription.textContent = button.dataset.hotelDescription || "";
            }

            if (thumbsContainer) {
                thumbsContainer.innerHTML = "";
                activeHotelGallery.forEach((src, index) => {
                    const thumb = document.createElement("button");
                    thumb.type = "button";
                    thumb.className = "itinerary-hotel-modal__thumb";
                    thumb.innerHTML = `<img src="${src}" alt="Hotel gallery thumbnail ${index + 1}">`;
                    thumb.addEventListener("click", () => {
                        activeHotelImageIndex = index;
                        renderHotelModalImage();
                    });
                    thumbsContainer.appendChild(thumb);
                });
            }

            renderHotelModalImage();
            itineraryHotelModal.classList.add("is-open");
            itineraryHotelModal.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
        };

        const closeHotelModal = () => {
            itineraryHotelModal.classList.remove("is-open");
            itineraryHotelModal.setAttribute("aria-hidden", "true");
            itineraryHotelGallery.classList.remove("is-open");
            itineraryHotelGallery.setAttribute("aria-hidden", "true");
            document.body.style.overflow = "";
        };

        const openFullscreenGallery = () => {
            renderFullscreenGalleryImage();
            itineraryHotelGallery.classList.add("is-open");
            itineraryHotelGallery.setAttribute("aria-hidden", "false");
        };

        const closeFullscreenGallery = () => {
            itineraryHotelGallery.classList.remove("is-open");
            itineraryHotelGallery.setAttribute("aria-hidden", "true");
        };

        hotelButtons.forEach((button) => {
            button.addEventListener("click", () => openHotelModal(button));
        });

        modalCloseButtons.forEach((button) => {
            button.addEventListener("click", closeHotelModal);
        });

        if (openMainImageButton) {
            openMainImageButton.addEventListener("click", openFullscreenGallery);
        }

        if (galleryCloseButton) {
            galleryCloseButton.addEventListener("click", closeFullscreenGallery);
        }

        if (galleryPrevButton) {
            galleryPrevButton.addEventListener("click", () => {
                if (!activeHotelGallery.length) return;
                activeHotelImageIndex = (activeHotelImageIndex - 1 + activeHotelGallery.length) % activeHotelGallery.length;
                renderHotelModalImage();
                renderFullscreenGalleryImage();
            });
        }

        if (galleryNextButton) {
            galleryNextButton.addEventListener("click", () => {
                if (!activeHotelGallery.length) return;
                activeHotelImageIndex = (activeHotelImageIndex + 1) % activeHotelGallery.length;
                renderHotelModalImage();
                renderFullscreenGalleryImage();
            });
        }
    }

    const hotelModal = document.querySelector("[data-hotel-modal]");
    if (hotelModal) {
        const openButtons = document.querySelectorAll("[data-hotel-open]");
        const closeButton = hotelModal.querySelector("[data-hotel-modal-close]");
        const image = hotelModal.querySelector("[data-hotel-image]");
        const title = hotelModal.querySelector("[data-hotel-title]");
        const description = hotelModal.querySelector("[data-hotel-description]");
        const price = hotelModal.querySelector("[data-hotel-price]");
        const supplement = hotelModal.querySelector("[data-hotel-supplement]");
        const cabin = hotelModal.querySelector("[data-hotel-cabin]");

        const closeHotelModal = () => {
            hotelModal.classList.remove("is-open");
            hotelModal.setAttribute("aria-hidden", "true");
        };

        openButtons.forEach((button) => {
            button.addEventListener("click", () => {
                if (image) image.src = button.dataset.image || "";
                if (title) title.textContent = button.dataset.title || "Hotel details";
                if (description) description.textContent = button.dataset.description || "";
                if (price) price.textContent = button.dataset.price || "";
                if (supplement) supplement.textContent = button.dataset.supplement || "";
                if (cabin) cabin.textContent = button.dataset.cabin || "";
                hotelModal.classList.add("is-open");
                hotelModal.setAttribute("aria-hidden", "false");
            });
        });

        if (closeButton) {
            closeButton.addEventListener("click", closeHotelModal);
        }
    }

    const monthAccordion = document.querySelector("[data-month-accordion]");
    if (monthAccordion) {
        const monthItems = Array.from(monthAccordion.querySelectorAll("[data-month-item]"));

        monthItems.forEach((item) => {
            const toggleButton = item.querySelector("[data-month-toggle]");
            if (!toggleButton) {
                return;
            }

            toggleButton.addEventListener("click", () => {
                const isOpen = item.classList.contains("is-open");
                monthItems.forEach((entry) => entry.classList.remove("is-open"));
                if (!isOpen) {
                    item.classList.add("is-open");
                }
            });
        });
    }

    const essentialAccordion = document.querySelector("[data-essential-accordion]");
    if (essentialAccordion) {
        const essentialItems = Array.from(essentialAccordion.querySelectorAll("[data-essential-item]"));

        essentialItems.forEach((item) => {
            const toggle = item.querySelector("[data-essential-toggle]");
            if (!toggle) {
                return;
            }

            toggle.addEventListener("click", () => {
                const isOpen = item.classList.contains("is-open");
                essentialItems.forEach((entry) => entry.classList.remove("is-open"));
                if (!isOpen) {
                    item.classList.add("is-open");
                }
            });
        });
    }

    const scrollSpyNavigations = document.querySelectorAll("[data-package-nav], [data-sticky-subnav]");
    scrollSpyNavigations.forEach((navigation) => {
        const navLinks = Array.from(navigation.querySelectorAll("a[href^='#']"));
        if (!navLinks.length) {
            return;
        }

        const sectionIds = navLinks
            .map((link) => link.getAttribute("href") || "")
            .filter((id) => id.startsWith("#") && id.length > 1);
        const sections = sectionIds
            .map((id) => document.querySelector(id))
            .filter((section) => section !== null);

        if (!sections.length) {
            return;
        }

        const setActiveLink = (targetId) => {
            navLinks.forEach((link) => {
                const linkTarget = link.getAttribute("href");
                link.classList.toggle("is-active", linkTarget === targetId);
            });
        };

        const resolveActiveSection = () => {
            const offset = window.scrollY + (window.innerHeight * 0.28);
            let activeId = sectionIds[0] || null;

            sections.forEach((section) => {
                if (!section || !section.id) {
                    return;
                }

                if (section.offsetTop <= offset) {
                    activeId = `#${section.id}`;
                }
            });

            if (activeId) {
                setActiveLink(activeId);
            }
        };

        navLinks.forEach((link) => {
            link.addEventListener("click", () => {
                const targetId = link.getAttribute("href");
                if (targetId) {
                    setActiveLink(targetId);
                }
            });
        });

        window.addEventListener("scroll", resolveActiveSection, { passive: true });
        resolveActiveSection();
    });

    const expertModal = document.querySelector("[data-expert-modal]");
    if (expertModal) {
        const openButtons = document.querySelectorAll("[data-expert-open]");
        const closeButtons = expertModal.querySelectorAll("[data-expert-close]");

        const openExpertModal = () => {
            expertModal.classList.add("is-open");
            expertModal.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
        };

        const closeExpertModal = () => {
            expertModal.classList.remove("is-open");
            expertModal.setAttribute("aria-hidden", "true");
            document.body.style.overflow = "";
        };

        openButtons.forEach((button) => {
            button.addEventListener("click", openExpertModal);
        });

        closeButtons.forEach((button) => {
            button.addEventListener("click", closeExpertModal);
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && expertModal.classList.contains("is-open")) {
                closeExpertModal();
            }
        });
    }

    const experienceModal = document.querySelector("[data-experience-modal]");
    if (experienceModal) {
        const openExperienceButtons = document.querySelectorAll("[data-experience-open]");
        const closeExperienceButtons = experienceModal.querySelectorAll("[data-experience-close]");
        const modalImage = experienceModal.querySelector("[data-experience-modal-image]");
        const modalTitle = experienceModal.querySelector("[data-experience-modal-title]");
        const modalDescription = experienceModal.querySelector("[data-experience-modal-description]");

        const openExperienceModal = (button) => {
            if (modalImage) {
                modalImage.src = button.dataset.experienceImage || "";
                modalImage.alt = button.dataset.experienceTitle || "Experience image";
            }
            if (modalTitle) {
                modalTitle.textContent = button.dataset.experienceTitle || "Experience";
            }
            if (modalDescription) {
                modalDescription.textContent = button.dataset.experienceDescription || "";
            }

            experienceModal.classList.add("is-open");
            experienceModal.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
        };

        const closeExperienceModal = () => {
            experienceModal.classList.remove("is-open");
            experienceModal.setAttribute("aria-hidden", "true");
            document.body.style.overflow = "";
        };

        openExperienceButtons.forEach((button) => {
            button.addEventListener("click", () => openExperienceModal(button));
        });

        closeExperienceButtons.forEach((button) => {
            button.addEventListener("click", closeExperienceModal);
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && experienceModal.classList.contains("is-open")) {
                closeExperienceModal();
            }
        });
    }

    const accommodationTrack = document.querySelector("[data-accommodation-track]");
    if (accommodationTrack) {
        const prevButton = document.querySelector("[data-accommodation-prev]");
        const nextButton = document.querySelector("[data-accommodation-next]");
        const scrollAmount = () => Math.max(accommodationTrack.clientWidth * 0.8, 280);

        if (prevButton) {
            prevButton.addEventListener("click", () => {
                accommodationTrack.scrollBy({ left: -scrollAmount(), behavior: "smooth" });
            });
        }

        if (nextButton) {
            nextButton.addEventListener("click", () => {
                accommodationTrack.scrollBy({ left: scrollAmount(), behavior: "smooth" });
            });
        }
    }

    const accommodationModal = document.querySelector("[data-accommodation-modal]");
    if (accommodationModal) {
        const openButtons = document.querySelectorAll("[data-accommodation-open]");
        const closeButtons = accommodationModal.querySelectorAll("[data-accommodation-close]");
        const modalTitle = accommodationModal.querySelector("[data-accommodation-modal-title]");
        const modalImage = accommodationModal.querySelector("[data-accommodation-modal-image]");
        const tabButtons = Array.from(accommodationModal.querySelectorAll("[data-accommodation-tab]"));
        const tabPanels = Array.from(accommodationModal.querySelectorAll("[data-accommodation-panel]"));
        const panelTitles = {
            boat: accommodationModal.querySelector("[data-accommodation-panel-title='boat']"),
            cabins: accommodationModal.querySelector("[data-accommodation-panel-title='cabins']"),
            food: accommodationModal.querySelector("[data-accommodation-panel-title='food']"),
        };
        const panelDescriptions = {
            boat: accommodationModal.querySelector("[data-accommodation-panel-description='boat']"),
            cabins: accommodationModal.querySelector("[data-accommodation-panel-description='cabins']"),
            food: accommodationModal.querySelector("[data-accommodation-panel-description='food']"),
        };

        const setActiveTab = (tabName) => {
            tabButtons.forEach((button) => {
                const isActive = button.dataset.accommodationTab === tabName;
                button.classList.toggle("is-active", isActive);
                button.setAttribute("aria-selected", String(isActive));
            });

            tabPanels.forEach((panel) => {
                const isActive = panel.dataset.accommodationPanel === tabName;
                panel.classList.toggle("is-active", isActive);
            });
        };

        const openAccommodationModal = (button) => {
            if (modalTitle) {
                modalTitle.textContent = button.dataset.accommodationTitle || "Accommodation";
            }
            if (modalImage) {
                modalImage.src = button.dataset.accommodationImage || "";
                modalImage.alt = button.dataset.accommodationTitle || "Accommodation image";
            }
            if (panelTitles.boat) {
                panelTitles.boat.textContent = button.dataset.accommodationBoatTitle || panelTitles.boat.textContent;
            }
            if (panelTitles.cabins) {
                panelTitles.cabins.textContent = button.dataset.accommodationCabinsTitle || panelTitles.cabins.textContent;
            }
            if (panelTitles.food) {
                panelTitles.food.textContent = button.dataset.accommodationFoodTitle || panelTitles.food.textContent;
            }
            if (panelDescriptions.boat) {
                panelDescriptions.boat.textContent = button.dataset.accommodationBoatDescription || panelDescriptions.boat.textContent;
            }
            if (panelDescriptions.cabins) {
                panelDescriptions.cabins.textContent = button.dataset.accommodationCabinsDescription || panelDescriptions.cabins.textContent;
            }
            if (panelDescriptions.food) {
                panelDescriptions.food.textContent = button.dataset.accommodationFoodDescription || panelDescriptions.food.textContent;
            }

            setActiveTab("boat");
            accommodationModal.classList.add("is-open");
            accommodationModal.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
        };

        const closeAccommodationModal = () => {
            accommodationModal.classList.remove("is-open");
            accommodationModal.setAttribute("aria-hidden", "true");
            document.body.style.overflow = "";
        };

        openButtons.forEach((button) => {
            button.addEventListener("click", () => openAccommodationModal(button));
        });

        closeButtons.forEach((button) => {
            button.addEventListener("click", closeAccommodationModal);
        });

        tabButtons.forEach((button) => {
            button.addEventListener("click", () => {
                setActiveTab(button.dataset.accommodationTab || "boat");
            });
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && accommodationModal.classList.contains("is-open")) {
                closeAccommodationModal();
            }
        });
    }

    const waysExploreSection = document.querySelector("[data-ways-explore]");
    if (waysExploreSection) {
        const tabButtons = Array.from(waysExploreSection.querySelectorAll("[data-ways-tab]"));
        const featureImage = waysExploreSection.querySelector("[data-ways-feature-image]");
        const featureEyebrow = waysExploreSection.querySelector("[data-ways-feature-eyebrow]");
        const featureTitle = waysExploreSection.querySelector("[data-ways-feature-title]");
        const featureDescription = waysExploreSection.querySelector("[data-ways-feature-description]");
        const featureCta = waysExploreSection.querySelector("[data-ways-feature-cta]");

        const setActiveWay = (button) => {
            tabButtons.forEach((entry) => {
                const isActive = entry === button;
                entry.classList.toggle("is-active", isActive);
                entry.setAttribute("aria-selected", String(isActive));
            });

            if (featureImage) {
                featureImage.src = button.dataset.wayImage || "";
                featureImage.alt = button.dataset.wayTitle || "Way to explore";
            }
            if (featureEyebrow) {
                featureEyebrow.textContent = button.dataset.wayEyebrow || "";
            }
            if (featureTitle) {
                featureTitle.textContent = button.dataset.wayTitle || "";
            }
            if (featureDescription) {
                featureDescription.textContent = button.dataset.wayDescription || "";
            }
            if (featureCta) {
                featureCta.textContent = button.dataset.wayCta || "Explore";
            }
        };

        tabButtons.forEach((button) => {
            button.addEventListener("click", () => {
                setActiveWay(button);
            });
        });
    }

    const departureRadios = Array.from(document.querySelectorAll("[data-departure-radio]"));
    const bookingSelectionCard = document.querySelector("[data-booking-selection]");
    if (departureRadios.length && bookingSelectionCard) {
        const adultsSelect = document.querySelector("[data-booking-adults]");
        const priceField = bookingSelectionCard.querySelector("[data-booking-price]");
        const fromField = bookingSelectionCard.querySelector("[data-booking-from]");
        const toField = bookingSelectionCard.querySelector("[data-booking-to]");
        const spacesField = bookingSelectionCard.querySelector("[data-booking-spaces]");
        const supplementField = bookingSelectionCard.querySelector("[data-booking-supplement]");
        const adultsText = bookingSelectionCard.querySelector("[data-booking-adults-text]");
        const cta = bookingSelectionCard.querySelector("[data-booking-cta]");

        const updateSelectedDepartureCard = () => {
            const selected = departureRadios.find((radio) => radio.checked);
            if (!selected) {
                return;
            }

            const adultsValue = Number(adultsSelect ? adultsSelect.value : 2);
            if (priceField) priceField.textContent = `From $${Number(selected.dataset.price || 0).toLocaleString()}`;
            if (fromField) fromField.textContent = selected.dataset.fromDate || "-";
            if (toField) toField.textContent = selected.dataset.toDate || "-";
            if (spacesField) spacesField.textContent = selected.dataset.availableSpaces || "-";
            if (supplementField) supplementField.textContent = `$${Number(selected.dataset.singleSupplement || 0).toLocaleString()}`;
            if (adultsText) adultsText.textContent = String(adultsValue);
            if (cta) {
                const currentPath = window.location.pathname;
                cta.setAttribute("href", `${currentPath}/book?departure_id=${encodeURIComponent(selected.dataset.id || "")}&adults=${adultsValue}`);
            }
        };

        departureRadios.forEach((radio) => {
            radio.addEventListener("change", updateSelectedDepartureCard);
        });

        if (adultsSelect) {
            adultsSelect.addEventListener("change", updateSelectedDepartureCard);
        }

        updateSelectedDepartureCard();
    }

    const passengersContainer = document.querySelector("[data-other-passengers]");
    if (passengersContainer) {
        const addPassengerButton = document.querySelector("[data-add-passenger]");
        let passengerIndex = 0;

        const createPassengerBlock = () => {
            passengerIndex += 1;
            const wrapper = document.createElement("div");
            wrapper.className = "booking-passenger";
            wrapper.innerHTML = `
                <h4>Passenger ${passengerIndex + 1}</h4>
                <div class="booking-form__grid">
                    <select required>
                        <option value="">Title</option>
                        <option>Mr.</option>
                        <option>Ms.</option>
                        <option>Mrs.</option>
                        <option>Dr.</option>
                    </select>
                    <select required>
                        <option value="">Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                    <input type="text" placeholder="Middle Name">
                    <input type="text" placeholder="Last Name*" required>
                    <input type="date" placeholder="Date of Birth*" required>
                    <input type="email" placeholder="Email*" required>
                    <input type="tel" placeholder="Phone*" required>
                </div>
            `;
            passengersContainer.appendChild(wrapper);
        };

        const initialCount = Number(passengersContainer.dataset.initialCount || 0);
        for (let i = 0; i < initialCount; i += 1) {
            createPassengerBlock();
        }

        if (addPassengerButton) {
            addPassengerButton.addEventListener("click", createPassengerBlock);
        }
    }

    const reelsTrack = document.querySelector("[data-reels-track]");
    if (reelsTrack) {
        const prevButton = document.querySelector("[data-reels-prev]");
        const nextButton = document.querySelector("[data-reels-next]");
        const scrollAmount = () => Math.max(reelsTrack.clientWidth * 0.85, 260);

        if (prevButton) {
            prevButton.addEventListener("click", () => {
                reelsTrack.scrollBy({ left: -scrollAmount(), behavior: "smooth" });
            });
        }

        if (nextButton) {
            nextButton.addEventListener("click", () => {
                reelsTrack.scrollBy({ left: scrollAmount(), behavior: "smooth" });
            });
        }
    }

    const reelModal = document.querySelector("[data-reel-modal]");
    if (reelModal) {
        const reelOpenButtons = document.querySelectorAll("[data-reel-open]");
        const reelCloseButtons = reelModal.querySelectorAll("[data-reel-close]");
        const reelVideo = reelModal.querySelector("[data-reel-video]");

        const openReelModal = (source) => {
            if (reelVideo) {
                reelVideo.src = source;
                reelVideo.play().catch(() => undefined);
            }
            reelModal.classList.add("is-open");
            reelModal.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
        };

        const closeReelModal = () => {
            reelModal.classList.remove("is-open");
            reelModal.setAttribute("aria-hidden", "true");
            if (reelVideo) {
                reelVideo.pause();
                reelVideo.removeAttribute("src");
                reelVideo.load();
            }
            document.body.style.overflow = "";
        };

        reelOpenButtons.forEach((button) => {
            button.addEventListener("click", () => {
                openReelModal(button.dataset.videoSrc || "");
            });
        });

        reelCloseButtons.forEach((button) => {
            button.addEventListener("click", closeReelModal);
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && reelModal.classList.contains("is-open")) {
                closeReelModal();
            }
        });
    }

    const aboutGalleryLightbox = document.querySelector("[data-about-gallery-lightbox]");
    if (aboutGalleryLightbox) {
        const galleryTriggers = Array.from(document.querySelectorAll("[data-about-gallery-open]"));
        const lightboxImage = aboutGalleryLightbox.querySelector("[data-about-gallery-image]");
        const closeButton = aboutGalleryLightbox.querySelector("[data-about-gallery-close]");
        const prevButton = aboutGalleryLightbox.querySelector("[data-about-gallery-prev]");
        const nextButton = aboutGalleryLightbox.querySelector("[data-about-gallery-next]");
        const zoomInButton = aboutGalleryLightbox.querySelector("[data-about-gallery-zoom-in]");
        const zoomOutButton = aboutGalleryLightbox.querySelector("[data-about-gallery-zoom-out]");
        const stage = aboutGalleryLightbox.querySelector("[data-about-gallery-stage]");
        const pointers = new Map();
        let startDistance = 0;
        let startScale = 1;
        let startSwipeX = null;
        let activeIndex = 0;
        let zoomLevel = 1;

        const getDistance = (a, b) => Math.hypot(a.clientX - b.clientX, a.clientY - b.clientY);

        const renderAboutGallery = () => {
            const activeTrigger = galleryTriggers[activeIndex];
            if (!activeTrigger || !lightboxImage) {
                return;
            }
            lightboxImage.src = activeTrigger.dataset.src || "";
            lightboxImage.style.transform = `scale(${zoomLevel})`;
        };

        const openAboutGallery = (index) => {
            activeIndex = index;
            zoomLevel = 1;
            renderAboutGallery();
            aboutGalleryLightbox.classList.add("is-open");
            aboutGalleryLightbox.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
        };

        const closeAboutGallery = () => {
            aboutGalleryLightbox.classList.remove("is-open");
            aboutGalleryLightbox.setAttribute("aria-hidden", "true");
            document.body.style.overflow = "";
            pointers.clear();
            startDistance = 0;
        };

        const goToGalleryIndex = (index) => {
            activeIndex = (index + galleryTriggers.length) % galleryTriggers.length;
            zoomLevel = 1;
            renderAboutGallery();
        };

        galleryTriggers.forEach((trigger, index) => {
            trigger.addEventListener("click", () => openAboutGallery(index));
        });

        if (closeButton) {
            closeButton.addEventListener("click", closeAboutGallery);
        }
        if (prevButton) {
            prevButton.addEventListener("click", () => goToGalleryIndex(activeIndex - 1));
        }
        if (nextButton) {
            nextButton.addEventListener("click", () => goToGalleryIndex(activeIndex + 1));
        }
        if (zoomInButton) {
            zoomInButton.addEventListener("click", () => {
                zoomLevel = Math.min(zoomLevel + 0.2, 3);
                renderAboutGallery();
            });
        }
        if (zoomOutButton) {
            zoomOutButton.addEventListener("click", () => {
                zoomLevel = Math.max(zoomLevel - 0.2, 1);
                renderAboutGallery();
            });
        }

        if (stage) {
            stage.addEventListener("pointerdown", (event) => {
                pointers.set(event.pointerId, event);
                if (pointers.size === 1) {
                    startSwipeX = event.clientX;
                }
                if (pointers.size === 2) {
                    const [first, second] = Array.from(pointers.values());
                    startDistance = getDistance(first, second);
                    startScale = zoomLevel;
                }
            });

            stage.addEventListener("pointermove", (event) => {
                if (!pointers.has(event.pointerId)) {
                    return;
                }
                pointers.set(event.pointerId, event);
                if (pointers.size === 2) {
                    const [first, second] = Array.from(pointers.values());
                    const currentDistance = getDistance(first, second);
                    if (startDistance > 0) {
                        const nextZoom = startScale * (currentDistance / startDistance);
                        zoomLevel = Math.min(Math.max(nextZoom, 1), 3);
                        renderAboutGallery();
                    }
                }
            });

            const onPointerEnd = (event) => {
                const existing = pointers.get(event.pointerId);
                pointers.delete(event.pointerId);
                if (existing && startSwipeX !== null && pointers.size === 0) {
                    const deltaX = event.clientX - startSwipeX;
                    if (Math.abs(deltaX) > 55 && zoomLevel <= 1.05) {
                        if (deltaX < 0) {
                            goToGalleryIndex(activeIndex + 1);
                        } else {
                            goToGalleryIndex(activeIndex - 1);
                        }
                    }
                }
                if (pointers.size < 2) {
                    startDistance = 0;
                }
                if (pointers.size === 0) {
                    startSwipeX = null;
                }
            };

            stage.addEventListener("pointerup", onPointerEnd);
            stage.addEventListener("pointercancel", onPointerEnd);
            stage.addEventListener("pointerleave", onPointerEnd);
        }

        document.addEventListener("keydown", (event) => {
            if (!aboutGalleryLightbox.classList.contains("is-open")) {
                return;
            }
            if (event.key === "Escape") {
                closeAboutGallery();
            }
            if (event.key === "ArrowRight") {
                goToGalleryIndex(activeIndex + 1);
            }
            if (event.key === "ArrowLeft") {
                goToGalleryIndex(activeIndex - 1);
            }
        });
    }

    const journeyFilterButtons = Array.from(document.querySelectorAll("[data-journey-filter]"));
    const journeyCards = Array.from(document.querySelectorAll("[data-journey-card]"));
    if (journeyCards.length) {
        const canAnimateJourneys = !window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        const revealJourneyItems = (elements, baseDelay = 0) => {
            elements.forEach((element, index) => {
                if (canAnimateJourneys) {
                    element.style.transitionDelay = `${baseDelay + index * 55}ms`;
                }
                requestAnimationFrame(() => element.classList.add("is-visible"));
            });
        };

        if (canAnimateJourneys && "IntersectionObserver" in window) {
            const animatedJourneyBlocks = Array.from(document.querySelectorAll("[data-journey-animate]"));
            const journeyObserver = new IntersectionObserver(
                (entries, observer) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }
                        const target = entry.target;
                        if (target.dataset.journeyAnimate === "card") {
                            return;
                        }
                        target.classList.add("is-visible");
                        observer.unobserve(target);
                    });
                },
                { threshold: 0.2, rootMargin: "0px 0px -40px 0px" }
            );

            animatedJourneyBlocks.forEach((block) => {
                if (block.dataset.journeyAnimate !== "card") {
                    journeyObserver.observe(block);
                }
            });

            revealJourneyItems(journeyCards, 140);
        } else {
            const animatedJourneyBlocks = Array.from(document.querySelectorAll("[data-journey-animate]"));
            animatedJourneyBlocks.forEach((block) => block.classList.add("is-visible"));
        }

        const applyJourneyFilter = (filter) => {
            const visibleCards = [];
            journeyCards.forEach((card) => {
                const category = card.dataset.category || "";
                const shouldShow = filter === "all" || category === filter;
                card.classList.toggle("is-hidden", !shouldShow);
                card.classList.remove("is-visible");
                if (shouldShow) {
                    visibleCards.push(card);
                } else {
                    card.style.removeProperty("transition-delay");
                }
            });

            if (!visibleCards.length) {
                return;
            }
            if (!canAnimateJourneys) {
                visibleCards.forEach((card) => card.classList.add("is-visible"));
                return;
            }
            revealJourneyItems(visibleCards);
        };

        if (journeyFilterButtons.length) {
            journeyFilterButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    const selectedFilter = button.dataset.journeyFilter || "all";
                    journeyFilterButtons.forEach((entry) => entry.classList.remove("is-active"));
                    button.classList.add("is-active");
                    applyJourneyFilter(selectedFilter);
                });
            });

            applyJourneyFilter("all");
        } else if (!canAnimateJourneys) {
            journeyCards.forEach((card) => card.classList.add("is-visible"));
        }
    }

    const blogSlider = document.querySelector("[data-blog-slider]");
    if (blogSlider) {
        const slides = Array.from(blogSlider.querySelectorAll("[data-blog-slide]"));
        const prevButton = blogSlider.querySelector("[data-blog-slider-prev]");
        const nextButton = blogSlider.querySelector("[data-blog-slider-next]");
        let activeSlideIndex = 0;

        const renderSlide = () => {
            slides.forEach((slide, index) => {
                slide.classList.toggle("is-active", index === activeSlideIndex);
            });
        };

        const goToSlide = (index) => {
            if (!slides.length) {
                return;
            }
            activeSlideIndex = (index + slides.length) % slides.length;
            renderSlide();
        };

        if (prevButton) {
            prevButton.addEventListener("click", () => goToSlide(activeSlideIndex - 1));
        }

        if (nextButton) {
            nextButton.addEventListener("click", () => goToSlide(activeSlideIndex + 1));
        }

        renderSlide();
    }

    const chatWidget = document.querySelector("[data-chat-widget]");
    if (chatWidget) {
        const toggleButton = chatWidget.querySelector("[data-chat-toggle]");
        const panel = chatWidget.querySelector("[data-chat-panel]");
        const siteFooter = document.querySelector(".site-footer");
        let isFooterVisible = false;

        const openChat = () => {
            chatWidget.classList.add("is-open");
            if (panel) {
                panel.classList.add("is-open");
                panel.setAttribute("aria-hidden", "false");
            }
            if (toggleButton) {
                toggleButton.setAttribute("aria-expanded", "true");
                toggleButton.setAttribute("aria-label", "Close chat");
            }
        };

        const closeChat = () => {
            chatWidget.classList.remove("is-open");
            if (panel) {
                panel.classList.remove("is-open");
                panel.setAttribute("aria-hidden", "true");
            }
            if (toggleButton) {
                toggleButton.setAttribute("aria-expanded", "false");
                toggleButton.setAttribute("aria-label", "Open chat");
            }
        };

        if (toggleButton) {
            toggleButton.addEventListener("click", () => {
                if (isFooterVisible) {
                    closeChat();
                    window.scrollTo({ top: 0, behavior: "smooth" });
                    return;
                }

                if (chatWidget.classList.contains("is-open")) {
                    closeChat();
                    return;
                }
                openChat();
            });
        }

        if ("IntersectionObserver" in window && siteFooter) {
            const footerObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        isFooterVisible = entry.isIntersecting;
                        chatWidget.classList.toggle("is-on-footer", isFooterVisible);
                        if (toggleButton) {
                            toggleButton.setAttribute("aria-label", isFooterVisible ? "Navigate to top" : "Open chat");
                        }
                    });
                },
                {
                    threshold: 0.15,
                }
            );

            footerObserver.observe(siteFooter);
        }

        document.addEventListener("click", (event) => {
            if (!chatWidget.classList.contains("is-open")) {
                return;
            }
            if (!chatWidget.contains(event.target)) {
                closeChat();
            }
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && chatWidget.classList.contains("is-open")) {
                closeChat();
            }
        });
    }

    const homeRevealItems = Array.from(document.querySelectorAll("[data-home-reveal]"));
    if (homeRevealItems.length) {
        const isReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        if (isReducedMotion || !("IntersectionObserver" in window)) {
            homeRevealItems.forEach((item) => item.classList.add("home-reveal--visible"));
        } else {
            const revealObserver = new IntersectionObserver(
                (entries, observer) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        const target = entry.target;
                        const revealDelay = Number(target.dataset.homeRevealDelay || 0);
                        if (revealDelay > 0) {
                            target.style.transitionDelay = `${revealDelay}ms`;
                        }

                        target.classList.add("home-reveal--visible");
                        observer.unobserve(target);
                    });
                },
                {
                    threshold: 0.2,
                    rootMargin: "0px 0px -10% 0px",
                }
            );

            homeRevealItems.forEach((item) => {
                if (item.classList.contains("home-reveal--visible")) {
                    return;
                }

                revealObserver.observe(item);
            });
        }
    }
})();
