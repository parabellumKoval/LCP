<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Landing;
use App\Models\Translation;

class MoveTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:move';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->moveTranslations();
        return 0;
    }
    
    /**
     * moveTranslations
     *
     * @return void
     */
    public function moveTranslations() {
      $landings = Landing::all();

      foreach($landings as $landing) {
        $strings = $landing->strings;
        $locale = $landing->seo['locale'];

        if(empty($locale) || empty($strings)) {
          $this->error("skip landing " . $landing->key . ". Has not stings or has'n locale.");
          continue;
        }

        $trans = Translation::where('locale', $locale)->first();

        if(!$trans) {
          $trans = new Translation();
          $this->line('Creating new translations for ' . $locale . ' locale...');
        }else {
          $this->line('Update existing locale...');
        }

        $trans->locale = $locale;
        $trans->strings = $strings;
        $trans->save();

        $this->info('Landing ' . $landing->key . ' move translations processed.');
      }
    }
}
