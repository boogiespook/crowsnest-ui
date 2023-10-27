<?php
// Start the session
session_start();
if (!isset($_SESSION['toggle'])) {
	$_SESSION['toggle'] = false;
}

if (isset($_POST['toggle_submit'])) {
	$_SESSION['toggle'] = !$_SESSION['toggle'];
}

?>
<!DOCTYPE html>
  <html lang="en-us" class="pf-theme-dark">
    <head>
      <meta charSet="utf-8"/>
      <meta http-equiv="x-ua-compatible" content="ie=edge"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<meta http-equiv='refresh' content='3'>
      <title data-react-helmet="true">CrowsNest Dashboard</title>
      <link rel="stylesheet" href="css/brands.css" />
      <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="css/tabs.css" />
      <link rel="stylesheet" href="css/patternfly.css" />
      <link rel="stylesheet" href="css/patternfly-addons.css" />
    </head>

    <body>
<?php

$pg_host = getenv('PG_HOST');
$pg_db = getenv('PG_DATABASE');
$pg_user = getenv('PG_USER');
$pg_passwd = getenv('PG_PASSWORD');

$db_connection = pg_connect("host=$pg_host port=5432  dbname=$pg_db user=$pg_user password=$pg_passwd");
include 'functions.php';

?>    
    
    
    
<div class="pf-c-page">


  <header class="pf-c-page__header">
                <div class="pf-c-page__header-brand">
                  <div class="pf-c-page__header-brand-toggle">
                  </div>
                  <a class="pf-c-page__header-brand-link" href="index.php">
                  <img class="pf-c-brand" src="images/crowsnest-banner.png" alt="CrowsNest logo" />
                  </a>
                </div>


<?php
if (isset($_GET['profile'])){
$_SESSION['profile'] = $_GET['profile'];
$_SESSION['profileName'] = $_GET['name'];	
} else {
$_SESSION['profile'] = '1';
$_SESSION['profileName'] = "Core";	

}
?>

<?php
putProfileOptions();
?>

</header>


<main class="pf-c-page__main" tabindex="-1">  
    <section class="pf-c-page__main-section pf-m-full-height">


  <div class="tab-panels">

<!--  Start of Dashboard -->  
    <section id="dashboard" class="tab-panel">

    <p id="dashboard" class="pf-c-title pf-m-3xl">Security Posture Overview (<?php print $_SESSION['profileName']; ?>)</p>

    <section class="pf-c-page__main-section pf-m-fill">
      <div class="pf-l-gallery pf-m-gutter">
<?php
## Get domains & capabilities

## Get domains & capabilities based on the profile
$profile = $_SESSION['profile'];

$chosenDomains = getDomainsByProfile($profile);
$i = 1;
foreach ($chosenDomains as $domain) {
$getDomains = "select domain.description, domain.id from domain WHERE domain.id = '" . $domain . "' ORDER BY domain.description;";
$domainResult = pg_query($getDomains) or die('Error message: ' . pg_last_error());

while ($row = pg_fetch_assoc($domainResult)) {
print '  
<div class="pf-c-card pf-m-selectable-raised pf-m-rounded" id="card-' . $i . '">
<div class="pf-c-card__header">';
putAperture($row['id']);
print '
</div>
<div class="pf-c-card__title">
            <p id="card-' . $i . '-check-label">'. $row['description'] . '</p>
            <div class="pf-c-content">
              <small>Key Capabilities</small>
            </div>
          </div>
          <div class="pf-c-card__body">
          <div class="pf-c-content">';
	$getCapabilities = "select capability.id as id, capability.description as capability, flag.description as flag from capability,flag where domain_id = '" . $row['id'] . "' and capability.flag_id = flag.id ORDER BY capability;";
	$capabilityResult = pg_query($getCapabilities) or die('Error message: ' . pg_last_error());
	while ($capRow = pg_fetch_assoc($capabilityResult)) {
       print putIcon($capRow['flag'], $capRow['capability']);
     }
       $i++;
print "</div></div></div>";
}
}

?>
</section>
<button  onClick="window.location.reload();" class="pf-c-button pf-m-primary" type="button">Refresh</button>
 </section>
  <!--  End of Dashboard -->  
    
  </div>
</div>


  </main>
</div>   

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
   
  </body>
</html>
