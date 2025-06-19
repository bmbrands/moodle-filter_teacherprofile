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

/**
 * Install script for Teacherprofile
 *
 * Documentation: {@link https://moodledev.io/docs/guides/upgrade}
 *
 * @package    filter_teacherprofile
 * @copyright  2025 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Executed on installation of Teacherprofile
 *
 * @return bool
 */
function xmldb_filter_teacherprofile_install() {
    create_teacherprofile_user_profile_fields();
    return true;
}


/**
 * Insert "Teacherinfo" category and "tqs" field.
 *
 */
function create_teacherprofile_user_profile_fields() {
    global $CFG, $DB;

    require_once($CFG->dirroot . '/user/profile/definelib.php');
    require_once($CFG->dirroot . '/user/profile/field/text/define.class.php');

    // Check if teacherinfo category exists.
    $categoryname = 'teacherinfo';
    $category = $DB->count_records('user_info_category', ['name' => $categoryname]);

    if ($category < 1) {
        $data = new \stdClass();
        $data->sortorder = $DB->count_records('user_info_category') + 1;
        $data->name = $categoryname;
        $data->id = $DB->insert_record('user_info_category', $data);

        $createdcategory = $DB->get_record('user_info_category', ['id' => $data->id]);
        $categoryid = $createdcategory->id;
        \core\event\user_info_category_created::create_from_category($createdcategory)->trigger();
    } else {
        $category = $DB->get_record('user_info_category', ['name' => $categoryname]);
        $categoryid = $category->id;
    }

    // Check if matrixuserid exists in user_info_field table.
    $tqs = $DB->count_records('user_info_field', [
        'shortname' => 'tqs',
        'categoryid' => $categoryid,
    ]);

    if ($tqs < 1) {
        $profileclass = new \profile_define_text();

        $data = (object) [
            'shortname' => 'tqs',
            'name' => 'Tutor Quality Standard',
            'datatype' => 'text',
            'categoryid' => $categoryid,
            'forceunique' => 0,
            'visible' => 0,
            'locked' => 1,
            'param1' => 30,
            'param2' => 2048,
        ];

        $profileclass->define_save($data);
        set_config('filter_teacherprofile_tqs', 'tqs', 'filter_teacherprofile');
    }
}
