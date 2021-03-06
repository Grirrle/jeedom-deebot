<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class deebot extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {

      }
     */


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */

    public static function dependancy_info() {
        $return = array();
        $return['log'] = 'deebot_dep';
        $request = realpath(dirname(__FILE__) . '/../../resources/node_modules/request');
        $return['progress_file'] = '/tmp/deebot_dep';
        if (is_dir($request)) {
          $return['state'] = 'ok';
        } else {
          $return['state'] = 'nok';
        }
        return $return;
    }

    public static function dependancy_install() {
        log::add('deebot','info','Installation des dépéndances nodejs');
        $resource_path = realpath(dirname(__FILE__) . '/../../resources');
        log::add('deebot', 'debug', '/bin/bash ' . $resource_path . '/nodejs.sh ' . $resource_path . ' deebot > ' . log::getPathToLog('deebot_dep') . ' 2>&1 &');
        passthru('/bin/bash ' . $resource_path . '/nodejs.sh ' . $resource_path . ' deebot > ' . log::getPathToLog('deebot_dep') . ' 2>&1 &');
    }

    /*     * *********************Méthodes d'instance************************* */
    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
      $deebotCmd = deebotCmd::byEqLogicIdAndLogicalId($this->getId(),'data');
      if (!is_object($deebotCmd)) {
        // Création de la commande
        $cmd = new deebotCmd();
        // Nom affiché
        $cmd->setName('Données');
        // Identifiant de la commande
        $cmd->setLogicalId('data');
        // Identifiant de l'équipement
        $cmd->setEqLogic_id($this->getId());
        // Type de la commande
        $cmd->setType('info');
        // Sous-type de la commande
        $cmd->setSubType('string');
        // Visibilité de la commande
        $cmd->setIsVisible(1);
        // Sauvegarde de la commande
        $cmd->save();
      }
      $deebotCmd = deebotCmd::byEqLogicIdAndLogicalId($this->getId(),'refresh');
      if (!is_object($deebotCmd)) {
        // Création de la commande
        $cmd = new deebotCmd();
        // Nom affiché
        $cmd->setName('Rafraichir');
        // Identifiant de la commande
        $cmd->setLogicalId('refresh');
        // Identifiant de l'équipement
        $cmd->setEqLogic_id($this->getId());
        // Type de la commande
        $cmd->setType('action');
        // Sous-type de la commande
        $cmd->setSubType('other');
        // Visibilité de la commande
        $cmd->setIsVisible(1);
        // Sauvegarde de la commande
        $cmd->save();
      }
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class deebotCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
      log::add('deebot','debug','DEEBOT EXCECUTE CMD:'.$this->getLogicalId());    
      if ($this->getLogicalId() == 'refresh') {
        log::add('deebot','debug','Deebot refresh');
        // On récupère l'équipement à partir de l'identifiant fournit par la commande
        $deebotObj = deebot::byId($this->getEqlogic_id());
        // On récupère la commande 'data' appartenant à l'équipement
        $dataCmd = $deebotObj->getCmd('info', 'data');
        // On lui ajoute un évènement avec pour information 'Données de test'
        $dataCmd->event(date('H:i'));
        // On sauvegarde cet évènement
        $dataCmd->save();      
      }
    }

    /*     * **********************Getteur Setteur*************************** */
}


