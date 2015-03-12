<?php
/*  Copyright 2015 Cyclos (www.cyclos.org)

    This plugin is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
    Be aware the plugin is publish under GPL2 Software, but Cyclos 4 PRO is not,
    you need to buy an appropriate license if you want to use Cyclos 4 PRO, see:
    www.cyclos.org.
*/

// Block people to access the script directly (against malicious attempts)
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*#################### Creates admin settings page ####################*/
add_action('admin_menu', 'cyclosAdminMenu');
function cyclosAdminMenu() {
    add_options_page('Cyclos', 'Cyclos','manage_options', 'Cyclos', 'cyclosAdminSettingsPage');
}

function cyclosAdminSettingsPage() {
    $token = get_option('cyclos_token');
        
    // If the settings are saved.
    if(isset($_POST['edit_cyclos_settings'])) {
         cyclosSaveAdminSettings(); 

    // If the translations are saved.
    } elseif(isset($_POST['edit_cyclos_translation'])) {
         cyclosSaveTranslate();
         
    // If no token is found show the configuration page.
    } elseif (empty($token)) {
         cyclosConfigureAccessClient();

    // Edit button is pressed show the configuration page.
    } elseif(isset($_POST['goto_cyclos_settings'])) {    
         cyclosConfigureAccessClient();

    // Translate button is pressed show the translate page.         
    } elseif(isset($_POST['goto_cyclos_translate'])) {    
         cyclosTranslate();
         
    // Show normal page
    } else {
        cyclosNormalAdminPage();
   }
}

/*#################### Shows the normal admin settings page ####################*/
function cyclosNormalAdminPage() {

     ?>
    <div class="wrap">
        <h2>Cyclos Wordpress Plugin</h2>
    <?php


    // Check if settings are working
    $rootUrl = get_option('cyclos_url');
    $token = get_option('cyclos_token');
    if (!empty($rootUrl) && !empty($token)) {
        configureCyclos();
        $userService = new Cyclos\UserService();
        
        // Get the logged user
        try {
            $user = $userService->getCurrentUser();
        } catch (Cyclos\ConnectionException $e) {
            $errorMessage = "The Cyclos server couldn't be contacted";
        } catch (Cyclos\ServiceException $e) {
            $errorMessage = "The current Cyclos plugin settings are not correct.<br>Make sure the administrator user and access client used in Cyclos are active.";
        }
    } else {
        $errorMessage = "Cyclos plugin is not correctly configured";
    }
     
     if (empty($errorMessage)) { ?>
        <p>Congratulations! Cyclos is corretly configured for: <a href="<?= $rootUrl ?>"><?= $rootUrl ?></a>.<br>
        The administrator used for access is <?= $user->name ?> (<?= $user->username ?>).</p> 
        <p>You can show the login form in any page, by inserting the code: &#91;cycloslogin&#93;</p>

        <?php
    } else { ?>
        <p><b>Warning!</b><br><?= $errorMessage ?></p> <?php
    }
    
    // Add the button to configure the plugin
     ?>
    
        <form name="cyclos_edit" method="post" action="#">
            <input type="hidden" name="goto_cyclos_settings" value="yes">
            <input type="submit" name="Submit" value="Edit Cyclos plugin settings"/></div>
        </form>
        <form name="cyclos_translate" method="post" action="#">
            <input type="hidden" name="goto_cyclos_translate" value="yes">
            <input type="submit" name="Submit" value="Change or translate labels of login form"/></div>
        </form>
    </div>
    <?php
}

/*#################### Shows the edit admin setting page ####################*/
function cyclosConfigureAccessClient() {
    $url = get_option('cyclos_url');
    $adminuser = get_option('cyclos_adminuser');
    $token = get_option('cyclos_token');
?>
<div class="wrap">
    <form name="cyclos_form" method="post" action="#">
        <input type="hidden" name="edit_cyclos_settings" value="Y">
        <h2>Cyclos Plugin Settings</h2>
        <hr>
        <table>
            <tr>
                <td>Cyclos URL:</td>
                <td>&nbsp;<input type="text" name="cyclos_url" value="<?= $url ?>" size="50"></td>
                <td>&nbsp; <i>e.g. https://demo.cyclos.org</i></td></tr>
            <tr>
                <td>Cyclos admin username:</td>
                <td>&nbsp;<input type="text" name="cyclos_adminuser" value="<?= $adminuser ?>" size="50"></td>
                <td>&nbsp; <i>e.g. wp_admin_user</i></td>
            </tr>
            <tr>
                <td>Cyclos admin password:</td>
                <td>&nbsp;<input type="password" name="cyclos_adminpwd" size="50"></td>
                <td>&nbsp; <i>e.g. mysecretpassword</i></td>
            </tr>
            <tr>
                <td>Access client activation code:</td>
                <td>&nbsp;<input type="text" name="cyclos_actcode" size="50"></td>
                <td>&nbsp; <i>The 4 digit code generated by Cyclos.</i></td>
            </tr>
        </table>
        <hr/>
        <div><input type="submit" name="Submit" value="Save settings"/></div>
    </form>
    <hr/>
    <h2>Instructions:</h2>
    <h3>Step 1 (create access client for wordpress plugin):</h3>
    <ol>
        <li>Login to Cyclos with an administrator that has sufficient permissions.</li>
        <li>Create an acces client: System > System configuration > User identification methods > New > Access client.</li>
        <li>Give the access client the name <i>wp_client</i>.
        <li>Under <i>Permission level</i> make sure to select <i>All permissions</i> and it would be a good practice to enable "Allow IP whitelist".</li>
        <li>Save the access client.</li>            
    </ol>
    <h3>Step 2 (create admin and admin group for wordpress plugin):</h3>
    <ol>
        <li>Create an admin group: System > User configuration > Group > New > Administrators group.</li>
        <li>Give the group the name <i>wp_admin_group</i>.</li>
        <li>Make sure this admin group cannot manage any other admins.</li>
        <li>Make sure this admin group can manage all user groups that should login through the wordpress plugin.</li>
        <li>Save the group.</li>
        <li>Go the the permissions tab (of the group wp_admin_group) and make sure this group has no permissions at all.</li>
        <li>In: My access clients, enable wp_client and save.</li>
        <li>In: User management > Login users via web services, make sure this option is enabled.</li>
        <li>Save the permissions.</li>
    </ol>
    <h3>Step 3 (give permissions to manage the access client):</h3>
    <ol>
        <li>Make sure the admin you are logged in with can manage the group <i>wp_admin_group</i>: System > User configuration > Group > <i>Your group</i> > Managed admins.</li>
        <li>Make sure the admin you are logged in with can manage the access client: System > Groups > <i>Your group</i> > Permissions (tab) > User management > Access clients, select here all options for wp_client (View, Manage, Activate, etc.).</li>
    </ol>
    <h3>Step 4 (configure logout redirect):</h3>
    <ol>
        <li>Go to the system default configuration: System > System configuration > default configuration.</li>
        <li>In the configuration set the logout redirect url to your wordpress site: Display > URL to redirect after logout.</li>
        <li>Save the configuration.</li>
    </ol>
    <h3>Step 5 (create user and activate access client for wordpress plugin):</h3>
    <ol>
        <li>Create a admin for the wordpress plugin: Users > Management > Users > New > wp_admin_group.</li>
        <li>Give the user the name <i>wp_admin_user</i>.</li>
        <li>Make sure to give give the admin a strong password (enough characters and numbers) and save.</li>
        <li>Now logout Cyclos (or use another browser) and login with the user <i>wp_admin_user</i>, if you can login successfull (if set answer the security questions) you can logout again and login with administrator your where previously logged in with.</li>
        <li>Go to the profile of the user <i>wp_admin_user</i>.</li>
        <li>Assign the access client: User management > wp_client > Add, you can call it <i>wp_client_1</i>.</li>
        <li>It would be a good practice to use the IP whitelist.</li>
        <li>Click on: Activation code > Confirm, and fill in this activation code together with the other information in the field on the top of this wordpress page (use the password and username for the user wp_admin_user).</li>
		<li>Click on save and congratulation you are done! In live versions always use https for both Cyclos and the wordpress site!</li>
    </ol>
</div>
<?php    
}

/*#################### Saves admin settings page ####################*/
function cyclosSaveAdminSettings() {
    // first retreive the posted data
    $url = esc_url($_POST['cyclos_url']);
    $adminuser = sanitize_text_field($_POST['cyclos_adminuser']);
    // Don't sanitize the password, as it can contain any characters and is sent directly via WS
    $adminpwd = $_POST['cyclos_adminpwd'];
    $actcode = sanitize_text_field($_POST['cyclos_actcode']);
    
    // Configure Cyclos with POST variables
    spl_autoload_register('autoload_cyclos'); 
    Cyclos\Configuration::setRootUrl($url);
    Cyclos\Configuration::setAuthentication($adminuser, $adminpwd);
    
    // Activate the access client
    $accessClientService = new Cyclos\AccessClientService();
    $result = $accessClientService->activate($actcode, null);
    $token = $result->token;
    
    // Save the options
    update_option('cyclos_url', $url);
    update_option('cyclos_adminuser', $adminuser);
    update_option('cyclos_token', $token);
    
    ?>
    <div class="updated"><p><strong>Options saved</strong></p></div>
    <?php
    
    cyclosNormalAdminPage();
}

/*#################### Shows the admin translations page ####################*/
function cyclosTranslate() {
    
    // Get all translated values.
    $t = cyclosGetTranslations(); ?>
    <div class="wrap">
        <form name="cyclos_form_translate" method="post" action="#">
            <input type="hidden" name="edit_cyclos_translation" value="Y">
            <h2>Cyclos Plugin Settings</h2>
            <hr>
            <table> <?
            foreach (CyclosKey::getAll() as $k => $key) { ?>
                <tr>
                    <td><?= $key->title ?></td>
                    <td>&nbsp;<input type="text" name="<?= $key->option ?>" value="<?= $t->$k ?>" size="50"></td>
                    <td>&nbsp; <i>e.g. <?= $key->defaultValue ?></i></td>
                </tr>
        <?  } ?>
            </table>
            <hr/>
            <div><input type="submit" name="Submit" value="Save settings"/></div>
        </form>
    </div>
    <?php
}

/*#################### Saves admin translate page ####################*/
function cyclosSaveTranslate() {
    // Get the translations to get all possible keys
    foreach (CyclosKey::getAll() as $k => $key) {
        update_option($key->option, str_replace("\'", "'", sanitize_text_field($_POST[$key->option])));
    }
    
    ?>
    <div class="updated"><p><strong>Options saved</strong></p></div>
    <?php
    
    cyclosNormalAdminPage();
}
?>