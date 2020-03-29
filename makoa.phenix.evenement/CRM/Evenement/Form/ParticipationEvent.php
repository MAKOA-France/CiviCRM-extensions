<?php

use CRM_Evenement_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Evenement_Form_ParticipationEvent extends CRM_Core_Form { 

  public $_event;
  
  public $_participantListingType;

  public $_eventTitle;

  public $_pager;

  public $_allowConfirmation = true;

  public $iduser;
  
  public $username;

  public $message;

  public $compteur;

  public $etat;

  public $verif;

  public $choice;

  public $comment;

  public function buildQuickForm() {
     

    $this->_event = CRM_Utils_Request::retrieve('id', 'Integer', $this, TRUE);
    
    $this->iduser = CRM_Core_Session::singleton()->getLoggedInContactID(); 
    $this->_participantListingType = 'Name and Role';
    $this->compteur = 0;
    $message = "";
    $this->choice = 18;
    $this->comment = "";
    //On vérifie si l'utilisateur connecté est inscrit à l'événement
    $this->verif = $this->getUserEvent($this->iduser, $this->_event);

    //On verifie si les inscription en ligne ne snt pas dépassées
    $today = Date('Y-m-d H:i:s') ;
    
    $events = \Civi\Api4\Event::get()
            ->setCheckPermissions(FALSE)
            ->setSelect([
            'title',
            'registration_start_date', 
            'registration_end_date', 
            'intro_text',
            'description',
            'is_online_registration'
            ])
        ->addWhere('id', '=', $this->_event)
        ->setLimit(25)
        ->execute();
        $online;
        $startDate ;
        $endDate ;
        $introText = "";
        $description = "";
        $titre = "";

    foreach ($events as $event) {
          // do something
          $titre = $event['title'];
          if(isset($event['registration_start_date']))
            $startDate = $event['registration_start_date'];
          if(isset($event['registration_end_date']))
            $endDate = $event['registration_end_date'];
          if(isset($event['intro_text']))
            $introText = $event['intro_text'];
          if(isset($event['description']))
            $description = $event['description'];
          if(isset($event['is_online_registration']))
            $online = $event['is_online_registration'];

    }
     
    CRM_Utils_System::setTitle($titre);

    //echo $today.'<br>';
    //echo $startDate.'<br>';
    //echo $endDate.'<br>';
    //echo $introText.'<br>';
 
    if($online == 0){
      CRM_Core_Session::setStatus(ts("Désolé Les inscriptions pour cet événement ne sont pas disponibe pour le moment "), ts('Registration'), 'fail');
      $this->assign('introtext', $description);
      
    }else if($online == 1){
      
      if(isset($endDate)){
        if($endDate<$today){
          $newDate = date("d-m-Y", strtotime($endDate.""));
          CRM_Core_Session::setStatus(ts("Désolé Les inscriptions pour cet événement se terminent le ".$newDate), ts('Registration'), 'fail');
          $this->assign('introtext', $description);
        }else{
          $this->assign('introtext', $introText);
          $this->onProcede();
        }
      }else{
          $this->assign('introtext', $introText);
          $this->onProcede();
      }
    } 
    parent::buildQuickForm();
    
  }

  public function onProcede(){
      if(count($this->verif)==0){
        $etat = "ins";
      }else{
         
        $etat = "update";
        $this->choice = $this->verif[0]['participant_status_id'];
        $this->comment =  $this->verif[0]['participant_note'];
      }
  
      if( $etat == "ins"){
        $labelSelect = 'N\'oubliez pas de vous inscrire à l’événement. Par défaut, vous êtes NON PARTICIPANT';
      }else{
        $labelSelect = 'Mettre à jour votre statut de participant';
      }
         
      $this->add(
          'select', // field type
          'role', // field name
          $labelSelect, // field label
          $this->getAllStatusParticipant(), // list of options
          TRUE // is required
      )->setSelected($this->choice);
      //var_dump($this->_elements[1]);
    
      $this->add(
          'textarea', // field type
          'commentaire', // field name
          'Commentaire', // field label
          TRUE // is required
      )->setValue($this->comment);
  
      $this->add(
        'hidden', // field type
        'etat', // field name
        $etat, // field label
        TRUE // is required
      );
  
      $this->add(
        'hidden', // field type
        'compteur', // field name
        ' ', // field label
        FALSE // is required
      );
  
      $this->addButtons(array(
        array(
          'type' => 'submit',
          'name' => E::ts('Submit'),
          'isDefault' => TRUE,
        ),
      ));
  
          $e = $this->getElement('compteur');
          $e->setValue($this->compteur);
   
          //On recupère les paramètres de l'utilisateur
          $this->getUsername($this->iduser);
          // export form elements
  
          if($this->_allowConfirmation == true){
            $this->assign('elementNames', $this->getRenderableElementNames());
          }else{
  
          }
          $this->assign('showbutton', true);
  }

  public function getUserEvent($iduser, $idevent){
    $resultReq = civicrm_api3('Participant', 'get', [
      'sequential' => 1,
      'event_id' => $idevent,
      'contact_id' => $iduser,
    ]);
    return $resultReq['values'];
  }

  public function postProcess() {

    $values = $this->exportValues();
    
    $this->iduser = CRM_Core_Session::singleton()->getLoggedInContactID(); 

    $this->compteur =  $values['compteur'];
    $this->compteur = $this->compteur+1;
    
    $e = $this->getElement('compteur');
    $e->setValue($this->compteur);

    $f = $this->getElement('role');
    $f->setValue($values['role']);

    $c = $this->getElement('commentaire');
    $c->setValue($values['commentaire']);


    $etat =  $values['etat'];

    $options3 = $this->getAllStatusParticipant();

    
    if(($this->compteur==1) && ($etat=="ins")){
      
      
      $message = "<div class='help'>
                    <strong>
      Merci de vérifier les informations ci-dessous. Vous pouvez modifier ou cliquer sur envoyer une deuxième fois pour valider votre inscription !  
                    </strong>
                  </div>";
      $this->assign('message', $message);
      }
    if(($this->compteur == 1) && ($etat=="update")){
              $message = "<div class='help'>
                          <strong>
      Merci de vérifier les informations ci-dessous. Vous pouvez modifier ou cliquer sur envoyer une deuxième fois pour modifier votre inscription !  
                          </strong>
                          </div>";
      $this->assign('message', $message);
        
    }

    if($this->compteur > 1 && $etat=="ins"){
      $results = \Civi\Api4\Participant::create()
      ->setCheckPermissions(FALSE)
      ->addValue('contact_id', $this->iduser )
      ->addValue('event_id', $this->_event)
      ->addValue('status_id', $values['role'])
      ->addValue('role_id', 1)
      ->addValue('register_date', date('m/d/Y'))
      ->addValue('participant_note', $values['commentaire'])
      ->execute();

      CRM_Core_Session::setStatus(ts('Inscription effectuée avec succés'), ts('Saved'), 'success');

      $url = CRM_Utils_System::url('civicrm/participantconfirm', 'etat='.$etat, FALSE, NULL, TRUE, TRUE, FALSE);
      CRM_Utils_System::redirect($url);
      
    }
    if($this->compteur > 1 && $etat=="update"){
 
      $results = \Civi\Api4\Participant::update()
      ->setCheckPermissions(FALSE)
      ->addValue('status_id', 4)
      ->addValue('participant_note', $values['commentaire'])
      ->addWhere('contact_id', '=', $this->iduser)
      ->addWhere('event_id', '=', $this->_event)
      ->setLimit(25)
      ->execute();

      /*$results = \Civi\Api4\Participant::update()
                ->setCheckPermissions(FALSE)
                ->addValue('status_id', $values['role'])
                ->addValue('role_id', 1)
                //->addValue('participant_note', $values['commentaire'])
                ->addWhere('contact_id', '=', $this->iduser)
                ->addWhere('event_id', $this->_event)
                ->setLimit(25)
                ->execute();

      */     
 
      CRM_Core_Session::setStatus(ts('Modification effectuée avec succés'), ts('Saved'), 'success');

      $url = CRM_Utils_System::url('civicrm/participantconfirm', 'etat='.$etat, FALSE, NULL, TRUE, TRUE, FALSE);
     
      CRM_Utils_System::redirect($url);
                	
    }
    //CRM_Core_Session::setStatus(E::ts('Rôle du participant %2', array(
    //  2 => $options3[$values['role']],
    //)));
    /*
    
    foreach ($results as $result) {
      // do something
    }

    */
    //echo __DIR__;
    //exit;
    //if (!file_exists(__DIR__.'/ParticipantConfirm.php')) {
    //  return NULL;
    //} else {
    //  $this->__allowConfirmation = false;
      //$confirm = new CRM_Myextension_Form_ParticipantConfirm();
      //$confirm::preProcess();
      //return $confirm::confirm($this);

      //$url = CRM_Utils_System::url('civicrm/participantconfirm', 'event='.$this->_event.'&participant='.$iduser.'&status='.$values['role'].'&comment='.$values['commentaire'], FALSE, NULL, TRUE, TRUE, FALSE);
      //CRM_Utils_System::redirect($url);
    //}
  
    //$this->preProcess();
    //$form2 = new CRM_Myextension_Form_ParticipantConfirm();
    //$form2->buildQuickForm();
    //parent::postProcess();
    
  }

  public function getColorOptions() {
    $options = array(
      '' => E::ts('- select -'),
      '#f00' => E::ts('Red'),
      '#0f0' => E::ts('Green'),
      '#00f' => E::ts('Blue'),
      '#f0f' => E::ts('Purple'),
    );
    foreach (array('1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e') as $f) {
      $options["#{$f}{$f}{$f}"] = E::ts('Grey (%1)', array(1 => $f));
    }
    return $options;
  }

  public function getAllEvent(){
      $options2 = array('' => E::ts('- select -'),
      // '#f00' => E::ts('Red'),
      //  '#0f0' => E::ts('Green'),
      //  '#00f' => E::ts('Blue'),
      //  '#f0f' => E::ts('Purple'),
      );
      $result = civicrm_api3('Event', 'get', [
                             'sequential' => 1,
      ]);

      $values = $result['values'];
      //var_dump($values);
      foreach ($values as $f) {
            $options2[$f['id']] = E::ts($f['title']);

      }
      //$options2 = $values;

      return $options2;
  }

  public function getAllStatusParticipant(){
      $options2 = array(
        '' => E::ts('- select -'),
      // '#f00' => E::ts('Red'),
      //  '#0f0' => E::ts('Green'),
      //  '#00f' => E::ts('Blue'),
      //  '#f0f' => E::ts('Purple'),
      );

      //$result = civicrm_api3('Participant', 'get', [  'sequential' => 1,  'return' => ["role_id"],  'role_id' => "",]);
      $result = civicrm_api3('ParticipantStatusType', 'get', ['sequential' => 1, 'visibility_id' => "public", ]);

      $values = $result['values'];
      //var_dump($values);
      foreach ($values as $f) {
            $particpantStatus[$f['id']] = E::ts($f['name']);
      }
      
      return $particpantStatus;
  }


  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

  /*public function preProcess(){
  
    $this->_event = CRM_Utils_Request::retrieve('event', 'Integer', $this, TRUE);
    $this->_participantListingType = 'Name and Role';
    // retrieve Event Title and include it in page title
    $this->_eventTitle = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_Event',
    $this->_event,
      'title'
    );
    CRM_Utils_System::setTitle(ts('%1 - Participants', [1 => $this->_eventTitle]));

    // we do not want to display recently viewed contacts since this is potentially a public page
    $this->assign('displayRecent', FALSE);

    $fromClause = "
                      FROM       civicrm_contact
                      INNER JOIN civicrm_participant ON ( civicrm_contact.id = civicrm_participant.contact_id
                      AND civicrm_contact.is_deleted = 0 )
                      INNER JOIN civicrm_event       ON civicrm_participant.event_id = civicrm_event.id
                      LEFT JOIN civicrm_participant_status_type ON civicrm_participant_status_type.id = civicrm_participant.status_id
                      LEFT JOIN  civicrm_email       ON ( civicrm_contact.id = civicrm_email.contact_id AND civicrm_email.is_primary = 1 )
                  ";

//    $whereClause = "
//WHERE    civicrm_event.id = %1
//AND      civicrm_participant.is_test = 0
//AND      civicrm_participant.status_id IN ( 1, 2 )";

$whereClause = "
WHERE    civicrm_event.id = %1
AND      civicrm_participant.is_test = 0";


    $params = [1 => [$this->_event, 'Integer']];
    $this->pager($fromClause, $whereClause, $params);
    $orderBy = $this->orderBy();

    list($offset, $rowCount) = $this->_pager->getOffsetAndRowCount();

    $query = "
SELECT   civicrm_contact.id           as contact_id    ,
         civicrm_contact.display_name as name          ,
         civicrm_contact.sort_name    as sort_name     ,
         civicrm_participant.id       as participant_id,
         civicrm_participant_status_type.label as statuslabel,
         civicrm_email.email          as email
         $fromClause
         $whereClause
ORDER BY $orderBy
LIMIT    $offset, $rowCount";

    $rows = [];
    $object = CRM_Core_DAO::executeQuery($query, $params);
    while ($object->fetch()) {
      $row = [
        'id' => $object->contact_id,
        'participantID' => $object->participant_id,
        'name' => $object->name,
        'participantStatus' => $object->statuslabel,
        'email' => $object->email,
      ];
      $rows[] = $row;
    }
    
    $this->assign_by_ref('rows', $rows);
    //parent::preProcess();

  }
  */

  /**
   * @param $fromClause
   * @param $whereClause
   * @param array $whereParams
   */
  public function pager($fromClause, $whereClause, $whereParams) {

    $params = [];

    $params['status'] = ts('Group') . ' %%StatusMessage%%';
    $params['csvString'] = NULL;
    $params['buttonTop'] = 'PagerTopButton';
    $params['buttonBottom'] = 'PagerBottomButton';
    $params['rowCount'] = $this->get(CRM_Utils_Pager::PAGE_ROWCOUNT);
    if (!$params['rowCount']) {
      $params['rowCount'] = CRM_Utils_Pager::ROWCOUNT;
    }

    $query = "
    SELECT count( civicrm_contact.id )
       $fromClause
       $whereClause
    ";

    $params['total'] = CRM_Core_DAO::singleValueQuery($query, $whereParams);
    $this->_pager = new CRM_Utils_Pager($params);
    $this->assign_by_ref('pager', $this->_pager);
  }

  /**
   * @return string
   */
  /*public function orderBy() {
    static $headers = NULL;
    if (!$headers) {
      $headers = [];
      $headers[1] = [
        'name' => ts('Name'),
        'sort' => 'civicrm_contact.sort_name',
        'direction' => CRM_Utils_Sort::ASCENDING,
      ];
      $headers[2] = [
        'name' => ts('Status'),
        'sort' => 'civicrm_participant.status_id',
        'direction' => CRM_Utils_Sort::DONTCARE,
      ];

      
      if ($this->_participantListingType == 'Name and Email') {
        $headers[2] = [
          'name' => ts('Email'),
          'sort' => 'civicrm_email.email',
          'direction' => CRM_Utils_Sort::DONTCARE,
        ];
      }
    }
    $sortID = NULL;
    if ($this->get(CRM_Utils_Sort::SORT_ID)) {
      $sortID = CRM_Utils_Sort::sortIDValue($this->get(CRM_Utils_Sort::SORT_ID),
        $this->get(CRM_Utils_Sort::SORT_DIRECTION)
      );
    }
    $sort = new CRM_Utils_Sort($headers, $sortID);
    $this->assign_by_ref('headers', $headers);
    $this->assign_by_ref('sort', $sort);
    $this->set(CRM_Utils_Sort::SORT_ID,
      $sort->getCurrentSortID()
    );
    $this->set(CRM_Utils_Sort::SORT_DIRECTION,
      $sort->getCurrentSortDirection()
    );

    return $sort->orderBy();
  }

  */

  public function getUsername($iduser){

    $contacts = \Civi\Api4\Contact::get()
              ->addWhere('id', '=', $iduser)
              ->setCheckPermissions(FALSE)
              ->setLimit(25)
              ->execute();
              
    // foreach ($contacts as $contact) { }

    /*$result = civicrm_api3('Contact', 'get', [
      'sequential' => 1,
      'return' => ["id", "display_name"],
      'id' => $iduser,
    ]);*/
 
    $emails = \Civi\Api4\Email::get()
              ->addWhere('contact_id', '=', $iduser)
              ->setCheckPermissions(FALSE)
              ->setLimit(25)
              ->execute();
    //foreach ($emails as $email) {
    // do something
    //} 

    $firstname = "";
    $lastname = "";
    $email = "";
    
    //echo $contacts[0]['last_name'];

      if(isset($contacts[0]['first_name']))
        $firstname = ' <div class="label"><span>Prénom : </span></div><br><div class="content"><span>'.$contacts[0]['first_name'].'</span></div>';

      if(isset($contacts[0]['last_name']))
        $lastname = ' <div class="label"><span>Nom de famille : </span></div><br><div class="content"><span>'.$contacts[0]['last_name'].'</span></div>';
    
      if(isset($emails[0]['email']))
        $email = ' <div class="label"><span>Adresse électronique :</span></div><br><div class="label"><span>'.$emails[0]['email'].'</span></div>';
    //return $username;

    $this->assign('first_name', $firstname);

    $this->assign('last_name', $lastname);

    $this->assign('email', $email);

  }
  

}
