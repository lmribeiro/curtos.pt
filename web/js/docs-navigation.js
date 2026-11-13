(function ($) {
    'use strict';

    const nav = document.querySelector('[data-docs-nav]');
    if (!nav) {
        return;
    }

    const panel = nav.querySelector('details');
    const links = Array.from(nav.querySelectorAll('a[href^="#"]'));
    const desktop = window.matchMedia('(min-width: 992px)');

    const updateCurrent = function () {
        const activeLinks = links.filter(function (link) {
            return link.classList.contains('active');
        });
        const current = activeLinks[activeLinks.length - 1] || links[0];
        links.forEach(function (link) {
            if (link === current) {
                link.setAttribute('aria-current', 'location');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    const updateLayout = function () {
        panel.open = desktop.matches;
        $('body').scrollspy('refresh');
        $(window).trigger('scroll.bs.scrollspy');
    };

    $(window).on('activate.bs.scrollspy', updateCurrent);
    $('body').scrollspy({target: '#docs-nav', offset: 48});
    updateLayout();
    updateCurrent();
    window.addEventListener('load', updateLayout);
    if (desktop.addEventListener) {
        desktop.addEventListener('change', updateLayout);
    } else {
        desktop.addListener(updateLayout);
    }

    nav.addEventListener('click', function (event) {
        const link = event.target.closest('a[href^="#"]');
        if (!link || !nav.contains(link) || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
            return;
        }
        if (!desktop.matches) {
            panel.open = false;
        }
        const heading = document.getElementById(link.hash.slice(1));
        if (heading) {
            heading.setAttribute('tabindex', '-1');
            heading.focus({preventScroll: true});
        }
    });
}(jQuery));
