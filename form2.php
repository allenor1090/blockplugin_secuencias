<?php
//AQUÍ SE AGREGA UN FORMULARIO AL PLUGIN
            //LA IDEA SERÍA QUE SE ABRIERA COMO UN MODAL
            //SE HIZO LA PRUEBA DE GUARDAR EL DATO QUE SE INGRESA PERO NO PUDE
            require_once ("formulario.php");
            // Instantiate the myform form from within the plugin.
            $mform = new simplehtml_form();

            // Form processing and displaying is done here.
            if ($mform->is_cancelled()) {
                // If there is a cancel element on the form, and it was pressed,
                // then the `is_cancelled()` function will return true.
                // You can handle the cancel operation here.
            } else if ($fromform = $mform->get_data()) {
                // When the form is submitted, and the data is successfully validated,
                // the `get_data()` function will return the data posted in the form.
            } else {
                // This branch is executed if the form is submitted but the data doesn't
                // validate and the form should be redisplayed or on the first display of the form.

                // Set anydefault data (if any).
                //$mform->set_data($to);

                // Display the form.
                //$mform->display();
                $this->content->text = $mform->render();
            }
?>