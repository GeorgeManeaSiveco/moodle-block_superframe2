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
// You should have received a copy of the GNU General Public License.
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/**
 * superframe view page
 *
 * @package    block_superframe
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * Modified for use in MoodleBites for Developers Level 1 by Richard Jones & Justin Hunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../config.php');

$blockid = required_param('blockid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$size = optional_param('size','none', PARAM_TEXT);

$defconfig = get_config('block_superframe');
$PAGE->set_course($COURSE);
$PAGE->set_url('/blocks/superframe/view.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout($defconfig->pagelayout);
$PAGE->set_title(get_string('pluginname', 'block_superframe'));
$PAGE->navbar->add(get_string('pluginname', 'block_superframe'));
require_login();
$usercontext = context_user::instance($USER->id);
require_capability('block/superframe:seeviewpage', $usercontext);

$configdata = $DB->get_field('block_instances', 'configdata', ['id' => $blockid]);

if ($configdata) {
    $config = unserialize(base64_decode($configdata));
} else {
    $config = $defconfig;
    $config->size = 'custom';
}

if ($size == 'none') {
    $size = strtolower($config->size);
}

$url = $config->url;


switch (strtolower($size)) {
    case 'custom':
        $width = $defconfig->width;
        $height = $defconfig->height;
        break;
    case 'small' :
        $width = 360;
        $height = 240;
        break;
    case 'medium' :
        $width = 600;
        $height = 400;
        break;
    case 'large' :
        $width = 1024;
        $height = 720;
        break;
}

$renderer = $PAGE->get_renderer('block_superframe');
$renderer->display_view_page($url, $width, $height, $USER, $courseid, $blockid);