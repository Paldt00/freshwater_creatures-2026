(function () {
    'use strict';

    const scriptElement = document.currentScript;

    const configuredSuggestionUrl =
        scriptElement?.dataset
            .searchSuggestionsUrl
        ?? null;

    function injectSuggestionStyles() {
        if (
            document.getElementById(
                'search-suggestion-styles'
            )
        ) {
            return;
        }

        const style =
            document.createElement('style');

        style.id =
            'search-suggestion-styles';

        style.textContent = `
            .nav-search .search-suggestion-wrapper {
                position: relative;
                flex: 1;
                min-width: 0;
            }

            .nav-search .search-suggestion-wrapper input {
                width: 100%;
            }

            .search-suggestion-panel[hidden] {
                display: none !important;
            }

            .search-suggestion-panel {
                position: absolute;
                top: calc(100% + 12px);
                right: 0;
                z-index: 1000;
                width: 430px;
                max-width: calc(100vw - 28px);
                overflow: hidden;
                border: 1px solid rgba(229, 231, 235, .96);
                border-radius: 20px;
                background: rgba(255, 255, 255, .98);
                box-shadow:
                    0 24px 60px rgba(8, 32, 50, .20);
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
            }

            .search-suggestion-panel::before {
                content: "";
                position: absolute;
                top: -7px;
                right: 30px;
                width: 14px;
                height: 14px;
                border-top: 1px solid #e5e7eb;
                border-left: 1px solid #e5e7eb;
                background: #ffffff;
                transform: rotate(45deg);
            }

            .search-suggestion-heading {
                position: relative;
                z-index: 1;
                padding: 14px 16px 10px;
                color: #6b7280;
                font-size: 12px;
                font-weight: 900;
                letter-spacing: .08em;
                text-transform: uppercase;
            }

            .search-suggestion-list {
                position: relative;
                z-index: 1;
                display: grid;
                padding: 0 8px 8px;
            }

            .search-suggestion-item {
                display: grid;
                grid-template-columns: 52px 1fr;
                gap: 12px;
                align-items: center;
                padding: 10px;
                border-radius: 14px;
                color: #1f2937;
                text-decoration: none;
                transition:
                    background-color .16s ease,
                    transform .16s ease;
            }

            .search-suggestion-item:hover,
            .search-suggestion-item.is-active {
                color: #0f4c75;
                background: #e6faff;
                transform: translateX(2px);
            }

            .search-suggestion-image {
                width: 52px;
                height: 52px;
                overflow: hidden;
                border-radius: 14px;
                display: grid;
                place-items: center;
                background:
                    linear-gradient(
                        135deg,
                        #caf0f8,
                        #90e0ef
                    );
                font-size: 24px;
            }

            .search-suggestion-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .search-suggestion-information {
                min-width: 0;
            }

            .search-suggestion-name {
                display: block;
                overflow: hidden;
                color: #082032;
                font-size: 15px;
                font-weight: 900;
                line-height: 1.3;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .search-suggestion-scientific {
                display: block;
                overflow: hidden;
                margin-top: 2px;
                color: #6b7280;
                font-size: 13px;
                font-style: italic;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .search-suggestion-meta {
                display: block;
                overflow: hidden;
                margin-top: 4px;
                color: #0f4c75;
                font-size: 12px;
                font-weight: 800;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .search-suggestion-state {
                padding: 26px 18px;
                color: #6b7280;
                text-align: center;
            }

            .search-suggestion-state-icon {
                display: block;
                margin-bottom: 8px;
                font-size: 30px;
            }

            .search-suggestion-state strong {
                display: block;
                margin-bottom: 4px;
                color: #082032;
            }

            .search-suggestion-footer {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 12px 16px;
                border-top: 1px solid #e5e7eb;
                color: #0f4c75;
                background: #f8fafc;
                font-size: 13px;
                font-weight: 900;
                text-decoration: none;
            }

            .search-suggestion-footer:hover {
                color: #082032;
                background: #e6faff;
            }

            .search-suggestion-loading {
                width: 24px;
                height: 24px;
                margin: 0 auto 10px;
                border: 3px solid #caf0f8;
                border-top-color: #0f4c75;
                border-radius: 50%;
                animation:
                    searchSuggestionSpin
                    .7s linear infinite;
            }

            @keyframes searchSuggestionSpin {
                to {
                    transform: rotate(360deg);
                }
            }

            @media (max-width: 1100px) {
                .search-suggestion-panel {
                    right: auto;
                    left: 0;
                    width: 100%;
                    max-width: 100%;
                }

                .search-suggestion-panel::before {
                    right: auto;
                    left: 28px;
                }
            }

            @media (max-width: 520px) {
                .search-suggestion-panel {
                    position: fixed;
                    top: 78px;
                    right: 14px;
                    left: 14px;
                    width: auto;
                    max-width: none;
                    max-height: calc(100vh - 100px);
                    overflow-y: auto;
                }

                .search-suggestion-panel::before {
                    display: none;
                }
            }
        `;

        document.head.appendChild(style);
    }

    function initializeSearchSuggestions() {
        const searchForm =
            document.querySelector(
                '.nav-search'
            );

        if (
            !searchForm
            || !(
                searchForm
                instanceof HTMLFormElement
            )
        ) {
            return;
        }

        if (
            searchForm.dataset
                .suggestionsInitialized
            === 'true'
        ) {
            return;
        }

        const searchInput =
            searchForm.querySelector(
                'input[name="q"]'
            );

        if (
            !searchInput
            || !(
                searchInput
                instanceof HTMLInputElement
            )
        ) {
            return;
        }

        searchForm.dataset
            .suggestionsInitialized =
            'true';

        injectSuggestionStyles();

        searchInput.setAttribute(
            'autocomplete',
            'off'
        );

        searchInput.setAttribute(
            'autocapitalize',
            'none'
        );

        searchInput.setAttribute(
            'spellcheck',
            'false'
        );

        searchInput.setAttribute(
            'aria-autocomplete',
            'list'
        );

        searchInput.setAttribute(
            'aria-expanded',
            'false'
        );

        const inputWrapper =
            document.createElement('div');

        inputWrapper.className =
            'search-suggestion-wrapper';

        searchInput.parentNode.insertBefore(
            inputWrapper,
            searchInput
        );

        inputWrapper.appendChild(
            searchInput
        );

        const suggestionPanel =
            document.createElement('div');

        suggestionPanel.className =
            'search-suggestion-panel';

        suggestionPanel.id =
            'searchSuggestionPanel';

        suggestionPanel.hidden = true;

        suggestionPanel.setAttribute(
            'role',
            'listbox'
        );

        inputWrapper.appendChild(
            suggestionPanel
        );

        searchInput.setAttribute(
            'aria-controls',
            suggestionPanel.id
        );

        const searchAction =
            searchForm.getAttribute('action')
            ?? '/search';

        function resolveSuggestionUrl() {
            if (configuredSuggestionUrl) {
                return configuredSuggestionUrl;
            }

            const actionUrl = new URL(
                searchAction,
                window.location.origin
            );

            actionUrl.pathname =
                actionUrl.pathname
                    .replace(/\/$/, '')
                + '/suggestions';

            actionUrl.search = '';

            return actionUrl.toString();
        }

        const suggestionUrl =
            resolveSuggestionUrl();

        let debounceTimer = null;
        let activeController = null;
        let activeIndex = -1;
        let suggestionItems = [];

        function openPanel() {
            suggestionPanel.hidden = false;

            searchInput.setAttribute(
                'aria-expanded',
                'true'
            );
        }

        function closePanel() {
            suggestionPanel.hidden = true;

            activeIndex = -1;
            suggestionItems = [];

            searchInput.setAttribute(
                'aria-expanded',
                'false'
            );
        }

        function showLoading() {
            suggestionPanel.innerHTML = `
                <div class="search-suggestion-state">
                    <div class="search-suggestion-loading"></div>
                    <strong>Mencari data ikan...</strong>
                </div>
            `;

            openPanel();
        }

        function showEmptyState(keyword) {
            suggestionPanel.innerHTML = '';

            const state =
                document.createElement('div');

            state.className =
                'search-suggestion-state';

            const icon =
                document.createElement('span');

            icon.className =
                'search-suggestion-state-icon';

            icon.textContent = '🔍';

            const title =
                document.createElement('strong');

            title.textContent =
                'Data tidak ditemukan';

            const description =
                document.createElement('span');

            description.textContent =
                `Tidak ada ikan yang cocok dengan “${keyword}”.`;

            state.appendChild(icon);
            state.appendChild(title);
            state.appendChild(description);

            suggestionPanel.appendChild(
                state
            );

            openPanel();
        }

        function setActiveItem(index) {
            suggestionItems.forEach(
                function (item) {
                    item.classList.remove(
                        'is-active'
                    );

                    item.setAttribute(
                        'aria-selected',
                        'false'
                    );
                }
            );

            if (
                index < 0
                || index
                    >= suggestionItems.length
            ) {
                activeIndex = -1;

                return;
            }

            activeIndex = index;

            const activeItem =
                suggestionItems[
                    activeIndex
                ];

            activeItem.classList.add(
                'is-active'
            );

            activeItem.setAttribute(
                'aria-selected',
                'true'
            );

            activeItem.scrollIntoView({
                block: 'nearest',
            });
        }

        function createSuggestionItem(
            suggestion,
            index
        ) {
            const link =
                document.createElement('a');

            link.href = suggestion.url;

            link.className =
                'search-suggestion-item';

            link.setAttribute(
                'role',
                'option'
            );

            link.setAttribute(
                'aria-selected',
                'false'
            );

            link.dataset.index =
                String(index);

            const imageWrapper =
                document.createElement('span');

            imageWrapper.className =
                'search-suggestion-image';

            if (suggestion.image) {
                const image =
                    document.createElement('img');

                image.src =
                    suggestion.image;

                image.alt =
                    suggestion.name;

                image.loading = 'lazy';

                image.addEventListener(
                    'error',
                    function () {
                        image.remove();

                        imageWrapper.textContent =
                            '🐟';
                    }
                );

                imageWrapper.appendChild(
                    image
                );
            } else {
                imageWrapper.textContent =
                    '🐟';
            }

            const information =
                document.createElement('span');

            information.className =
                'search-suggestion-information';

            const name =
                document.createElement('span');

            name.className =
                'search-suggestion-name';

            name.textContent =
                suggestion.name;

            information.appendChild(name);

            if (
                suggestion.scientific_name
            ) {
                const scientificName =
                    document.createElement(
                        'span'
                    );

                scientificName.className =
                    'search-suggestion-scientific';

                scientificName.textContent =
                    suggestion
                        .scientific_name;

                information.appendChild(
                    scientificName
                );
            }

            const metadata =
                document.createElement('span');

            metadata.className =
                'search-suggestion-meta';

            metadata.textContent =
                `${suggestion.region} • ${suggestion.category}`;

            information.appendChild(
                metadata
            );

            link.appendChild(
                imageWrapper
            );

            link.appendChild(
                information
            );

            link.addEventListener(
                'mouseenter',
                function () {
                    setActiveItem(index);
                }
            );

            return link;
        }

        function renderSuggestions(
            data,
            keyword,
            searchUrl
        ) {
            suggestionPanel.innerHTML = '';

            const heading =
                document.createElement('div');

            heading.className =
                'search-suggestion-heading';

            heading.textContent =
                'Saran Pencarian';

            suggestionPanel.appendChild(
                heading
            );

            const list =
                document.createElement('div');

            list.className =
                'search-suggestion-list';

            data.forEach(
                function (
                    suggestion,
                    index
                ) {
                    list.appendChild(
                        createSuggestionItem(
                            suggestion,
                            index
                        )
                    );
                }
            );

            suggestionPanel.appendChild(
                list
            );

            const footer =
                document.createElement('a');

            footer.className =
                'search-suggestion-footer';

            footer.href =
                searchUrl
                ?? `${searchAction}?q=${encodeURIComponent(keyword)}`;

            const footerText =
                document.createElement('span');

            footerText.textContent =
                `Lihat semua hasil “${keyword}”`;

            const footerArrow =
                document.createElement('span');

            footerArrow.textContent = '→';

            footer.appendChild(
                footerText
            );

            footer.appendChild(
                footerArrow
            );

            suggestionPanel.appendChild(
                footer
            );

            suggestionItems = Array.from(
                suggestionPanel
                    .querySelectorAll(
                        '.search-suggestion-item'
                    )
            );

            activeIndex = -1;

            openPanel();
        }

        async function loadSuggestions() {
            const keyword =
                searchInput.value.trim();

            if (keyword.length < 2) {
                if (activeController) {
                    activeController.abort();
                }

                closePanel();

                return;
            }

            if (activeController) {
                activeController.abort();
            }

            activeController =
                new AbortController();

            const requestedKeyword =
                keyword;

            showLoading();

            try {
                const url = new URL(
                    suggestionUrl,
                    window.location.origin
                );

                url.searchParams.set(
                    'q',
                    requestedKeyword
                );

                const response =
                    await fetch(
                        url.toString(),
                        {
                            method: 'GET',
                            credentials:
                                'same-origin',
                            cache: 'no-store',

                            signal:
                                activeController
                                    .signal,

                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',
                            },
                        }
                    );

                if (!response.ok) {
                    throw new Error(
                        'Saran pencarian gagal dimuat.'
                    );
                }

                const result =
                    await response.json();

                if (
                    searchInput.value.trim()
                    !== requestedKeyword
                ) {
                    return;
                }

                if (
                    !Array.isArray(
                        result.data
                    )
                    || result.data.length
                        === 0
                ) {
                    showEmptyState(
                        requestedKeyword
                    );

                    return;
                }

                renderSuggestions(
                    result.data,
                    requestedKeyword,
                    result.search_url
                );
            } catch (error) {
                if (
                    error.name
                    === 'AbortError'
                ) {
                    return;
                }

                console.error(error);

                closePanel();
            }
        }

        searchInput.addEventListener(
            'input',
            function () {
                window.clearTimeout(
                    debounceTimer
                );

                debounceTimer =
                    window.setTimeout(
                        loadSuggestions,
                        250
                    );
            }
        );

        searchInput.addEventListener(
            'focus',
            function () {
                if (
                    searchInput.value
                        .trim()
                        .length >= 2
                ) {
                    loadSuggestions();
                }
            }
        );

        searchInput.addEventListener(
            'keydown',
            function (event) {
                if (
                    suggestionPanel.hidden
                ) {
                    return;
                }

                if (
                    event.key
                    === 'ArrowDown'
                ) {
                    event.preventDefault();

                    const nextIndex =
                        activeIndex
                        < suggestionItems.length
                            - 1
                            ? activeIndex + 1
                            : 0;

                    setActiveItem(
                        nextIndex
                    );

                    return;
                }

                if (
                    event.key
                    === 'ArrowUp'
                ) {
                    event.preventDefault();

                    const previousIndex =
                        activeIndex > 0
                            ? activeIndex - 1
                            : suggestionItems
                                .length - 1;

                    setActiveItem(
                        previousIndex
                    );

                    return;
                }

                if (
                    event.key === 'Enter'
                    && activeIndex >= 0
                ) {
                    event.preventDefault();

                    suggestionItems[
                        activeIndex
                    ].click();

                    return;
                }

                if (
                    event.key === 'Escape'
                ) {
                    closePanel();
                }
            }
        );

        document.addEventListener(
            'click',
            function (event) {
                if (
                    !inputWrapper.contains(
                        event.target
                    )
                ) {
                    closePanel();
                }
            }
        );

        searchForm.addEventListener(
            'submit',
            function () {
                closePanel();
            }
        );
    }

    if (
        document.readyState
        === 'loading'
    ) {
        document.addEventListener(
            'DOMContentLoaded',
            initializeSearchSuggestions
        );
    } else {
        initializeSearchSuggestions();
    }
})();
