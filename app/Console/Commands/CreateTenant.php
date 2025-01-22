<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {name}';
    protected $description = 'Create a new tenant and clone the master database';

    public function handle()
    {
        $tenantName = $this->argument('name');
        $databaseName = 'tenant_' . strtolower($tenantName);

        // Step 1: Create a new tenant and assign the database name
        $tenant = tenancy()->create($tenantName, [
            'name' => $tenantName,
            'database' => $databaseName,
        ]);

        // Step 2: Clone the master database into the tenant's database
        $this->cloneMasterDatabase($databaseName);

        $this->info("Tenant '{$tenantName}' created successfully with database '{$databaseName}'.");
    }

    private function cloneMasterDatabase($tenantDatabase)
    {
        $masterDatabase = config('database.connections.mysql.database'); // Get the master DB name

        // Step 1: Create the new tenant database
        DB::statement("CREATE DATABASE `$tenantDatabase`");

        // Step 2: Get all tables from the master database
        $tables = DB::select("SHOW TABLES FROM `$masterDatabase`");

        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];

            // Step 3: Copy table structure
            DB::statement("CREATE TABLE `$tenantDatabase`.`$tableName` LIKE `$masterDatabase`.`$tableName`");

            // Step 4: Copy table data
            DB::statement("INSERT INTO `$tenantDatabase`.`$tableName` SELECT * FROM `$masterDatabase`.`$tableName`");
        }
    }
}
