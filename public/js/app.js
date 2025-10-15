 // FAQ Toggle
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const toggle = question.querySelector('.faq-toggle');
                
                if (answer.style.display === 'none' || answer.style.display === '') {
                    answer.style.display = 'block';
                    toggle.textContent = '−';
                } else {
                    answer.style.display = 'none';
                    toggle.textContent = '+';
                }
            });
        });

        // Form submission
        document.getElementById('quoteForm').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Merci pour votre demande ! Nous vous contacterons très prochainement.');
        });

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