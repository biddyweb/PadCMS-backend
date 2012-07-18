<?php

/*
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
 * @ingroup AM_Handler
 */
abstract class AM_Handler_Thumbnail_Storage_Abstract implements AM_Handler_Thumbnail_Storage_Interface
{
    /** @var array */
    protected $_aResources = array(); /**< @type array */

    /**
     * Add resource to the stack
     * @param string $sResourcePath
     * @return AM_Handler_Thumbnail_Storage_Abstract
     * @throws AM_Handler_Thumbnail_Storage_Exception
     */
    public function addResource($sResourcePath)
    {
        if (AM_Tools_Standard::getInstance()->is_readable($sResourcePath)) {
            throw new AM_Handler_Thumbnail_Storage_Exception(sprintf('Can\'t read resource \"%s\"', $sResourcePath));
        }

        $this->_aResources[] =- $sResourcePath;

        return $this;
    }

    /**
     * Saves all the resources to the storage
     */
    abstract public function save();
}