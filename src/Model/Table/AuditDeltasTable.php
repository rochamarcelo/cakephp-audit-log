<?php
namespace AuditLog\Model\Table;

use AuditLog\Model\Entity\AuditDelta;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AuditDeltas Model
 */
class AuditDeltasTable extends Table
{
    public $filterArgs = [];
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setTable('audit_deltas');
        $this->setDisplayField('property_name');
        $this->setPrimaryKey('id');
        $this->belongsTo('Audits', [
            'foreignKey' => 'audit_id',
            'joinType' => 'INNER',
            'className' => 'AuditLog.Audits'
        ]);
        $this->addBehavior('CounterCache', [
             'Audits' => ['delta_count']
        ]);
        // Disable the search plugin by default
        if (Configure::read('AuditLog.enableSearch')) {
            $this->setupSearchPlugin();
        }
    }

    public function setupSearchPlugin()
    {
        $this->filterArgs = [
            'source_id' => [
                'type'  => 'value',
                'field' => 'Audits.source_id',
                'model' => 'Audits',
                'fields' => [
                    'id' => 'source_id',
                    'label' => 'source_id',
                    'value' => 'source_id'
                ]
            ],
            'model' => [
                'type'  => 'value',
                'field' => 'Audits.model',
                'model' => 'Audits',
                'fields' => [
                    'id' => 'model',
                    'label' => 'model',
                    'value' => 'model'
                ]
            ],
            'entity_id' => [
                'type'  => 'value',
                'field' => 'Audits.entity_id',
                'model' => 'Audits',
                'fields' => [
                    'id' => 'entity_id',
                    'label' => 'entity_id',
                    'value' => 'entity_id'
                ]
            ],
            'property_name' => ['type' => 'value'],
            'old_value'         => ['type' => 'value'],
            'new_value'         => ['type' => 'value'],
        ];

        $this->addBehavior('Search.Searchable');
    }
}
