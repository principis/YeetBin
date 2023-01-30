/*!
 * Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
 * Copyright 2011-2022 The Bootstrap Authors
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 *
 * Modified by Arthur Bols for YeetBin.
 */

(() => {
    'use strict';

    const storedTheme = localStorage.getItem('theme');

    const getPreferredTheme = () => {
        if (storedTheme) {
            return storedTheme;
        }

        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    const setTheme = function (theme) {
        if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme);
        }
    };

    setTheme(getPreferredTheme());

    const showActiveTheme = theme => {
        const activeThemeIcon = document.querySelector('.theme-icon-active');
        let currentSvgClass = null;

        activeThemeIcon.classList.forEach((el) => {
            if (el.startsWith('bi-')) {
                currentSvgClass = el;
            }
        });
        const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
        let activeSvgClass = null;

        btnToActive.querySelector('i.bi').classList.forEach((el) => {
            if (el.startsWith('bi-')) {
                activeSvgClass = el;
            }
        });

        document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
            element.classList.remove('active');
        });

        btnToActive.classList.add('active');
        activeThemeIcon.classList.remove(currentSvgClass);
        activeThemeIcon.classList.add(activeSvgClass);
    };

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (storedTheme !== 'light' || storedTheme !== 'dark') {
            setTheme(getPreferredTheme());
        }
    });

    window.addEventListener('DOMContentLoaded', () => {
        showActiveTheme(getPreferredTheme());

        document.querySelectorAll('[data-bs-theme-value]')
            .forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value');
                    localStorage.setItem('theme', theme);
                    setTheme(theme);
                    showActiveTheme(theme);
                });
            });
    });
})();