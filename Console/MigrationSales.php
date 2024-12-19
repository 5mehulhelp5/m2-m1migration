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

declare(strict_types=1);

namespace Qoliber\MigrationTool\Console;

use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Qoliber\MigrationTool\Model\Import\Entities\CreditMemo as CreditMemoImport;
use Qoliber\MigrationTool\Model\Import\Entities\Invoice as InvoiceImport;
use Qoliber\MigrationTool\Model\Import\Entities\Order as OrderImport;
use Qoliber\MigrationTool\Model\Import\Setup\SequenceTables as SequenceTables;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend_Db_Exception;
use Zend_Db_Statement_Exception;

class MigrationSales extends MigrationAbstract
{
    /** @var string  */
    public const NAME = "qoliber:import:sales";

    /** @var string  */
    public const DESCRIPTION = "Import sales data";

    protected SequenceTables $sequenceTables;
    protected OrderImport $orderImport;
    protected InvoiceImport $invoiceImport;
    protected CreditMemoImport $creditMemoImport;

    /**
     * MigrationToolSales constructor.
     * @param CreditMemoImport $creditMemoImport
     * @param SequenceTables $sequenceTables
     * @param InvoiceImport $invoiceImport
     * @param OrderImport $orderImport
     * @param State $state
     */
    public function __construct(
        CreditMemoImport $creditMemoImport,
        SequenceTables $sequenceTables,
        InvoiceImport $invoiceImport,
        OrderImport $orderImport,
        State $state
    ) {
        $this->creditMemoImport = $creditMemoImport;
        $this->invoiceImport = $invoiceImport;
        $this->orderImport = $orderImport;
        $this->sequenceTables = $sequenceTables;
        parent::__construct($state);
    }

    /**  */
    protected function configure()
    {
        $this->setName(self::NAME)->setDescription(self::DESCRIPTION);
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     * @throws LocalizedException
     * @throws Zend_Db_Exception
     * @throws Zend_Db_Statement_Exception
     */
    protected function migrate(InputInterface $input, OutputInterface $output): int
    {
        $this->init($input, $output);

        if (!$this->delta) {
            $this->sequenceTables->recreatedSequenceTables();
        }
        $this->orderImport->sync($this->delta);
        $this->invoiceImport->sync($this->delta);
        $this->creditMemoImport->sync($this->delta);

        return Cli::RETURN_SUCCESS;
    }
}
