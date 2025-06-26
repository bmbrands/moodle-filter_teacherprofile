<?php
// This file is part of Moodle - http://moodle.org/
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

namespace filter_teacherprofile;

use core_user;
use moodle_url;
use stdClass;

/**
 * Class text_filter
 *
 * @package    filter_teacherprofile
 * @copyright  2025 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class text_filter extends \core_filters\text_filter {

    #[\Override]
    public function filter($text, array $options = []) {
        if (strpos($text, '[[course_teacherprofile]]')) {
            $teacherid = self::get_first_teacher_id();
            if ($teacherid) {
                return str_replace('[[course_teacherprofile]]', self::get_user_profile($teacherid), $text);
            } else {
                return str_replace('[[course_teacherprofile]]', '', $text);
            }
        }

        if (strpos($text, '[[course_teacherprofile:')) {
            $pattern = '/\[\[course_teacherprofile:([0-9]*?)\]\]/is';
            // Get the first id from the pattern.
            $result = preg_replace_callback($pattern, function ($matches) {
                return $this->filter_callback($matches);
            }, $text);
            return $result;
        }
        return $text;
    }

    public static function quick_get_teacher() {

        // Get the first teacher id for this course.
        $teacherid = self::get_first_teacher_id();
        if ($teacherid) {
            return self::get_user_profile($teacherid);
        }
        return '';
    }

    /**
     * Get the teacher profile to display on the course page.
     *
     * @return string
     */
    private function filter_callback($matches) {
        $userid = $matches[1];
        if (empty($userid)) {
            return '';
        }
        return self::get_user_profile($userid);
    }

    /**
     * Get the role id from a shortname.
     *
     * @param string $shortname the role shortname.
     * @return stdClass the role from the DB.
     */
    protected static function get_role(string $shortname): stdClass {
        global $DB;
        return $DB->get_record('role', ['shortname' => $shortname]);
    }

    /**
     * Get the first teacher id for this course.
     * @return int|null
     */
    public static function get_first_teacher_id() {
        global $COURSE;
        $coursectx = \context_course::instance($COURSE->id);
        if (!$coursectx) {
            return null;
        }
        // Check if the course has a custom role for the teacher profile.
        $customrole = get_config('filter_teacherprofile', 'teacherprofilecustomrole');
        if (!empty($customrole)) {
            // Get the role by name.
            $role = self::get_role($customrole);
            if ($role) {
                // Get the users with this role in the course context.
                $userfieldsapi = \core_user\fields::for_name();
                $userfields = 'u.id, u.username' . $userfieldsapi->get_sql('u')->selects;
                $teachers = get_role_users($role->id, $coursectx, false, $userfields);
                if (empty($teachers)) {
                    return null;
                }
                // Get the first teacher.
                $teacher = reset($teachers);
                return $teacher->id ?? null;
            }
        }
        // Teachers
        $teachers = get_enrolled_users($coursectx, 'moodle/course:changefullname');
        if (empty($teachers)) {
            return null;
        }
        // Get the first teacher.
        $teacher = reset($teachers);
        return $teacher->id ?? null;
    }


    /**
     * Get the user profile to display.
     *
     * @param int $userid The user ID to get the profile for.
     * @return string
     */
    public static function get_user_profile($userid) {
        global $OUTPUT;

        // Get the first teacher.
        $user = core_user::get_user($userid, '*', MUST_EXIST);
        $usercontext = \context_user::instance($user->id);
        $fields = (array)profile_user_record($user->id);
        $tqsfield = get_config('filter_teacherprofile', 'tqs');
        if (empty($tqsfield) || !isset($fields[$tqsfield])) {
            $tqsfield = 'tqs';
        }
        return $OUTPUT->render_from_template(
            'filter_teacherprofile/teacherinfo',
            [
                'fullname' => fullname($user),
                'picture' => $OUTPUT->user_picture($user, ['size' => 100, 'link' => false]),
                'description' => file_rewrite_pluginfile_urls($user->description, 'pluginfile.php', $usercontext->id, 'user',
                    'profile', null),
                'teacher' => $user,
                'fields' => $fields,
                'tqs' => isset($fields[$tqsfield]) ? $fields[$tqsfield] : '',
            ]
        );
    }
}
