<?php
// This file is part of the inactive courses block
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * blocks_courses_inactive
 *
 * @package    block
 * @subpackage courses_inactive
 * @author     Barry Oosthuizen <barry@elearningstudio.co.uk>
 * @copyright  2013 onwards Barry Oosthuizen (http://elearningstudio.co.uk)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/blocks/courses_inactive/lib.php');

class block_courses_inactive extends block_list {
    public function init() {
        $this->title = get_string('pluginname', 'block_courses_inactive');
    }

    public function has_config() {
        return true;
    }

    public function get_content() {
        global $CFG, $USER, $DB, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $icon  = '<img src="' . $OUTPUT->pix_url('i/course') . '" class="icon" alt="" />&nbsp;';

        if (isloggedin() and !isguestuser()) {
            if ($courses = get_my_inactive_courses(null, 'visible DESC, fullname ASC')) {

                foreach ($courses as $course) {
                    $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);

                    $this->content->items[]="<a title=\"" . format_string($course->shortname,
                            true, array('context' => $coursecontext)) . "\" " .
                            "href=\"$CFG->wwwroot/course/view.php?id=$course->id\">" . $icon .
                            format_string($course->fullname). "</a>";
                }
            }
            if ($this->content->items) {
                return $this->content;
            } else {
                    $this->content->icons[] = '';
                    $this->content->items[] = get_string('noinactivecourses', 'block_courses_inactive');
                    return $this->content;
            }
        }
        return $this->content;
    }
}
