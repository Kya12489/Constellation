<script setup>
    import { Link } from '@inertiajs/vue3';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';

    defineProps({
        canLogin: {
            type: Boolean,
            default: false,
        },
        canRegister: {
            type: Boolean,
            default: false,
        },
    });
</script>

<template>
    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <header class="border-b px-6 lg:px-10 grid grid-cols-1 lg:grid-cols-3 items-center gap-4 py-10">
            <!-- Logo à gauche -->
            <div class="flex justify-center lg:justify-start">
                <a :href="'/'">
                    <img
                        class="h-20 w-auto lg:h-32"
                        src="/images/logo.png"
                        alt="Constellation Logo"
                    />
                </a>
            </div>
            
            <!-- Titre au centre -->
            <div class="text-center">
                <h1 class="text-xl lg:text-2xl font-bold text-gray-800">
                    Constellation - Votre annuaire pour les associations françaises !
                </h1>
            </div>
            
            <!-- Navigation à droite -->
            <nav v-if="canLogin" class="flex justify-center lg:justify-end items-center">
                <template v-if="$page.props.auth.user">
                    <Link
                        :href="route('dashboard')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Dashboard
                    </Link>
                    
                    <!-- Settings Dropdown -->
                    <div class="ms-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                    >
                                        {{ $page.props.auth.user.name }}

                                        <svg
                                            class="-me-0.5 ms-2 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </template>
                
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
    </div>
</template>