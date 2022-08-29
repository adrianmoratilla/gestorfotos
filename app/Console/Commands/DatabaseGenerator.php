<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new SQLite Database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $status = 0;

        $path = database_path()."/database.sqlite";

        try{

            file_put_contents($path, "");

            $this->info("Database created at ".$path);

        }catch(\Exception $e){
            $this->error($e->getMessage());
            $status = -1;
        }


        return $status;
    }
}
