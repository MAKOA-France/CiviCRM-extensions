<?php

use CRM_Myextension_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2019
 */

class CRM_Evenement_Form_ParticipantConfirm extends CRM_Core_Form {

  public $etat;
  
   
  public function preProcess(){
    parent::preProcess();

    $this->etat = CRM_Utils_Request::retrieve('etat', 'String', $this, TRUE);
     
    if($this->etat == "ins")
        $message = "Votre inscription a été prise en compte ! ";
    else if($this->etat == "update")
        $message = "Votre mise à jour a été prise en compte ! ";
    
        $this->assign('message', $message);
    
  }
  

  public function postProcess() {
    //$values = $this->exportValues();
    //$options = $this->getColorOptions();
    //CRM_Core_Session::setStatus(E::ts('You picked color "%1"', array(
    //  1 => $options[$values['favorite_color']],
    //)));
    //parent::postProcess();
  }

  

}
