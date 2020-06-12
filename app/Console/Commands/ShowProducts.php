<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;

class ShowProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:showQty';

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
     * @return mixed
     */
    public function handle()
    {
        $productsQty = Product::where('qty','<=','5')->where('qty','!=','0')->get();
        logger( $productsQty);
    }
}
