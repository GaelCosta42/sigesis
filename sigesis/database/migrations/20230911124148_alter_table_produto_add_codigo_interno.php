<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterTableProdutoAddCodigoInterno extends AbstractMigration
{
    public function up(): void
    {
		$table = $this->table('produto');
		$check = $table->hasColumn('codigo_interno');
		if (!$check) {
			$table->addColumn('codigo_interno', 'string', ['limit' => 45, 'after' => 'codigo']);
			$table->save();
			
			$this->execute("UPDATE produto SET codigo_interno=CONCAT('#',id) WHERE 1");

		}
		
	}

	public function down(): void
    {
        $table = $this->table('produto');
        $table->removeColumn('codigo_interno');
        $table->save();
    }
}
