<?php
use CRM_Groupe_ExtensionUtil as E;

class CRM_Groupe_Page_DetailGroupe extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('DetailGroupe'));


    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));

    CRM_Utils_System::setTitle(E::ts('Détails du groupe'));

    //On recupère le détails du groupe
    $idgroupe = CRM_Utils_Request::retrieve('id', 'Positive', $this, TRUE);
    $groups = \Civi\Api4\Group::get()
          ->setSelect([
          'title', 
          'description'
        ])
          ->setCheckPermissions(false)
          ->addWhere('id', '=', $idgroupe)
          ->setLimit(25)
          ->execute();
        $titregroupe = "";
        $detailgroupe = "";
        foreach ($groups as $group) {
            // do something
            $titregroupe = $group["title"];
            $detailgroupe = $group["description"];
        }

        $this->assign('titregroupe',  $titregroupe);
        $this->assign('detailgroupe',  $detailgroupe);


        //on recupère les membres du groupe
        $groupeContactList = array();
        $groupContacts = \Civi\Api4\GroupContact::get()
            ->setSelect([
            'contact.first_name', 
            'contact.last_name'
          ])
            ->setCheckPermissions(false)
            ->addWhere('id', '=', 1)
            ->setLimit(25)
            ->execute();
        foreach ($groupContacts as $groupContact) {
            // do something
            array_push( $groupeContactList, $groupContact['contact']['first_name']." ".$groupContact['contact']['last_name'] );

        }
        $this->assign('lesmembres',  $groupeContactList);

    parent::run();
  }
}
