<?php
/**
 * Copyright © Q-Solutions Studio: eCommerce Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_MigrationTool
 * @author      Jakub Winkler <jwinkler@qoliber.com
 * @author      Sebastian Strojwas <sstrojwas@qoliber.com>
 * @author      Wojciech M. Wnuk <wwnuk@qoliber.com>
 * @author      Lukasz Owczarczuk <lowczarczuk@qoliber.com>
 */

namespace Qoliber\MigrationTool\Model\Import\Entities;

use Qoliber\MigrationTool\Model\Import\SyncAbstract;

class CustomerAddressSync extends SyncAbstract
{
    /**
     * @var bool
     */
    protected bool $truncate = true;

    /**
     * @var bool
     */
    protected bool $syncEavValues = true;

    protected function getTablesToSync(): array
    {
        return [
            'customer_address_entity' => $this->connectionHelper->getM2Connection()->getTableName('customer_address_entity')
        ];
    }

    /**
     * @param $m1Entity
     * @param $matchingColumns
     * @return array
     */
    public function prepareRowToInsert($m1Entity, $matchingColumns): array
    {
        unset($m1Entity['attribute_set_id']);
        unset($m1Entity['entity_type_id']);

        return $m1Entity;
    }

    /**
     * @return int|null
     */
    protected function getEntityTypeId(): ?int
    {
        return 2;
    }
}
