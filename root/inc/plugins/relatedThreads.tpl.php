<?php
/**
 * This file is part of Related Threads plugin for MyBB.
 * Copyright (C) 2010-2013 Lukasz Tkacz <lukasamd@gmail.com>
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
 * Plugin Activator Class
 * 
 */
class relatedThreadsActivator
{

    private static $tpl = array();

    private static function getTpl()
    {
        global $db, $lang;

        self::$tpl[] = array(
            "tid" => NULL,
            "title" => 'relatedThreads_title',
            "template" => $db->escape_string('<strong>' . $lang->relatedThreadsName . '</strong>'),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );


        self::$tpl[] = array(
            "tid" => NULL,
            "title" => 'relatedThreads_withForum',
            "template" => $db->escape_string('<a {$linkTarget}href="{$thread[\'link\']}">{$thread[\'subject\']}</a> (<a {$linkTarget}href="{$forum[\'link\']}">{$forum[\'name\']}</a>)'),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );


        self::$tpl[] = array(
            "tid" => NULL,
            "title" => 'relatedThreads_withoutForum',
            "template" => $db->escape_string('<a {$linkTarget}href="{$thread[\'link\']}">{$thread[\'subject\']}</a>'),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
    }

    public static function activate()
    {
        global $db;
        self::deactivate();

        for ($i = 0; $i < sizeof(self::$tpl); $i++)
        {
            $db->insert_query('templates', self::$tpl[$i]);
        }
        
        find_replace_templatesets("newthread", '#<\/head>#', "<script type=\"text/javascript\" src=\"jscripts/relatedThreads.js\"></script>\n</head>");
        find_replace_templatesets("newthread", '#{\$posticons}(\r?)\n#', "<tr id=\"relatedThreadsRow\" style=\"display:none;\"><td class=\"trow2\"></td><td class=\"trow2\" id=\"relatedThreads\">{\$relatedThreads}</td></tr>\n{\$posticons}\n");
        find_replace_templatesets("newthread", '#name="subject"#', "name=\"subject\" onkeyup=\"return relatedThreads.init(this.value);\"");
    }

    public static function deactivate()
    {
        global $db;
        self::getTpl();

        for ($i = 0; $i < sizeof(self::$tpl); $i++)
        {
            $db->delete_query('templates', "title = '" . self::$tpl[$i]['title'] . "'");
        }

        include MYBB_ROOT . '/inc/adminfunctions_templates.php';
        find_replace_templatesets("newthread", '#<script type="text/javascript" src="jscripts/relatedThreads.js"></script>(\r?)\n#', "", 0);
        find_replace_templatesets("newthread", '#<tr id="relatedThreadsRow" style="display:none;"><td class="trow2"></td><td class="trow2" id="relatedThreads">{\$relatedThreads}</td></tr>(\r?)\n#', "", 0);
        find_replace_templatesets("newthread", '#onkeyup="(.*?)" #', "", 0);
    }

}
