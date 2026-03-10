<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="relative mx-auto flex min-h-screen w-full max-w-6xl flex-col px-6 py-10">
            <header class="flex items-center justify-between">
                <div class="flex items-center gap-3 text-sm uppercase tracking-[0.2em] text-slate-400">
                    <span class="flex size-10 items-center justify-center rounded-full bg-slate-900/80 text-[#FF2D20] shadow-lg shadow-[#ff2d20]/30">
                        <svg class="size-5" viewBox="0 0 62 65" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253Z" /></svg>
                    </span>
                    <span>Laravel Template</span>
                    </div>

                <nav v-if="canLogin" class="flex items-center gap-3 text-sm font-semibold text-slate-300">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                        class="rounded-full border border-slate-700/60 px-4 py-2 transition hover:border-[#FF2D20]/60 hover:text-white"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                            class="rounded-full border border-slate-700/60 px-4 py-2 transition hover:border-[#FF2D20]/60 hover:text-white"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                            class="rounded-full border border-[#FF2D20]/60 bg-[#FF2D20]/10 px-4 py-2 text-[#FF7A66] transition hover:bg-[#FF2D20]/20 hover:text-[#FFB8AA]"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

            <main class="mt-16 flex flex-1 flex-col gap-16 pb-16">
                <section class="text-center">
                    <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                        Thanks for forking this starter kit
                    </h1>
                    <p class="mx-auto mt-5 max-w-3xl text-lg leading-relaxed text-slate-300">
                        You’re now standing on top of a batteries-included Laravel 12 foundation with Jetstream, Inertia, and Vue 3 already wired together.
                        Use it to bootstrap clean, repeatable projects with modern tooling and zero setup friction.
                    </p>

                    <div class="mt-10 flex flex-wrap justify-center gap-6">
                        <a
                            href="mailto:hello@halfshellstudios.co.uk"
                            class="inline-flex items-center gap-2 rounded-full bg-[#FF2D20] px-6 py-3 text-base font-semibold text-white shadow-lg shadow-[#FF2D20]/30 transition hover:-translate-y-0.5 hover:bg-[#ff4c3c]"
                        >
                            Let’s talk about your project
                        </a>
                        <a
                            href="https://www.linkedin.com/in/stuarttodd/"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-700 px-6 py-3 text-base font-semibold text-slate-200 transition hover:-translate-y-0.5 hover:border-[#FF2D20]/70 hover:text-white"
                        >
                            Connect on LinkedIn
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.98 3.5C4.98 5 3.9 6.1 2.4 6.1 1 6.1 0 5 0 3.5 0 2 1 1 2.4 1c1.5 0 2.58 1 2.58 2.5zM.24 8.79h4.32V24H.24zM9.18 8.79h4.15v2.07h.06c.58-1.1 2-2.26 4.12-2.26 4.4 0 5.21 2.9 5.21 6.66V24h-4.3v-7.43c0-1.77-.03-4.05-2.47-4.05-2.47 0-2.85 1.93-2.85 3.92V24H9.18z" />
                            </svg>
                        </a>
                    </div>

                    <div class="mt-12 flex flex-col items-center gap-4">
                        <img
                            src="https://halfshellstudios.co.uk/assets/images/primary-logo.png"
                            alt="Half Shell Studios Logo"
                            class="h-14 w-auto md:h-20"
                        />
                        <img
                            src="http://stuart-todd.co.uk/theme/assets/img/profile2.jpg"
                            alt="Stuart Todd"
                            class="h-32 w-32 rounded-full border border-slate-700/70 object-cover shadow-lg shadow-slate-900/40"
                        />
                        <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Built & maintained by Stuart Todd</p>
                        <div class="flex flex-wrap justify-center gap-3 text-sm font-semibold text-[#FF7A66]">
                            <a
                                href="https://halfshellstudios.co.uk/cv.pdf"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="transition hover:text-[#ffb8aa]"
                            >
                                View Stuart’s CV
                            </a>
                            <span class="text-slate-600">•</span>
                            <a
                                href="https://halfshellstudios.co.uk"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="transition hover:text-[#ffb8aa]"
                            >
                                Visit halfshellstudios.co.uk
                            </a>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-800/60 bg-slate-900/60 p-10 shadow-2xl shadow-black/40 backdrop-blur">
                    <h2 class="text-2xl font-semibold text-white sm:text-3xl">What’s in the box?</h2>
                    <p class="mt-3 max-w-2xl text-base text-slate-300">
                        This template is designed to be forked and shipped. Everything you need to go from idea to production lives here, including
                        a fully configured CI pipeline, testing stack, and developer tooling.
                    </p>

                    <div class="mt-10 grid gap-8 md:grid-cols-2">
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-white">Core stack</h3>
                            <ul class="space-y-3 text-sm text-slate-300">
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 size-2 rounded-full bg-[#FF2D20]"></span>
                                    Laravel 12 with Jetstream (Inertia + Vue 3), Tailwind CSS, Vite 7, Ziggy, and Axios for a modern SPA workflow.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 size-2 rounded-full bg-[#FF2D20]"></span>
                                    Sail-powered Docker stack with PHP 8.4, Node 22.x, MySQL, Redis, Telescope, and Debugbar.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 size-2 rounded-full bg-[#FF2D20]"></span>
                                    Pre-seeded demo user and ready-to-go auth scaffolding for instant demos.
                                </li>
                            </ul>
                            </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-white">Quality gates</h3>
                            <ul class="space-y-3 text-sm text-slate-300">
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 size-2 rounded-full bg-[#FF2D20]"></span>
                                    Pest test suite with 100% coverage configured out of the box (`composer coverage` / `composer test:coverage`).
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 size-2 rounded-full bg-[#FF2D20]"></span>
                                    Jest unit tests for Vue components plus Playwright end-to-end tests with HTML reporting.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 size-2 rounded-full bg-[#FF2D20]"></span>
                                    Standards scripts wrapping PHPCS, PHPMD, PHPStan, and Rector (check/fix), along with Laravel Pint.
                                </li>
                            </ul>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-white">Composer scripts</h3>
                            <ul class="space-y-3 text-sm text-slate-300">
                                <li><code class="rounded bg-slate-800 px-2 py-1 text-xs text-slate-200">composer tests</code> — build assets, prep SQLite DB, run Pest.</li>
                                <li><code class="rounded bg-slate-800 px-2 py-1 text-xs text-slate-200">composer coverage</code> — Pest with coverage and HTML report support.</li>
                                <li><code class="rounded bg-slate-800 px-2 py-1 text-xs text-slate-200">composer standards:check</code> / <code class="rounded bg-slate-800 px-2 py-1 text-xs text-slate-200">composer standards:fix</code> — enforce and auto-fix code quality.</li>
                            </ul>
                            </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-white">CI/CD ready</h3>
                            <ul class="space-y-3 text-sm text-slate-300">
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 size-2 rounded-full bg-[#FF2D20]"></span>
                                    GitHub Actions workflow mirrors the local scripts, running tests and standards on every push / PR.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 size-2 rounded-full bg-[#FF2D20]"></span>
                                    Node dependencies installed via <code class="rounded bg-slate-800 px-2 py-1 text-xs text-slate-200">npm ci</code> to keep assets in sync during CI runs.
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-1 size-2 rounded-full bg-[#FF2D20]"></span>
                                    Easy to extend for deploy pipelines—just plug in your staging or production steps.
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-800/60 bg-slate-900/40 p-10 text-center shadow-2xl shadow-black/30 backdrop-blur">
                    <h2 class="text-2xl font-semibold text-white sm:text-3xl">Need help delivering your next project?</h2>
                    <p class="mx-auto mt-4 max-w-3xl text-base text-slate-300">
                        I partner with teams to architect, ship, and scale Laravel + Vue products—whether you need a production-ready starter kit,
                        feature delivery, or just a calm pair of hands to steady an ambitious roadmap.
                    </p>

                    <div class="mt-8 flex flex-wrap justify-center gap-4 text-sm font-medium text-slate-200">
                        <span class="flex items-center gap-2 rounded-full border border-slate-700/60 px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-[#FF7A66]" fill="currentColor" viewBox="0 0 24 24"><path d="M21 3H3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2ZM3 7.7l6.52 4.58L3 16.82Zm9 4.3L3.23 5h17.54Zm-2.48.58L21 7.7v9.12Z" /></svg>
                            hello@halfshellstudios.co.uk
                        </span>
                        <span class="flex items-center gap-2 rounded-full border border-slate-700/60 px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-[#FF7A66]" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5C4.98 5 3.9 6.1 2.4 6.1 1 6.1 0 5 0 3.5 0 2 1 1 2.4 1c1.5 0 2.58 1 2.58 2.5zM.24 8.79h4.32V24H.24zM9.18 8.79h4.15v2.07h.06c.58-1.1 2-2.26 4.12-2.26 4.4 0 5.21 2.9 5.21 6.66V24h-4.3v-7.43c0-1.77-.03-4.05-2.47-4.05-2.47 0-2.85 1.93-2.85 3.92V24H9.18z" /></svg>
                            <a href="https://www.linkedin.com/in/stuarttodd/" target="_blank" rel="noopener noreferrer" class="underline decoration-[#FF7A66]/50 transition hover:text-white hover:decoration-[#FF2D20]">
                                linkedin.com/in/stuarttodd
                            </a>
                        </span>
                    </div>
                </section>
                </main>

            <footer class="pb-10 text-center text-xs uppercase tracking-[0.3em] text-slate-500">
                Laravel v{{ laravelVersion }} · PHP v{{ phpVersion }}
                </footer>
        </div>
    </div>
</template>
