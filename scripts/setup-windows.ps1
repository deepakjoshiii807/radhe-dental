param(
    [switch]$RemoveJson = $false
)

# Script to inject service-account.json into .env for Windows PowerShell
# Usage: .\scripts\setup-windows.ps1           # keeps service-account.json
#        .\scripts\setup-windows.ps1 -RemoveJson  # deletes service-account.json after use

$root = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $root

$servicePath = Join-Path $root 'service-account.json'
$envPath = Join-Path $root '.env'
$envExample = Join-Path $root '.env.example'

if (-not (Test-Path $servicePath)) {
    Write-Error "service-account.json not found in project root ($servicePath). Place it there and re-run the script."
    exit 1
}

if (-not (Test-Path $envPath)) {
    if (Test-Path $envExample) {
        Copy-Item $envExample $envPath
        Write-Output "Created .env from .env.example"
    } else {
        Write-Error ".env not found and .env.example missing. Create .env manually and re-run."
        exit 1
    }
}

# Ensure PHP is available
$php = 'php'
try {
    & $php -v > $null 2>&1
} catch {
    Write-Error "PHP not found on PATH. Provide full path to php.exe by editing this script or add PHP to PATH."
    exit 1
}

# Generate APP_KEY (non-interactive)
Write-Output "Generating APP_KEY..."
& $php artisan key:generate

# Read service account JSON
Write-Output "Reading service-account.json..."
$saRaw = Get-Content $servicePath -Raw
try {
    $sa = $saRaw | ConvertFrom-Json
} catch {
    Write-Error "service-account.json is not valid JSON."
    exit 1
}

# Prepare values
$projectId = $sa.project_id
$clientEmail = $sa.client_email
$privateKey = $sa.private_key -replace "`n","\\n"

# Update .env (replace existing lines or append)
$envLines = Get-Content $envPath -Raw
if ($envLines -match 'FIREBASE_PROJECT_ID=') {
    $envLines = $envLines -replace 'FIREBASE_PROJECT_ID=.*', "FIREBASE_PROJECT_ID=$projectId"
} else {
    $envLines = $envLines + "`nFIREBASE_PROJECT_ID=$projectId"
}

if ($envLines -match 'FIREBASE_CLIENT_EMAIL=') {
    $envLines = $envLines -replace 'FIREBASE_CLIENT_EMAIL=.*', "FIREBASE_CLIENT_EMAIL=$clientEmail"
} else {
    $envLines = $envLines + "`nFIREBASE_CLIENT_EMAIL=$clientEmail"
}

if ($envLines -match 'FIREBASE_PRIVATE_KEY=') {
    $envLines = $envLines -replace 'FIREBASE_PRIVATE_KEY=.*', "FIREBASE_PRIVATE_KEY=\"$privateKey\""
} else {
    $envLines = $envLines + "`nFIREBASE_PRIVATE_KEY=\"$privateKey\""
}

Set-Content -Path $envPath -Value $envLines -Encoding UTF8
Write-Output ".env updated with Firebase service account values."

if ($RemoveJson) {
    Remove-Item $servicePath -Force
    Write-Output "service-account.json removed as requested."
}

Write-Output "Setup complete. You can now run 'npm run build' and 'php artisan serve'."
