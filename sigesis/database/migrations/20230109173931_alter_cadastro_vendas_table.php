<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterCadastroVendasTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('cadastro_vendas');

        $valor_original_check = $table->hasColumn('valor_original');

	if (!$valor_original_check) {
        	$table->addColumn('valor_original', 'decimal',['precision'=>11, 'scale'=>3,'after' => 'quantidade_trocada']);
	}
       
	if (!$valor_original_check) {
        	$table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('cadastro_vendas');

        $table->removeColumn('valor_original');
        
        $table->save();
    }
}
