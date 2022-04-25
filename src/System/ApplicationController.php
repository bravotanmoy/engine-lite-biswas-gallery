<?php

namespace Elab\Lite\System;

use Elab\Lite\Engine;

/**
 * Bazinis kontrolleris, iš kurio turi paveldėti visi kontroleriai, turintys galimybę veikti
 * svetainėje - atvaizduoti duomenis - backend, frontend, etc controlleriai
 *
 * @package core
 */
abstract class ApplicationController extends BaseController
{
    protected $_cache = [];

    /**
     * @var unknown_type kontrollerio esybė.
     */
    private $entity = null;

    /**
     * @param type|string $name
     * @return \Elab\Lite\System\EntityController
     */
    public function get_e($name = false, $config = array())
    {
        if ($name) {
            return $this->load_entity_controller($name, $config);
        } else {
            return $this->get_entity();
        }
    }

    /**
     * @param $name
     * @return \Elab\Lite\System\EntityController
     */
    public function load_entity_controller($name, $config = array())
    {
        if (!empty($this->_cache['entity_controllers'][$name])) {
            $obj = $this->_cache['entity_controllers'][$name];
        } else {
            $obj = Engine::load_entity_controller($name, $this, $config);
            $this->_cache['entity_controllers'][$name] = $obj;
        }
        return $obj;
    }

    /**
     * @return \Elab\Lite\System\EntityController
     */
    public function get_entity()
    {
        return $this->entity;
    }

    protected function set_entity($entity)
    {
        $this->entity = $entity;
    }

    public function assign($tpl_var, $value = null, $nocache = false)
    {
        Repository::$smarty->assign($tpl_var, $value, $nocache);
    }

    /**
     * Čia igyvendiname strategy design pattern'ą tam, kad atvaizduotume moduli
     *
     */
    public function run()
    {
        $this->init();
        $this->prepare();
        $this->logic();
        $this->before_render();
        $this->render();
        $this->clean_up();
    }

    /**
     * Čia realizuojama kotrolerio inicializavimo logika
     *
     */
    protected function init()
    {
    }

    /**
     * Čia realizuojamas pasiruošimas darbui
     *
     */
    protected function prepare()
    {
    }

    /**
     * Kontrolerio logika
     *
     */
    protected function logic()
    {
    }

    /**
     * Čia realizuojama pasiruošimui renderinti kontrollerio logiką
     *
     */
    protected function before_render()
    {
    }

    /**
     * Čia realizuojama renderinimo logika
     *
     */
    protected function render()
    {
    }

    /**
     * Čia realizuojama apsivalymo po renderinimo logika
     *
     */
    protected function clean_up()
    {
    }

    public function add_extra_messages($action)
    {
        // Jeigu buvo nesekmingai bandyta ikelti nuotraukas, prideti laukus ir pan., tada parodom atitinkama zinute ir gryztam i redagavima.
        if (!empty($this->get_entity()->available_containers)) {
            foreach ($this->get_entity()->available_containers as $container_name) {
                if (!empty($this->get_entity()->config[$container_name]) && !empty($this->get_entity()->entity_errors[$container_name])) {
                    // pasalinima pranesima apie sekminga issaugojima.
                    $this->add_message('message', false);
                    // pridedam nauja klaidos pranesima
                    $this->add_message('notice_message', $this->get_entity()->entity_errors[$container_name] . "<br/>" . t("Kiti pakeitimai (jei tokių buvo) išsaugoti sėkmingai."));
                    if ($action != 'add') {
                        $_POST['return_url'] = FULL_URL_TRUNC;
                    }
                }
            }
        }
    }

    /**
     * Issaugo sesijoje pranesima, kuris veliau bus isvestas i ekrana.
     *
     * @param string $type
     * @param string $msg
     */
    public function add_message($type, $msg, $append = false)
    {
        Engine::add_message($this->get_name(), $type, $msg, $append);
    }

    /**
     * Realizuojama bazinė filtro pridėjimo konkrečiai esybei logika
     */
    protected function add_filter($entity)
    {

        // jeigu filtro užkrovimui nepakanka duomenų, pasiimtų iš konfigūracijos, juos generuojame čia:
        $entity->init_filter();

        // apdoroja forma
        $entity->read_filter();

        // suformuoja condition'us
        $entity->process_filter();

        // paruosia optionus
        $entity->prepare_filter();
    }

    /**
     * Pagal paduoto modelio konfigūraciją, sugeneruoja „įjungtų“ laukų sąrašą
     * @param $model - Model klasės objektas
     * @return array('field_id'=>true, ..)
     */
    protected function get_fields_config($view_entity)
    {
        if (empty($view_entity)) {
            return false;
        }

        $result = array();
        foreach ((!empty($view_entity->fields)) ? $view_entity->fields : array() as $key => $field) {
            if (isset($field['validation']) && is_array($field['validation']) && in_array('not_empty', array_values($field['validation']))) {
                $result[$key] = array('required' => true);
            } elseif (isset($field['validation']['not_empty'])) {
                $result[$key] = array('required' => true);
            } else {
                $result[$key] = true;
            }
        }
        return $result;
    }
}
