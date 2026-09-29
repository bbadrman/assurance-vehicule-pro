// main.js - Version sécurisée pour éviter les conflits avec le formulaire
document.addEventListener("DOMContentLoaded", function () {
    console.log("Script main.js chargé");

    // Animation de typing - avec vérification d'existence
    const text = "Quelle que soient votre activité et vos antécédents d'assurances, nous assurerons votre véhicule au meilleur tarif.";
    const typingText = document.querySelector(".typing-text");
    const cursor = document.querySelector(".typing-cursor");

    if (typingText && cursor) {
        console.log("Éléments typing trouvés, démarrage de l'animation");

        cursor.style.display = "none";
        let i = 0;
        let isErasing = false;
        const typingSpeed = 20;
        const erasingSpeed = 10;
        const delayAfterTyping = 800;
        const delayAfterErasing = 500;

        function typeWriter() {
            if (!isErasing && i < text.length) {
                typingText.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, typingSpeed);
            } else if (!isErasing && i === text.length) {
                cursor.style.display = "inline-block";
                setTimeout(() => {
                    isErasing = true;
                    cursor.style.display = "none";
                    setTimeout(typeWriter, delayAfterTyping);
                }, delayAfterTyping);
            } else if (isErasing && i > 0) {
                typingText.textContent = text.substring(0, i - 1);
                i--;
                setTimeout(typeWriter, erasingSpeed);
            } else if (isErasing && i === 0) {
                isErasing = false;
                cursor.style.display = "none";
                setTimeout(typeWriter, delayAfterErasing);
            }
        }

        typeWriter();
    }

    // GSAP - Chargement conditionnel et sécurisé
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        console.log("GSAP disponible, configuration des animations");

        // Attendre que le formulaire soit complètement rendu
        setTimeout(() => {
            gsap.registerPlugin(ScrollTrigger);

            // Exclure les éléments de formulaire des animations automatiques
            const excludeSelectors = [
                'form', 'input', 'select', 'textarea', 'button[type="submit"]',
                '.form-group', '.input-group'
            ];

            // Animation Header (sans conflits)
            const header = document.querySelector("header");
            if (header) {
                gsap.set(header, { opacity: 1, y: 0 }); // S'assurer qu'il est visible
                gsap.from(header, {
                    duration: 1,
                    y: -50,
                    ease: "power3.out",
                });
            }

            // Animation Hero Section (sans affecter le formulaire)
            const heroSection = document.querySelector(".hero-section");
            if (heroSection) {
                gsap.set(heroSection, { opacity: 1, y: 0 }); // S'assurer qu'il est visible
                gsap.from(heroSection, {
                    duration: 1,
                    y: 50,
                    ease: "power3.out",
                });
            }

            // Animations des sections de contenu uniquement
            const contentSections = ["#about", "#per", "#avantages"];
            contentSections.forEach(selector => {
                const element = document.querySelector(selector);
                if (element) {
                    gsap.from(element, {
                        scrollTrigger: {
                            trigger: element,
                            start: "top 80%",
                            toggleActions: "play none none reverse",
                        },
                        duration: 1,
                        y: 50,
                        opacity: 0,
                        ease: "power2.out",
                    });
                }
            });

            // Animation Footer
            const footer = document.querySelector("footer");
            if (footer) {
                gsap.from(footer, {
                    scrollTrigger: {
                        trigger: footer,
                        start: "top 90%",
                        toggleActions: "play none none reverse",
                    },
                    duration: 1,
                    y: 30,
                    opacity: 0,
                    ease: "power2.out",
                });
            }

            // Animation des cards de contenu (pas le formulaire)
            const contentCards = document.querySelectorAll("section:not(.hero-section) .card-hover");
            if (contentCards.length > 0) {
                contentCards.forEach((card, index) => {
                    gsap.from(card, {
                        scrollTrigger: {
                            trigger: card,
                            start: "top 85%",
                            toggleActions: "play none none reverse",
                        },
                        duration: 0.8,
                        y: 30,
                        opacity: 0,
                        delay: index * 0.1,
                        ease: "power2.out",
                    });
                });
            }

            console.log("Animations GSAP configurées (sans affecter le formulaire)");
        }, 500); // Délai pour s'assurer que le formulaire est rendu
    } else {
        console.log("GSAP non disponible - animations désactivées");
    }

    // FAQ Toggle
  document.addEventListener('click', function (e) {
    const question = e.target.closest('.faq-question');

    if (!question) return;

    const answer = question.nextElementSibling;
    const toggle = question.querySelector('.faq-toggle');

    if (answer && answer.classList.contains('faq-answer')) {
        answer.classList.toggle('hidden');

        if (toggle) {
            toggle.textContent = answer.classList.contains('hidden') ? '+' : '−';
        }
    }
});
    // Form submission
    const quoteForm = document.getElementById('quoteForm');
    if (quoteForm) {
        quoteForm.addEventListener('submit', (e) => {
            // e.preventDefault();
            //alert('Merci pour votre demande ! Nous vous contacterons très prochainement.');
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});