<?php

require_once 'groupe.civix.php';
use CRM_Groupe_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/ 
 */
function groupe_civicrm_config(&$config) {
  _groupe_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function groupe_civicrm_xmlMenu(&$files) {
  _groupe_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function groupe_civicrm_install() {
  _groupe_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function groupe_civicrm_postInstall() {
  _groupe_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function groupe_civicrm_uninstall() {
  _groupe_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function groupe_civicrm_enable() {
  _groupe_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function groupe_civicrm_disable() {
  _groupe_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function groupe_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _groupe_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function groupe_civicrm_managed(&$entities) {
  _groupe_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function groupe_civicrm_caseTypes(&$caseTypes) {
  _groupe_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function groupe_civicrm_angularModules(&$angularModules) {
  _groupe_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function groupe_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _groupe_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function groupe_civicrm_entityTypes(&$entityTypes) {
  _groupe_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function groupe_civicrm_themes(&$themes) {
  _groupe_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *
function groupe_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 *
function groupe_civicrm_navigationMenu(&$menu) {
  _groupe_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _groupe_civix_navigationMenu($menu);
} // */


function groupe_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions)
{

   // skip permission checks for contact/create calls
  // (but keep the ones for email, address, etc./create calls)
  // note: unsetting the below would require the default ‘access CiviCRM’ permission
  $permissions['contact']['create'] = array();

  // enforce ‘view all contacts’ check for contact/get, but do not test ‘access CiviCRM’
  $permissions['contact']['get'] = array('view all contacts');

  // add a new permission requirement for your own custom API call
  // (if all you want to enforce is ‘access CiviCRM’ you can skip the below altogether)
  $permissions['foo']['get'] = array('access CiviCRM', 'get all foos');

  // allow everyone to get info for a given event; also – another way to skip permissions
  if ($entity == 'contact' and $action == 'get' and $params['title'] == 'CiviCon 2038') {
      $params['check_permissions'] = false;
  }
 
  // skip permission checks for contact/create calls
  // (but keep the ones for email, address, etc./create calls)
  // note: unsetting the below would require the default ‘access CiviCRM’ permission
  $permissions['group']['create'] = array();
  
  $permissions['group']['update'] = array();

  // enforce ‘view all contacts’ check for contact/get, but do not test ‘access CiviCRM’
  $permissions['group']['get'] = array('view all groups');

  // add a new permission requirement for your own custom API call
  // (if all you want to enforce is ‘access CiviCRM’ you can skip the below altogether)
  $permissions['foo']['get'] = array('access CiviCRM', 'get all foos');

  // allow everyone to get info for a given event; also – another way to skip permissions
  if ($entity == 'group' and $action == 'get' and $params['title'] == 'CiviCon 2038') {
      $params['check_permissions'] = false;
  }
  
  // skip permission checks for contact/create calls
  // (but keep the ones for email, address, etc./create calls)
  // note: unsetting the below would require the default ‘access CiviCRM’ permission
  $permissions['groupContacts']['create'] = array();
  
  $permissions['groupContacts']['update'] = array();

  // enforce ‘view all contacts’ check for contact/get, but do not test ‘access CiviCRM’
  $permissions['groupContacts']['get'] = array('view all groupContacts');

  // add a new permission requirement for your own custom API call
  // (if all you want to enforce is ‘access CiviCRM’ you can skip the below altogether)
  $permissions['foo']['get'] = array('access CiviCRM', 'get all foos');

  // allow everyone to get info for a given event; also – another way to skip permissions
  if ($entity == 'groupContacts' and $action == 'get' and $params['title'] == 'CiviCon 2038') {
      $params['check_permissions'] = false;
  }

}
