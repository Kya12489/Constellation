<script setup>
    import { Link } from '@inertiajs/vue3';

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
    <div class="overflow-hidden  bg-white shadow-sm sm:rounded-lg">
        
            
                <header
                    class=" border-b   grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3"
                >
                    <div class="flex lg:col-start-2 lg:justify-center ">
                            <img
                                class="h-12 w-auto lg:h-16"
                                src="/resources/images/logo.png"
                                alt="Constellation Logo"
                            />
                        </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end mr-10">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] "
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] "
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