<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Night') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("You're at the game night page!") }}
                    </div>

                    <form method="POST" action="{{ route('leave.game', ['id' => $userGame[0]->game_id]) }}">
                        @csrf
                        <button class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"
                        type="submit">Leave Game</button>
                    </form>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("You're  Role is:") }}
                    </div>

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

                    
                    
                    <div class="p-6 text-gray-900 dark:text-gray-100" style="margin-top: 2rem;">
                        {{ __("Alive Players") }}
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg" style=" margin-top: 2rem;">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Player
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($players as $player)
                                    @if ($player->user->id != $userId && $player->is_alive == 1)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{  $player->user->name . " ". $player->user->lastname }}
                                            </th>
                                            @if($userGame[0]->is_alive == 1 && $player->role_id != 1)
                                                <td class="px-6 py-4 text-right">
                                                    <form method="POST" action="{{ route('insta.kill.player.night', ['user_id' => $player->user->id]) }}" class="mb-2">
                                                        @csrf
                                                        <button class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none focus:ring focus:ring-opacity-50" type="submit">
                                                            Insta kill
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="p-6 text-gray-900 dark:text-gray-100" style="margin-top: 2rem;">
                        {{ __("Dead Players") }}
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg" style=" margin-top: 2rem;">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Player
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($players as $player)
                                    @if ($player->user->id != $userId && $player->is_alive == 0)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" 
                                            style="background-color: #F6A0A0;">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{  $player->user->name . " ". $player->user->lastname }}
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>



                </div>
            </div>
        </div>
    </div>
</x-app-layout>
