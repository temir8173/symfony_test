# импортирует сертификат с нашими локальными доменами в хранилище
Import-Certificate -FilePath "private.crt" -CertStoreLocation Cert:\LocalMachine\Root
echo "Certificates was imported"

$hostsFileName = 'C:\Windows\System32\drivers\etc\hosts'
$hosts = Get-Content -Raw -Path 'C:\Windows\System32\drivers\etc\hosts'
$webserverHosts = Get-Content -Raw -Path '../samples/hosts'

if($hosts.Contains('# Webserver hosts') -And $hosts.Contains('# Webserver hosts end')) {
      $replacedHosts = $hosts -replace '(?ms)# Webserver hosts.*# Webserver hosts end', $webserverHosts
} else {
      $replacedHosts = "$($hosts)","$($webserverHosts)"
}

$replacedHosts | Set-Content -Path 'C:\Windows\System32\drivers\etc\hosts'

echo "Hosts file was updated"
