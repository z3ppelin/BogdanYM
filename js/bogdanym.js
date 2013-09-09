/**
 * JS File for Bogdan YM.
 * 
 * @author      Bogdan Constantinescu <bog_con@yahoo.com>
 * @since       2013.08.01
 * @version     1.0
 * @link        GitHub  https://github.com/z3ppelin/BogdanYM.git
 * @licence     The BSD License (http://opensource.org/licenses/BSD-3-Clause); see LICENCE.txt
 */
"use strict";

var intNotificationTimeout = 0;
var intNotificationInterval = 0;

jQuery(document).ready(function() {
    /* dialog-ize login form */
    jQuery('#login-form').dialog({
        autoOpen: false,
        height: 500,
        width: 250,
        modal: false,
        closeOnEscape: false,
        buttons:  {
            signin: {
                text: 'Sign In',
                id: 'signInBtn',
                click: function() {
                    logIn();
                }         
            }
        },
        open: function(event, ui) {
            jQuery(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').hide();
        }
    });
    jQuery('#login-form').dialog('open');
    
    /* make ymessenger draggable and resizable */
    jQuery('#ymessenger').draggable({
        containment: 'window', 
        scroll: false
    });
    jQuery('#ymessenger').resizable({
        minWidth: 250,
        minHeight: 500,
        resize: function(event, ui) {
            var intHeightDiff = ui.size.height - ui.originalSize.height;
            if (intHeightDiff != 0) {
                jQuery('#contacts-list').css('height', jQuery('#contacts-list').data('heightOnStartResize') + intHeightDiff + 'px');
            }
        },
        start: function(event, ui) {
            jQuery('#contacts-list').data('heightOnStartResize', jQuery('#contacts-list').height());
        }
    });
    
    /* chat window close action */            
    jQuery('span.ui-window-close').bind('click', function() {
        jQuery('#chatwindow ul li').each(function(){
            var strPanelId = jQuery(this).remove().attr('aria-controls');
            jQuery('div[id="' + strPanelId + '"]').remove();
        });
        jQuery('#chatwindow').hide();
    });
    
    /* chat window tab close action */
    jQuery('#chatwindow').delegate('span.ui-tab-close', 'click', function() {
        var strPanelId = jQuery(this).closest('li').remove().attr('aria-controls');
        jQuery('div[id="' + strPanelId + '"]').remove();
        jQuery('#chatwindow').tabs('refresh');
        if (!jQuery('#chatwindow ul li').length) {
            jQuery('#chatwindow').hide();
        }
    });
    
    /* send message */
    jQuery('#chatwindow').delegate('.writeMessage', 'keypress', function(event) {
        var intCode = (event.keyCode ? event.keyCode : event.which);
        if (13 === intCode) { // user pressed "Enter"
            var strTargetId = jQuery(this).parent('div').attr('id').replace('tab_content_', '');
            var strMessage  = jQuery.trim(jQuery(this).val());
            if (strTargetId.length > 0 && strMessage.length > 0) {
                sendMessage(strTargetId, strMessage);
                var objMessengesContainer = jQuery(this).parent('div').find('.messagesContainer');
                objMessengesContainer.html(
                    objMessengesContainer.html() +
                    '(' + getCurrentDate() + ') ' +
                    '<strong>Me:</strong> ' + strMessage + '<br />'
                    );
                objMessengesContainer.prop('scrollTop', objMessengesContainer.prop('scrollHeight'));      
                jQuery(this).val('');    
            }
        }  
    });
    
    /* sign in user on enter keypress in login form */
    jQuery("#username, #password").bind('keypress', function(event) {
        var intCode = (event.keyCode ? event.keyCode : event.which);
        if(13 === intCode) { // user pressed "Enter"
            logIn();
        }
    });
    
    /* make chat window tab-ble and draggable */
    jQuery('#chatwindow').tabs();
    jQuery('#chatwindow').draggable({
        containment: 'window', 
        scroll: false
    });
    
    /* show chat window on dbl click on a buddy */
    jQuery('#ymessenger').delegate('.ycontact', 'dblclick', function() {
        var strContactId = jQuery(this).attr('id');
        if (jQuery('li[id="tab_' + strContactId + '"]').length < 1) {
            var strContactName = jQuery(this).find('.name').text();
            var objTabHead = jQuery('#sampleTabHead').clone();
            var objTabContent = jQuery('#sampleTabContent').clone();
            objTabHead.attr('id', 'tab_' + strContactId);
            objTabHead.find('a').attr('href', '#tab_content_' + strContactId).text(strContactName);
            objTabContent.attr('id', 'tab_content_' + strContactId);
            jQuery('#chatwindow').append(objTabContent);
            jQuery('#chatwindow ul').append(objTabHead);
            jQuery('#chatwindow').tabs('refresh');
            
            if (jQuery('li[id="' + strContactId + '"]').hasClass('offline')) {
                var objMessengesContainer = objTabContent.find('.messagesContainer');
                objMessengesContainer.html(
                    objMessengesContainer.html() +
                    '(' + getCurrentDate() + ') ' +
                    '<i style="color: #333">' + strContactName + ' appears to be offline.</i>' + '<br />'
                    );
                objMessengesContainer.prop('scrollTop', objMessengesContainer.prop('scrollHeight'));
            }
        }
        
        if (!jQuery('li[id="tab_' + strContactId + '"]').data('doNotSetTabAsActive')) { // doNotSetTabAsActive is not set
            var intIndex = jQuery("#chatwindow ul li").index(jQuery('li[id="tab_' + strContactId + '"]'));
            jQuery('#chatwindow').tabs('option', 'active', intIndex);
        }
        
        if (!jQuery('#chatwindow').is(':visible')) {
            jQuery('#chatwindow').show();
        }
    });
    
    /* toogle buddies on click on a group */
    jQuery('#ymessenger').delegate('.groupname', 'click', function() {
        if (jQuery(this).next().is('.ygroup')) {
            jQuery(this).next().slideToggle();
        }
    });
    
    /* status changes */
    jQuery('#state-select').change(function(){
        changeStatus();
    });
    jQuery('#state-message').focus(function(event) { 
        if ('Enter status message here...' === jQuery(this).val()) {
            jQuery(this).val('');
        }
    }).blur(function(event) {
        if ('' === jQuery.trim(jQuery(this).val())) {
            jQuery(this).val('Enter status message here...');
        } 
        changeStatus(); 
    }).keypress(function(event) {
        var intCode = (event.keyCode ? event.keyCode : event.which);
        if(13 === intCode) { // user pressed "Enter"
            jQuery(this).blur();
        }
    });
    
    /* stop tab blink animation */
    jQuery('#chatwindow').delegate('li[id^="tab_"]', 'click', function(event) {
        jQuery(this).find('a').stop(true).css('background-color', '#FFF');
    });
    jQuery('#chatwindow').delegate('div[id^="tab_content_"]', 'mouseenter', function(event) {
        jQuery('a[href="#' + jQuery(this).attr('id') + '"]').stop(true).css('background-color', '#FFF');
    });
});
                     
                
                
/**
 * Logs out a user.  
 */     
function logOut()
{
    jQuery.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: {
            'ajax_action': 'logout'
        },
        dataType: 'json',
        success: function (objResponse) {
            if ('success' === objResponse.status) {
                jQuery('#ymessenger').hide(); // hide yahoo messenger window and reopen login window
                jQuery('#login-form').dialog('open');
                jQuery('#contacts-list').html(); // reset buddies list
                jQuery('span.ui-window-close').click(); // close chat window
                jQuery(document).unbind('keydown'); // cancel CTR + G event
                jQuery(window).unbind('beforeunload'); // cancel browser close confirmation
                /* stop getting notifications */
                if (intNotificationTimeout != 0) {
                    clearTimeout(intNotificationTimeout);
                    intNotificationTimeout = 0;
                }                
                if (intNotificationInterval != 0) {
                    clearInterval(intNotificationInterval);
                    intNotificationInterval = 0;
                }
            } else {
                alert(objResponse.response);
            }
        },
        error: function () {
            alert('Could not log out.');
        }
    });
}
   
   

/**
 * Logs in a user.
 */
function logIn()
{
    /* validate form */
    var blnReturnValue = false;
    var blnFormValid   = true;
    var objUsername    = jQuery('#username');
    var objPassword    = jQuery('#password');
    var intRememberMe  = jQuery('#remember-me').attr('checked') === 'checked' ? 1 : 0;
    var intInvisible   = jQuery('#invisible').attr('checked') === 'checked' ? 1 : 0;

    objUsername.removeClass('ui-state-error');
    objPassword.removeClass('ui-state-error');
    jQuery('span.error').remove();

    if (objUsername.val().length < 3 || objUsername.val().length > 97) {
        showError(objUsername, 'Invalid username.');
        blnFormValid = false;
    }
    if (objPassword.val().length < 3 || objPassword.val().length > 32) {
        showError(objPassword, 'Invalid password.');
        blnFormValid = false;
    }
    if (/.*@.*@.*/.test(objUsername.val())) {
        showError(strUsername, 'Invalid username.');
        blnFormValid = false;
    }

    if (!blnFormValid) {
        return;
    }
    /* disable Sign In button and show ajax loader */
    jQuery('#signInBtn').attr('disabled', 'disabled');
    jQuery('#signInBtn').before('<img id="signInAjaxLoader" class="signInAjaxLoader" src="images/ajax-loader.gif" width="16" height="16" alt="" />');
    
    jQuery.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: {
            'ajax_action': 'login',
            'username': objUsername.val(),
            'password': objPassword.val(),
            'remember_me': intRememberMe,
            'invisible': intInvisible
        },
        dataType: 'json',
        success: function (objResponse) {
            if ('success' === objResponse.status) {
                jQuery('#login-form').dialog('close');
                jQuery('#ymessenger').fadeIn(2000);
                
                if (!intRememberMe) {
                    objUsername.val('');
                    objPassword.val('');
                }
                
                populateContacts(objResponse.response['contacts']);
                
                /* show user 's avatar */
                jQuery('#myavatar').attr('src', objResponse.response['user_avatar']);
                
                /* add extra italic class if logged in as Invisible */
                if (intInvisible) {
                    jQuery('#contacts-list').addClass('italic');
                    jQuery('#state-select').val('invisible');
                } else {
                    jQuery('#state-select').val('online');
                }
                
                /* show/hide offline buddies on CTRL + G key combination */
                jQuery(document).bind('keydown', function(event) {
                    if (event.ctrlKey && 71 === event.keyCode) {
                        if (!jQuery('li.offline').is(':visible')) {
                            jQuery('li.offline').show('fast');
                        } else {
                            jQuery('li.offline').hide('fast');
                        }
                    }
                });
                
                /* start getting notifications after a while, wait a litlle for avatars to get load */
                intNotificationTimeout = setTimeout(function() {
                    intNotificationInterval = setInterval(function() {
                        getNotifications();
                    }, 7000);
                }, 25000);
                
                /* log out the user on browser close */
                jQuery(window).bind('beforeunload', function() {
                    if (jQuery('#ymessenger').is(':visible')) { // user is logged in
                        if (confirm("Are you sure you want to sign off and quit the application?")) {
                            logOut();
                            return true;
                        }
                        return false;
                    }
                    return true;
                });
            } else {
                alert(objResponse.response);
            }
        },
        error: function() {
            alert("Could not log in.");
        },
        complete: function() {
            /* enable Sign In button and hide ajax loader */
            jQuery('#signInBtn').removeAttr('disabled');
            jQuery('#signInAjaxLoader').remove();
        }
    });
}



/**
 * Populate contacts list.
 * @param   arrGroups   array   The array with groups and contacts info.
 */            
function populateContacts(arrGroups)
{
    var strContactsList = '';
    var strGroupName, intContactKey;
    for (strGroupName in arrGroups) {
        var strContactsHtml = '';
        var intGroupOfflineContactsCount = 0;
        for (intContactKey in arrGroups[strGroupName]) {
            strContactsHtml += '<li class="ycontact ' + arrGroups[strGroupName][intContactKey]['state'] + '" id="' + arrGroups[strGroupName][intContactKey]['id'] + '">';
            strContactsHtml += '    <img class="statusimg" src="images/' + arrGroups[strGroupName][intContactKey]['state'] + '.png" alt="" />';
            strContactsHtml += '    <span class="text">';
            strContactsHtml += '       <span class="name" title="' + arrGroups[strGroupName][intContactKey]['name'] + '">' + arrGroups[strGroupName][intContactKey]['name'] + '</span><br />';
            strContactsHtml += '       <span class="statusmessage" title="' + arrGroups[strGroupName][intContactKey]['status'] + '">' + arrGroups[strGroupName][intContactKey]['status'] + '</span>';
            strContactsHtml += '    </span>';
            strContactsHtml += '    <img class="avatar" src="" alt="" width="32" height="32" />';
            strContactsHtml += '</li>';          
            intGroupOfflineContactsCount += 'offline' === arrGroups[strGroupName][intContactKey]['state'] ? 1 : 0;
        }
        strContactsList += '<li class="groupname">' + strGroupName + ' (<span>' + (arrGroups[strGroupName].length - intGroupOfflineContactsCount) + '</span>/' + arrGroups[strGroupName].length + ')</li>';
        strContactsList += '<li class="ygroup">';
        if ('' !== strContactsHtml) {
            strContactsList += '    <ul>' + strContactsHtml + '</ul>';
        }
        strContactsList += '</li>';
    }
    jQuery('#contacts-list').html(strContactsList);
    
    /* get avatar for each buddy */
    for (strGroupName in arrGroups) {
        for (intContactKey in arrGroups[strGroupName]) {
            getAvatar(arrGroups[strGroupName][intContactKey]['id']);
        }
    }
}



/**
 * Fetch a user 's avatar.
 * @param   strContactId    string  User 's id.
 */
function getAvatar(strContactId)
{
    jQuery.ajax({
        type: 'GET',
        url: 'ajax.php',
        data: {
            'ajax_action': 'get_avatar',
            'user_id': strContactId
        },
        dataType: 'json',
        success: function (objResponse) {
            if ('success' === objResponse.status) {
                jQuery('li[id="' + objResponse.response['user_id'] + '"]').find('.avatar').attr('src', objResponse.response['avatar_url']);
            }
        }
    });
}



/**
 * Retrieve notidications from yahoo servers.            
 */            
function getNotifications()
{
    jQuery.ajax({
        type: 'GET',
        url: 'ajax.php',
        data: {
            'ajax_action': 'get_notifications'
        },
        dataType: 'json',
        success: function (objResponse) {
            if ('success' === objResponse.status) {
                for (var intKey in objResponse.response) {
                    switch (objResponse.response[intKey]['type']) {
                        case 'logOff':
                            var objContact = jQuery('li[id="' + objResponse.response[intKey]['info']['buddy'] + '"]');
                            if (objContact.length) {
                                /* update presence state, presence message */
                                objContact.removeClass('idle')
                                .removeClass('online')
                                .removeClass('busy')
                                .addClass('offline');
                                objContact.find('.statusimg').attr('src', 'images/offline.png');
                                objContact.find('.statusmessage').text('');
                                
                                /* show notification on the buttom of ym window */
                                var objNotif = jQuery('<span>').addClass('notif');
                                objNotif.text(objContact.find('.name').text() + ' is offline.');
                                jQuery('#notifications').prepend('<br />');
                                jQuery('#notifications').prepend(objNotif);
                                objNotif.fadeOut(5000, function() {
                                    jQuery(this).next('br').remove();
                                });
                                
                                if (jQuery('li[id="tab_' + objResponse.response[intKey]['info']['buddy'] + '"]').length > 0) {
                                    /* add message "X has signed out" on chat window */
                                    var objMessengesContainer = jQuery('div[id="tab_content_' + objResponse.response[intKey]['info']['buddy'] + '"]').find('.messagesContainer');
                                    objMessengesContainer.html(
                                        objMessengesContainer.html() +
                                        '(' + getCurrentDate() + ') ' +
                                        '<i style="color: #333">' + objContact.find('.name').text() + ' has signed out.</i>' + '<br />'
                                        );
                                    objMessengesContainer.prop('scrollTop', objMessengesContainer.prop('scrollHeight'));
                                }
                                
                                /* decrement group online users count */
                                var onlineCount = parseInt(objContact.parents('li').first().prev('.groupname').first().find('span').first().text());
                                if (!isNaN(onlineCount) && onlineCount > 0) {
                                    objContact.parents('li').first().prev('.groupname').first().find('span').first().text(--onlineCount);
                                }
                            }
                            break;                
                        case 'buddyInfo':
                        case 'buddyStatus':
                            var objContact = jQuery('li[id="' + objResponse.response[intKey]['info']['buddy'] + '"]');
                            if (objContact.length) {
                                /* update presence state, presence message */
                                var oldState = 'online';
                                if (objContact.hasClass('idle')) {
                                    oldState = 'idle';
                                } else if (objContact.hasClass('busy')) {
                                    oldState = 'busy';
                                } else if (objContact.hasClass('offline')) {
                                    oldState = 'offline';
                                } else if (objContact.hasClass('online')) {
                                    oldState = 'online';
                                }
                                
                                objContact.removeClass(oldState)
                                .addClass(objResponse.response[intKey]['info']['presenceState']);
                                objContact.find('.statusimg').attr('src', 'images/' + objResponse.response[intKey]['info']['presenceState'] + '.png');
                                objContact.find('.statusmessage').text(objResponse.response[intKey]['info']['presenceMessage']);
                                
                                if ('offline' === oldState && 'offline' !== objResponse.response[intKey]['info']['presenceState']) { // user is online
                                    /* show notification on the buttom of ym window */
                                    var objNotif = jQuery('<span>').addClass('notif');
                                    objNotif.text(objContact.find('.name').text() + ' is online.');
                                    jQuery('#notifications').prepend('<br />');
                                    jQuery('#notifications').prepend(objNotif);
                                    objNotif.fadeOut(5000, function() {
                                        jQuery(this).next('br').remove();
                                    });
                                    
                                    /* add message "X is online" on chat window */
                                    if (jQuery('li[id="tab_' + objResponse.response[intKey]['info']['buddy'] + '"]').length > 0) {
                                        var objMessengesContainer = jQuery('div[id="tab_content_' + objResponse.response[intKey]['info']['buddy'] + '"]').find('.messagesContainer');
                                        objMessengesContainer.html(
                                            objMessengesContainer.html() +
                                            '(' + getCurrentDate() + ') ' +    
                                            '<i style="color: #333">' + objContact.find('.name').text() + ' is online.</i>' + '<br />'
                                            );
                                        objMessengesContainer.prop('scrollTop', objMessengesContainer.prop('scrollHeight'));
                                    }
                                    
                                    /* increment group online users count */
                                    var onlineCount = parseInt(objContact.parents('li').first().prev('.groupname').first().find('span').first().text());
                                    if (!isNaN(onlineCount) && onlineCount >= 0) {
                                        objContact.parents('li').first().prev('.groupname').first().find('span').first().text(++onlineCount);
                                    }
                                }
                            }
                            break;             
                        case 'message':
                            var strContactId = objResponse.response[intKey]['info']['buddy'];
                            var strMsg = objResponse.response[intKey]['info']['message'];
                            /* open chat window if not opened */
                            jQuery('li[id="tab_' + strContactId + '"]').data('doNotSetTabAsActive', true);
                            if (jQuery('li[id="' + strContactId + '"]').length > 0) {
                                jQuery('li[id="' + strContactId + '"]').dblclick();
                            } else { // maybe a spammer ?
                                objTabHead.attr('id', 'tab_' + strContactId);
                                objTabHead.find('a').attr('href', '#tab_content_' + strContactId).text(strContactId);
                                objTabContent.attr('id', 'tab_content_' + strContactId);
                                jQuery('#chatwindow').append(objTabContent);
                                jQuery('#chatwindow ul').append(objTabHead);
                                jQuery('#chatwindow').tabs('refresh');
                                if (!jQuery('#chatwindow').is(':visible')) {
                                    jQuery('#chatwindow').show();
                                }
                            }
                            /* add message to the chat window */
                            var objMessengesContainer = jQuery('div[id="tab_content_' + strContactId + '"]').find('.messagesContainer');
                            objMessengesContainer.html(
                                objMessengesContainer.html() +
                                '(' + getCurrentDate() + ') ' +
                                '<strong>' + strContactId + ':</strong> ' + strMsg + '<br />'
                                );
                            objMessengesContainer.prop('scrollTop', objMessengesContainer.prop('scrollHeight'));
                            /* blink tab if not focused */
                            if (!jQuery('div[id="tab_content_' + strContactId + '"]').find('.writeMessage').is(':focus')) {
                                animateBackground(strContactId);
                            }
                            break;
                        case 'displayImage':
                            /* update buddy 's avatar */
                            var objContact = jQuery('li[id="' + objResponse.response[intKey]['info']['buddy'] + '"]');
                            if (objContact.length) {
                                objContact.find('.avatar').attr('src', objResponse.response[intKey]['info']['url']);
                            }
                        break;
                        default:
                            // unsupported operation, do nothing
                    }
                }
            }
        }
    });
}
            


/**
* Show error messages.
* @param   objInput        object  The jQuery input object.
* @param   strErrorText    string  The error message to display.
*/            
function showError(objInput, strErrorText)
{
    objInput.addClass('ui-state-error');
    jQuery('span#error-' + objInput.attr('id')).remove();
    objInput.after('<span id="error-' + objInput.attr('id') + '" class="error">' + strErrorText + '</span>');
}
            


/**
* Send a message to another user.
* @param   strTargetId     string      The ID of the user to send message to.
* @param   strMsg          string      The message to send.
*/            
function sendMessage(strTargetId, strMsg)
{
    jQuery.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: {
            'ajax_action': 'send_message',
            'target_id': strTargetId,
            'message': strMsg
        },
        dataType: 'json'
    });
}



/**
* Change presence status.
*/
function changeStatus()
{
    jQuery.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: {
            'ajax_action': 'change_status',
            'state': jQuery('#state-select').val(),
            'state_message': jQuery('#state-message').val() === 'Enter status message here...' ? '' : jQuery('#state-message').val() 
        },
        dataType: 'json',
        success: function (objResponse) {
            if ('success' === objResponse.status) {
                if ('invisible' !== jQuery('#state-select').val()) {
                    jQuery('#contacts-list').removeClass('italic');
                } else {
                    jQuery('#contacts-list').addClass('italic');
                }
            }
        }
    });
}



/**
* Retrieve current date.
* @return  string  Date is formatted as in PHP YYYY-mm-dd H:i:s.
*/
function getCurrentDate()
{
    var objNow = new Date();
    var intHours = objNow.getHours();
    var intMinutes = objNow.getMinutes();
    var intSeconds = objNow.getSeconds();
    var strNow = jQuery.datepicker.formatDate('yy-mm-dd', objNow)
               + ' ' + (intHours < 10 ? "0" + intHours : intHours)
               + ':' + (intMinutes < 10 ? "0" + intMinutes : intMinutes)
               + ':' + (intSeconds < 10 ? "0" + intSeconds : intSeconds);
    return strNow;       
}



/**
 * Blinks chat tab background.
 * @param   strContactId    string  Buddy 's id.
 */
function animateBackground(strContactId)
{
    jQuery('li[id="tab_' + strContactId + '"] a').animate({backgroundColor: '#FFF' }, 400);
    jQuery('li[id="tab_' + strContactId + '"] a').animate({backgroundColor: '#CCC' }, 700, function() {
        animateBackground(strContactId);
    });
}
