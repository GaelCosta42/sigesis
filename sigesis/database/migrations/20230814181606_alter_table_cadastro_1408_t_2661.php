<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterTableCadastro1408T2661 extends AbstractMigration
{
   
    public function up()
    {
        $table = $this->table('cadastro');

        $hasColumnDataNasc = $table->hasColumn('data_nascimento');
        
        // $table->addColumn('data_nascimento', 'datetime', ['after' => 'razao_social']);
        // $table->save();
        if (!$table->hasColumn('data_nascimento')) {
            $table->addColumn('data_nascimento', 'date', ['after' => 'razao_social', 'null' => true]);
            $table->update();
        }
    }

    public function down()
    {
        // $table = $this->table('cadastro');
        // $table->removeColumn('data_nascimento');
        // $table->save();

        $table = $this->table('cadastro');
        $table->removeColumn('data_nascimento');
        $table->update();
    }
}
