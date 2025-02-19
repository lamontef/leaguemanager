=== Racketmanager ===
Contributors: Paul Moffat, Kolja Schleich, LaMonte Forthun
Donate link:
Tags: league management, sport, widget, basketball, football, hockey, league, soccer, volleyball, gymnastics, tennis, baseball, rugby
Requires at least: 5.4
Tested up to: 5.4.2
Stable tag: 5.4.2
License: LGPLv2.1 or later

Plugin to manage and present Sports Leagues

== Description ==

This Plugin is designed to manage rackets leagues and tournaments and display them on your blog.

**Features**

* easy adding of teams and matches
* numerous point-rules implemented to also support special rules (e.g. Hockey, Pool, Baseball, Cornhole)
* weekly-based ordering of matches with bulk editing mechanism
* automatic or manual saving of standings table
* automatic or drag & drop ranking of teams
* link posts with specific match for match reports
* unlimited number of widgets
* modular setup for easy implementation of sport types
* separate capability to control access and compatibility with Role Manager
* dynamic match statistics
* Championship mode with preliminary and k/o rounds

**Translations**

== Installation ==

To install the plugin to the following steps

1. Unzip the zip-file and upload the content to your Wordpress Plugin directory.
2. Activiate the plugin via the admin plugin page.

== Screenshots ==
1. Main page for selected League
2. League Preferences
3. Adding of up to 15 matches simultaneously for one date
4. Easy insertion of tags via TinyMCE Button
5. Widget control panel
6. Example of 'Last 5' (shows 'Last 3') Functionality
7. Match Report

== Credits ==
The RacketManager icons were designed by Yusuke Kamiyamane (http://p.yusukekamiyamane.com/)

== Changelog ==

= 6.4.0 =
* BUGFIX: fix season setting
* UPDATE: add competition constitution option
* UPDATE: allow league entry by match secretaries
* UPDATE: give archive links correct name
* UPDATE: set correct rewrite rules
* UPDATE: set number of rounds correctly in championship construct
* UPDATE: use bootstrap v5 for admin screens

= 6.3.0 =
* BUGFIX: check team found in competition->getTeamInfo
* BUGFIX: correct html element in email_welcome template
* BUGFIX: get roster request auto confirmation working correctly
* UPDATE: allow cup entries by match secretaries
* UPDATE: fadeout success messages from ajax after 10 seconds
* UPDATE: format roster request emails
* UPDATE: format tournament entry email
* UPDATE: get date and time formats once and store in global variable for use
* UPDATE: get options once and store in global variable for use
* UPDATE: get site info once and store in global variable for use
* UPDATE: only update match if result changed
* UPDATE: pass from email address into matchnotification
* UPDATE: redirect to referral page after login
* UPDATE: remove depreciated variable for wp_new_user_notification
* UPDATE: standardise result notification emails

= 6.2.2 =
* UPDATE: sort tournaments by date in admin pages
* UPDATE: use separate display format for tournament dates

= 6.2.1 =
* BUGFIX: fix player result check bug with playedrounds

= 6.2.0 =
* BUGFIX: add missing end of statement to do_action in admin/includes/match.php
* BUGFIX: fix admin player stats
* BUGFIX: handle debug handle correctly when using ajax
* BUGFIX: only check match update allowed if affiliatedclub is set
* BUGFIX: replace &mdash; with '-'
* BUGFIX: send result notification emails correctly
* UPDATE: add ability to notify teams of match details at individual level
* UPDATE: add braces around if statement in admin/show-league and reformat lines
* UPDATE: add password complexity check to frontend
* UPDATE: align points correctly in championship admin screens
* UPDATE: allow headers to be passed to email send
* UPDATE: create dummy team object for "Bye"
* UPDATE: fix styling errors
* UPDATE: format change password confirmation email
* UPDATE: format password reset emails
* UPDATE: format privacy emails
* UPDATE: get team details in match.php
* UPDATE: link to league match page from admin results page
* UPDATE: move match notification function to racketmanager class
* UPDATE: move racketmanager.js file to js folder
* UPDATE: organise email templates into separate folder
* UPDATE: send notification of next tournament match details
* UPDATE: send notification of next tournament match details for first rounds
* UPDATE: store results options by competition type
* UPDATE: style welcome and reset password emails
* UPDATE: use competitiontype
* UPDATE: use league-tab rather than jquery-ui-tab
* UPDATE: use standard getTournaments for all tournament queries

= 6.1.0 =
* UPDATE: allow non-rubber match results to be entered
* UPDATE: add result confirmation email addresses for cups and tournaments
* UPDATE: replace &#8211; with -
* UPDATE: make match confirmation status able to be passed in league->_updateResults
* UPDATE: make tournament bracket header flexible width to allow headings to appear correctly
* UPDATE: make modal close button not overflow
* UPDATE: make modal fit screens correctly
* UPDATE: remove number incrementors on modal screen
* UPDATE: rename updateRubbers to updateResults
* UPDATE: make function to get confirmation email address
* BUGFIX: remove extra parameter from $league->_updateResults in championship->updateFinalResults
* BUGFIX: user correct variables for num_sets and num_rubbers
* BUGFIX: ensure correct parameters passed to getMatchUpdateAllowed function

= 6.0.5 =
* BUGFIX: sort matches by id for championship proceed to next round

= 6.0.4 =
* BUGFIX: fix group explode error in admin
* UPDATE: use tournament name on tournament entry form
* UPDATE: change tab name to "Draw" on championship pages
* UPDATE: allow match card to be printed for player tournaments
* UPDATE: reformat lines
* UPDATE: set and show home team for championships

= 6.0.3 =
* BUGFIX: make privacy exporter work
* UPDATE: make championship proceed function clearer

= 6.0.2 =
* UPDATE: apply consistent widths for standings and crosstable fields
* UPDATE: replace css style num with column-num in admin
* UPDATE: display set and total scrore boxes when printing match card

= 6.0.1 =
* BUGFIX: use correct plugin name in widget.php to load Javascript
* BUGFIX: use correct plugin name in admin.php for ajax settings
* UPDATE: remove tinymce

= 6.0.0 =
* NEW: use racketmanager instead of leaguemanager

= 5.6.22 =
* BUGFIX: user correct team field for checkPlayerResult
* BUGFIX: in championship match only call setteams when round specified
* BUGFIX: reset queryargs when getting matches in tennis.php
* BUGFIX: check action is set in login
* UPDATE: made match modal full width in front end
* UPDATE: reformat lib class code layouts
* UPDATE: remove unused core.php
* UPDATE: remove penalty and overtime checks
* UPDATE: remove -:- as score
* UPDATE: set match score consistently in shortcodes.php
* UPDATE: only show start time if specified
* UPDATE: allow match results for tournaments

= 5.6.21 =
* BUGFIX: make club admin page work
* BUGFIX: handle missing team in results checker
* BUGFIX: use correct tournament->id

= 5.6.20 =
* UPDATE: use CDN to pull in datatables files (js and css)
* UPDATE: tidy up code

= 5.6.19 =
* BUGFIX: remove debug statements
* BUGFIX: print array details in error_log correctly in debug_to_console
* BUGFIX: advance winning team correctly in championship admin
* BUGFIX: remove modal css directs includes in css files
* BUGFIX: handle cache bypass correctly
* BUGFIX: tidy up AJAX functions to display rubbers
* UPDATE: allow match calendar by league, competition and club

= 5.6.18 =
* BUGFIX: remove duplicate heading on competition template
* BUGFIX: remove labels for archive drop-downs
* BUGFIX: format league titles correctly on competition page
* BUGFIX: handle missing league in shortcode
* UPDATE: make league heading <h1> in league archive template
* UPDATE: add page templates correctly
* UPDATE: create category template for rules

= 5.6.17 =
* UPDATE: show latest results on club page

= 5.6.16 =
* UPDATE: show latest results

= 5.6.15 =
* UPDATE: use common function for email send and use HTML format
* UPDATE: add option to mark result checker entries as handled
* UPDATE: sort result checker entries in descending order

= 5.6.14 =
* BUGFIX: sort tournaments by descending name
* BUGFIX: remove shortcode debug statements and extraneous return
* BUGFIX: fix deletion of matches in league
* BUGFIX: add debug logging to console
* UPDATE: tidy up indentions in code

= 5.6.13 =
* BUGFIX: remove debug statement from tennis.php calculatepoints function
* UPDATE: show winners of tournaments

= 5.6.12 =
* BUGFIX: remove link from results checker

= 5.6.11 =
* BUGFIX: ensure result and standings updates use correct season

= 5.6.10 =
* BUGFIX: allow delete of players correctly
* UPDATE: delete duplicate player teams

= 5.6.9 =
* BUGFIX: handle championship proceed to first round team names correctly
* BUGFIX: use final_round instead of final for match object
* BUGFIX: correct team display for championship match edit

= 5.6.8 =
* BUGFIX: do not exit when no matches for daily match check
* BUGFIX: pull details from correct table in getRosterEntry
* BUGFIX: make addTeamCompetition function public
* BUGFIX: use correct path for match.php in admin.php
* BUGFIX: handle no time in match edit
* BUGFIX: allow reset of teamqueryarg

= 5.6.7 =
* UPDATE: do not check results for system records
* BUGFIX: check result on played rounds when in relevant timeframe

= 5.6.6 =
* UPDATE: add tennis specific isTie for ranking

= 5.6.5 =
* UPDATE: change wp_cache_add to wp_cache_add
* UPDATE: remove unused getTable function
* UPDATE: name cache entries properly
* BUGFIX: save custom fields on standingsdata update
* BUGFIX: delete cached data after update

= 5.6.4 =
* BUGFIX: handle missing round in championship admin
* BUGFIX: handle no matches in template tags
* BUGFIX: delete competition cache after season changes
* BUGFIX: ensure team name calculation ignores player teams
* UPDATE: restructure admin area
* UPDATE: add club name to partner on tournament entry form

= 5.6.3 =
* UPDATE: change tournament entry order display
* UPDATE: add documentation for tournaments
* BUGFIX: fix tournament entry season update

= 5.6.2 =
* BUGFIX: stop conflict with elementor plugin
* BUGFIX: fix match card popup error
* BUGFIX: handle popup blocker for matchcard print
* BUGFIX: ensure current match day is correctly set

= 5.6.1 =
* UPDATE: get season from tournament details

= 5.6.0 =
* NEW: allow payers to enter tournaments online
* BUGFIX: fix table import

= 5.5.12 =
* UPDATE: update the way error messages are displayed
* BUGFIX: redirect on login if required for non-admin users

= 5.5.11 =
* UPDATE: change player team template
* UPDATE: add front end form validation
* UPDATE: make ajax frontend call synchronous

= 5.5.10 =
* UPDATE: restrict team selection to competition type
* BUGFIX: handle no primary league set

= 5.5.9 =
* BUGFIX: handle not found correctly in shortcodes

= 5.5.8 =
* NEW: how to documentation for administrators
* UPDATE: make racketmanager section on admin menu
* NEW: add player locked to team check

= 5.5.7 =
* UPDATE: change member account update to highlight specific errors

= 5.5.6 =
* NEW: match result validation for administrators
* UPDATE: automatically generate team name from club and type

= 5.5.5 =
* UPDATE: change profile screen template
* UPDATE: allow password visibility toggle
* UPDATE: use svg icons
* BUGFIX: create player when roster request player_id not set
* BUGFIX: only action incomplete roster requests

= 5.5.4 =
* UPDATE: highlight winner of matches and rubbers
* UPDATE: change admin form layouts
* UPDATE: remove dashboard widget
* UPDATE: allow club updates by match secretary

= 5.5.3 =
* UPDATE: allow match secretaries to update team captain information
* BUGFIX: roster count to check for affiliated club

= 5.5.2 =
* UPDATE: send email to inform administrator of pending roster requests and pending results
* UPDATE: remove UpdateResultsMatch function and replace by admin UpdateResults
* BUGFIX: fix championship match updates from frontend

= 5.5.1 =
* BUGFIX: ensure login pages work with shortcodes
* BUGFIX: make match modal popup work for cups
* UPDATE: make css files versioned

= 5.5.0 =
This is release contains major restructuring on the technical level to improve performance and security.
* NEW: Major restructuring of plugin code
* NEW: mobile responsive admin panel
* NEW: wordpress style template tags system
* NEW: Generalization of sports modules for easier implementation of new sport types
* BUGFIX: league settings issue due to caching

= 5.4.7 =
* UPDATE: allow seasons to be added across competitions

= 5.4.6 =
* UPDATE: use modal popup not thickbox
* UPDATE: use modal popup for user tennis match updates
* BUGFIX: ensure user match updates are reflected in tables

= 5.4.5 =
* BUGFIX: change getMatches to return all results when final parameter not specified
* UPDATE: display drawn matches in player stats if a draw is possible
* UPDATE: make additional points handle half points
* UPDATE: show pending player requests on club page
* UPDATE: allow match secretaries to remove players from their roster
* UPDATE: allow club shortcode as search term
* UPDATE: make add to calendar text white
* UPDATE: show user who removed roster record
* UPDATE: remove stats

= 5.4.4 =
* BUGFIX: fixed match entry to only allow logged in user to access
* UPDATE: show created date in player admin screen
* UPDATE: show splash page when loading/updating match results
* UPDATE: show user created date

= 5.4.3 =
* UPDATE: get daily match day short url working

= 5.4.2 =
* NEW:    add club management
* BUGFIX: pass email address on account form when already entered
* UPDATE: allow matches to be completed in rounds when previous round is not complete

= 5.4.1 =
* NEW:    allow match secretaries to request players to be added to club
* UPDATE: store creation date and created user against roster record

= 5.4.0 =
* UPDATE: make user match update work for cups
* UPDATE: allow rosters to exclude system records (NO PLAYER, WALKOVER, SHARE)
* UPDATE: allow club records to be edited outside of league
* UPDATE: reduce font size on playerstats view
* BUGFIX: fix height of rubber view popup

= 4.1.1 =
* BUGFIX: fixed problem that matches per page was not set correctly with league mysql cache
* BUGFIX: fixed issue with negative offsets for matches

= 4.1 =
* BUGFIX: fixed double logos in widget
* BUGFIX: fixed issue with negtive offset error when multiple leagues are on same page
* BUGFIX: fixed some issues with cache system
* BUGFIX: fixed issue with fatal error on plugin activation
* BUGFIX: fixed error in Fancy Slideshows filter

= 4.0.9 =
* NEW: show standingstable for home/away matches and after each match day
* NEW: functions get_next_matches() and get_last_matches() (functions.php) to get next or last matches for specific team
* NEW: added hebrew translation by Bar Shai
* BUGFIX: fixed match_day option in [matches] shortcode when using multiple shortcodes on same page
* BUGFIX: fixed calculation of apparatus points for gymnastics
* BUGFIX: improved page loading times by caching MySQL query results and prevent unneccessary queries
* BUGFIX: fix in matches pagination
* BUGFIX: several small fixes

= 4.0.8 =
* NEW: options to set various logo sizes and cropping options
* NEW: button to regenerate all thumbnails
* NEW: button to scan for and delete unused logo files
* NEW: set relegation teams for up and down
* NEW: use last5 standings template in championship mode
* UPDATE: some style updates
* BUGFIX: fixed standings table colors in widget
* BUGFIX: fixed soccer team ranking
* BUGFIX: fixed pool team ranking
* BUGFIX: fixed an AJAX issue

= 4.0.7 =
* NEW: show team logos in final matches and final results tree in championship mode including marking home team in bold
* BUGFIX: some additional fixes for championship mode
* BUGIFX: fixed logo size in widget
* BUGFIX: some small fixed to prevent notices in championship mode

= 4.0.6 =
* NEW: new shortcode [league id=ID] to display all contents of a league, i.e. standings, crosstable, matchlist and teamlist with fancy jQuery UI Tabs
* BUGFIX: several fixes for championship mode
* UPDATE: updated some german translations for championship mode

= 4.0.5 =
* NEW: added logos to crosstable
* SECURITY: fixed SQL injection and XSS vulnerabilities reported by islamoc (https://wordpress.org/support/topic/responding-to-security-problems-and-credit). I am pretty sure the SQL injection vulnerability had been already fixed before
* BUGFIX: some style fixes

= 4.0.4 =
* BUGFIX: only automatically calculated final results scores if none are provided by the user
* BUGFIX: fixed problem with Yoast SEO due to loading scriptaculous drag&drop
* BUGFIX: fixed some issues with matches shortcode
* BUGFIF: some style fixes

= 4.0.3 =
* BUGFIX: fixed some issues in matches shortcode

= 4.0.2 =
* NEW: don't automatically calculate results for basketball if a final score is submitted
* BUGFIX: added again logo copying upgrade routine to copy logos to new upload folder structure
* BUGFIX: fixed [leaguearchive league_id=X] bug
* UPDATE: updated French translation

= 4.0.1 =
* BUGFIX: fixed problem with logo upload

= 4.0 =
* NEW: fancy slideshows of matches using the [Fancy Slideshows Plugin](https://wordpress.org/plugins/sponsors-slideshow-widget/)
* NEW: point rule for volleyball giving 3 points for wins and 2 points for 3:2 wins and 1 point for 3:2 loss
* NEW: improved security for data export
* NEW: some new fancy styles using jQuery UI tabs
* NEW: jQuery UI sortable standings table if team ranking is manual
* NEW: accordion styled list of teams
* NEW: updated teams and matches single view with fancy new style
* NEW: documentation on data import file structures
* NEW: gymnastics sport - support for score points of each apparatus and automatic apparatus points and score points calculation upon updating competition results
* BUGFIX: fixed some issues in data import and export
* BUGFIX: fixed some smaller issues
* BUGFIX: fixed a small issue in plugin activation with notifications giving unexpected output
* BUGFIX: fixed activation issue for missing roles
* BUGFIX: fixed issue with team export and import
* BUGFIX: fixed logo URLs upon export/import

= 3.9.8 =
* BUGFIX: fixed an issue with deleting logos used also by other teams

= 3.9.7 =
* BUGFIX: fixed an important issue with editing seasons

= 3.9.6 =
* some fixes

= 3.9.5 =
* NEW: load custom sport files from stylesheet directory in subdirectory sports
* BUGFIX: fixed problem saving match report

= 3.9.4 =
* BUGFIX: fixed an issue with saving match results
* BUGFIX: some small fixes

= 3.9.3 =
* NEW: show multiple leagues on the same page
* NEW: global options to set support news widget options in dashboard
* BUGFIX: limit in matches shortcode
* BUGFIX: get next and previous matches in widget on a scale of minutes instead of 1 day

= 3.9.2 =
* BUGFIX: fixed some poor file location calling
* BUGFIX: fixed TinyMCE window width

= 3.9.1.9 =
* BUGFIX: fixed issue with wrong next match in last-5 standings table

= 3.9.1.8 =
* NEW: matches pagination
* NEW: team filter for matches in admin panel
* BUGFIX: fixed home_only argument in matches shortcode
* BUGFIX: fixed some styling issues

= 3.9.1.7 =
* UPDATED: updated french translation
* BUGFIX: fixed setting getting stuck on user-defined point rule
* BUGFIX: fixed an SQL query error in getMatches()

= 3.9.1.6 =
* BUGFIX: fixed team selection in matches template
* BUGFIX: fixed getting league by name

= 3.9.1.5 =
* SECURITY: major change in retrieving teams (getTeams() in core.php) and matches (getMatches() in core.php) to avoid sql injections
* SECURITY: fixed multiple possible sql injection vulnerabilities
* BUGFIX: add stripslashes
* BUGFIX: correctly load stylesheet and javascript scripts
* BUGFIX: limit the number of matches to add to 50 (to avoid problems with memory limit)
* BUGFIX: fixed some possible security issues
* BUGFIX: fixed several small issues
* BUGFIX: fixed issues with match statistics
* BUGFIX: fixed some small issues with undefined variable notices in different sports

= 3.9.1.4 =
* BUGFIX: ordering of teams by rank

= 3.9.1.3 =
* SECURITY: fixed security issues

= 3.9.1.2 =

= 3.9.1.1 =
* NEW: load custom templates from child themes
* BUGFIX: some fixes in championship mode

= 3.9.1 =
* NEW: new template to show individual racer standings table
* CHANGE: changed fields for racing results (points and time)
* BUGFIX: fixed ajax in widget to navigate through next and last matches
* BUGFIX: fixed bridge to projectmanager for compatibility with latest version
* BUGFIX: several small fixes for racing mode
* BUGFIX: added missing template matches-by_matchday.php to svn repository

= 3.9.0.9 =
* BUGFIX: fixed last-5 standings table to reflect scores of matches with overtime or penalty
* BUGFIX: some small fixes

= 3.9.0.8 =
* BUGFIX: fixed issue with zero scores not displaying in tennis sports
* BUGFIX: several small fixes

= 3.9.0.7 =
* NEW: new match_day values in [matches] shortcode: "next" to show matches of upcoming match day, "last" for last match day, "current" or "latest" for match day closest to current date
* NEW: new template to display matches separated by match day. Use "template=by_matchday" in the shortcode to load template matches-by_matchday.php
* NEW: show logo in matches tables
* NEW: show home team in standings table and home team matches in bold in admin interface
* NEW: added paramters $match->homeScore and $match->awayScore holding the match score depending if game has been finished after regular time, overtime or penalty. This can be used in the templates loaded by the [matches] or [match] shortcodes
* NEW: don't show match day selection if specific match day is selected. Using "next", "last", "current" or "latest" will still show match day selection dropdown
* NEW: new shortcode options for [matches] shortcode: "show_match_day_selection" and "show_team_selection" to force showing or hiding match day or team selection dropdown menus, respectively
* BUGFIX: fixed problem with zeros in matches with empty scores
* BUGFIX: fixed datepicker in match adding/editing page

= 3.9.0.6 =
* BUGFIX: Manual ranking of teams
* BUGFIX: fixed several small bugs
* BUGFIX: AJAX in widget

= 3.9.0.5 =
* BUGFIX: small fixes

= 3.9.0.4 =
* BUGFIX: fix colorpicker in global settings page
* BUGFIX: fix default match day to -1 (all matches) in matches shortcode

= 3.9.0.3 =
* BUGFIX: fix match day issue in shortcode

= 3.9.0.2 =
* BUGFIX: show all matches in admin panel due to problems
* BUGFIX: team edit save button not showing

= 3.9.0.1 =
* UPDATE: show first matches of first match day by default

= 3.9 =
* BUGFIX: fixed TinyMCE for Wordpress >= 3.9 preserving backwards compatibility
* BUGFIX: removed broken sortable standings table
* UPDATE: saving standings manually using POST button
* BUGFIX: setting point-rule
* BUGFIX: fixed several XSS Vulnerabilities
* BUGFIX: fixed match day match editing

= 3.8.9 =
* UPDATE: Numerous files have been worked on to remove PHP Strict Mode warnings. These warnings didn't affect RacketManager use, but if your WordPress installation had debugging mode turned on there were many, many warnings being thrown at you. There are no doubt more that will need to be fixed, but a conservative guess is that over 100 fixes have been applied.
* BUGFIX: Fixed the error with the Widget not changing
* BUGFIX: Permissions error on documenation page
* UPDATE: Added completed games to soccer ranking
* UPDATE: Numerous areas with deprecated code
* UPDATE: Started to get into the sport files to get a consistent look to the output, centering headings over input fields, centering input fields in the space allocated and centering the text in the input fields.
* UPDATE: Started work on the Championship mode, fixed a few none working areas, much work left to do, let me know if you've got suggestions...
* ADDED:  Ability to allow for matches between groups (out of group/division games)
* BUGFIX: Fixed issue with sport files throwing error regarding a not-found function (I hope! I can't duplicate it, let me know if there are still issues)
* UPDATE: Removed all traces of dropdowns for date, replaced with Datepicker
* UPDATE: Fixed a number of areas to keep the user in the same group when adding or updating teams or matches. If you are working in one group and add a team the group knows where you came from and when you click submit you go back to the group you started from (your welcome!)

= 3.8.8.5 =
* BUGFIX: Fix standings numbers
* BUGFIX: Fix widget issues after adding groups
* ADDED:  US Football Sport file

= 3.8.8.4 =
* BUGFIX: Wrong numbers on standing positions

= 3.8.8.3 =
* BUGFIX: Permission error
* UPDATE: Changed some internal code from 'leagues' to 'racketmanager'
* ADDED: Dashboard Widget

= 3.8.8.2 =
* ADDED: Code for Widget to show/hide logos and limit to group
* BUGFIX: "Clas=" in a number of sport files, changed to "Class="
* ADDED: JQuery tooltip for 'Last 5' to show date, score and teams of a game in the standings
* ADDED: Ability to change color of Widget title in 'style.css'
* ADDED: Limited code to set up out of group matches
* Code clean up, removed extra whitespace in a number of files, replaced deprecated _c tag with _x or _e.

= 3.8.8.1 =
* TEST: Test version to add 'Last 5' function to standings. Only update to this version if you're willing to test.
use this shortcode to test:
[standings league_id=1 template=last5] or
[standings league_id=1 group=A template=last5 logo=true]
(group and logo are optional)

If you test and find that the icons at the end of each line in the standings are moving to a second line it means you don't have enough room on your template for five past results. You can then change to a lesser number in the template, named 'standings-last5.php' in the 'admin/templates' folder. Go to 43:

    <th width="100" class="last5"><?php _e( 'Last 5', 'racketmanager' ) ?></th>

change the 'Last 5' text to 'Last 3' if you're going to use three past results, or whatever you choose. Then go to line 93:

    $results = get_latest_results($team->id, 5);

Change the '5' at the end to '3' if you want three past results.

The final version will probably have this as a preference option.

= 3.8.8 =
* BUGFIX: add matches in championship mode not working.

= 3.8.7 =
* BUGFIX: various
* ADDED: Shortcode additions for: option of using website link on standings, standings and crosstables by group.
* ADDED: when adding a team from db, bring the stadium info into the form with the rest of the information.

= 3.8.6 =
* BUGFIX: standings

= 3.8.5 =
*** IF YOU'VE DONE ANY MANUAL MODIFICATIONS, DOWNLOAD THIS AND CHECK THAT YOU AREN'T GOING TO LOSE THEM WHEN YOU UPDATE (INSTEAD OF DOING AN AUTO UPDATE). THIS UPDATE TOUCHES A NUMBER OF FILES (17). IF YOU HAVE QUESTIONS BEFORE UPDATING, LEAVE A MESSAGE ON THE SUPPORT FORUM ON WORDPRESS.ORG. A LIST OF ALL FILES UPDATED IS LISTED IN A POST THERE. ***

http://wordpress.org/support/topic/racketmanager-385-changes-info

* CHANGED: 'championchip' to 'championship' throughout the plugin
* BUGFIX: fixed missing '>' in core.php that was causing white screen after adding or editing matches.
* BUGFIX: fixed date format in widget.php so date shows.

= 3.8.4 =
* BUGFIX: export function

= 3.8.3 =
* BUGFIX: export function

= 3.8.2 =
* BUGFIX: Undefined function in racketmanager.php upon export

= 3.8.1 =
* BUGFIX: Fixed security vulnerability of SQL Injection. Added security check current_user_can('manage_leagues') and cast $_POST['league_id'] as (int)

= 3.8 =
* BUGFIX: Fixed reported XSS Vulnerabilities

= 3.7 =
* BUGFIX: decimals for add points field

= 3.6.9 =
* BUGFIX: upgrade process

= 3.6.8 =
* BUGFIX: Language
* BUGFIX: Team names with ' or similar

= 3.6.7 =
* BUGFIX: upgrade

= 3.6.6 =
* BUGFIX: changed DATEDIFF to TIMEDIFF in lib/widget.php
* BUGFIX: season update. also update teams and matches

= 3.6.5 =
* NEW: allow half points in match scores
* CHANGED: score after penalty is calculated by the plugin as "penalty score + overtime score"

= 3.6.4 =
* NEW: user defined point rule with win/loose overtime points. only works with certain sport types
* BUGFIX: team ranking for pool first by points
* BUGFIX: javascript problems

= 3.6.3 =
* CHANGED: change database field for team points to float to support half points
* BUGFIX: user defined point rule

= 3.6.2 =
* NEW: Score Point-Rule. Teams get one point according to the game score
* BUGFIX: only load javascript files on racketmanager pages to avoid malfunction of WP image editor
* BUGFIX: Widget option

= 3.6.1 =
* NEW: don't remove logo if other teams are using the same one
* CHANGED: sort teams in alphabetical order in match list on frontend
* BUGFIX: problem of displaying matches on same date
* BUGFIX: drag & drop sorting of teams

= 3.6 =
* NEW: documentation
* NEW: add stadium for teams and automatically add location for matches when choosing team
* NEW: Arabian translation
* CHANGED: add 15 matches at once independent of team number
* BUGFIX: Link to match report in widget
* BUGFIX: Championship advancement to finals
* UPDATED: French translation

= 3.5.6 =
* NEW: limit number of matches in shortcode [matches] with limit=X

= 3.5.5 =
* CHANGED: use first group if none is selected to add matches in championship preliminary rounds

= 3.5.4 =
* BUGFIX: stripslashes for team name to allow ' and "

= 3.5.3 =
* UPDATED: swedish translation

= 3.5.2 =
* BUGFIX: last match on single team page was not correct

= 3.5.1 =
* NEW: css class "relegation" for teams that need to go into relegation
* NEW: settings for number of teams that ascend, descend or need to go into relegation
* NEW: set background colors for teams that ascend, descend or need to go into relegation
* BUGFIX: row colors for ascending/descending teams

= 3.5 =
* NEW: cut down standings to home teams with surrounding teams. Attribute home=X where X is an integer controlling the number of surrounding teams up and down
* BUGFIX: teams tied only when they have same points, point difference and goals
* BUGFIX: championship mode
* NEW: css class "ascend" and "descend" for first and last two teams. class "homeTeam" for home team row. Table rows (tr)
* CHANGED: ranking of teams in soccer by points, goal difference and shot goals

= 3.4.2 =
* BUGFIX: crosstable popup
* BUGFIX: improved time attribute for matches shortcode
* BUGFIX: crosstable with home and away match

= 3.4.1 =
* BUGFIX: team website in next match box of widget
* BUGFIX: get matches of current match day in matches shortcode

= 3.4 =
* NEW: shortcode attribute 'match_day' for matches
* NEW: shortcode attribute 'group' for matches
* NEW: shortcode attribute 'time' ("prev" or "next") for matches to display upcoming or past matches
* NEW: shortcode attribute 'group' for standings
* BUGFIX: widget AJAX match navigation
* BUGFIX: scores with 0 possible in Rugby

= 3.4-RC3 =
* NEW: template tags for next and previous match boxes of widget
* UPDATED: template tag for single team to display individual team member information
* BUGFIX: match scrambling
* BUGFIX: ranking in soccer
* BUGFIX: plus/minus points affects ranking (reload of page necessary)
* BUGFIX: widget prev match does not show latest match

= 3.4-RC2 =
* NEW: improved administration of championship
* NEW: template tag for championship
* NEW: updated championship template and archive template
* NEW: display team roster if present on team info page (requires ProjectManager 2.8+)
* CHANGED: Widget design upgrade
* CHANGED: single match template layout
* CHANGED: updated template tags


* NEW: group teams and individual ranking in groups
* NEW: full championship mode
* NEW: mach with unspecific date N/A
* NEW: Widget with 2.8 API

= 3.3.1 =
* BUGFIX: empty query when adding League
* BUGFIX: 0-0 score if game not played changed to -:-

= 3.3 =
* NEW: double matches for tennis with individual standings

= 3.2.2 =
* BUGIFX: parse error

= 3.2.1 =
* BUGFIX: no default value for longtext fields

= 3.2 =
* NEW: options to display played, won, tie and lost games in standings table
* BUGIFX: Tennis scoring

= 3.2-RC1 =
* NEW: Tennis, Rugby and Volleyball Rules and Scoring
* NEW: set logo upload directory
* NEW: set default start time for matches
* BUGFIX: chmod of logos

= 3.1.9 =
* BUGFIX: spacer between teams in widget

= 3.1.8 =
* BUGFIX: widget match JQuery Navigation

= 3.1.7 =
* I hate bugfixing

= 3.1.6 =
* BUGFIX: team logos

= 3.1.5 =
* BUGFIX: fixed permission for upload directory

= 3.1.4 =
* BUGFIX: match stats and results saving data loss (IMPORTANT)

= 3.1.3 =
* BUGFIX: add teams from previous season with season as string
* BUGIFIX: export matches
* BUGFIX: create new thumbnails upon upgrade

= 3.1.2 =
* BUGFIX: load Thickbox stylesheet
* BUGFIX: edit of match day
* BUGFIX: created new thumbnail

= 3.1.1 =
* NEW: add Logo from url (for WPMU)
* BUGFIX: call-time pass-by-reference deprecated
* BUGFIX: match import

= 3.1 =
* NEW: supercool dynamic match statistics
* NEW: edit season
* BUGFIX: match days in frontend

= 3.0.4 =
* CHANGED: moved AJAX functions to own class
* BUGFIX: shortcode display with season as string, e.g. 08/09
* BUGFIX: Team Roster

= 3.0.3 =
* BUGFIX: archive template
* BUGFIX: racketmanager_matches function
* BUGFIX: team display in matches template

= 3.0.2 =
* CHANGED: static function for display

= 3.0.1 =
* BUGIFX: database table creation

= 3.0 =
* NEW: Team Roster for each team if ProjectManager is installed
* NEW: Basic support for racing
* NEW: standings actions in Frontend templates
* CHANGED: restructured settings in one database longtext field
* BUGFIX: crosstable score

= 2.9.3 =
* BUGFIX: match days in matches shortcode

= 2.9.2 =
* NEW: upgrade page to set seasons for teams and matches
* BUGFIX: Add old teams upon adding of new season
* BUGFIX: match edit
* BUGFIX: matches display shortcode

= 2.9.1 =
* NEW: added games behind for baseball
* NEW: TinyMCE Button for Teamlist and Team page
* NEW: AJAX adding team from database
* BUGFIX: display of goals, ap etc.
* BUGFIX: added hidden fields to team edit page where necessary to avoid loss of data
* BUGFIX: unsetting of widget options if deleted
* BUGFIX: TinyMCE Button

= 2.9 =
* NEW: modular setup of plugin
* NEW: actions and filters for specific sport types
* NEW: shortcodes to display list of teams and team info
* NEW: three drop-down menus for leagues, seasons and matches on post page
* NEW: track status of team ranking compared to last standing
* NEW: several new sports
* NEW: Match Statistics with Team Roster from ProjectManager
* CHANGED: changed shortcodes, deleted convert function

= 2.9-RC2 =
* BUGFIX: adding matches with seasons like 2008/2009

= 2.9-RC1 =
* NEW: seasons support
* NEW: League Archive and single match view

= 2.8 =
* NEW: add Team data from database
* NEW: Option to insert standings manually on admin page
* NEW: import and export of teams/matches (experimental)
* NEW: option to manually save standings in admin panel
* NEW: manually rank teams via drag & drop if needed
* NEW: field to add/subtract points (useful, e.g. for Rugby)
* NEW: option to show/hide logos in match widget
* BUGFIX: display of next match in widget
* BUGFIX: no update of diff if saving standings manually
* CHANGED: Update logo name in database if image already exists on server
* CHANGED: included updated dutch translation
* CHANGED: added some descriptions to translation

= 2.7.1 =
* BUGFIX: plugin installation missed `coach` field for teams

= 2.7 =
* NEW: predefined point rules
* NEW: support for Hockey and Basketball leagues to insert points of thirds and quarters respectively
* NEW: set point format
* NEW: short documentation on league types and point rules
* NEW: add website and coach for each team
* NEW: remove logo directory upon plugin uninstallation
* NEW: global option to set language file
* NEW: add separate results for overtime and penalty
* NEW: template system
* BUGFIX: Logo upload and thumbnail creation
* BUGFIX: upgrade
* CHANGED: New Widget with jQuery Sliding of matches
* CHANGED: simplified frontend templates

= 2.6.3 =
* BUGFIX: database upgrade

= 2.6.2 =
* BUGFIX: database upgrade

= 2.6.1 =
* BUGFIX: TinyMCE Button
* BUGFIG: PHP4 compatibility
* CHANGED: don't show match day drop-down if number of match days is 0
* CHANGED: warning message if number of match days is 0

= 2.6 =
* NEW: nicer upgrade method
* NEW: enter halftime results for ballgame leagues
* NEW: meta box on post writing screen to write match reports
* NEW: insert standings manually with simple constant switch
* NEW: templates for each shortcode to make customization easy
* CHANGED: major restructuring of plugin structure
* CHANGED: using shortcodes from Wordpress API
* CHANGED: new icon for menu and TinyMCE Button

= 2.5.2 =
* BUGFIX: match display in widget

= 2.5.1 =
* NEW: separate Date and Time Format for widget
* NEW: display of match start time in widget

= 2.5 =
* NEW: weekly based match ordering
* NEW: bulk editing of weekly matches
* NEW: date based grouping of matches in widget
* BUGFIX: crosstable popup
* CHANGED: css styling
* CHANGED: moved logo directory to wp-content/uploads
* REMOVED: match display of specific dates

= 2.4.1 =
* BUGFIX: database bug

= 2.4 =
* NEW: logo support
* NEW: change color scheme for frontend tables via admin interface
* NEW: display of matches for specific dates
* NEW: dividers in standings table

= 2.3.1 =
* BUGFIX: database collation

= 2.3 =
* NEW: optional display of crosstable in popup window

= 2.2 =
* NEW: implemented crosstable for easy overview of all match results
* NEW: TinyMCE Button
* BUGFIX: secondary ranking of teams by goal difference if not gymnastics league
* CHANGED: css styling

= 2.1 =
* NEW: adding of up to 15 matches simultaneously for one date
* NEW: using date and time formats from Wordpress settings
* BUGFIX: results determination if score was 0:0

= 2.0 =
* NEW: automatic point calculation
* REMOVED: dynamic table columns

= 1.5 =
* NEW: design standings table display in widget

= 1.4.2 =
* BUGFIX: check_admin_referer for WP 2.3.x

= 1.4.1 =
* BUGFIX: saving of standings table

= 1.4 =
* NEW: wp_nonce_field for higher security
* NEW: separate capability to control access
* BUGFIX: some minor bugfixes

= 1.3 =
* NEW: activation/deactivation switch
* NEW: widget for every active league
* NEW: use of short title for widget

= 1.2.2 =
* BUGFIX: Javascript for adding table columns

= 1.2.1 =
* BUGFIX: database creation

= 1.2 =
* BUGFIX: teams sorting in widget
* CHANGED: load javascript only on Racketmanager admin pages
* CHANGED: remodeling of the plugin structure

= 1.1 =
* NEW: deletion of multiple leagues, teams or competitions
* NEW: display widget statically
* NEW: uninstallation method
* BUGFIX: table structure settings and deleting leagues, teams or competitions

= 1.0 =
* initial release
