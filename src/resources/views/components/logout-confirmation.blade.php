<div
    class="logout-confirmation-wrapper"
    data-logout-confirmation
>
    <button
        type="button"
        class="logout-trigger-button"
        data-logout-open
    >
        Keluar
    </button>

    <div
        class="logout-modal-overlay"
        data-logout-modal
        aria-hidden="true"
        hidden
    >
        <div
            class="logout-modal-backdrop"
            data-logout-close
        ></div>

        <div
            class="logout-modal-card"
            role="dialog"
            aria-modal="true"
            aria-labelledby="logoutModalTitle"
            aria-describedby="logoutModalDescription"
        >
            <div class="logout-modal-icon">
                🚪
            </div>

            <h2
                class="logout-modal-title"
                id="logoutModalTitle"
            >
                Anda Yakin Ingin Keluar?
            </h2>

            <p
                class="logout-modal-description"
                id="logoutModalDescription"
            >
            </p>

            <div class="logout-modal-actions">
                <button
                    type="button"
                    class="logout-cancel-button"
                    data-logout-close
                >
                    Batal
                </button>

                <form
                    action="{{ route('public.logout') }}"
                    method="POST"
                    class="logout-submit-form"
                >
                    @csrf

                    <button
                        type="submit"
                        class="logout-confirm-button"
                    >
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .logout-confirmation-wrapper {
        display: inline-flex;
        align-items: center;
    }

    .logout-trigger-button {
        border: none;
        border-radius: 999px;
        padding: 9px 14px;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        font-weight: 900;
        color: #ffffff;
        background: #dc2626;
        box-shadow: none;
        transition:
            background-color .2s ease,
            transform .2s ease;
    }

    .logout-trigger-button:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    .logout-modal-overlay[hidden] {
        display: none !important;
    }

    .logout-modal-overlay {
        position: fixed !important;
        inset: 0 !important;
        z-index: 2147483647 !important;
        width: 100vw !important;
        height: 100vh !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 24px !important;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition:
            opacity .2s ease,
            visibility .2s ease;
    }

    .logout-modal-overlay.is-open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .logout-modal-backdrop {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(8, 32, 50, .46);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .logout-modal-card {
        position: relative;
        z-index: 1;
        width: min(100%, 540px);
        max-height: calc(100vh - 48px);
        overflow-y: auto;
        padding: 36px;
        border: 1px solid rgba(255, 255, 255, .84);
        border-radius: 28px;
        background: rgba(255, 255, 255, .95);
        box-shadow:
            0 30px 90px rgba(8, 32, 50, .30);
        text-align: center;
        transform: translateY(18px) scale(.97);
        transition: transform .22s ease;
    }

    .logout-modal-overlay.is-open
    .logout-modal-card {
        transform: translateY(0) scale(1);
    }

    .logout-modal-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 20px;
        border-radius: 22px;
        display: grid;
        place-items: center;
        background: #fef2f2;
        color: #dc2626;
        font-size: 32px;
    }

    .logout-modal-title {
        margin: 0 0 12px;
        color: #082032;
        font-size: 30px;
        line-height: 1.2;
    }

    .logout-modal-description {
        margin: 0;
        color: #6b7280;
        font-size: 17px;
        line-height: 1.7;
    }

    .logout-modal-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 28px;
    }

    .logout-submit-form {
        margin: 0;
    }

    .logout-cancel-button,
    .logout-confirm-button {
        min-width: 125px;
        border-radius: 999px;
        padding: 12px 20px;
        cursor: pointer;
        font-family: inherit;
        font-size: 15px;
        font-weight: 900;
        transition:
            background-color .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .logout-cancel-button {
        border: 1px solid #d1d5db;
        color: #0f4c75;
        background: #ffffff;
    }

    .logout-cancel-button:hover {
        background: #f3f4f6;
    }

    .logout-confirm-button {
        border: none;
        color: #ffffff;
        background: #dc2626;
        box-shadow:
            0 10px 24px rgba(220, 38, 38, .22);
    }

    .logout-confirm-button:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow:
            0 14px 30px rgba(220, 38, 38, .28);
    }

    body.logout-modal-open {
        overflow: hidden !important;
    }

    @media (max-width: 1100px) {
        .logout-confirmation-wrapper {
            width: 100%;
        }

        .logout-trigger-button {
            width: 100%;
            border-radius: 14px;
            padding: 11px 14px;
        }
    }

    @media (max-width: 600px) {
        .logout-modal-overlay {
            padding: 16px !important;
        }

        .logout-modal-card {
            padding: 28px 20px;
            border-radius: 22px;
        }

        .logout-modal-icon {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            font-size: 27px;
        }

        .logout-modal-title {
            font-size: 26px;
        }

        .logout-modal-description {
            font-size: 15px;
        }

        .logout-modal-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .logout-submit-form,
        .logout-cancel-button,
        .logout-confirm-button {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const component = document.querySelector(
                '[data-logout-confirmation]'
            );

            if (!component) {
                return;
            }

            const openButton = component.querySelector(
                '[data-logout-open]'
            );

            const modal = component.querySelector(
                '[data-logout-modal]'
            );

            if (!openButton || !modal) {
                return;
            }

            /*
             * Modal dipindahkan ke body agar tidak terikat
             * oleh backdrop-filter dan posisi navbar.
             */
            document.body.appendChild(modal);

            const closeButtons = modal.querySelectorAll(
                '[data-logout-close]'
            );

            function openModal() {
                modal.hidden = false;

                requestAnimationFrame(function () {
                    modal.classList.add('is-open');
                });

                modal.setAttribute(
                    'aria-hidden',
                    'false'
                );

                document.body.classList.add(
                    'logout-modal-open'
                );
            }

            function closeModal() {
                modal.classList.remove('is-open');

                modal.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.classList.remove(
                    'logout-modal-open'
                );

                window.setTimeout(
                    function () {
                        modal.hidden = true;
                    },
                    220
                );
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
                    if (
                        event.key === 'Escape'
                        && modal.classList.contains(
                            'is-open'
                        )
                    ) {
                        closeModal();
                    }
                }
            );
        }
    );
</script>
