# Gamer-Profile - a way to share your game experience

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/e75e4c16407042fba61f9531c8f79d8f)](https://app.codacy.com/app/lkochniss/gamer-profile?utm_source=github.com&utm_medium=referral&utm_content=lkochniss/gamer-profile&utm_campaign=Badge_Grade_Dashboard)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/9129eba354d24974abe4f789cf089293)](https://www.codacy.com/app/lkochniss/gamer-profile?utm_source=github.com&utm_medium=referral&utm_content=lkochniss/gamer-profile&utm_campaign=Badge_Coverage)

[Demo](https://gamer-profile.kochniss.com)

My current idea is to access a Steam Profile, copy some basic information like the title and the header image of a game and also persist the overall playtime and recent playtime based on a Steam UserID.

Within the admin panel you can see some overall game stats like total achievements as well as your daily playtime in a calendar with colored squares according to the playtime of the day 
(from light green to dark red), all games played in the month and some statistics about average playtime per month and overall playtime per month. All these information are accumulated 
over all games but most can also be checked by game to get a better understanding of gaming habits. 

If you want to participate in the participate in a "beta" fork this project, add your [Steam User ID](https://steamidfinder.com/) in the 
[SteamController](https://github.com/lkochniss/gamer-profile/blob/master/src/Controller/SteamController.php#L18) and create a PullRequest. As soon as I update the project on my server (too stupid for CD on my own server)
manually, you can login.
