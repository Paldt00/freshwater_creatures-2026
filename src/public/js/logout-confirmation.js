(function () {
    'use strict';

    function getFocusableElements(container) {
        const selectors = [
            'button:not([disabled])',
            'a[href]',
            'input:not([disabled])',
            'select:not([disabled])',
            'textarea:not([disabled])',
            '[tabindex]:not([tabindex="-1"])',
        ].join(',');

        return Array.from(
            container.querySelectorAll(selectors)
        ).filter(function (element) {
            return (
                element instanceof HTMLElement
                && element.offsetParent !== null
                && element.getAttribute('aria-hidden')
                    !== 'true'
            );
        });
    }

    function initializeLogoutConfirmation() {
        const components = document.querySelectorAll(
            '[data-logout-confirmation]'
        );

        components.forEach(function (component) {
            if (
                component.dataset.logoutInitialized
                === 'true'
            ) {
                return;
            }

            const openButton = component.querySelector(
                '[data-logout-open]'
            );

            const modal = component.querySelector(
                '[data-logout-modal]'
            );

            if (
                !(
                    openButton instanceof HTMLButtonElement
                )
                || !(modal instanceof HTMLElement)
            ) {
                return;
            }

            component.dataset.logoutInitialized =
                'true';

            document.body.appendChild(modal);

            const dialog = modal.querySelector(
                '[data-logout-dialog]'
            );

            const closeButtons = modal.querySelectorAll(
                '[data-logout-close]'
            );

            const cancelButton = modal.querySelector(
                '[data-logout-cancel]'
            );

            const logoutForm = modal.querySelector(
                '[data-logout-form]'
            );

            const submitButton = modal.querySelector(
                '[data-logout-submit]'
            );

            let closeTimer = null;
            let previouslyFocusedElement = null;
            let isSubmitting = false;

            function isModalOpen() {
                return modal.classList.contains(
                    'is-open'
                );
            }

            function setOpenButtonState(isOpen) {
                openButton.setAttribute(
                    'aria-expanded',
                    isOpen ? 'true' : 'false'
                );
            }

            function focusInitialElement() {
                if (
                    cancelButton
                    instanceof HTMLButtonElement
                ) {
                    cancelButton.focus();

                    return;
                }

                if (dialog instanceof HTMLElement) {
                    dialog.focus();
                }
            }

            function openModal() {
                if (
                    isSubmitting
                    || isModalOpen()
                ) {
                    return;
                }

                window.clearTimeout(closeTimer);

                previouslyFocusedElement =
                    document.activeElement
                    instanceof HTMLElement
                        ? document.activeElement
                        : openButton;

                modal.hidden = false;

                modal.setAttribute(
                    'aria-hidden',
                    'false'
                );

                setOpenButtonState(true);

                document.body.classList.add(
                    'logout-modal-open'
                );

                window.requestAnimationFrame(
                    function () {
                        modal.classList.add(
                            'is-open'
                        );

                        focusInitialElement();
                    }
                );
            }

            function closeModal() {
                if (
                    isSubmitting
                    || !isModalOpen()
                ) {
                    return;
                }

                modal.classList.remove(
                    'is-open'
                );

                modal.setAttribute(
                    'aria-hidden',
                    'true'
                );

                setOpenButtonState(false);

                document.body.classList.remove(
                    'logout-modal-open'
                );

                window.clearTimeout(closeTimer);

                closeTimer = window.setTimeout(
                    function () {
                        modal.hidden = true;

                        const focusTarget =
                            previouslyFocusedElement
                            instanceof HTMLElement
                                ? previouslyFocusedElement
                                : openButton;

                        focusTarget.focus();
                    },
                    220
                );
            }

            function trapFocus(event) {
                if (
                    event.key !== 'Tab'
                    || !isModalOpen()
                    || !(dialog instanceof HTMLElement)
                ) {
                    return;
                }

                const focusableElements =
                    getFocusableElements(dialog);

                if (
                    focusableElements.length === 0
                ) {
                    event.preventDefault();
                    dialog.focus();

                    return;
                }

                const firstElement =
                    focusableElements[0];

                const lastElement =
                    focusableElements[
                        focusableElements.length - 1
                    ];

                const activeElement =
                    document.activeElement;

                if (
                    event.shiftKey
                    && (
                        activeElement === firstElement
                        || !dialog.contains(
                            activeElement
                        )
                    )
                ) {
                    event.preventDefault();
                    lastElement.focus();

                    return;
                }

                if (
                    !event.shiftKey
                    && activeElement === lastElement
                ) {
                    event.preventDefault();
                    firstElement.focus();
                }
            }

            function startSubmitting() {
                if (
                    isSubmitting
                    || !(
                        submitButton
                        instanceof HTMLButtonElement
                    )
                ) {
                    return;
                }

                isSubmitting = true;

                modal.classList.add(
                    'is-submitting'
                );

                if (
                    logoutForm
                    instanceof HTMLFormElement
                ) {
                    logoutForm.setAttribute(
                        'aria-busy',
                        'true'
                    );
                }

                submitButton.disabled = true;

                submitButton.textContent =
                    submitButton.dataset.loadingText
                    ?? 'Sedang Keluar...';

                if (
                    cancelButton
                    instanceof HTMLButtonElement
                ) {
                    cancelButton.disabled = true;
                }
            }

            function resetSubmitting() {
                isSubmitting = false;

                modal.classList.remove(
                    'is-submitting'
                );

                if (
                    logoutForm
                    instanceof HTMLFormElement
                ) {
                    logoutForm.removeAttribute(
                        'aria-busy'
                    );
                }

                if (
                    submitButton
                    instanceof HTMLButtonElement
                ) {
                    submitButton.disabled = false;

                    submitButton.textContent =
                        submitButton.dataset.idleText
                        ?? 'Ya, Keluar';
                }

                if (
                    cancelButton
                    instanceof HTMLButtonElement
                ) {
                    cancelButton.disabled = false;
                }
            }

            openButton.addEventListener(
                'click',
                openModal
            );

            closeButtons.forEach(
                function (button) {
                    button.addEventListener(
                        'click',
                        closeModal
                    );
                }
            );

            document.addEventListener(
                'keydown',
                function (event) {
                    if (!isModalOpen()) {
                        return;
                    }

                    if (
                        event.key === 'Escape'
                        && !isSubmitting
                    ) {
                        event.preventDefault();
                        closeModal();

                        return;
                    }

                    trapFocus(event);
                }
            );

            if (
                logoutForm
                instanceof HTMLFormElement
            ) {
                logoutForm.addEventListener(
                    'submit',
                    function (event) {
                        /*
                         * csrf-refresh.js mencegah submit pertama
                         * untuk memperbarui token.
                         *
                         * Tombol baru dinonaktifkan saat submit
                         * sebenarnya dijalankan.
                         */
                        if (event.defaultPrevented) {
                            return;
                        }

                        if (isSubmitting) {
                            event.preventDefault();

                            return;
                        }

                        startSubmitting();
                    }
                );
            }

            window.addEventListener(
                'pageshow',
                function () {
                    resetSubmitting();
                }
            );
        });
    }

    if (
        document.readyState === 'loading'
    ) {
        document.addEventListener(
            'DOMContentLoaded',
            initializeLogoutConfirmation
        );
    } else {
        initializeLogoutConfirmation();
    }
})();
