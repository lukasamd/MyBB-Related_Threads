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

var relatedThreads =
{
    timeout: 0,
    config: {
        timer: 1000,
        minLength: 4,
        fid: 0
    },

    init: function(subject)
    {
        if (subject.length >= relatedThreads.config.minLength)
        {
            clearTimeout(relatedThreads.timeout);
            relatedThreads.timeout = setTimeout(function() {
                relatedThreads.refresh(subject, relatedThreads.config.fid);
            }, relatedThreads.config.timer);
        }
        else
        {
            clearTimeout(relatedThreads.timeout);
            relatedThreads.display({responseText: ''});
        }
        return false;
    },


    refresh: function(subject, fid)
    {
        if(use_xmlhttprequest == 1)
        {
            $.ajax(
            {
                url: 'xmlhttp.php',
                type: 'get',
                data: {
                    action: 'relatedThreads',
                    subject: subject,
                    fid: fid
                },
                complete: function(request)
                {
                    relatedThreads.display(request);
                }
            });

            return false;
        }
        else
        {
            return true;
        }
    },


    display: function(request)
    {
        if (request.responseText != "")
        {
            $('#relatedThreadsRow').css("display", "table-row");
            $('#relatedThreads').html(request.responseText);         
        } 
        else 
        {
            $('#relatedThreadsRow').css("display", "none");
            $('#relatedThreads').html("");
        } 
    }
};

$(document).ready(function()
{
    $('input[name="subject"]').on('keyup input', function()
    {
        relatedThreads.init(this.value);
    });
});
