<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.
 
/**
 * Block secuencia_didactica_form is defined here.
 *
 * @package     block_secuencia_didactica_form
 * @copyright   2024 Alberto Lleras <albertolleras0206@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
// moodleform is defined in formslib.php
global $CFG, $DB;
require_once ($CFG->dirroot . '/lib/formslib.php');
 
class simplehtml_form extends moodleform{
   
    public function definition()
    {
        // A reference to the form is stored in $this->form.
        // A common convention is to store it in a variable, such as `$mform`.
        $mform = $this->_form; // Don't forget the underscore!
 
        //ID CURSO
        $course = $this->_customdata['course'];
 
        // Course ID.
        //$mform->addElement('hidden', 'courseid', 4);
        //$mform->setType('courseid', PARAM_INT);
       
        $mform->addElement('text', 'titulo', 'Titulo');// Add elements to your form.
        $mform->setType('titulo', PARAM_TEXT);// Set type of element.
        $mform->setDefault('titulo', '');// Default value.
 
        $mform->addElement('text', 'descripcion', 'Descripción');// Add elements to your form.
        $mform->setType('descripcion', PARAM_TEXT);// Set type of element.
        $mform->setDefault('descripcion', '');// Default value.
 
        $mform->addElement('text', 'objetivos', 'Objetivos');// Add elements to your form.
        $mform->setType('objetivos', PARAM_TEXT);// Set type of element.
        $mform->setDefault('objetivos', '');// Default value.
 
        $mform->addElement('text', 'recursos', 'Recursos');// Add elements to your form.
        $mform->setType('recursos', PARAM_TEXT);// Set type of element.
        $mform->setDefault('recursos', '');// Default value.
 
        $this->add_action_buttons();
    }
 
    // Custom validation should be added here.
    function validation($data, $files)
    {
        return array();
    }
 
    function definition_after_data() {
        // Processes data before sending to DB
        if ($this->is_submitted()) {
            $data = $this->get_data();
            if ($data) {
                // saves data in database
                $record = new stdClass();
                $record->courseid = 4;
                $record->titulo = $data->titulo;
                $record->descripcion = $data->descripcion;
                $record->objetivos = $data->objetivos;
                $record->recursos = $data->recursos;
 
                $DB->insert_record('secuencia_didactica/db/install.xml', $record);//asigns where to insert the record.
               
            }
        }
    }
 
}