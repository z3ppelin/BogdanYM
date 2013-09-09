<?php
/**
 * Index route.
 * 
 * @author      Bogdan Constantinescu <bog_con@yahoo.com>
 * @since       2013.08.01
 * @version     1.0
 * @link        GitHub  https://github.com/z3ppelin/BogdanYM.git
 * @licence     The BSD License (http://opensource.org/licenses/BSD-3-Clause); see LICENCE.txt
 */
?>
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Bogdan YM</title>
        <link rel="stylesheet" href="js/jQuery/themes/base/minified/jquery-ui.min.css" />
        <link rel="stylesheet" href="css/bogdanym.css" />
        <script type="text/javascript" src="js/jQuery/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/jQuery/ui/minified/jquery-ui.min.js"></script>    
        <script type="text/javascript" src="js/bogdanym.js"></script>    
    </head>
    <body>
        <!-- login form -->
        <div id="login-form" title="Yahoo Messenger" class="login-form">
            <img src="images/ym_logo.png" alt="" width="140" height="140" /><br />
            <form>
                <fieldset>
                    <label for="username">Username: *</label><br />
                    <input type="text" name="username" id="username" value=""  /><br /><br />
                    <label for="password">Password: *</label><br />
                    <input type="password" name="password" id="password" value="" /><br /><br />
                    <input type="checkbox" name="remember-me" id="remember-me" checked="checked" />
                    <label for="remember-me">Remember my ID</label><br />
                    <input type="checkbox" name="invisible" id="invisible" checked="checked" />
                    <label for="invisible">Sign in as invisible to everyone</label><br />
                </fieldset>
            </form>
        </div>

        <!-- yahoo messenger contact lists -->
        <div id="ymessenger" class="ymessenger ui-corner-all">
            <h3 class="ui-widget-header ui-corner-all">Yahoo Messenger</h3>
            <div id="info" class="info">
                <img id="myavatar" class="myavatar" src="http://cdn.arstechnica.net/wp-content/uploads/2012/10/06_Place_20773_1_Mis.jpg" alt="" width="50" height="50" />
                <select name="state-select" id="state-select" class="state-select">
                    <option class="online-option" value="online">Available</option>
                    <option class="busy-option" value="busy">Busy</option>
                    <option class="invisible-option" value="invisible">Invisible</option>
                    <option class="idle-option" value="idle">Away</option>
                </select>
                <br />
                <input type="text" name="state-message" value="Enter status message here..." id="state-message" class="state-message" />

                <br style="clear: both;" />
            </div>

            <ul class="contacts-list" id="contacts-list">
<!--<li class="groupname">YM-Friends</li>
                <li class="ygroup">
                    <ul>
                        <li class="ycontact online" id="gigel">
                            <img class="statusimg" src="images/online.png" alt="" />
                            <span class="text">
                                <span class="name">Gigel_dasdas_dasd_as_das_das_das_das</span><br />
                                <span class="statusmessage" title="I' m online :P das as das das das das das das dasd as da dsffsdfsd fsdf sd fsdf sds">I' m online :P das as das das das das das das dasd as da dsffsdfsd fsdf sd fsdf sds</span>
                            </span>
                            <img  class="avatar" src="http://cdn.arstechnica.net/wp-content/uploads/2012/10/06_Place_20773_1_Mis.jpg" width="32" height="32" />
                        </li>

                        <li class="ycontact online" id="purcel">
                            <img class="statusimg" src="images/busy.png" alt="" />
                            <span class="text">
                                <span class="name">Purcel</span>
                                <span class="statusmessage">Busy</span>
                            </span>
                            <img  class="avatar" src="http://cdn.arstechnica.net/wp-content/uploads/2012/10/06_Place_20773_1_Mis.jpg" width="32" height="32" />
                        </li>
                    </ul>
                </li>-->
            </ul>
            <br style="clear: both;">
            <div class="buttons">
                <button type="button" onclick="logOut()">Sign Out</button>
            </div>
            <div id="notifications" class="notifications"></div>
        </div>

        <!-- chat window -->
        <div id="chatwindow" class="chatwindow">
            <span class='ui-icon ui-window-close' role='presentation'>Close window</span>
            <ul></ul>
        </div>
        <div id="sampleChatWindow" class="sampleChatWindow">
            <ul>
                <li id="sampleTabHead"><a href=""></a><span class='ui-icon ui-tab-close' role='presentation'>Remove Tab</span></li>
            </ul>    
            <div id="sampleTabContent">
                <div class="messagesContainer"></div>
                <br />
                <input type="text" class="writeMessage" maxlength="2000" />
            </div>
        </div>
        
        <!-- footer -->
        <p id="footer" class="footer">
            &COPY; Copyright 2013 <?php echo '2013' != date('Y') ? ' - ' . date('Y') : ''; ?> Bogdan Constantinescu
        </p>
    </body>
</html>