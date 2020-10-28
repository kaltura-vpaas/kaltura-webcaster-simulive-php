<?php
date_default_timezone_set('America/New_York');
require_once('../KalturaClient/KalturaClient.php');
require_once('../AppSettings.php');

// Live stream entry ID
$liveStreamEntryId = $_GET['entryId'];

// Get Kaltura client
$config = new KalturaConfiguration();
$config->setServiceUrl('https://www.kaltura.com');
$client = new KalturaClient($config);
$ks = $client->generateSession(API_ADMIN_SECRET, VIEWER_USER_ID, KalturaSessionType::ADMIN, PARTNER_ID, KS_EXPIRY);
$client->setKS($ks);

// Create the Kaltura Session and set it to the Kaltura Client
$ksPrivileges = 'sview:'.$liveStreamEntryId.',restrictexplicitliveview:'.$liveStreamEntryId.',enableentitlement,appid:'.APP_ID.',appdomain:'.APP_DOMAIN.'sessionkey:'.VIEWER_USER_ID;
$ks = $client->generateSession(API_ADMIN_SECRET, VIEWER_USER_ID, KalturaSessionType::USER, PARTNER_ID, KS_EXPIRY, $ksPrivileges);
$client->setKS($ks);

// Create the embed code javascript source link
$kalturaJSSrc = 'https://cdnapisec.kaltura.com/p/'.PARTNER_ID.'/embedPlaykitJs/uiconf_id/'.V7_UI_CONF_ID;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Kaltura Simulive Webcast Viewer</title>
	<!-- Responsive Embed CSS -->
	<style>
		body { padding: 20px; }
		.embed_container { position: relative;  padding-bottom: 56.25%; /* 16:9 */  height: 0; } 
		.embed_container .kaltura_player_embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
	</style>
	<!-- This line loads the Kaltura player library -->
	<script type="text/javascript" src="<?php echo $kalturaJSSrc; ?>"></script>
</head>
<body>

<!-- This div is where the player will be rendered -->
<div class="embed_container">
	<div id="kaltura_player" class="kaltura_player_embed" style=""></div>
</div>

<script type="text/javascript">
  try {
    var kalturaPlayer = KalturaPlayer.setup({
      targetId: "kaltura_player",
      provider: {
        partnerId: <?php echo PARTNER_ID; ?>,
        uiConfId: <?php echo V7_UI_CONF_ID; ?>,
      	ks: "<?php echo $ks; ?>"
      },
      playback: {
        autoplay: true
      },
      session: {
	      userId: "<?php echo VIEWER_USER_ID; ?>"
      },
      /*
      plugins: {
        "qna": {
            dateFormat: "mmmm do, yyyy",
            expandMode: "OverTheVideo",
            expandOnFirstPlay: true,
            userRole: 'unmoderatedAdminRole' 
        },
        "kaltura-live": {
            checkLiveWithKs: false,
            isLiveInterval: 10
        }
      }
      */
    });
    kalturaPlayer.loadMedia({entryId: "<?php echo $liveStreamEntryId; ?>"});
  } catch (e) {
    console.error(e.message)
  }
</script>

</body>
</html>
