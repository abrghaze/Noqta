# Clean Git History Script

Write-Host "Step 1: Removing old Git history..." -ForegroundColor Yellow
Remove-Item -Path .git -Recurse -Force -ErrorAction SilentlyContinue
Write-Host "Done!" -ForegroundColor Green

Write-Host "`nStep 2: Initializing new Git repo..." -ForegroundColor Yellow
git init
Write-Host "Done!" -ForegroundColor Green

Write-Host "`nStep 3: Adding all files..." -ForegroundColor Yellow
git add .
Write-Host "Done!" -ForegroundColor Green

Write-Host "`nStep 4: Creating initial commit..." -ForegroundColor Yellow
git commit -m "Initial commit: Noqta - School Management System

Complete Laravel 11 school management application with:
- Multi-role authentication (Director, Teacher, Student, Parent)
- Grade management with automatic notifications
- Attendance tracking
- Real-time notification system
- Modern UI with Tailwind CSS and Alpine.js
- Comprehensive testing suite
- PostgreSQL database

Tech Stack: Laravel 11, PHP 8.2+, PostgreSQL, Tailwind CSS, Alpine.js, Chart.js"
Write-Host "Done!" -ForegroundColor Green

Write-Host "`n=== GIT HISTORY RESET COMPLETE ===" -ForegroundColor Cyan
Write-Host "`nYour project now has a clean history with 1 commit!" -ForegroundColor Green

Write-Host "`nNext steps:" -ForegroundColor Yellow
Write-Host "1. Delete old repo on GitHub" -ForegroundColor White
Write-Host "2. Create new repo 'Noqta' on GitHub" -ForegroundColor White
Write-Host "3. Run: git remote add origin https://github.com/abrghaze/Noqta.git" -ForegroundColor White
Write-Host "4. Run: git branch -M main" -ForegroundColor White
Write-Host "5. Run: git push -u origin main" -ForegroundColor White
