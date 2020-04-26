<?php
/**
 * @Package Module: Ascripts_Testcode
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */



namespace Ascripts\Testcode\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddRecommendedAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $_moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $_eavSetupFactory;


    public function __construct
    (
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    )
    {
        /** @var  _moduleDataSetup */
        $this->_moduleDataSetup = $moduleDataSetup;
        /**
         * eavSetupFactory
         */
        $this->_eavSetupFactory = $eavSetupFactory;
    }

    /**
     * getDependencies()
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $setup = $this->_moduleDataSetup;

        /** @var EavSetup $eavSetup */
        $currentEavSetup = $this->_eavSetupFactory->create(
            ['setup' => $setup]
        );

        /** current Eavsetup */

        $currentEavSetup->addAttribute(
            'catalog_product', 'is_plastic',
            [
                'type' => 'int',
                'label' => 'Is Plastic',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'default' => 0,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'used_in_product_listing' => true,
                'user_defined' => true,
                'required' => false,
                'group' => 'General',
                'sort_order' => 80,
            ]);

    }

    /**
     * getAliases()
     */
    public function getAliases()
    {
        return [];
    }
}