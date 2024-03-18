<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're at the role page!") }}
                </div>
                

                <div class="flex flex-wrap">
                    @foreach ($roles as $role)
                    <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700" style="margin-left: 1rem; margin-top: 1rem;">
                        <img class="rounded-t-lg" src="{{ asset('storage/image/' . $role->name . '.png') }}" alt="" />
                        <div class="p-5">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                {{$role->name}}
                            </h5>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">   
                                {{$role->description}}
                            </p>
                        </div>
                    </div>
                    @endforeach

                </div>

                <br>
            </div>
        </div>



    </div>
</x-app-layout>
