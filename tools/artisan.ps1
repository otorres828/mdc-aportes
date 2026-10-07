param([Parameter(ValueFromRemainingArguments=$true)][string[]]$ArtisanArguments)
$projectPath = Split-Path $PSScriptRoot -Parent
$phpExecutable = 'C:\laragon\bin\php\php-8.3.0-nts-Win32-vs16-x64\php.exe'
if (!(Test-Path -LiteralPath $phpExecutable)) { $phpExecutable = (Get-Command php -ErrorAction Stop).Source }
Push-Location -LiteralPath $projectPath
try {
    & $phpExecutable artisan @ArtisanArguments
    $artisanExit = $LASTEXITCODE
} finally {
    Pop-Location
}
exit $artisanExit