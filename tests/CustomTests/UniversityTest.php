<?php

namespace Tests\CustomTests;

use App\Events\GameClockEnded;
use App\Events\MoveMade;
use App\Events\PlayerPaired;
use App\Models\User;
use App\Models\ChessGame;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

use Tests\TestCase;

class UniversityTest extends TestCase
{
    use RefreshDatabase;
    
    // 1.
    public function test_users_can_access_home_page_1()
    {
        $this->get('/')->assertStatus(200)->assertViewIs('pages.home');
    }

    // 2.
    public function test_users_can_access_login_and_register_pages_2()
    {
        $this->get('/login')->assertStatus(200)->assertViewIs('pages.login');
        $this->get('/register')->assertStatus(200)->assertViewIs('pages.register');
    }

    // 3.
    public function test_logged_users_get_redirected_on_login_and_register_pages_3()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/login')->assertRedirect('/dashboard');
        $this->actingAs($user)->get('/register')->assertRedirect('/dashboard');
    }

    // 4.
    public function test_non_logged_users_cannot_access_dashboard_page_4()
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
    
    // 5.
    public function test_logged_user_can_access_dashboard_page_5()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertStatus(200)->assertViewIs('pages.dashboard');
    }

    // 6.
    public function test_non_logged_users_cannot_access_profile_page_6()
    {
        $this->get('user-profile')->assertRedirect('/login');
    }

    // 7.
    public function test_logged_user_can_access_profile_page_7()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('user-profile')->assertStatus(200)->assertViewIs('pages.user-profile');
    }

    // 8.
    public function test_player_paired_event_starts_8()
    {
        Event::fake();

        event(new PlayerPaired('žaidėjas_1', 'žaidėjas_2'));

        Event::assertDispatched(PlayerPaired::class, function($event) {
            return $event->player1 === 'žaidėjas_1' && $event->player2 === 'žaidėjas_2';
        });
    }

    // 9.
    public function test_player_paired_event_broadcasts_9()
    {
        Broadcast::shouldReceive('queue');

        $event = new PlayerPaired('žaidėjas_1', 'žaidėjas_2');

        $this->assertEquals('chess-room', $event->broadcastOn()->name);
        $this->assertEquals('players.paired', $event->broadcastAs());
        $this->assertEquals('žaidėjas_1', $event->player1);
        $this->assertEquals('žaidėjas_2', $event->player2);
    }

    // 10.
    public function test_move_made_event_starts_10()
    {
        Event::fake();

        event(new MoveMade('e2', 'e4', 'white'));

        Event::assertDispatched(MoveMade::class, function ($event) {
            return $event->move_source === 'e2' 
                && $event->move_target === 'e4'
                && $event->player_turn === 'white';
        });
    }

    // 11.
    public function test_move_made_event_broadcasts_11()
    {
        $event = new MoveMade('e2', 'e4', 'white');

        $this->assertEquals('chess-room', $event->broadcastOn()->name);
        $this->assertEquals('move.made', $event->broadcastAs());
        $this->assertEquals('e2', $event->move_source);
        $this->assertEquals('e4', $event->move_target);
        $this->assertEquals('white', $event->player_turn);
    }

    // 12.
    public function test_game_clock_ended_event_starts_12()
    {
        Event::fake();

        event(new GameClockEnded());

        Event::assertDispatched(GameClockEnded::class);
    }

    // 13.
    public function test_game_clock_ended_event_broadcasts_13()
    {
        $event = new GameClockEnded();

        $this->assertEquals('chess-room', $event->broadcastOn()->name);
        $this->assertEquals('clock.ended', $event->broadcastAs());
    }

    // 14.
    public function test_saves_chess_game_to_db_14()
    {
        ChessGame::create([
            'player1_id' => 1,
            'player2_id' => 2,
            'player1_name' => 'žaidėjas_1',
            'player2_name' => 'žaidėjas_2',
            'result' => '1-0',
            'game_finish_fen' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR',
            'game_history' => 'e2e4 e7e5',
        ]);

        $this->assertDatabaseHas('chess_games', [
            'player1_id' => 1,
            'player2_id' => 2,
            'player1_name' => 'žaidėjas_1',
            'player2_name' => 'žaidėjas_2',
            'result' => '1-0',
        ]);
    }

    // 15.
    public function test_updates_chess_game_in_db_15()
    {
        $chess_game = ChessGame::create([
            'player1_id' => 1,
            'player2_id' => 2,
            'player1_name' => 'žaidėjas_1',
            'player2_name' => 'žaidėjas_2',
            'result' => '1-0',
            'game_finish_fen' => 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR',
            'game_history' => 'e2e4 e7e5',
        ]);

        $chess_game->update([
            'result' => '0-1',
        ]);

        $this->assertDatabaseHas('chess_games', [
            'id' => $chess_game->id,
            'result' => '0-1',
        ]);
    }

    // 16.
    public function test_returns_302_when_game_not_found_16()
    {
        $this->get(route('analyse_game', ['game_id' => 99999]))->assertStatus(302);
    }

    // 17.
    public function test_non_logged_user_cannot_access_stockfish_page_17()
    {
        $this->get('/stockfish')->assertRedirect(route('login'));
    }

    // 18.
    public function test_play_route_returns_user_data_18()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $this->get('/play')->assertStatus(200)->assertViewHas('username', $user->name);
    }

    // 19.
    public function test_non_logged_user_cannot_access_play_route_19()
    {
        $this->get('/play')->assertStatus(302);
    }

    // 20.
    public function test_player_paired_event_not_dispatched_if_players_not_given_20()
    {
        Event::fake();

        $player_1 = '';
        $player_2 = '';
        
        if (!empty($player_1) && !empty($player_2)) {
            event(new PlayerPaired($player_1, $player_2));
        }

        Event::assertNotDispatched(PlayerPaired::class);
    }

}
