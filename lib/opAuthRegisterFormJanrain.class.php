<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAuthRegisterFormOpenID represents a form to register by OpenID
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Mamoru Tejima<tejima@tejimaya.com>
 */
class opAuthRegisterFormJanrain extends opAuthRegisterForm
{
  public function doSave()
  {
    $this->getMember()->setIsActive(true);
    $this->getMember()->save();

    return $this->getMember();
  }
}

