(function () {
    'use strict';

    const scriptElement = document.currentScript;

    const refreshUrl =
        scriptElement?.dataset.csrfRefreshUrl
        ?? '/csrf-token';

    let activeCsrfRequest = null;

    function updateCsrfToken(token) {
        if (
            typeof token !== 'string'
            || token.trim() === ''
        ) {
            return;
        }

        const metaToken = document.querySelector(
            'meta[name="csrf-token"]'
        );

        if (metaToken) {
            metaToken.setAttribute(
                'content',
                token
            );
        }

        document
            .querySelectorAll(
                'input[name="_token"]'
            )
            .forEach(function (input) {
                input.value = token;
            });
    }

    async function refreshCsrfToken() {
        if (activeCsrfRequest) {
            return activeCsrfRequest;
        }

        activeCsrfRequest = fetch(
            refreshUrl,
            {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With':
                        'XMLHttpRequest',
                },
            }
        )
            .then(function (response) {
                if (!response.ok) {
                    throw new Error(
                        'Token CSRF gagal diperbarui.'
                    );
                }

                return response.json();
            })
            .then(function (data) {
                updateCsrfToken(
                    data.token
                );

                return data.token;
            })
            .finally(function () {
                activeCsrfRequest = null;
            });

        return activeCsrfRequest;
    }

    function initializeCsrfRefresh() {
        window.addEventListener(
            'focus',
            function () {
                refreshCsrfToken()
                    .catch(function () {
                        /*
                         * Kegagalan pembaruan otomatis
                         * tidak perlu mengganggu pengguna.
                         */
                    });
            }
        );

        document.addEventListener(
            'visibilitychange',
            function () {
                if (!document.hidden) {
                    refreshCsrfToken()
                        .catch(function () {
                            /*
                             * Kegagalan pembaruan otomatis
                             * tidak perlu mengganggu pengguna.
                             */
                        });
                }
            }
        );

        document.addEventListener(
            'submit',
            async function (event) {
                const form = event.target;

                if (
                    !(
                        form
                        instanceof HTMLFormElement
                    )
                ) {
                    return;
                }

                const method = (
                    form.getAttribute('method')
                    ?? 'GET'
                ).toUpperCase();

                if (method === 'GET') {
                    return;
                }

                if (
                    form.dataset.csrfReady
                    === 'true'
                ) {
                    delete form.dataset.csrfReady;

                    return;
                }

                event.preventDefault();

                const submitter =
                    event.submitter;

                try {
                    await refreshCsrfToken();

                    form.dataset.csrfReady =
                        'true';

                    if (
                        submitter
                        instanceof HTMLElement
                    ) {
                        form.requestSubmit(
                            submitter
                        );
                    } else {
                        form.requestSubmit();
                    }
                } catch (error) {
                    console.error(error);

                    alert(
                        'Sesi gagal diperbarui. Silakan muat ulang halaman lalu coba kembali.'
                    );
                }
            },
            true
        );
    }

    if (
        document.readyState
        === 'loading'
    ) {
        document.addEventListener(
            'DOMContentLoaded',
            initializeCsrfRefresh
        );
    } else {
        initializeCsrfRefresh();
    }
})();
