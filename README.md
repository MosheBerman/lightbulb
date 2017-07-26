lightbulb
=========

This webapp alerts users when information for a CUNY class changes. Class info is pulled from the CUNY website. (**Note:** The course information used to be hosted on CUNY's website, [here][0], before the university system migrated to CUNYFirst.)

# History

Lightbulb was a project I wrote during sophomore year at Brooklyn College. 

It had several parts:

1. A bot that scraped the Brooklyn College course system and regularly alerted me of changes to course listings.
2. A student-facing webapp was planned but never finished. As a part of this, I had planned to build out an API, which could eventually be used for an app as well.

The bot is written in PHP, and is mostly visible in this repository. There was a cron job that ran it regularly. The cron configuration has unfortunately has been lost to time. I stopped working on this project when CUNY switched over all of the colleges to CUNYFirst. 

# Time Complexity

This project included several technical challenges, including time complexity issues. 

One performance gain was found in [scraper.php][5]. Instead of iterating a list at a time complexity of `O(n)` (linear), I continuously popped the first element until there's nothing left. This has the effective complexity of `O(1)` (constant time,) because `O(n)` where `n` is `1`, is `O(1)`. I researched and documented the changes made to the scraper [on Stack Overflow][3].

In [`differ.php`][4], I was iterating courses and sections to compare contents. The initial loop had a complexity of `O(courses^2) * O(sections^2)`. I changed the comparison to use PHP's `array_key_exists`, which itself has a time complexity that's  ["really close to `O(1)`"][2]. This theoretically brought my iteration down to `O(courses) * O(sections)`.

Between these two fixes, the bot ran in a reasonable amount of time, and actually helped people get into classes they needed to graduate.

# License

Copyright 2012 Moshe Berman.

[0]: http://student.cuny.edu/cgi-bin/SectionMeeting/SectMeetColleges.pl?COLLEGECODE=05
[1]: https://github.com/MosheBerman/lightbulb/commit/da0f8a4ee5366b28eed9521a78e5cd268a152fa5#diff-712c420d940d1a22c672127f9cbb2e8a
[2]: https://stackoverflow.com/a/2484455/224988
[3]: https://stackoverflow.com/a/13931470/224988
[4]: https://github.com/MosheBerman/lightbulb/blob/master/system/differ.php
[5]: https://github.com/MosheBerman/lightbulb/blob/master/system/scraper.php