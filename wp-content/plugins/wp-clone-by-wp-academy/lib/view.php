<script type="text/javascript">
    jQuery ( function($) {

        ZeroClipboard.setDefaults( { moviePath: '<?php echo WPCLONE_URL_PLUGIN ?>lib/js/ZeroClipboard.swf' } );

        /**workaround for firefox versions 18 and 19.
           https://bugzilla.mozilla.org/show_bug.cgi?id=829557
           https://github.com/jonrohan/ZeroClipboard/issues/73
        */
        var enableZC = true;
        var is_firefox18 = navigator.userAgent.toLowerCase().indexOf('firefox/18') > -1;
        var is_firefox19 = navigator.userAgent.toLowerCase().indexOf('firefox/19') > -1;
        if (is_firefox18 || is_firefox19) enableZC = false;

        if ( $( ".restore-backup-options" ).length ) {
            $( ".restore-backup-options" ).each( function() {
                var clip = new ZeroClipboard( $( "a.copy-button",this ) );
                /** FF 18/19 users won't see an alert box. */
                if (enableZC) {
                    clip.on( 'complete', function (client, args) {
                        alert( "Copied to clipboard:\n" + args.text );
                    });
                }
            });
        } else {
            var clip = new ZeroClipboard( $( "a.copy-button" ) );
            /** FF 18/19 users won't see an alert box. */
            if (enableZC) {
                clip.on( 'complete', function (client, args) {
                    alert( "Copied to clipboard:\n" + args.text );
                });
            }
        }
    });

</script>

<?php
if (wpa_wpfs_init()) return;
global $wpdb;
$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpclone ORDER BY id DESC", ARRAY_A);

?>
<div id="wrapper">
<div id="MainView">

    <h2>Welcome to WP Clone by WP Academy</h2>

    <p>You can use this tool to create a backup of this site and (optionally) restore it to another server, or another WordPress installation on the same server.</p>

    <p><strong>Here is how it works:</strong> the "Backup" function will give you a URL that you can then copy and paste
        into the "Restore" dialog of a new WordPress site, which will clone the original site to the new site. You must
        install the plugin on the new site and then run the WP Clone > Restore function.</p>

    <p><strong>Choose your selection below:</strong> either create a backup of this site, or choose which backup you
        would like to restore.</p>

    <p>&nbsp;</p>
	
    <form id="backupForm" name="backupForm" action="#" method="post">
<?php 
    if ( isset($_GET['mode']) && 'advanced' == $_GET['mode'] ) { ?>
        <div class="info">
            <table>
                <tr align="left"><th colspan=""><label for="zipmode">Alternate zip method</label></th><td colspan="2"><input type="checkbox" name="zipmode" value="alt" /></td></tr>
                <tr align="left"><th><label for="use_wpdb">Use wpdb to backup the database</label></th><td colspan="2"><input type="checkbox" name="use_wpdb" value="true" /></td></tr>
                <tr align="left"><th><label for="maxmem">Maximum memory limit</label></th><td colspan="2"><input type="text" name="maxmem" /></td></tr>
                <tr align="left"><th><label for="maxexec">Script execution time</label></th><td><input type="text" name="maxexec" /></td></tr>
                <tr><td colspan="4"><h3>Exclude directories from backup, and backup database only</h3></td></tr>
                <tr><td colspan="4"><p>Depending on your web host, WP Clone may  not work for large sites.
                            You may, however, exclude all of your 'wp-content' directory from the backup (use "Backup database only" option below), or exclude specific directories.  
                            You would then copy these files over to the new site via FTP before restoring the backup with WP Clone.</p></td></tr>
                <tr align="left"><th><label for="dbonly">Backup database only</label></th><td colspan="2"><input type="checkbox" name="dbonly" value="true" /></td></tr>
                <tr align="left"><th><label for="exclude">Excluded directories</label></th><td><textarea cols="70" rows="5" name="exclude" ></textarea></td></tr>
                <tr><th></th><td colspan="5"><p>Enter one per line, i.e.  <code>uploads/backups</code>,use the forward slash <code>/</code> as the directory separator. Directories start at 'wp-content' level.</p></td></tr>
            </table>
        </div>
<?php
}
?>        
        <strong>Create Backup</strong>
        <input id="createBackup" name="createBackup" type="radio" value="fullBackup" checked="checked"/><br/><br/>

        <?php if (count($result) > 0) : ?>

        <div class="try">

            <?php foreach ($result AS $row) :

            $filename = convertPathIntoUrl(WPCLONE_DIR_BACKUP . $row['backup_name']);
			$url = wp_nonce_url( get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=wp-clone&del=' . $row['id'], 'wpclone-submit');
			?>
            <div class="restore-backup-options">
                <strong>Restore backup </strong>

                <input class="restoreBackup" name="restoreBackup" type="radio"
                       value="<?php echo $filename ?>" />&nbsp;

                <a href="<?php echo $filename ?>" class="zclip">
                    (&nbsp;<?php echo bytesToSize($row['backup_size']);?>&nbsp;)&nbsp; <?php echo $row['backup_name'] ?>
                </a>&nbsp;|&nbsp;

                <input type="hidden" name="backup_name" value="<?php echo $filename ?>" />

                <a class="copy-button" href="#" data-clipboard-text="<?php echo $filename ?>" >Copy URL</a> &nbsp;|&nbsp;
                <a href="<?php echo $url; ?>">Delete</a>
            </div>

            <?php endforeach ?>

        </div>

        <?php endif ?>

        <strong>Restore from URL:</strong><input id="backupUrl" name="backupUrl" type="radio" value="backupUrl"/>

        <input type="text" name="restore_from_url" class="Url" value="" size="80px"/><br/><br/>

        <div class="RestoreOptions" id="RestoreOptions">

            <input type="checkbox" name="approve" id="approve" /> I AGREE (Required for "Restore" function):<br/>

            1. You have nothing of value in your current site <strong>[<?php echo site_url() ?>]</strong><br/>

            2. Your current site at <strong>[<?php echo site_url() ?>]</strong> may become unusable in case of failure,
            and you will need to re-install WordPress<br/>

            3. Your WordPress database <strong>[<?php echo DB_NAME; ?>]</strong> will be overwritten from the database in the backup file. <br/>

        </div>

        <input id="submit" name="submit" class="btn-primary btn" type="submit" value="Create Backup"/>
        <?php wp_nonce_field('wpclone-submit')?>
    </form>
    <?php
        if(!isset($_GET['mode'])){
            $link = admin_url( 'admin.php?page=wp-clone&mode=advanced' );
            echo "<p style='padding:5px;'><a href='{$link}' style='margin-top:10px'>Advanced Settings</a></p>";
        }
    ?>
</div>
<div id="sidebar">

		<ul>
			<h2>WP Academy Resources</h2>
                        <iframe src="//player.vimeo.com/video/98912458?title=0&amp;byline=0&amp;portrait=0" width="300" height="225" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			<li><a href="http://wpencyclopedia.com" target="_blank" title="WP Encyclopedia">WordPress Business Encyclopedia</a></li>
			<li><a href="http://www.wpacademy.com/wordpress-training" target="_blank" title="WP Live">WP Live</a></li>
			<li><a href="http://wpacademy.com/websites" target="_blank" title="WP Academy Websites">Websites</a></li>
			<li><a href="http://www.wpacademy.com/hosting" target="_blank" title="Managed WordPress Hosting">Managed WordPress Hosting</a></li>
		</ul>
		
		<ul>
			<h2>WP Academy News</h2>
			<h3>WPAcademy.com</h3>			
			<?php wpa_fetch_feed ('http://members.wpacademy.com/category/news/feed', 3); ?>
			<h3>Twitter @WPAcademy</h3>
			<?php /* wpa_fetch_feed ('http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=wpacademy', 5); */ ?>
			<a class="twitter-timeline"  height="400" href="https://twitter.com/WPAcademy"  data-widget-id="342116561412304898">Tweets by @WPAcademy</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

		</ul>
		
		<ul>
			<h2>Help & Support</h2>
			<li><a href="http://members.wpacademy.com/wpclone-faq" target="_blank" title="WP Clone FAQ">Visit the WP Clone FAQ Page</a></li>
			<li><a href="http://wordpress.org/support/plugin/wp-clone-by-wp-academy" target="_blank" title="Support Forum">Support forum at WordPress.org</a></li>
		</ul>
    
	</div>
</div> <!--wrapper-->
<p style="clear: both;" ></p>
<?php
    if ( isset($_GET['mode']) && 'advanced' == $_GET['mode'] ) {
        global $wpdb;
        echo '<div class="info">';
        echo '<h3>System Info:</h3>';
        echo 'Memory limit: ' . ini_get('memory_limit') . '</br>';
        echo 'Maximum execution time: ' . ini_get('max_execution_time') . ' seconds</br>';
        echo 'PHP version : ' . phpversion() . '</br>';
        echo 'MySQL version : ' . $wpdb->db_version() . '</br>';
        if (ini_get('safe_mode')) { echo '<span style="color:#f11">PHP is running in safemode!</span></br>'; }
        echo '<h4>Directory list:</h4>';
        echo 'Uploads path : <pre>' . WPCLONE_DIR_UPLOADS . '</pre></br>';
        echo 'Plugin path : <pre>' . WPCLONE_DIR_PLUGIN . '</pre></br>';
        echo 'Plugin URL : <pre>' . WPCLONE_URL_PLUGIN . '</pre></br>';
        echo 'Backup path : <pre>' . WPCLONE_DIR_BACKUP . '</pre></br>';
        echo 'wp-content path : <pre>' . WPCLONE_WP_CONTENT . '</pre></br>';
        echo 'Site Root : <pre>' . WPCLONE_ROOT . '</pre></br>';
		echo 'ABSPATH : <pre>' . ABSPATH . '</pre></br>';
        if (!is_writable(WPCLONE_DIR_BACKUP)) { echo '<span style="color:#f11">Cannot write to the backup directory!</span></br>'; }
        if (!is_writable(WPCLONE_WP_CONTENT)) { echo '<span style="color:#f11">Cannot write to the root directory!</span></br>'; }
        echo '</div>';
    }

    function wpa_fetch_feed ($feed, $limit) {
        include_once(ABSPATH . WPINC . '/feed.php');
        $rss = fetch_feed($feed);
        if (!is_wp_error( $rss ) ) :
            $maxitems = $rss->get_item_quantity($limit);
            $rss_items = $rss->get_items(0, $maxitems); 
        endif;
        if ( isset($maxitems) && $maxitems == 0 ) echo '<li>No items.</li>';
        else
        // Loop through each feed item and display each item as a hyperlink.
        foreach ( $rss_items as $item ) : ?>
        <li>
            <a href='<?php echo esc_url( $item->get_permalink() ); ?>'
            title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
            <?php echo esc_html( $item->get_title() ); ?></a>
        </li>
        <?php endforeach;
    }
	
/** it all ends here folks. */