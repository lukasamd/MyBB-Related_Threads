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

$plugins->add_hook('xmlhttp', ['relatedThreads', 'displayThreads']);
$plugins->add_hook('newthread_start', ['relatedThreads', 'injectNewthread']);

/**
 * Standard MyBB info function
 * 
 */
function relatedThreads_info()
{
    global $lang;

    $lang->load('relatedThreads');
    
    $lang->relatedThreadsDesc = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="float:right;">' .
        '<input type="hidden" name="cmd" value="_s-xclick">' . 
        '<input type="hidden" name="hosted_button_id" value="3BTVZBUG6TMFQ">' .
        '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">' .
        '<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">' .
        '</form>' . $lang->relatedThreadsDesc;

    return [
        'name' => $lang->relatedThreadsName,
        'description' => $lang->relatedThreadsDesc,
        'website' => 'http://lukasztkacz.com',
        'author' => 'Lukasz "LukasAMD" Tkacz',
        'authorsite' => 'http://lukasztkacz.com',
        'version' => '2.0.0',
        'compatibility' => '18*'
    ];
}

/**
 * Standard MyBB installation functions 
 * 
 */
function relatedThreads_install()
{
    require_once('relatedThreads.settings.php');
    relatedThreadsInstaller::install();
}

function relatedThreads_is_installed()
{
    global $mybb;

    return (isset($mybb->settings['relatedThreadsLength']));
}

function relatedThreads_uninstall()
{
    require_once('relatedThreads.settings.php');
    relatedThreadsInstaller::uninstall();
}

/**
 * Standard MyBB activation functions 
 * 
 */
function relatedThreads_activate()
{
    require_once('relatedThreads.tpl.php');
    relatedThreadsActivator::activate();
}

function relatedThreads_is_activated()
{
    global $mybb;

    return (isset($mybb->settings['relatedThreadsLength']));
}

function relatedThreads_deactivate()
{
    require_once('relatedThreads.tpl.php');
    relatedThreadsActivator::deactivate();
}

/**
 * Plugin Class
 * 
 */
class relatedThreads
{

    // Where statement for sql
    private static $where = '';

    private static $defaults = [
        'CodeStatus' => '0',
        'Length' => '4',
        'Limit' => '5',
        'LinkLastPost' => '0',
        'NewWindow' => '1',
        'Fulltext' => '0',
        'ForumOnly' => '0',
        'ShowPrefixes' => '1',
        'TimeLimitSelect' => '0',
        'TimeLimit' => '0',
        'TimeLimitMethod' => 'firstPost',
        'ForumGet' => '0',
        'Exceptions' => '',
        'BadWords' => '',
        'Timer' => '1000',
    ];

    /**
     * Inject hidden fields for javascript code in newthread template.
     * 
     * @return bool False.
     */
    public static function injectNewthread()
    {
        global $forum, $lang, $mybb, $relatedThreads, $relatedThreadsJavaScript, $templates;

        $lang->load('relatedThreads');
        $relatedThreads = '';
        eval("\$relatedThreadsJavaScript = \"" . $templates->get("relatedThreads_javascript") . "\";");
    }

    /**
     * Search for related threads and display output list.
     * 
     * @return bool False if there are not related threads or string if there are.
     */
    public static function displayThreads()
    {
        global $mybb, $db, $lang, $relatedThreads, $templates;

        if ($mybb->get_input('action') !== 'relatedThreads')
        {
            return;
        }
        header("Content-type: text/html; charset=UTF-8");

        $minLength = self::getPositiveIntConfig('Length', 4, 1, 85);
        $userSubject = trim($mybb->get_input('subject'));
        if (empty($mybb->user['uid']) || my_strlen($userSubject) < $minLength)
        {
            return;
        }

        $limit = self::getPositiveIntConfig('Limit', 5, 1, 50);

        // Prepare sql statements
        self::$where = '';
        self::getStandardWhere();
        self::getForumCheck();
        self::getExceptions();
        self::getTimeLimit();
        self::getPermissions();
        self::getUnsearchableForums();
        self::getInactiveForums();

        // Use fulltext search index...
        if (self::getConfig('Fulltext') && $db->supports_fulltext("threads") && $db->is_fulltext("threads", "subject"))
        {
            self::$where .= " AND MATCH(t.subject) AGAINST('" . $db->escape_string($userSubject) . "') ";
        }
        // ...or build LIKE query
        else
        {
            $keywords = self::getKeywords($userSubject);
            $countKeywords = count($keywords);

            if (!$countKeywords)
            {
                return;
            }

            $likes = [];
            foreach ($keywords as $keyword)
            {
                $likes[] = "t.subject LIKE '%" . $db->escape_string_like($keyword) . "%'";
            }

            self::$where .= " AND (" . implode(' OR ', $likes) . ") ";
        }

        $sql = "SELECT tid, fid, subject, prefix
                FROM " . TABLE_PREFIX . "threads t
                WHERE " . self::$where . "
                LIMIT " . $limit;
        $result = $db->query($sql);
        
        if (!$db->num_rows($result))
        {
            return;
        }

        $fids = [];
        $threadsList = [];
        while ($row = $db->fetch_array($result))
        {
            $fid = (int)$row['fid'];
            $tid = (int)$row['tid'];
            $fids[] = $fid;

            $threadsList[$tid] = [
                'subject' => htmlspecialchars_uni($row['subject']),
                'link' => htmlspecialchars_uni((self::getConfig('LinkLastPost')) ? get_thread_link($tid, 0, 'lastpost') : get_thread_link($tid)),
                'fid' => $fid,
                'threadprefix' => '',
            ];

            // Thread prefix
            if(self::getConfig('ShowPrefixes') != 0 && $row['prefix'] != 0)
            {
                $threadprefix = build_prefixes($row['prefix']);
                $threadsList[$tid]['threadprefix'] = $threadprefix['displaystyle'].'&nbsp;';
            }
        }

        // Get forums data
        $forumsList = [];
        if (self::getConfig('ForumGet'))
        {
            $fids = array_unique(array_map('intval', $fids));
            if (empty($fids))
            {
                return;
            }

            $sql = "SELECT name, fid
                    FROM " . TABLE_PREFIX . "forums
                    WHERE fid IN (" . implode(',', $fids) . ")";
            $result = $db->query($sql);
            while ($row = $db->fetch_array($result))
            {
                $forumsList[(int)$row['fid']] = [
                    'name' => htmlspecialchars_uni(strip_tags($row['name'])),
                    'link' => htmlspecialchars_uni(get_forum_link($row['fid'])),
                ];
            }
        }

        // Prepare variable for templates
        $linkTarget = (self::getConfig('NewWindow')) ? 'target="_blank" rel="noopener" ' : '';

        // Display additional code
        if (self::getConfig('CodeStatus'))
        {
            echo $templates->get("relatedThreads_code");
        }

        // Display all elements
        $relatedThreads['list'] = '';
        foreach ($threadsList as $thread)
        {
            $relatedThreads['element'] = '';
            if (self::getConfig('ForumGet') && isset($forumsList[$thread['fid']]))
            {
                $forum['link'] = $forumsList[$thread['fid']]['link'];
                $forum['name'] = $forumsList[$thread['fid']]['name'];
                eval("\$relatedThreads['element'] = \"" . $templates->get("relatedThreads_withForum") . "\";");
            }
            else
            {
                eval("\$relatedThreads['element'] = \"" . $templates->get("relatedThreads_withoutForum") . "\";");
            }

            eval("\$relatedThreads['list'] .= \"" . $templates->get("relatedThreads_listElement") . "\";");
        }
        eval("\$relatedThreads['content'] = \"" . $templates->get("relatedThreads_list") . "\";");
        echo $relatedThreads['content'];
    }

    /**
     * Additional function for get keywords from string 
     * 
     * @param string String to analyze
     * @return array Table with keywords.
     */
    private static function getKeywords($string)
    {
        $string = preg_replace('#\s+#i', ' ', $string);

        $keywords = explode(' ', $string);
        $keywords = array_map('trim', $keywords);
        $keywords = array_filter($keywords, ['relatedThreads', 'checkKeywords']);
        $keywords = array_unique($keywords);

        return $keywords;
    }

    /**
     * Additional function for array_filter when we build LIKE query - delete too short keywords 
     * 
     * @return bool False if there keyword is too short.
     */
    private static function checkKeywords($val)
    {
        $badWords = explode(',', self::getConfig('BadWords'));
        $badWords = array_map('trim', $badWords);
        if (my_strlen($val) >= self::getPositiveIntConfig('Length', 4, 1, 85) && !in_array($val, $badWords))
        {
            return true;
        }
        return false;
    }

    /**
     * Get standard SQL WHERE statement - closed and moved threads are not allowed
     */
    private static function getStandardWhere()
    {
        self::$where .= "t.visible = 1 AND t.closed NOT LIKE 'moved|%'";
    }

    /**
     * Additional function to build all WHERE statements - privilages, exceptions, groups etc.
     * 
     * @return string	- query part.
     */
    private static function getTimeLimit()
    {
        $timeLimit = (int) self::getConfig('TimeLimit');
        if (self::getConfig('TimeLimitSelect') === '0' || $timeLimit < 1)
        {
            return;
        }

        switch (self::getConfig('TimeLimitSelect'))
        {
            case 'h':
            default:
                $timeLimit *= 3600;
                break;

            case 'd':
                $timeLimit *= 3600 * 24;
                break;
                
            case 'w':
                $timeLimit *= 3600 * 24 * 7;
                break;
                
            case 'm':
                $timeLimit *= 3600 * 24 * 30;
                break;
                
            case 'y':
                $timeLimit *= 3600 * 24 * 365;
                break;
        }

        $timeLimit = TIME_NOW - $timeLimit;

        if (self::getConfig('TimeLimitMethod') == 'firstPost')
        {
            self::$where .= " AND t.dateline > " . $timeLimit . " ";
        }
        else
        {
            self::$where .= " AND t.lastpost > " . $timeLimit . " ";
        }
    }

    /**
     * Additional function to check if script should search threads on the same forum.
     */
    private static function getForumCheck()
    {
        global $mybb;

        if (!self::getConfig('ForumOnly'))
        {
            return;
        }

        $actualFid = $mybb->get_input('fid', MyBB::INPUT_INT);
        if ($actualFid > 0)
        {
            self::$where .= " AND t.fid = '{$actualFid}' ";
        }
    }

    /**
     * Get all forums exceptions to SQL WHERE statement
     */
    private static function getExceptions()
    {
        if (self::getConfig('Exceptions') == '')
        {
            return;
        }

        $exceptions_list = explode(',', self::getConfig('Exceptions'));
        $exceptions_list = array_map('intval', $exceptions_list);
        $exceptions_list = array_filter($exceptions_list);

        if (count($exceptions_list) > 0)
        {
            self::$where .= " AND t.fid NOT IN (" . implode(',', $exceptions_list) . ")";
        }
    }

    /**
     * Build a comma separated list of the forums this user cannot search
     *
     * @param int The parent ID to build from
     * @param int First rotation or not (leave at default)
     * @return return a CSV list of forums the user cannot search
     */
    private static function getUnsearchableForums()
    {
        if (!function_exists('get_unsearchable_forums'))
        {
            require_once MYBB_ROOT . 'inc/functions_search.php';
        }

        global $permissioncache, $unsearchableforums;
        $permissioncache = $unsearchableforums = false;

        $unsearchable = get_unsearchable_forums();
        if ($unsearchable)
        {
            self::$where .= " AND t.fid NOT IN ($unsearchable)";
        }
    }

    /**
     * Get all forums premissions to SQL WHERE statement
     */
    private static function getPermissions()
    {
        global $mybb;

        $onlyusfids = [];

        // Check group permissions if we can't view threads not started by us
        $group_permissions = forum_permissions();
        foreach ($group_permissions as $fid => $forum_permissions)
        {
            if (!empty($forum_permissions['canonlyviewownthreads']))
            {
                $onlyusfids[] = (int)$fid;
            }
        }
        if (!empty($onlyusfids))
        {
            $uid = (int)$mybb->user['uid'];
            self::$where .= " AND ((t.fid IN(" . implode(',', $onlyusfids) . ") AND t.uid='{$uid}') OR t.fid NOT IN(" . implode(',', $onlyusfids) . "))";
        }
    }

    /**
     * Get all inactive forums
     */
    private static function getInactiveForums()
    {
        $inactiveforums = get_inactive_forums();
        if ($inactiveforums)
        {
            self::$where .= " AND t.fid NOT IN ($inactiveforums)";
        }
    }

    /**
     * Helper function to get variable from config
     * 
     * @param string $name Name of config to get
     * @return string Data config from MyBB Settings
     */
    private static function getConfig($name)
    {
        global $mybb;

        $setting = "relatedThreads{$name}";
        if (isset($mybb->settings[$setting]))
        {
            return $mybb->settings[$setting];
        }

        return isset(self::$defaults[$name]) ? self::$defaults[$name] : '';
    }

    /**
     * Helper function to get a bounded positive integer setting.
     */
    private static function getPositiveIntConfig($name, $default, $min, $max)
    {
        $value = (int)self::getConfig($name);

        if ($value < $min)
        {
            $value = $default;
        }
        if ($value > $max)
        {
            $value = $max;
        }

        return $value;
    }

}
