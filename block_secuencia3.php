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
 * Block secuencia3 is defined here.
 *
 * @package     block_secuencia3
 * @copyright   2024 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_secuencia3 extends block_base
{

    /**
     * Initializes class member variables.
     */
    public function init()
    {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_secuencia3');
    }

    /**
     * Returns an array of formats for which this block can be used.
     *
     * @return array
     */
    public function applicable_formats()
    {
        return array(
            'course-view-weeks' => true,
            'course-view-topics' => true
        );
    }

    /**
     * Generates the content of the block and returns it.
     *
     * If the content has already been generated then the previously generated content is returned.
     *
     * @return stdClass
     */
    public function get_content()
    {
        global $CFG, $USER;

        // The config should be loaded by now.
        // If its empty then we will use the global config for the section links block.
        if (isset($this->config)) {
            $config = $this->config;
        } else {
            $config = get_config('block_section_links');
        }

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = '';

        if (empty($this->instance)) {
            return $this->content;
        }

        $course = $this->page->course;
        $courseformat = course_get_format($course);
        $numsections = $courseformat->get_last_section_number();
        $context = context_course::instance($course->id);

        $roles = get_user_roles($context, $USER->id, true);
        $role = key($roles);
        $rolename = $roles[$role]->shortname;

        // Course format options 'numsections' is required to display the block.
        if (empty($numsections)) {
            return $this->content;
        }

        // Prepare the increment value.
        if (!empty($config->numsections1) and ($numsections > $config->numsections1)) {
            $inc = $config->incby1;
        } else if ($numsections > 22) {
            $inc = 2;
        } else {
            $inc = 1;
        }
        if (!empty($config->numsections2) and ($numsections > $config->numsections2)) {
            $inc = $config->incby2;
        } else {
            if ($numsections > 40) {
                $inc = 5;
            }
        }

        // Whether or not section name should be displayed.
        //$showsectionname = !empty($config->showsectionname) ? true : false;
        $showsectionname = true;

        $data = array();

        array_push($data, $course->id);
        array_push($data, $rolename);
        // Prepare an array of sections to create links for.
        $sections = array();
        $canviewhidden = has_capability('moodle/course:update', $context);
        $coursesections = $courseformat->get_sections();
        $coursesectionscount = count($coursesections);
        $sectiontojumpto = false;
        for ($i = $inc; $i <= $coursesectionscount; $i += $inc) {
            if ($i > $numsections || !isset($coursesections[$i])) {
                continue;
            }
            $section = $coursesections[$i];
            if ($section->section && ($section->visible || $canviewhidden)) {
                $sections[$i] = (object) array(
                    'section' => $section->section,
                    'visible' => $section->visible,
                    'highlight' => false
                );
                if ($courseformat->is_section_current($section)) {
                    $sections[$i]->highlight = true;
                    $sectiontojumpto = $section->section;
                }
                if ($showsectionname) {
                    $sections[$i]->name = $courseformat->get_section_name($i);

                    array_push($data, $sections[$i]->name);

                    //echo "<br><br><br>NOMBRE: " . $data[$i];
                }
            }
        }

        if (!empty($sections)) {
            // Render the sections.
            $renderer = $this->page->get_renderer('block_section_links');
            /*$this->content->text = $renderer->render_section_links(
                $this->page->course,
                $sections,
                $sectiontojumpto,
                $showsectionname
            );*/


            //ESTE RENDER AGREGA EL ENLACE QUE ABRE EL MODAL
            //CON EL GESTOR DE ARCHIVOS
            /* $renderer2 = $this->page->get_renderer('block_secuencia3');
             $this->content->text = $renderer2->formulario();
             if (has_capability('moodle/user:manageownfiles', $this->context)) {
                 $this->content->footer = html_writer::link(
                     new moodle_url('/user/files.php'),
                     get_string('privatefilesmanage') . '...',
                     ['data-action' => 'manageprivatefiles']
                 );
                 $this->page->requires->js_call_amd(
                     'core_user/private_files',
                     'initModal',
                     ['[data-action=manageprivatefiles]', \core_user\form\private_files::class]
                 );
             }*/



        }

        // Suponiendo que $data contiene los datos que deseas pasar al iframe
        
        // Codificar el arreglo como JSON
        $json_data = json_encode($data);

        // Escapar caracteres especiales para usarlo en una URL
        $json_data_url = urlencode($json_data);

        // Construye la URL con los parámetros de consulta
        $url = new moodle_url('http://localhost/secuencias/index.php', array('parametro' => $json_data_url));

        $iframe_url = $url->out();

        $this->content->text .= html_writer::start_tag(
            'iframe',
            array(
                'src' => $iframe_url,
                'width' => '100%',
                'height' => '400',
                'frameborder' => '0',
                'allowfullscreen' => true,
            )
        );
        $this->content->text .= html_writer::end_tag('iframe');

        return $this->content;
    }



    /**
     * Returns true if this block has instance config.
     *
     * @return bool
     **/
    public function instance_allow_config()
    {
        return true;
    }



    /**
     * Returns true if this block has global config.
     *
     * @return bool
     */
    public function has_config()
    {
        return true;
    }

    /**
     * Return the plugin config settings for external functions.
     *
     * @return stdClass the configs for both the block instance and plugin
     * @since Moodle 3.8
     */
    public function get_config_for_external()
    {
        // Return all settings for all users since it is safe (no private keys, etc..).
        $instanceconfigs = !empty($this->config) ? $this->config : new stdClass();
        $pluginconfigs = get_config('block_section_links');

        return (object) [
            'instance' => $instanceconfigs,
            'plugin' => $pluginconfigs,
        ];
    }
}
