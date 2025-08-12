<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterProdutoTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('produto');

        $prazo_trocaCheck = $table->hasColumn('prazo_troca');

	if (!$prazo_trocaCheck) {
        	$table->addColumn('prazo_troca', 'integer',['after' => 'consumo_medio']);
	}
       
	if (!$prazo_trocaCheck) {
        	$table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('produto');

        $table->removeColumn('prazo_troca');
        
        $table->save();
    }
}
