<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($game -> count() > 0)

                    <button type="button" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <a href="{{route('create.game')}}">
                            Continue Game
                        <a>
                    </button>

                    @else

                    <button type="button"  
                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <a href="{{route('create.game')}}">
                            Start Game
                        </a>
                    </button>

                    @endif



                    <div class="p-6 text-gray-900 dark:text-gray-100" style="margin-top: 2rem;">
                        {{ __("History Table") }}
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
                                
                                @foreach ($history as $game)
                                    @php
                                        $role_name = null;
                                        if ($game->pivotGameUser && $game->pivotGameUser->role_id) {
                                            if ($game->pivotGameUser->role_id == 1) {
                                                $role_name = "Mafia";
                                            } elseif ($game->pivotGameUser->role_id == 2) {
                                                $role_name = "Villager";
                                            } elseif ($game->pivotGameUser->role_id == 3) {
                                                $role_name = "Doctor";
                                            } elseif ($game->pivotGameUser->role_id == 4) {
                                                $role_name = "Mayor";
                                            } elseif ($game->pivotGameUser->role_id == 5) {
                                                $role_name = "Sheriff";
                                            }
                                        }
                                    @endphp
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                        @if(($game->winner === 0 && $game->pivotGameUser->role_id == 1) || ($game->winner === 1 && $game->pivotGameUser->role_id != 1))
                                        style="background-color: #d9f99d;"
                                        @elseif($game->winner === null)
                                        style="background-color: #cbd5e1;"
                                        @else
                                        style="background-color: #fee2e2;"
                                        @endif>
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                               {{ $game->id." ".$game->status->name . " (". $role_name . ")" }}
                                            </th>
                                                @if($game->winner === 0)
                                                <td class="px-6 py-4 text-right">
                                                    Mafia have Wone! 
                                                </td>
                                                @elseif($game->winner === 1)
                                                <td class="px-6 py-4 text-right">
                                                    Mafia have Lost!
                                                </td>
                                                @else
                                                <td class="px-6 py-4 text-right">
                                                    You have leaft the game! 
                                                </td>
                                                @endif
                                           
                                        </tr>
                                  
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
