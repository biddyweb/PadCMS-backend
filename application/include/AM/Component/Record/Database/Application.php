<?php
/**
 * @file
 * AM_Component_Record_Database_Application class definition.
 *
 * LICENSE
 *
 * This software is governed by the CeCILL-C  license under French law and
 * abiding by the rules of distribution of free software.  You can  use,
 * modify and/ or redistribute the software under the terms of the CeCILL-C
 * license as circulated by CEA, CNRS and INRIA at the following URL
 * "http://www.cecill.info".
 *
 * As a counterpart to the access to the source code and  rights to copy,
 * modify and redistribute granted by the license, users are provided only
 * with a limited warranty  and the software's author,  the holder of the
 * economic rights,  and the successive licensors  have only  limited
 * liability.
 *
 * In this respect, the user's attention is drawn to the risks associated
 * with loading,  using,  modifying and/or developing or reproducing the
 * software by the user in light of its specific status of free software,
 * that may mean  that it is complicated to manipulate,  and  that  also
 * therefore means  that it is reserved for developers  and  experienced
 * professionals having in-depth computer knowledge. Users are therefore
 * encouraged to load and test the software's suitability as regards their
 * requirements in conditions enabling the security of their systems and/or
 * data to be ensured and,  more generally, to use and operate it in the
 * same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL-C license and that you accept its terms.
 *
 * @author Copyright (c) PadCMS (http://www.padcms.net)
 * @version $DOXY_VERSION
 */

/**
 * Application record component
 * @ingroup AM_Component
 * @todo refactoring
 */
class AM_Component_Record_Database_Application extends AM_Component_Record_Database
{

    /** @var array */
    protected static $_aValidTypes = array(
        AM_Model_Db_ApplicationType::TYPE_GENERIC,
        AM_Model_Db_ApplicationType::TYPE_RUE98WE,
    ); /**< @type array */

    /**
     *
     * @param AM_Controller_Action $oActionController
     * @param string $sName
     * @param int $iId
     * @param int $iClientId
     * @return viod
     */
    public function  __construct(AM_Controller_Action $oActionController, $sName, $iId, $iClientId)
    {
        $aControls   = array();

        $aControls[] = new Volcano_Component_Control_Database_Static($oActionController, 'client', $iClientId);

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'title',
                'Title',
                array(array('require')));

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'preview',
                'Preview',
                array(array('numeric')));

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'description',
                'Description',
                array(array('require')));

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'product_id',
                'Product id',
                array(array('regexp', '/^[a-zA-Z0-9\.]+$/')));

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'type',
                'Type', array(array('require')));

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'nm_twitter_ios',
                'Message');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'nm_fbook_ios',
                'Message');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'nt_email_ios',
                'Title');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'nm_email_ios',
                'Message');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'nm_twitter_android',
                'Message');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'nm_fbook_android',
                'Message');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'nt_email_android',
                'Title');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'nm_email_android',
                'Message');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'push_apple_enabled',
                'Enable');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'push_boxcar_enabled',
                'Enable');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'push_boxcar_provider_key',
                'Key');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'push_boxcar_provider_secret',
                'Secret');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'push_boxcar_name',
                'Name');

        $aControls[] = new Volcano_Component_Control_Database($oActionController,
                'push_boxcar_icon',
                'Icon');

        return parent::__construct($oActionController, $sName, $aControls, $oActionController->oDb,
                'application', 'id', $iId);
    }

    public function show()
    {
        if (!$this->isSubmitted) {
            if (!$this->controls['preview']->getValue())
                $this->controls['preview']->setValue(0);

            if (!$this->controls['title']->getValue()) {
                $maxId = $this->getMaxId();
                $this->controls['title']->setValue('Application #' . ($maxId ? $maxId + 1: 1));
            }
        }

        $aTypes = array();
        foreach (self::$_aValidTypes as $sType) {
            $aTypes[$sType] = AM_Model_Db_ApplicationType::typeToText($sType);
        }

        $aRecord = array(
            'types' => $aTypes,
        );

        if (isset($this->view->{$this->getName()})) {
            $aRecord = array_merge($aRecord, $this->view->{$this->getName()});
        }

        $this->view->{$this->getName()} = $aRecord;

        parent::show();
    }

    /**
     * Returns max application id
     *
     * @return int
     */
    protected function getMaxId()
    {
        $oQuery = $this->db->select()
                ->from('application', 'MAX(id)')
                ->where('client = ?', $this->controls['client']->getValue());

        return $this->db->fetchOne($oQuery);
    }

    protected function _preOperation()
    {
        $sTitle = $this->controls['title']->getValue();
        $this->controls['title']->setValue(AM_Tools::filter_xss($sTitle));

        $sDescription = $this->controls['description']->getValue();
        $this->controls['description']->setValue(AM_Tools::filter_xss($sDescription));

        $sProductId = $this->controls['product_id']->getValue();
        $this->controls['product_id']->setValue(AM_Tools::filter_xss($sProductId));
    }

    /**
     * @return boolean
     */
    public function validate()
    {
        if (!parent::validate()) {
            return false;
        }

        // Check state
        if (array_search($this->controls['type']->getValue(), self::$_aValidTypes) === false) {
            $this->errors[] = $this->actionController->__('Not valid type');

            return false;
        }

        return true;
    }
}
