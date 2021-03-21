<?php

namespace App\Game\Adventures\Jobs;

use App\Admin\Mail\GenericMail;
use App\Flare\Models\Adventure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Flare\Models\Character;
use App\Flare\Models\CharacterSnapShot;
use App\Flare\Models\User;
use App\Game\Adventures\Builders\RewardBuilder;
use App\Game\Adventures\Services\AdventureService;
use Cache;
use Mail;

class AdventureJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Character $character
     */
    protected $character;

    /**
     * @var Adventure $adventure
     */
    protected $adventure;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var int $currentLevel
     */
    protected $currentLevel;

    /**
     * @var bool $characterModeling
     */
    protected $characterModeling;

    protected $adminUser;

    protected $sendEmail;

    /**
     * Create a new job instance.
     *
     * @param Character $character
     * @param Adventure $adventure
     * @param string $name
     * @param int $curentLevel
     * @return void
     */
    public function __construct(
        Character $character, 
        Adventure $adventure, 
        string $name, 
        int $currentLevel, 
        bool $characterModeling = false, 
        User $adminUser = null, 
        bool $sendEmail = false
    ) {
        $this->character          = $character;
        $this->adventure          = $adventure;
        $this->name               = $name;
        $this->currentLevel       = $currentLevel;
        $this->characterModeling  = $characterModeling;
        $this->adminUser          = $adminUser;
        $this->sendEmail          = $sendEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RewardBuilder $rewardBuilder)
    {
        $name = Cache::get('character_'.$this->character->id.'_adventure_'.$this->adventure->id);

        if (is_null($name) || $name !== $this->name) {
            return;
        }

        $adevntureService = resolve(AdventureService::class, [
            'character'           => $this->character->refresh(),
            'adventure'           => $this->adventure,
            'rewardBuilder'       => $rewardBuilder,
            'name'                => $this->name
        ]);

        $adevntureService->processAdventure($this->currentLevel, $this->adventure->levels, $this->characterModeling);

        if ($this->currentLevel === $this->adventure->levels) {
            Cache::forget('character_'.$this->character->id.'_adventure_'.$this->adventure->id);
        }

        if ($this->characterModeling) {
            $data = [];
            $data[$this->currentLevel] = $adevntureService->getLogInformation();

            $snapShot = CharacterSnapShot::where('snap_shot->level', strval($this->character->level))
                                         ->where('character_id', $this->character->id)
                                         ->first();
            
            $snapShot->update(['adventure_simmulation_data' => $data]);

            if ($this->currentLevel === $this->adventure->levels) {
                $data         = [];
                $snapShotData = $snapShot->refresh()->adventure_simmulation_data;

                $data['adventure_id']   = $this->adventure->id;
                $data['snap_shot_data'] = $snapShotData;

                $snapShot->update([
                    'adventure_simmulation_data' => $data,
                ]);

                $snapShot = $snapShot->refresh();

                // Finally reset the character back to level 1000.
                $this->character->update(
                    $this->character->snapShots()->orderBy('snap_shot->level', 'desc')->first()->snap_shot
                );
                
                if ($this->sendEmail) {
                    Mail::to($this->adminUser->email)->send(new GenericMail($this->adminUser, 'Your adventure simulation has completed. Login and see the details for adventure: ' . $this->adventure->name . '.', 'Adventure Simulation Results', false));

                    Cache::delete('processing-adventure');
                }
            }
        }
    }
}
