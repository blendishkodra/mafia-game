## Mafia Game

This is a simple Laravel Web Game based on [Mafia (party game)](https://en.wikipedia.org/wiki/Mafia_(party_game))

### Roles in Mafia Game

- **Mafia**: The antagonists of the game. Each night, they vote to eliminate one player.
- **Villager**: The innocent civilians. During the day, they vote to eliminate one player.
- **Doctor**: A townsperson with healing abilities. Each night, they can choose a player to save from being killed.
- **Mayor**: A townsperson with special voting power. Their vote counts as double during the day.
- **Sheriff**: A townsperson with investigative abilities. During the day, they can attempt to kill a player. If the player is not a Mafia, the Sheriff gets killed.


## How to run the game

- Clone the repository
- Run `composer install`
- Run `php artisan migrate --seed`
- Run `php artisan storage:link`
- Run `php artisan serve`
- Run `npm install`
- Run `npm run dev`
- Open your browser and go to `http://localhost:8000`

## UML 

![Mafia Game UML Diagram](mafiaGameDiagram.png)

