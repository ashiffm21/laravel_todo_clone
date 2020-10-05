<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (file_exists(dirname(__FILE__) . "/../workbench/todo.sql")) {
            $db = file_get_contents(dirname(__FILE__) . "/../workbench/todo.sql");
            DB::unprepared($db);
        } else {
            throw new FileNotFoundException('Sql file is missing');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $rest_tables = (DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'' . DB::getDatabaseName() . '\''));

        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($rest_tables as $table) {
            $name = (isset($table->TABLE_NAME) ? $table->TABLE_NAME : ((isset($table->table_name) ? $table->table_name : '')));
            if ($name != 'migrations') {
                DB::statement('DROP TABLE IF EXISTS `' . $name . '`;');
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
