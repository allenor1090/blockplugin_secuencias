<?php
// moodleform is defined in formslib.php
global $CFG;
require_once ('C:/xampp1/htdocs/moodle/lib/formslib.php');


class simplehtml_form extends moodleform
{
    
    // Add elements to form.
    public function definition()
    {
        // A reference to the form is stored in $this->form.
        // A common convention is to store it in a variable, such as `$mform`.
        $mform = $this->_form; // Don't forget the underscore!

        //ID CURSO
        $course = $this->_customdata['course'];

        // Course ID.
        $mform->addElement('hidden', 'courseid', 4);
        
        $mform->setType('courseid', PARAM_INT);

        // Add elements to your form.
        $mform->addElement('text', 'campoprueba', get_string('campoprueba'));

        // Set type of element.
        $mform->setType('text', PARAM_TEXT);

        // Default value.
        $mform->setDefault('PRUEBA', '');

        $this->add_action_buttons();
    }

    // Custom validation should be added here.
    function validation($data, $files)
    {
        return array();
    }

}

?>