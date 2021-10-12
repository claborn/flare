<?php

namespace App\Game\Core\Events;

use App\Game\Core\Services\CharacterInventoryService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
Use App\Flare\Models\User;

class CharacterInventoryUpdateBroadCastEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var array $inventory
     */
    public $inventory;

    /**
     * @var User $users
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param array $boons
     */
    public function __construct(User $user) {
        $this->user      = $user;
        $this->inventory = resolve(CharacterInventoryService::class)
            ->setCharacter($user->character->refresh())
            ->getInventoryForApi();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('update-inventory-' . $this->user->id);
    }
}