<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class VirtualHelper extends Module
{
    public function __construct()
    {
        $this->name = 'virtualhelper';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Your Name';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Virtual Helper');
        $this->description = $this->l('Display product caretaker information on the product page.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayProductAdditionalInfo')
            && $this->installSql()
            && $this->installTab();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallSql() && $this->uninstallTab();
    }

    private function installSql()
    {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'product_caretaker` (
                `id_caretaker` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `avatar` VARCHAR(255) NOT NULL,
                `contact_number` VARCHAR(20) NOT NULL,
                `email` VARCHAR(255) NOT NULL
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        ') && Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'product_caretaker_assignment` (
                `id_product` INT UNSIGNED NOT NULL,
                `id_caretaker` INT UNSIGNED NOT NULL,
                PRIMARY KEY (`id_product`),
                KEY `id_caretaker` (`id_caretaker`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;
        ');
    }

    private function uninstallSql()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'product_caretaker`, `' . _DB_PREFIX_ . 'product_caretaker_assignment`');
    }

    private function installTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminVirtualHelper';
        $tab->module = $this->name;
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminCatalog');
        $tab->name = array();

        foreach (Language::getLanguages(true) as $lang)
            $tab->name[$lang['id_lang']] = $this->l('Virtual Helper');

        return $tab->add();
    }

    private function uninstallTab()
    {
        $id_tab = (int) Tab::getIdFromClassName('AdminVirtualHelper');
        $tab = new Tab($id_tab);

        return $tab->delete();
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $id_product = (int)$params['product']['id_product'];
        $caretaker = Db::getInstance()->getRow('
            SELECT pc.*
            FROM `' . _DB_PREFIX_ . 'product_caretaker_assignment` pca
            JOIN `' . _DB_PREFIX_ . 'product_caretaker` pc ON pca.id_caretaker = pc.id_caretaker
            WHERE pca.id_product = ' . $id_product
        );

        $this->context->smarty->assign(array(
            'caretaker' => $caretaker,
        ));

        return $this->display(__FILE__, 'views/templates/hook/virtualhelper.tpl');
    }
}
