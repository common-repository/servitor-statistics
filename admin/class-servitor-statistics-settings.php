<?php

class Servitor_Statistics_Settings {


    /**
     */
    public function __construct() {
        $this->check_force_run();
    }

    public function check_force_run() {
        if(!isset($_POST['servitor-force-run']) || !$_POST['servitor-force-run']) {
            return;
        }

        add_action('admin_notices', [$this, 'show_force_run_check']);
    }

    public function show_force_run_check() {
        $response = (new StatisticsCronjob())->run_statistics_cronjob();
        $class = 'notice-warning';
        // If the $response is 200: Okay
        // If anything else, show it.
        if($response == 200) {
            $class = 'notice-success';
            $response = "Received an 200 status code, your statistic should now be available for you to see on the Website.";
        }else{
            $response = '<pre>' . $response . "</pre>";
        }

        echo '<div class="notice is-dismissible ' . $class . '">';
        print_r($response);
        echo '</div>';
    }

    public function show_settings_page() {
        ob_start();
        ?>

        <div class="wrap servitor">
            <h1>Servitor - Settings</h1>

            <form method="post" action="options.php" class="settings-form">
            <?php settings_fields( Servitor_Statistics_Settings_Group ); ?>
            <?php do_settings_sections( Servitor_Statistics_Settings_Group ); ?>

            <table class="form-table">
                <h3>Server monitoring</h3>
                <p>
                    To monitor your server we need to know the user and api key of your server on <a href="https://servitor.io">Servitor</a>.
                    If you want to use this feature please follow the steps below:
                </p>
                <ul class="how-to">
                    <li>Register or login to <a href="https://servitor.io">Servitor</a></li>
                    <li>Create a server <a href="https://servitor.io/admin/servers/create">here.</a></li>
                    <li>Go to the <a href="https://servitor.io/admin/daemon">daemon page</a> and select the correct server at the top.</li>
                    <li>In the <strong>Execute the daemon once to store your credentials</strong> section, copy your user key and api key into the fields below.</li>
                </ul>
                <tr valign="top">
                    <th scope="row">User key</th>
                    <td><input type="text" name="servitor_user_key" value="<?php echo esc_attr( get_option('servitor_user_key') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">API key</th>
                    <td><input type="text" name="servitor_api_key" value="<?php echo esc_attr( get_option('servitor_api_key') ); ?>" /></td>
                </tr>
            </table>

            <hr>

            <h3>Website monitoring</h3>
            <p>
                To monitor your website you need to create a monitor inside <a href="https://servitor.io">Servitor</a>, if you want to get some details
                about your server in your Wordpress dashboard, please fill in the monitor ID below.
                <br>
                <small>The monitor ID can be found in the URL bar of your monitor page.</small>
            </p>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Monitor ID</th>
                    <td><input type="text" name="servitor_monitor_id" value="<?php echo esc_attr(get_option('servitor_monitor_id')) ?>"></td>
                </tr>
            </table>

            <?php submit_button(); ?>
            </form>
        </div>
        <hr>
        <div class="wrap servitor">
            <form action="" method="post" class="settings-form">
                <h3>Force run</h3>
                <p>If you want to test out the plugin, please click the button below. The output will be given back to you. Use this to debug your plugin if something doesn't work as expected.</p>
                <input type="hidden" name="servitor-force-run" value="1" />
                <?php submit_button("Force run Servitor") ?>
            </form>
        </div>

        <?php
        echo ob_get_clean();
    }
}