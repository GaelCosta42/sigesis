<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlaterNotaFiscalItensTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('nota_fiscal_itens');

        $cod_anpCheck = $table->hasColumn('cod_anp');
        $valor_partidaCheck = $table->hasColumn('valor_partida');

        if (!$cod_anpCheck) {
            $table->addColumn('cod_anp', 'string', ['limit' => 20, 'after' => 'valor_negociado_total']);
        }

        if (!$valor_partidaCheck) {
            $table->addColumn('valor_partida', 'decimal',['precision' => 11, 'scale' => 3,'after' => 'cod_anp']);
        }

        if (!$cod_anpCheck) {
            $table->save();
        }

        if (!$valor_partidaCheck) {
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('nota_fiscal_itens');

        $table->removeColumn('cod_anp');
        $table->removeColumn('valor_partida');

        $table->save();
    }
}