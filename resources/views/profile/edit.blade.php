<x-app-layout>

    <x-slot name="header">
        <h5 class="font-semibold text-gray-800 leading-tight mb-0" style="font-size: 1.3rem;">
            {{ __('Perfil') }}
        </h5>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-0">
            <style>
                /* Forzar tamaños reducidos en el perfil para igualar con SGA */
                .perfil-container input[type="text"],
                .perfil-container input[type="email"],
                .perfil-container input[type="password"] {
                    font-size: 0.85rem !important;
                    padding: 0.25rem 0.5rem !important;
                    height: auto !important;
                    border-radius: 6px !important;
                }

                .perfil-container label {
                    font-size: 0.75rem !important;
                    margin-bottom: 0px !important;
                }

                .perfil-container button {
                    font-size: 0.75rem !important;
                    padding: 0.25rem 0.75rem !important;
                }

                .perfil-container .text-sm {
                    font-size: 0.75rem !important;
                }

                .perfil-container .text-lg {
                    font-size: 0.95rem !important;
                    font-weight: bold !important;
                }
            </style>

            <div class="card card-body p-3 shadow-sm perfil-container">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card card-body p-3 shadow-sm perfil-container">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card card-body p-3 shadow-sm perfil-container">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>