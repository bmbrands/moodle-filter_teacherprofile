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
 * Settings for the Teacherprofile filter.
 *
 * @package    filter_teacherprofile
 * @copyright  2025 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    // Home server URL.
    $name = new lang_string('tqs', 'filter_teacherprofile');
    $desc = new lang_string('tqs_desc', 'filter_teacherprofile');
    $settings->add(new admin_setting_configtext('filter_teacherprofile/tqs', $name, $desc, ''));

    // Custom role for teacher profile.
    $name = new lang_string('teacherprofilecustomrole', 'filter_teacherprofile');
    $desc = new lang_string('teacherprofilecustomrole_desc', 'filter_teacherprofile');
    $settings->add(new admin_setting_configtext('filter_teacherprofile/teacherprofilecustomrole', $name, $desc, ''));

}