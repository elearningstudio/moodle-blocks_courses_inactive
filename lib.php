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

/**
 * Returns list of courses current $USER is enrolled in and can access
 *
 * - $fields is an array of field names to ADD
 *   so name the fields you really need, which will
 *   be added and uniq'd
 *
 * @param string|array $fields
 * @param string $sort
 * @param int $limit max number of courses
 * @return array
 */
function get_my_inactive_courses($fields = null, $sort = 'visible DESC,sortorder ASC', $limit = 0) {
    global $DB, $USER;

    // Guest account does not have any courses.
    if (isguestuser() or !isloggedin()) {
        return(array());
    }

    $sql = "SELECT c.id, c.shortname, c.fullname FROM {course_completions} cc, {course} c
                WHERE cc.course = c.id
                    AND cc.timeenrolled > 0
                    AND cc.timestarted = 0
                    AND cc.timecompleted IS NULL
                    AND cc.userid = :userid
                ORDER BY c.shortname ASC";
    $params = array();
    $params['userid'] = $USER->id;
    $courses = $DB->get_records_sql($sql, $params);

    foreach ($courses as $id => $course) {
        context_instance_preload($course);
        $courses[$id] = $course;
    }

    return $courses;
}
