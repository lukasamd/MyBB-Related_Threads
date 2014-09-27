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

$l['relatedThreadsName'] = 'Related Threads';
$l['relatedThreadsDesc'] = 'This plugin checks to see if there are already similar threads when we write new topic (using AJAX).';
$l['relatedThreadsGroupDesc'] = 'Settings for plugin "Related Threads"';

$l['relatedThreadsCodeStatus'] = 'Display additional code';
$l['relatedThreadsCodeStatusDesc'] = 'Specifies whether the additional code above the list of related topics will be displayed.<br />If you want add code, please edit "relatedThreads_title" global template.';

$l['relatedThreadsLength'] = 'The minimum length of phrase';
$l['relatedThreadsLengthDesc'] = 'Specifies the minimum length of text (and keywords) for which to search related topics.';

$l['relatedThreadsLimit'] = 'Related topics limit';
$l['relatedThreadsLimitDesc'] = 'Specifies how many related topics are displayed.';

$l['relatedThreadsLinkLastPost'] = 'Links to the last posts';
$l['relatedThreadsLinkLastPostDesc'] = 'Specifies whether the link should lead to the last post rather than the first found in the topic.';

$l['relatedThreadsNewWindow'] = 'Open links in a new window';
$l['relatedThreadsNewWindowDesc'] = 'Specifies whether links to related topics are to be opened in a new window / tab.';

$l['relatedThreadsFulltext'] = 'Full Text Search';
$l['relatedThreadsFulltextDesc'] = 'Specifies whether the search is to use a more efficient system for full-text (if available).';

$l['relatedThreadsExceptions'] = 'Excluded forums';
$l['relatedThreadsExceptionsDesc'] = 'List of identifiers for excluded from the search (separated by commas.)';

$l['relatedThreadsBadWords'] = 'Excluded words';
$l['relatedThreadsBadWordsDesc'] = 'The list of words excluded from the search (separated by commas.)';

$l['relatedThreadsTimer'] = 'Javascript delay to start search';
$l['relatedThreadsTimerDesc'] = 'Specifies the time in milliseconds after you press the button to start the search.';

$l['relatedThreadsForumOnly'] = 'Search only in this same forum';
$l['relatedThreadsForumOnlyDesc'] = 'Specifies whether similar threads will be searched only in the same forum.';

$l['relatedThreadsTimeLimitSelect'] = 'Time limit (period)';
$l['relatedThreadsTimeLimitSelectDesc'] = 'Specifies the time interval, "age" looking for posts.';

$l['relatedThreadsTimeOptionNone'] = 'None'; 
$l['relatedThreadsTimeOptionHours'] = 'Hours';
$l['relatedThreadsTimeOptionDays'] = 'Days'; 
$l['relatedThreadsTimeOptionWeeks'] = 'Weeks'; 
$l['relatedThreadsTimeOptionMonths'] = 'Months';
$l['relatedThreadsTimeOptionYears'] = 'Years';
$l['relatedThreadsTimeOptionFirstPost'] = 'First post in topic';
$l['relatedThreadsTimeOptionLastPost'] = 'Last post in topic'; 

$l['relatedThreadsTimeLimit'] = 'Time limit (value)';
$l['relatedThreadsTimeLimitDesc'] = 'Specifies the time interval value - for example, you can choose "Days" in period and "3" in value = "max. 3 days ago".';

$l['relatedThreadsTimeLimitMethod'] = 'Time limit mode';
$l['relatedThreadsTimeLimitMethodDesc'] = 'Specifies which post age in topic will be checked.';

$l['relatedThreadsForumGet'] = 'Get information about the forum';
$l['relatedThreadsForumGetDesc'] = 'Get similar thread-forum data from database, for example, to display its name and link.';

$l['relatedThreadsShowPrefixes'] = 'Display thread prefixes';
$l['relatedThreadsShowPrefixesDesc'] = 'If enabled, plugin will show thread prefix before subject.';
