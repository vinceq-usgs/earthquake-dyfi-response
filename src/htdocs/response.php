<?php

// This script must do 4 things:
// 1. Write to a file
// 2. Lookup intensity, numresp for this event and location (removed)
// 3. Compute intensity for this response
// 4. Output summary HTML


include_once 'inc/response.inc.php';

// defines the $CONFIG hash of configuration variables
include_once '../conf/config.inc.php';


// for debugging, TODO: remove?
// $rawData = file_get_contents("php://input");
// file_put_contents("$log_dir/raw.input",$rawData);


// Firstly validate the form

if (!isset($_POST['fldSituation_felt'])) {
  header('HTTP/1.0 400 Bad Request');

?>
  <p class="alert warning">
    Required entries were not provided!
    Please re-submit the form after answering all required questions.
  </p>
<?php

  return;
}


// Main loop

$server = $CONFIG['SERVER_SHORTNAME'];
$backends = explode(',', $CONFIG['BACKEND_SERVERS']);
$backup_dir = $CONFIG['BACKUP_DIR'] . '/responses';
$data_dir = $CONFIG['WRITE_DIR'];
$log_dir = $CONFIG['BACKUP_DIR'] . '/log';

$count = getmypid();
$eventid = eventid();
$microtime = str_replace('.', '_', microtime(true));
$time = time();

$form_version = isset($_POST['form_version']) ? $_POST['form_version'] : null;
$language = isset($_POST['language']) ? $_POST['language'] : 'en';
$mapAddress = isset($_POST['ciim_mapAddress']) ? $_POST['ciim_mapAddress'] : null;

// Do we need to do a geocode?
if ($mapAddress !== null && $mapAddress !== 'null') {
  include_once('../lib/geocode.php');
  try {
    $client_id = $CONFIG['ARCGIS_CLIENT_ID'];
    $client_secret = $CONFIG['ARCGIS_CLIENT_SECRET'];
    $result = geocode($client_id, $client_secret, $mapAddress);
    if ($result) {
      $_POST['ciim_mapLat'] = $result['latitude'];
      $_POST['ciim_mapLon'] = $result['longitude'];
    }
  } catch (Exception $ex) {
    // Oh well, we tried...
    trigger_error($ex->getMessage());
  }
}


// Write to file

// Build $post from $_POST rather than php://input because we may have
// modified $_POST d_text
$post = array();
foreach ($_POST as $k => $v) {
  if (is_array($v)) {
    // dyfi backend expects custom encoding of arrays
    $v = implode(' ', $v);
  }
  $post[$k] = $v;
}
$post['timestamp'] = $time;
$post['server'] = $server;

$raw = http_build_query($post);


// files to be written
$files = array();
$basename = "entry.${server}.${eventid}.${microtime}.${count}";

// backup dir first
$files[] = "${backup_dir}/${basename}";

// last entry received on this server
$files[] = "${log_dir}/latest_entry.post";

// one per backend
foreach ($backends as $backend) {
  $files[] = "${data_dir}/incoming.${backend}/${basename}";
}

foreach ($files as $dest) {
  $dest_dir = dirname($dest);
  if (!is_dir($dest_dir)) {
    mkdir($dest_dir, 0777, true);
  }

  file_put_contents($dest, $raw);
}


// Get language translation

$translate_file = 'inc/labels.' . $language . '.inc.php';
if (!file_exists($translate_file)) {
  $translate_file = 'inc/labels.en.inc.php';
}
include_once($translate_file);


$data = $_POST;
$data['eventid'] = $eventid;
$cdi = _rom(compute_intensity());
if (isset($cdi)) {
  $data['your_cdi'] = $cdi;
}

// Lookup of other entries is disabled for now
// if (isset($lookup)) {
//	$data['all_cdi'] = $lookup['rom_cdi'];
//	$data['nresp'] = $lookup['nresp'];
//}

$OUTPUT = array (
  'eventid' => $T['EVENTID_LABEL'],
  'your_cdi' => $T['ESTIMATED_II_LABEL'],
  'all_cdi' => $T['COMMUNITY_II_LABEL'],
  'nresp' => $T['NRESP_LABEL'],
  'ciim_zip' => $T['ZIPCODE_LABEL'],
  'ciim_city' => $T['CITY_LABEL'],
  'ciim_region' => $T['REGION_LABEL'],
  'ciim_country' => $T['COUNTRY_LABEL'],
  'fldContact_name' => $T['NAME_LABEL'],
  'fldContact_email' => $T['EMAIL_LABEL'],
  'fldContact_phone' => $T['PHONE_LABEL'],
  'ciim_address' => $T['ADDRESS_LABEL'],
  'ciim_time' => $T['EVENTTIME_LABEL'],
  'ciim_mapLat' => 'Latitude',
  'ciim_mapLon' => 'Longitude',

  'filename' => "Output",
  'form_version' => "Form version",
);

$OUTPUT_DATA = array();
foreach($OUTPUT as $key => $desc) {
  if (!array_key_exists($key, $data)) {
    continue;
  }

  $val = $data[$key];
  if (!$val) {
    continue;
  }

  if ($key == 'ciim_city' || $key == 'ciim_region' || $key == 'ciim_country') {
    $val = _strip_code($val);
    if (!$val) {
      continue;
    }
  }

  $OUTPUT_DATA[$key] = $val;
}


// output json if requested
if (isset($_POST['format']) && $_POST['format'] === 'json') {
  header('Content-type: application/json');
  echo json_encode($OUTPUT_DATA);
  exit();
}


// otherwise output html
?>
<!doctype html>
<html>
<head>
  <title><?php echo $T['THANKS_LABEL']; ?></title>
</head>
<body>

<?php

echo '<p class="alert success">' . $T['THANKS_LABEL'] . '</p>';
echo '<dl>';

foreach ($OUTPUT_DATA as $key => $val) {
  // Loop over results and append the rows
  echo '<dt>' . $OUTPUT[$key] . '</dt>';
  echo '<dd>' . htmlspecialchars($val) . '</dd>';
}

echo '</dl>';

?>
