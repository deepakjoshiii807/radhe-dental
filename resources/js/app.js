import './bootstrap';

const initializeDarkModeToggle = () => {
  const toggle = document.querySelector('[data-theme-toggle]');
  const root = document.documentElement;
  const storedTheme = localStorage.getItem('smilecare-theme');

  // Force light theme for consistent site appearance (disable dark mode)
  root.classList.remove('dark');
  localStorage.setItem('smilecare-theme', 'light');

  // Hide the theme toggle UI if present to avoid user confusion
  if (toggle && toggle instanceof HTMLElement) {
    toggle.style.display = 'none';
  }
};

const initializeToasts = () => {
  document.querySelectorAll('[data-toast]').forEach((toast) => {
    window.setTimeout(() => {
      toast.classList.add('opacity-0', 'translate-y-2');
    }, 3500);
  });
};

const initializeCounters = () => {
  document.querySelectorAll('[data-counter]').forEach((el) => {
    const target = Number(el.getAttribute('data-counter') || '0');
    const duration = 1200;
    const steps = 40;
    const increment = target / steps;
    let current = 0;
    const tick = () => {
      current += increment;
      el.textContent = Math.round(Math.min(current, target)).toString();
      if (current < target) {
        window.requestAnimationFrame(tick);
      }
    };
    tick();
  });
};

const initializeScrollReveal = () => {
  const targets = document.querySelectorAll('[data-reveal]');
  if (!('IntersectionObserver' in window)) {
    targets.forEach((target) => target.classList.add('opacity-100', 'translate-y-0'));
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('opacity-100', 'translate-y-0');
        entry.target.classList.remove('opacity-0', 'translate-y-4');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  targets.forEach((target) => observer.observe(target));
};

document.addEventListener('DOMContentLoaded', () => {
  initializeDarkModeToggle();
  initializeToasts();
  initializeCounters();
  initializeScrollReveal();
});
