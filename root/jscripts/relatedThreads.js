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

var rTTimer = 0; 
var rTTimeout = 0;
var rTMinLength = 0;
var rTSpinnerStatus = -1;
var rTSpinner = '';
var rTFid = 0;

var relatedThreads = 
{
    init: function(subject)
    {
        if (rTTimer == 0)
        {
            rTTimer = $('rTTimer').value;
        }
        if (rTMinLength == 0)
        {
            rTMinLength = $('rTMinLength').value; 
        }
        if (rTSpinnerStatus == -1)
        {
            rTSpinnerStatus = $('rTSpinner').value; 
        }
        
        if (subject.length >= rTMinLength)
        {
            if (rTFid == 0)
            {
                rTFid = $('rTFid').value; 
            }
          
            clearTimeout(rTTimeout);
            rTTimeout = setTimeout("relatedThreads.refresh('" + subject + "', '" + rTFid + "')", rTTimer);
        }
        return false;
    },
  
  
	refresh: function(subject, fid)
	{
        if (rTSpinnerStatus == 1)
        {
            rTSpinner = new ActivityIndicator("body", {image: "images/spinner_big.gif"});
        }
        
        new Ajax.Request('xmlhttp.php?action=relatedThreads&subject=' + subject + '&fid=' + fid, 
        {
            method: 'get',
            onComplete: function(request) { relatedThreads.display(request); }
        });
        
        return false;
    },


    display: function(request)
    {
        if (request.responseText != "")
        {
            $('relatedThreadsRow').style.display = "table-row";
            $('relatedThreads').innerHTML = request.responseText;         
        } 
        else 
        {
            $('relatedThreadsRow').style.display = "none";
            $('relatedThreads').innerHTML = "";
        } 
        
        if (rTSpinnerStatus == 1)
        {
            rTSpinner.destroy();
            rTSpinner = ''; 
        }
    }
};
            