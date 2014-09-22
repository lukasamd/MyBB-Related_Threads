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
            "title" => 'relatedThreads_code',
            "template" => $db->escape_string('<strong>' . $lang->relatedThreadsName . '</strong>'),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
        
        self::$tpl[] = array(
            "tid" => NULL,
            "title" => 'relatedThreads_javascript',
            "template" => $db->escape_string('
<script type="text/javascript" src="jscripts/relatedThreads.js"></script>            
<script type="text/javascript">
<!--
	var rTTimer = "{$mybb->settings[\'relatedThreadsTimer\']}";
	var rTMinLength = "{$mybb->settings[\'relatedThreadsLength\']}";
	var rTFid = "{$forum[\'fid\']}";
// -->
</script>'),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );

        self::$tpl[] = array(
            "tid" => NULL,
            "title" => 'relatedThreads_withForum',
            "template" => $db->escape_string('{$thread[\'threadprefix\']}<a {$linkTarget}href="{$thread[\'link\']}">{$thread[\'subject\']}</a> (<a {$linkTarget}href="{$forum[\'link\']}">{$forum[\'name\']}</a>)'),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );


        self::$tpl[] = array(
            "tid" => NULL,
            "title" => 'relatedThreads_withoutForum',
            "template" => $db->escape_string('{$thread[\'threadprefix\']}<a {$linkTarget}href="{$thread[\'link\']}">{$thread[\'subject\']}</a>'),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
        
        self::$tpl[] = array(
            "tid" => NULL,
            "title" => 'relatedThreads_list',
            "template" => $db->escape_string('<ul class="relatedThreadsList">{$relatedThreads[\'list\']}</ul>'),
            "sid" => "-1",
            "version" => "1.0",
            "dateline" => TIME_NOW,
        );
        
        self::$tpl[] = array(
            "tid" => NULL,
            "title" => 'relatedThreads_listElement',
            "template" => $db->escape_string('<li>{$relatedThreads[\'element\']}</li>'),
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
        
        find_replace_templatesets("newthread", '#' . preg_quote('</head>') . '#', "{\$relatedThreadsJavaScript}</head>");
        find_replace_templatesets("newthread", '#' . preg_quote('{$posticons}') . '#', '<tr id="relatedThreadsRow" style="display:none;"><td class="trow2" valign="top"><strong>{$lang->relatedThreadsTitle}</strong></td><td class="trow2" id="relatedThreads">{$relatedThreads}</td></tr>{$posticons}');
        find_replace_templatesets("newthread", '#' . preg_quote('name="subject"') . '#', 'name="subject" onkeyup="return relatedThreads.init(this.value);"');        
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
        find_replace_templatesets("newthread", '#' . preg_quote('{$relatedThreadsJavaScript}') . '#', "", 0);
        find_replace_templatesets("newthread", '#' . preg_quote('<tr id="relatedThreadsRow" style="display:none;"><td class="trow2" valign="top"><strong>{$lang->relatedThreadsTitle}</strong></td><td class="trow2" id="relatedThreads">{$relatedThreads}</td></tr>') . '#', "", 0);
        find_replace_templatesets("newthread", '#' . preg_quote(' onkeyup="return relatedThreads.init(this.value);"') . '#', "", 0);      
    }

}
