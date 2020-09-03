<?php
/**
 * @link https://github.com/Aikrof
 * @package App\Console\Commands
 * @author Denys <AikrofStark@gmail.com>
 */

declare(strict_types = 1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class CreateDb
 *
 */
class CreateDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create{name?}';

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
     */
    public function handle(): void
    {
        $dbName = $schemaName = $this->argument('name') ?: env('DB_DATABASE');
        $charset = 'utf8';
        $collation = 'utf8_general_ci';

        config(["database.connections.mysql.database" => null]);

        $query = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$collation};";

        DB::statement($query);

        config(["database.connections.mysql.database" => $dbName]);
    }
}
