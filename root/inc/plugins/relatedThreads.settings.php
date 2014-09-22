<?php
/**
 * This file is part of Related Threads plugin for MyBB.
 * Copyright (C) Lukasz Tkacz <lukasamd@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */ 
 
/**
 * Disallow direct access to this file for security reasons
 * 
 */
if (!defined("IN_MYBB")) exit;

/**
 * Plugin Installator Class
 * 
 */
class relatedThreadsInstaller
{

    public static function install()
    {
        global $db, $lang, $mybb;
        self::uninstall();

        $result = $db->simple_select('settinggroups', 'MAX(disporder) AS max_disporder');
        $max_disporder = $db->fetch_field($result, 'max_disporder');
        $disporder = 1;

        $settings_group = array(
            'gid' => 'NULL',
            'name' => 'relatedThreads',
            'title' => $db->escape_string($lang->relatedThreadsName),
            'description' => $db->escape_string($lang->relatedThreadsGroupDesc),
            'disporder' => $max_disporder + 1,
            'isdefault' => '0'
        );
        $db->insert_query('settinggroups', $settings_group);
        $gid = (int) $db->insert_id();

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsCodeStatus',
            'title' => $db->escape_string($lang->relatedThreadsCodeStatus),
            'description' => $db->escape_string($lang->relatedThreadsCodeStatusDesc),
            'optionscode' => 'onoff',
            'value' => '0',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsLength',
            'title' => $db->escape_string($lang->relatedThreadsLength),
            'description' => $db->escape_string($lang->relatedThreadsLengthDesc),
            'optionscode' => 'text',
            'value' => '4',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsLimit',
            'title' => $db->escape_string($lang->relatedThreadsLimit),
            'description' => $db->escape_string($lang->relatedThreadsLimitDesc),
            'optionscode' => 'text',
            'value' => '5',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsLinkLastPost',
            'title' => $db->escape_string($lang->relatedThreadsLinkLastPost),
            'description' => $db->escape_string($lang->relatedThreadsLinkLastPostDesc),
            'optionscode' => 'onoff',
            'value' => '0',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsNewWindow',
            'title' => $db->escape_string($lang->relatedThreadsNewWindow),
            'description' => $db->escape_string($lang->relatedThreadsNewWindowDesc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsFulltext',
            'title' => $db->escape_string($lang->relatedThreadsFulltext),
            'description' => $db->escape_string($lang->relatedThreadsFulltextDesc),
            'optionscode' => 'onoff',
            'value' => ($mybb->settings['searchtype'] == 'fulltext') ? '1' : '0',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsForumOnly',
            'title' => $db->escape_string($lang->relatedThreadsForumOnly),
            'description' => $db->escape_string($lang->relatedThreadsForumOnlyDesc),
            'optionscode' => 'onoff',
            'value' => '0',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
		
        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsShowPrefixes',
            'title' => $db->escape_string($lang->relatedThreadsShowPrefixes),
            'description' => $db->escape_string($lang->relatedThreadsShowPrefixesDesc),
            'optionscode' => 'onoff',
            'value' => '1',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $options = 'select\n0=' . $lang->relatedThreadsTimeOptionNone . '\n';
        $options .= 'h=' . $lang->relatedThreadsTimeOptionHours . '\n';
        $options .= 'd=' . $lang->relatedThreadsTimeOptionDays . '\n';
        $options .= 'w=' . $lang->relatedThreadsTimeOptionWeeks . '\n';
        $options .= 'm=' . $lang->relatedThreadsTimeOptionMonths . '\n';
        $options .= 'y=' . $lang->relatedThreadsTimeOptionYears;
        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsTimeLimitSelect',
            'title' => $db->escape_string($lang->relatedThreadsTimeLimitSelect),
            'description' => $db->escape_string($lang->relatedThreadsTimeLimitSelectDesc),
            'optionscode' => $options,
            'value' => '0',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsTimeLimit',
            'title' => $db->escape_string($lang->relatedThreadsTimeLimit),
            'description' => $db->escape_string($lang->relatedThreadsTimeLimitDesc),
            'optionscode' => 'text',
            'value' => '0',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $options = 'select\nfirstPost=' . $lang->relatedThreadsTimeOptionFirstPost . '\n';
        $options .= 'lastPost=' . $lang->relatedThreadsTimeOptionLastPost;
        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsTimeLimitMethod',
            'title' => $db->escape_string($lang->relatedThreadsTimeLimitMethod),
            'description' => $db->escape_string($lang->relatedThreadsTimeLimitMethodDesc),
            'optionscode' => $options,
            'value' => 'firstPost',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsForumGet',
            'title' => $db->escape_string($lang->relatedThreadsForumGet),
            'description' => $db->escape_string($lang->relatedThreadsForumGetDesc),
            'optionscode' => 'onoff',
            'value' => '0',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsExceptions',
            'title' => $db->escape_string($lang->relatedThreadsExceptions),
            'description' => $db->escape_string($lang->relatedThreadsExceptionsDesc),
            'optionscode' => 'text',
            'value' => '',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsBadWords',
            'title' => $db->escape_string($lang->relatedThreadsBadWords),
            'description' => $db->escape_string($lang->relatedThreadsBadWordsDesc),
            'optionscode' => 'text',
            'value' => '',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);

        $setting = array(
            'sid' => 'NULL',
            'name' => 'relatedThreadsTimer',
            'title' => $db->escape_string($lang->relatedThreadsTimer),
            'description' => $db->escape_string($lang->relatedThreadsTimerDesc),
            'optionscode' => 'text',
            'value' => '1000',
            'disporder' => $disporder++,
            'gid' => $gid
        );
        $db->insert_query('settings', $setting);
        
        rebuild_settings();
    }

    public static function uninstall()
    {
        global $db;

        $result = $db->simple_select('settinggroups', 'gid', "name = 'relatedThreads'");
        $gid = (int) $db->fetch_field($result, "gid");
        
        if ($gid > 0)
        {
            $db->delete_query('settings', "gid = '{$gid}'");
        }
        $db->delete_query('settinggroups', "gid = '{$gid}'");
        
        rebuild_settings();
    }

}
