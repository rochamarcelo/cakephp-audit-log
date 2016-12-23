<?php
namespace AuditLog\Model\Table;

use AuditLog\Model\Entity\Audit;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Audits Model
 */
class AuditsTable extends Table
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
        $this->table('audits');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('AuditDeltas', [
            'foreignKey' => 'audit_id',
            'className' => 'AuditLog.AuditDeltas'
        ]);
        // Disable the search plugin by default
        if (Configure::read('AuditLog.enableSearch')) {
            $this->setupSearchPlugin();
        }
    }


    /**
     * Enable search plugin
     *
     * @return null
     */
    public function setupSearchPlugin()
    {
        $this->filterArgs = [
            'event' => [
                'type' => 'value'
            ],
            'model' => [
                'type' => 'value'
            ],
            'source_id' => [
                'type' => 'value'
            ],
            'entity_id' => [
                'type' => 'value'
            ],
        ];

        $this->addBehavior('Search.Searchable');
    }
}
