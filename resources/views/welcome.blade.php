<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CritCrafter</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background-color: #0f0f0f;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% 0%, rgba(180, 120, 20, 0.12) 0%, transparent 70%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M0 30 L30 0 L60 30 L30 60 Z' fill='none' stroke='rgba(245,158,11,0.04)' stroke-width='1'/%3E%3C/svg%3E");
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Instrument Sans', sans-serif;
            color: #e5e7eb;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3rem;
            padding: 2rem;
            text-align: center;
        }

        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.25rem;
        }

        .logo-wrap img {
            width: 260px;
            height: auto;
            filter: drop-shadow(0 0 24px rgba(245,158,11,0.3));
        }

        .tagline {
            font-size: 0.9rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #6b7280;
        }

        .divider {
            width: 80px;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(245,158,11,0.5), transparent);
        }

        .actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 2.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: #f59e0b;
            color: #0f0f0f;
            border: 1px solid #f59e0b;
        }

        .btn-primary:hover {
            background-color: #d97706;
            border-color: #d97706;
            box-shadow: 0 0 20px rgba(245,158,11,0.25);
        }

        .btn-secondary {
            background-color: transparent;
            color: #f59e0b;
            border: 1px solid rgba(245,158,11,0.4);
        }

        .btn-secondary:hover {
            background-color: rgba(245,158,11,0.08);
            border-color: rgba(245,158,11,0.7);
        }

        .footer {
            position: fixed;
            bottom: 1.5rem;
            font-size: 0.75rem;
            color: #374151;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="logo-wrap">
            <img src="{{ asset('images/logo.svg') }}" alt="CritCrafter">
            <div class="divider"></div>
            <p class="tagline">Your LOSS character sheet companion</p>
        </div>

        <div class="actions">
            @if (Route::has('filament.player.auth.login'))
                <a href="{{ route('filament.player.auth.login') }}" class="btn btn-primary">Login</a>
            @endif

            @if (Route::has('filament.player.auth.register'))
                <a href="{{ route('filament.player.auth.register') }}" class="btn btn-secondary">Register</a>
            @endif
        </div>
    </div>

    <p class="footer">CritCrafter &mdash; LOSS RPG Character Management</p>
</body>
</html>
