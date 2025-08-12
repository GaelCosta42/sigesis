<?php
declare(strict_types=1);
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class UpdateCrediario extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('tipo_pagamento_categoria');
        $singleRow = [
            'id' => '9',
            'categoria' => 'CREDIARIO',
            'inativo' => '0'
        ];
        $table->insert($singleRow)->saveData();

        $table = $this->table('cadastro_crediario');
        $table->addColumn('valor_pago',     'decimal',['precision'=>11, 'scale'=>3, 'after'=>'valor']);
        $table->addColumn('juros',          'decimal',['precision'=>11, 'scale'=>3, 'after'=>'operacao']);
        $table->addColumn('multa',          'decimal',['precision'=>11, 'scale'=>3, 'after'=>'juros']);
        $table->addColumn('valor_sem_juros','decimal',['precision'=>11, 'scale'=>3, 'after'=>'multa']);
        $table->save();

        $table = $this->table('empresa');
        $table->addColumn('multa_crediario',     'decimal',['precision'=>5, 'scale'=>2, 'after'=>'crediario']);
        $table->addColumn('juros_crediario',     'decimal',['precision'=>5, 'scale'=>2, 'after'=>'multa_crediario']);
        $table->addColumn('tolerancia_crediario','decimal',['precision'=>5, 'scale'=>2, 'after'=>'juros_crediario']);
        $table->save();

        $table = $this->table('cadastro_crediario_pagamentos');
        $table->addColumn('id_cadastro','integer');
        $table->addColumn('id_cadastro_crediario','integer');
        $table->addColumn('valor_pago','decimal',['precision'=>11, 'scale'=>3]);
        $table->addColumn('tipo_pagamento','integer',['limit' => MysqlAdapter::INT_TINY]);
        $table->addColumn('data_pagamento','datetime');
        $table->addColumn('inativo','integer',['limit' => MysqlAdapter::INT_TINY]);
        $table->addColumn('usuario','string', ['limit' => 20]);
        $table->addColumn('data','datetime');
        $table->create();

        $table = $this->table('receita');
        $table->addColumn('promissoria','integer',['limit' => MysqlAdapter::INT_TINY, 'after'=>'conciliado']);
        $table->save();
       
    }

    public function down(): void
    {
        $builder = $this->getQueryBuilder();
        $builder
            ->delete('tipo_pagamento_categoria')
            ->where(['id' => '9'])
            ->execute();
        
        $table = $this->table('cadastro_crediario');
        $table->removeColumn('valor_pago');
        $table->removeColumn('juros');
        $table->removeColumn('multa');
        $table->removeColumn('valor_sem_juros');
        $table->save();

        $table = $this->table('empresa');
        $table->removeColumn('multa_crediario');
        $table->removeColumn('juros_crediario');
        $table->removeColumn('tolerancia_crediario');
        $table->save();

        $table = $this->table('cadastro_crediario_pagamentos');
        $table->drop()->save();

        $table = $this->table('receita');
        $table->removeColumn('promissoria');
        $table->save();

    }
}
