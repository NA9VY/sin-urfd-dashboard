<?php
// =============================================
// YSF Auto-Reconnect Guide for WPSD
// Southern Indiana Network (SIN)
// NA9VY
// =============================================

$pageTitle = "YSF Auto-Reconnect Setup";
include_once 'config.inc.php';
include_once 'header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2><i class="fa fa-refresh"></i> YSF Auto-Reconnect Script</h2>
            <p class="lead">Automatically reconnect your Zumspot / WPSD to a YSF reflector after network interruptions. Created by NA9VY on stardate: 97523.4 (Friday, May 22, 2026).</p>

            <div class="alert alert-info">

	<!-- Star Trek Intro -->
            <div class="alert alert-info">
                <strong>Captain's Log, Stardate 97523.4</strong><br><br>
                We are currently in orbit around the planet Indiana. The crew of the USS Grok continues to be plagued by 
                the mysterious YSF disconnect phenomenon on the WPSD system. Despite repeated attempts at communication, 
                the creator of WPSD remains stubbornly uncooperative.<br><br>
		
		I have ordered the Chief Engineer and Science Officer to develop a workaround for this persistent issue. 
                What they have created is not a permanent solution, but it should allow us to maintain 
                reliable communications with the Southern Indiana Network for the time being.<br><br>
                These instructions have been prepared for distribution to other Starfleet vessels. 
                <br>
		Live long and prosper.
            </div>

                <strong>Smart Behavior:</strong> This script only acts if YSF is an active mode and when you are <strong>completely unlinked</strong>.
                It will <strong>not</strong> disconnect you if you're currently linked to any YSF reflector or interrup
		ongoing conversations.
                <br><br>
                The script will run every 5 minutes and check the YSF connection. You can change the timing in the Cron 
                job to other values.
                <br><br>
                The script is configured to reconnect to YSF-31188/URF-SIN if YSF is not linked. You can connect to
                a different reflector by changing the <strong>REFLECTOR</strong> variable in the script.
            </div>

            <div class="alert alert-warning">
                <strong>Important:</strong> You will be working at the command line level of your WPSD.
                It is highly recommended to back up your WPSD configuration before making these changes.
            </div>

            <div class="alert alert-info">
                <strong>Tested On:</strong> Zumspot Elite 3.5 running the latest version of WPSD on Trixie.
                Your mileage may vary.
            </div>

            <hr>

            <h3>Installation Instructions</h3>

            <div class="panel panel-default">
                <div class="panel-heading"><strong>Step 1: Connect to your WPSD via SSH</strong></div>
                <div class="panel-body">
                    <p style="color: #333 !important;"><strong>From a computer on the same network:</strong></p>
                    <p style="color: #333 !important;">Open a terminal (Linux/Mac) or command prompt (or PuTTY) (Windows) and run the following command:</p>
                    <pre><code>ssh pi-star@&lt;IP-ADDRESS-OF-YOUR-WPSD-PI&gt;</code></pre>
                    <p style="color: #333 !important;"><strong>Default password:</strong> <code>raspberry</code> (unless you changed it).</p>
		    <p style="color: #333 !important;"><strong>Note:</strong> Don't use the SSH access from within the WPSD dashboard. The paste method there may cause issues.
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><strong>Step 2: Create Script Directory</strong></div>
                <div class="panel-body">
                    <pre><code>mkdir -p /home/pi-star/bin</code></pre>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><strong>Step 3: Create the Monitor Script</strong></div>
                <div class="panel-body">
                    <pre><code>sudo nano /home/pi-star/bin/ysf-monitor.sh</code></pre>
                    <p style="color: #333 !important;"><strong>Paste the following code into the editor and make any wanted adjustments to the reflector or number of log lines:</strong></p>
                    <pre><code>#!/bin/bash
# YSF Auto-Reconnect for WPSD by NA9VY - May 22, 2026
# This script was put together to address the issue that when the network drops out WPSD will drop the
# YSF link and not reconnect.
#
# Tested on WPSD trixie version on Zumpost Elite 3.5.
#
# What this script does:
# Manage the size of the log file it keeps
# Check to make sure YSF is an active mode (if not log action taken and exit)
# Check to see if YSF is connected to a reflector (if it is log action taken and exit)
# Connect to a reflector (then log action taken and exit)

cd /tmp 2>/dev/null || true
export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

# ### Adjust reflector and log lines in this section. No other adjustment should be needed

REFLECTOR="ysf31188"	# Reflector to be reconnected to
MAX_LOG_LINES=2000	# Number of log lines to keep

LOGFILE="/home/pi-star/ysf-monitor.log"

echo "=== $(date -u '+%Y-%m-%d %H:%M:%S UTC') ===" >> "$LOGFILE"

# === Log Management: Keep log from growing too large ===
if [ -f "$LOGFILE" ]; then
    LOG_LINE_COUNT=$(wc -l < "$LOGFILE")
    if [ "$LOG_LINE_COUNT" -gt "$MAX_LOG_LINES" ]; then
        echo "Log file too large ($LOG_LINE_COUNT lines). Truncating to last $MAX_LOG_LINES lines..." >> "$LOGFILE"
        tail -n $MAX_LOG_LINES "$LOGFILE" > "$LOGFILE.tmp"
        mv "$LOGFILE.tmp" "$LOGFILE"
        echo "Log truncated at $(date -u '+%Y-%m-%d %H:%M:%S UTC')" >> "$LOGFILE"
    fi
fi

# Check if YSF is enabled
if ! systemctl is-active --quiet ysfgateway.service 2>/dev/null; then
    echo "⚠️  YSF Gateway is NOT enabled. Exiting script." | tee -a "$LOGFILE"
    exit 0
fi

# Get recent log lines
LOG_LINES=$(tail -n 400 /var/log/pi-star/YSFGateway-*.log 2>/dev/null)

LAST_LINK=$(echo "$LOG_LINES" | grep "Linked to" | tail -n 1)
LAST_DISCONNECT=$(echo "$LOG_LINES" | grep "Closing YSF network connection" | tail -n 1)

echo "Last Link: $LAST_LINK" >> "$LOGFILE"
echo "Last Disconnect: $LAST_DISCONNECT" >> "$LOGFILE"

# Determine if linked to anything
if [ -n "$LAST_LINK" ] && [ -n "$LAST_DISCONNECT" ]; then
    LINK_TIME=$(echo "$LAST_LINK" | cut -d' ' -f2-3)
    DISC_TIME=$(echo "$LAST_DISCONNECT" | cut -d' ' -f2-3)
    
    if [[ "$DISC_TIME" > "$LINK_TIME" ]]; then
        LINKED=false
    else
        LINKED=true
    fi
elif [ -n "$LAST_LINK" ]; then
    LINKED=true
else
    LINKED=false
fi

if [ "$LINKED" = true ]; then
    CURRENT_REFLECTOR=$(echo "$LAST_LINK" | sed 's/.*Linked to //')
    echo "✅ Currently linked to: $CURRENT_REFLECTOR - Doing nothing" | tee -a "$LOGFILE"
    exit 0
else
    echo "⚠️  Completely unlinked. Reconnecting to $REFLECTOR..." | tee -a "$LOGFILE"
fi

# Reconnect
echo "Unlinking..." >> "$LOGFILE"
sudo /usr/local/sbin/wpsd-ysflink unlink >> "$LOGFILE" 2>&1
sleep 6

echo "Linking to $REFLECTOR..." >> "$LOGFILE"
sudo /usr/local/sbin/wpsd-ysflink "$REFLECTOR" >> "$LOGFILE" 2>&1

echo "🔄 Re-link command completed" | tee -a "$LOGFILE"</code></pre>

                    <p style="color: #333 !important;"><strong>To save and exit nano:</strong></p>
                    <ul style="color: #333 !important;">
                        <li>Press <kbd>Ctrl</kbd> + <kbd>O</kbd> → then press <kbd>Enter</kbd> (to save)</li>
                        <li>Press <kbd>Ctrl</kbd> + <kbd>X</kbd> (to exit)</li>
                    </ul>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><strong>Step 4: Set Permissions and Test</strong></div>
                <div class="panel-body">
                    <pre><code>sudo chown pi-star:pi-star /home/pi-star/bin/ysf-monitor.sh
chmod +x /home/pi-star/bin/ysf-monitor.sh
/home/pi-star/bin/ysf-monitor.sh</code></pre>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><strong>Step 5: Add to Cron Job</strong></div>
                <div class="panel-body">
                    <pre><code>sudo crontab -e</code></pre>
                    <p style="color: #333 !important;">To use a time other than 5 minutes change the 5 to your desired time in minutes below.<br>
		    Add this line at the bottom of the file, then save and exit the cron editor:</p>
                    <pre><code>*/5 * * * * /home/pi-star/bin/ysf-monitor.sh</code></pre>
                </div>
            </div>

	    <div class="panel panel-default">
                <div class="panel-heading"><strong>Step 6: Test the Script</strong></div>
                <div class="panel-body">
                    <p style="color: #333 !important;">You can now type <code>exit</code> to leave the SSH connection.</p>
                    <p style="color: #333 !important;">To test the script:</p>
                    <ol style="color: #333 !important;">
                        <li>Go to the WPSD dashboard</li>
                        <li>Manually unlink the YSF reflector</li>
                        <li>Wait up to 5 minutes</li>
                        <li>The script should automatically reconnect to the reflector you set in the <strong>REFLECTOR</strong> variable.</li>
                    </ol>
                </div>
            </div>

            <hr>

            <h3>Useful Commands</h3>
            <table class="table table-striped">
                <tr><td><strong>Run script manually</strong></td><td><code>/home/pi-star/bin/ysf-monitor.sh</code></td></tr>
                <tr><td><strong>View script log</strong></td><td><code>tail -n 50 /home/pi-star/ysf-monitor.log</code></td></tr>
                <tr><td><strong>Check YSF activity</strong></td><td><code>tail -n 40 /var/log/pi-star/YSFGateway-*.log | grep -E "Linked|Closing|Disconnect"</code></td></tr>
            </table>

        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>
