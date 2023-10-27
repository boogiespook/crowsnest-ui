<?php

function putAperture($domainId) {
# Get total number of capabilities
$capabilityCount = "select count(capability) as total, domain.description from capability,domain where domain.id = capability.domain_id and domain.id ='" . $domainId . "' group by domain.description;";
$capabilityCountTotal = pg_query($capabilityCount) or die('Error message: ' . pg_last_error());
$capabilityRow = pg_fetch_assoc($capabilityCountTotal);
$totalCapabilities = $capabilityRow['total'];
if ($totalCapabilities != "") {
$capabilityName = $capabilityRow['description'];
$greenCount = "select count(flag_id) as totalgreen from capability where domain_id = '" . $domainId . "' and flag_id = '2'";
$greenTotal = pg_query($greenCount) or die('Error message: ' . pg_last_error());
$greens = pg_fetch_assoc($greenTotal);
$totalGreens = $greens['totalgreen'];

$percentComplete = ($totalGreens / $totalCapabilities) * 100;

# If greens < total, add red aperture
if ($totalGreens < $totalCapabilities) {
print "<img src=images/aperture-red-closed.png title='" . round($percentComplete) . "% Compliant'>";
} else {
print "<img src=images/aperture-green.png title='" . round($percentComplete) . "% Compliant'>";
}
} else {
print "<img src=images/aperture-red-closed.png>";
}
}

function putIcon($colour, $capability) {
if ($colour == 'green') {
print '
<span class="pf-c-icon pf-m-inline">
  <span class="pf-c-icon__content  pf-m-success">
    <i class="fas fa-check-circle" aria-hidden="true"></i>
  </span>
</span>&nbsp;<span>' . $capability . "</span><br><br>";
} else {
print '
<span class="pf-c-icon pf-m-inline">
  <span class="pf-c-icon__content pf-m-danger">
    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
  </span>
</span>&nbsp;<span>' . $capability . "</span><br><br>";
}
}

function putProfileOptions() {
$qq = "select id, name from profiles order by id asc";
$profilesCall = pg_query($qq) or die('Error message: ' . pg_last_error());
print '<div class="pf-c-toggle-group "><span class="profileTitle">Profiles:</span> ';
while ($row = pg_fetch_assoc($profilesCall)) {
print '
<div class="pf-c-toggle-group__item">
    <button onclick="location.href=\'index.php?profile=' . $row['id'] . '&name=' . $row['name'] . '\'" class="pf-c-toggle-group__button" type="button">
      <span class="pf-c-toggle-group__text">' . $row['name'] . '</span>
    </button>
  </div>

';
	}
print "</div>";
}

function getDomainsByProfile($profile) {
# First get the domain IDs based  on the profile
$domains = "select array_to_json(domains) as domain from profiles where id = '" . $profile . "'";
$selectedDomains = pg_query($domains) or die('Error message: ' . pg_last_error());
$selectedDomainsArray = pg_fetch_array($selectedDomains);
$selectedDomains = json_decode($selectedDomainsArray[0]);
return $selectedDomains;
}

function getDomainsForProfiles() {
$getDomains = "select description, id from domain order by description asc";
$domainResults = pg_query($getDomains) or die('Error message: ' . pg_last_error());
while ($row = pg_fetch_assoc($domainResults)) {
print '    &nbsp<input type="checkbox" name="domain' . $row['id'] . '" value="' . $row['id'] . '" id="' . $row['id']. '" >&nbsp' . $row['description'] . '<br>';  	
}

}

function putUserTabs() {
print '
  <input type="radio" name="tabset" id="tab1" aria-controls="dashboard" checked>
  <label for="tab1" >Dashboard</label>
';

}

?>