<div
    class="logout-confirmation-wrapper"
    data-logout-confirmation
>
    <button
        type="button"
        class="logout-trigger-button"
        data-logout-open
        aria-haspopup="dialog"
        aria-controls="logoutConfirmationModal"
        aria-expanded="false"
    >
        Keluar
    </button>

    <div
        class="logout-modal-overlay"
        id="logoutConfirmationModal"
        data-logout-modal
        aria-hidden="true"
        hidden
    >
        <div
            class="logout-modal-backdrop"
            data-logout-close
            aria-hidden="true"
        ></div>

        <div
            class="logout-modal-card"
            role="dialog"
            aria-modal="true"
            aria-labelledby="logoutModalTitle"
            aria-describedby="logoutModalDescription"
            tabindex="-1"
            data-logout-dialog
        >
            <div
                class="logout-modal-icon"
                aria-hidden="true"
            >
                🚪
            </div>

            <h2
                class="logout-modal-title"
                id="logoutModalTitle"
            >
                Anda Yakin Ingin Keluar?
            </h2>

            <p
                class="logout-modal-description visually-hidden"
                id="logoutModalDescription"
            >
                Pilih batal untuk tetap berada di website
                atau pilih ya keluar untuk mengakhiri sesi.
            </p>

            <div class="logout-modal-actions">
                <button
                    type="button"
                    class="logout-cancel-button"
                    data-logout-close
                    data-logout-cancel
                >
                    Batal
                </button>

                <form
                    action="{{ route('public.logout') }}"
                    method="POST"
                    class="logout-submit-form"
                    data-logout-form
                >
                    @csrf

                    <button
                        type="submit"
                        class="logout-confirm-button"
                        data-logout-submit
                        data-idle-text="Ya, Keluar"
                        data-loading-text="Sedang Keluar..."
                    >
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@once
    <link
        rel="stylesheet"
        href="{{ asset('css/logout-confirmation.css') }}"
    >

    <script
        src="{{ asset('js/logout-confirmation.js') }}"
        defer
    ></script>
@endonce
