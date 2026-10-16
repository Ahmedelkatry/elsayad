$src = 'c:\xampp\htdocs\adam\resources\views\employees\employees_clean.blade.php'
$dst = 'c:\xampp\htdocs\adam\resources\views\employees\employees.blade.php'
$content = [System.IO.File]::ReadAllBytes($src)
[System.IO.File]::WriteAllBytes($dst, $content)
Write-Host "Replaced successfully."
