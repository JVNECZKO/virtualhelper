<?php

class AdminProductsController extends AdminProductsControllerCore
{
    public function __construct()
    {
        parent::__construct();

        // Dodajemy nowe pole do formularza edycji produktu
        $this->fields_form[1]['form']['input'][] = array(
            'type' => 'select',
            'label' => $this->l('Product Caretaker'),
            'name' => 'id_caretaker',
            'options' => array(
                'query' => $this->getCaretakers(),
                'id' => 'id_caretaker',
                'name' => 'name'
            ),
            'required' => false
        );
    }

    protected function getCaretakers()
    {
        return Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'product_caretaker`');
    }

    protected function processAdd()
    {
        $result = parent::processAdd();

        if ($result) {
            $this->updateCaretakerAssignment(Tools::getValue('id_product'), Tools::getValue('id_caretaker'));
        }

        return $result;
    }

    protected function processUpdate()
    {
        $result = parent::processUpdate();

        if ($result) {
            $this->updateCaretakerAssignment(Tools::getValue('id_product'), Tools::getValue('id_caretaker'));
        }

        return $result;
    }

    protected function updateCaretakerAssignment($id_product, $id_caretaker)
    {
        Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'product_caretaker_assignment` WHERE `id_product` = ' . (int)$id_product);

        if ((int)$id_caretaker > 0) {
            Db::getInstance()->insert('product_caretaker_assignment', array(
                'id_product' => (int)$id_product,
                'id_caretaker' => (int)$id_caretaker
            ));
        }
    }

    public function renderForm()
    {
        // Renderowanie formularza produktu z nowym polem
        $this->addJqueryPlugin(array('autocomplete', 'fancybox'));

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $this->initForm();

        $this->fields_value['id_caretaker'] = Db::getInstance()->getValue('
            SELECT `id_caretaker`
            FROM `' . _DB_PREFIX_ . 'product_caretaker_assignment`
            WHERE `id_product` = ' . (int)$obj->id
        );

        return parent::renderForm();
    }
}
