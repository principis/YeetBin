/*!
 * This file is part of YeetBin.
 * Copyright (C) 2023 Arthur Bols
 *
 * YeetBin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * YeetBin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with YeetBin.  If not, see <https://www.gnu.org/licenses/>.
 */

import highlight from './highlight';
import ClipboardJS from 'clipboard';
import Tooltip from "bootstrap/js/dist/tooltip";

import './styles/code.scss';

(() => {
    highlight();

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl));

    const clipboard = new ClipboardJS('.btn-clipboard');
    clipboard.on('success', event => {
        const iconFirstChild = event.trigger.querySelector('.bi');
        const tooltipBtn = Tooltip.getInstance(event.trigger);
        const originalTitle = event.trigger.getAttribute('data-bs-original-title');

        tooltipBtn.setContent({'.tooltip-inner': 'Copied!'});
        event.trigger.addEventListener('hidden.bs.tooltip', () => {
            tooltipBtn.setContent({'.tooltip-inner': originalTitle});
        }, {once: true});
        event.clearSelection();
        iconFirstChild.classList.remove('bi-clipboard');
        iconFirstChild.classList.add('bi-check2');

        setTimeout(() => {
            iconFirstChild.classList.remove('bi-check2');
            iconFirstChild.classList.add('bi-clipboard');
        }, 2000);
    });
})();