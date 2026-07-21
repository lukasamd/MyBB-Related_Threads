# Related Threads for MyBB

This plugin checks to see if there are already similar threads when we write new topic (using AJAX). 
If yes, we can simply check them.

Version 2.x targets current MyBB 1.8.x installations and PHP 8.x. It fixes the reported thread subject XSS issue and removes the legacy spider-only author link injection.

Admin can configure:
- Additional (X)HTML code before related threads list (in global template)
- Fulltext / LIKE search mode
- Minimum length of phrase
- Open links on new / the same window or tab
- Excluded forums
- Excluded words
- Search on the same forum
- Max search threads age
- Display forum info
- Delay from press last key to search
- Change all plugin templates    
