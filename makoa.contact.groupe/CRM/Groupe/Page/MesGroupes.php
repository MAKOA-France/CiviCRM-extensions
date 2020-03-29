<?php
use CRM_Groupe_ExtensionUtil as E;

class CRM_Groupe_Page_MesGroupes extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Mes Groupes'));

    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));

    //Aficher la liste des groupes de l'utilisateurs
    //On recupère le contact de l'utilisateur encours
    $groupeContactList = Array();
    $idContact = CRM_Core_Session::singleton()->getLoggedInContactID(); 
    
    $groupContacts = \Civi\Api4\GroupContact::get()
    ->setSelect([
      'group.id', 
      'group.title'
    ])
    ->setCheckPermissions(false)
    ->addWhere('contact_id', '=',  $idContact )
    ->setLimit(25)
    ->execute();
  
   foreach ($groupContacts as $groupContact) {
    // do something
    $titregroup = $groupContact['group']['title'];
    $idgroupe = $groupContact['group']['id'];
    $legroup = array();
    $legroup = array('id'=>$idgroupe, 'titre'=>$titregroup);
    array_push($groupeContactList,$legroup);
  }

  $this->assign('listeOfGroupe', $groupeContactList);
  ////////////////////////
  
  parent::run();
  }

}

