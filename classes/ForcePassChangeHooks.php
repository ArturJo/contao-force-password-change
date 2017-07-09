<?php

/**
 * @package     ForcePassChange
 * @author      Basti Buck (http://www.bastibuck.de)
 * @license     LGPL
 * @copyright   Basti Buck, 2017
 */

namespace Bastibuck;

class ForcePassChangeHooks extends \Frontend
{
  /**
   * redirect all members without changed passwort to password change page
   */
  public function memberChangePass($objPage,$objLayout,$objPageRegular)
  {
    // load logged user
    $this->import('FrontendUser', 'Member');

    if (TL_MODE == "FE" && FE_USER_LOGGED_IN && $this->Member->passChanged!=1)
    {
      // check if user is in a group that forces pass change
      $arrGroupIDs = $this->Member->groups;
      foreach ($arrGroupIDs as $id)
      {
        // get member group
        $objMemberGroup = \MemberGroupModel::findById($id);

        if ($objMemberGroup->force_pass_change)
        {
          $jumpTo = $objMemberGroup->pass_change_jumpTo;
        }
      }

      if($objPage->id != $jumpTo)
      {
        // get objPage of jumpTo page
        $objSite = $this->Database
          ->prepare('SELECT id,alias FROM tl_page WHERE id=?')
          ->limit(1)
          ->execute($jumpTo);

        // redirect member to pass change page
        $this->redirect($this->generateFrontendUrl($objSite->fetchAssoc()));
      }
    }

  }
}
