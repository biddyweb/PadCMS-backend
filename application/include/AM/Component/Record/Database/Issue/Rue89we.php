<?php
/**
 * @file
 * AM_Component_Record_Database_Issue_Rue89we class definition.
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
 * Issue record component
 * @ingroup AM_Component
 */
class AM_Component_Record_Database_Issue_Rue89we extends AM_Component_Record_Database_Issue_Generic
{

    public function __construct(AM_Controller_Action $oActionController, $sName, $iIssueId, $iApplicationId)
    {
        parent::__construct($oActionController, $sName, $iIssueId, $iApplicationId);

        $this->addControl(new Volcano_Component_Control_Database($oActionController,
                'subtitle', 'Subtitle', array(array('require'), array('maximum length', 50)), 'subtitle'));

        $this->addControl(new Volcano_Component_Control_Database($oActionController,
                'author', 'Author', array(array('require'), array('maximum length', 100)), 'author'));

        $this->addControl(new Volcano_Component_Control_Database($oActionController,
                'words', 'Words', array(array('require'), array('integer'), array('minimum value', 1)), 'words'));

        $this->addControl(new Volcano_Component_Control_Database($oActionController,
                'excerpt', 'Excerpt', array(array('maximum length', 180), array('require')), 'excerpt'));

        $this->addControl(new Volcano_Component_Control_Database($oActionController,
                'welcome', 'Welcome message', array(array('maximum length', 350)), 'welcome'));

        $aUser = $oActionController->getUser();

        $validationsRules = array();

        if (!$iIssueId) {
            $validationsRules[] = array('require');
            $this->addControl(new Volcano_Component_Control_Database_Static($oActionController, 'user', $aUser['id']));
        }
        $oIssue = AM_Model_Db_Table_Abstract::factory('issue')->findOneBy('id', $iIssueId);
        $sImageValue = !empty($oIssue->image) ? $oIssue->image : null;
        $this->addControl(new AM_Component_Control_Database_File($oActionController,
                'image', 'Image',  $validationsRules, 'image',
                AM_Tools::getContentPath(AM_Model_Db_Issue::PRESET_ISSUE_IMAGE)
                . DIRECTORY_SEPARATOR . '[ID]', TRUE, $sImageValue));

        $this->postInitialize();
    }
}
