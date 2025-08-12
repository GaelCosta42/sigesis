<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterProdutosDadosFiscaisTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('produto');

        $icmspercentualstCheck = $table->hasColumn('icms_percentual_st');
        $mvapercentualCheck = $table->hasColumn('mva_percentual');
        $pisaliquotaCheck = $table->hasColumn('pis_aliquota');
        $cofinsaliquotaCheck = $table->hasColumn('cofins_aliquota');

        if (!$icmspercentualstCheck) {
            $table->addColumn('icms_percentual_st', 'decimal', ['precision' => 11, 'scale' => 2, 'after' => 'icms_percentual']);
        }

        if (!$mvapercentualCheck) {
            $table->addColumn('mva_percentual', 'decimal', ['precision' => 11, 'scale' => 2, 'after' => 'icms_percentual_st']);
        }

        if (!$pisaliquotaCheck) {
            $table->addColumn('pis_aliquota', 'decimal', ['precision' => 5, 'scale' => 2, 'after' => 'pis_cst']);
        }

        if (!$cofinsaliquotaCheck) {
            $table->addColumn('cofins_aliquota', 'decimal', ['precision' => 5, 'scale' => 2, 'after' => 'cofins_cst']);
        }

        if (!$icmspercentualstCheck || !$mvapercentualCheck || !$pisaliquotaCheck || !$cofinsaliquotaCheck) {
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('produto');

        $table->removeColumn('icms_percentual_st');
        $table->removeColumn('mva_percentual');
        $table->removeColumn('pis_aliquota');
        $table->removeColumn('cofins_aliquota');

        $table->save();
    }
}