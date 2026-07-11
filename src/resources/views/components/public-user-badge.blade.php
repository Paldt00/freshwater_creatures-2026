@props([
    'user' => null,
])

@php
    $userName = $user?->name ?? 'Pengguna';

    $userEmail = $user?->email
        ? mb_strtolower(trim($user->email))
        : null;

    $avatarUrl = $userEmail
        ? 'https://www.gravatar.com/avatar/' . md5($userEmail) . '?s=96&d=404'
        : null;
@endphp

<span
    class="public-user-badge"
    title="{{ $userName }}"
>
    <span class="public-user-avatar">
        @if($avatarUrl)
            <img
                src="{{ $avatarUrl }}"
                alt="Foto profil {{ $userName }}"
                loading="lazy"
                referrerpolicy="no-referrer"
                onerror="this.remove(); this.parentElement.classList.add('is-fallback');"
            >
        @endif

        <span
            class="public-user-avatar-fallback"
            aria-hidden="true"
        >
            👤
        </span>
    </span>

    <span class="public-user-name">
        {{ $userName }}
    </span>
</span>

@once
    <style>
        .public-user-badge {
            max-width: 190px;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 7px 12px 7px 8px;
            border-radius: 999px;
            color: var(--blue);
            background: var(--cyan-soft);
            font-size: 13px;
            font-weight: 900;
            white-space: nowrap;
        }

        .public-user-avatar {
            width: 30px;
            height: 30px;
            flex: 0 0 auto;
            overflow: hidden;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background:
                linear-gradient(
                    135deg,
                    var(--blue),
                    var(--cyan)
                );
            color: #ffffff;
            box-shadow:
                0 6px 14px rgba(15, 76, 117, .18);
        }

        .public-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .public-user-avatar-fallback {
            display: none;
            font-size: 16px;
            line-height: 1;
        }

        .public-user-avatar:empty
        .public-user-avatar-fallback,
        .public-user-avatar.is-fallback
        .public-user-avatar-fallback {
            display: inline;
        }

        .public-user-avatar:not(:has(img))
        .public-user-avatar-fallback {
            display: inline;
        }

        .public-user-name {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 1100px) {
            .public-user-badge {
                width: 100%;
                max-width: none;
                justify-content: center;
                border-radius: 14px;
                padding: 10px 12px;
            }
        }
    </style>
@endonce
