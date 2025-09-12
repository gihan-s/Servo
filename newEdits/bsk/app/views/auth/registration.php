<?php
// Data passed from controller: $clientEmails, $providerEmails, $providerNics
$baseUrl = BASE_URL;
ob_start();
include BASE_PATH . '/app/views/static/registration_static.php';
$html = ob_get_clean();
// Inject JSON data blobs before closing </body> for client-side validation
$payloadScript = '<script>window.__REG_DATA__ = ' . json_encode([
	'clientEmails' => $clientEmails ?? [],
	'providerEmails' => $providerEmails ?? [],
	'providerNics' => $providerNics ?? [],
	'categories' => $categories ?? [],
	'skills' => $skills ?? [],
	'locations' => $locations ?? [],
	'districts' => $districts ?? [],
]) . ';</script>';
$html = str_replace('</body>', $payloadScript . '</body>', $html);
// Adjust asset paths when app served from subfolder
$html = str_replace('"/assets/', '"' . $baseUrl . '/assets/', $html);
echo $html;
?>


