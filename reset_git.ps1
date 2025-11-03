# Script pour créer un historique Git propre

Write-Host "=== NETTOYAGE DE L'HISTORIQUE GIT ===" -ForegroundColor Cyan

# 1. Supprimer l'ancien historique Git
Write-Host "`n1. Suppression de l'ancien .git..." -ForegroundColor Yellow
Remove-Item -Path .git -Recurse -Force -ErrorAction SilentlyContinue
Write-Host "   ✓ Historique supprimé" -ForegroundColor Green

# 2. Initialiser un nouveau repo
Write-Host "`n2. Initialisation d'un nouveau repo Git..." -ForegroundColor Yellow
git init
Write-Host "   ✓ Nouveau repo créé" -ForegroundColor Green

# 3. Ajouter tous les fichiers
Write-Host "`n3. Ajout de tous les fichiers..." -ForegroundColor Yellow
git add .
Write-Host "   ✓ Fichiers ajoutés" -ForegroundColor Green

# 4. Créer le commit initial
Write-Host "`n4. Création du commit initial..." -ForegroundColor Yellow
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

Write-Host "   OK Commit initial cree" -ForegroundColor Green

Write-Host "`n=== HISTORIQUE GIT REINITIALISE ===" -ForegroundColor Cyan
Write-Host "`nVotre projet a maintenant un historique propre avec 1 seul commit!" -ForegroundColor Green
Write-Host "`nProchaines etapes:" -ForegroundColor Yellow
Write-Host "1. Supprimez ancien repo sur GitHub" -ForegroundColor White
Write-Host "2. Creez nouveau repo Noqta sur GitHub" -ForegroundColor White
Write-Host "3. git remote add origin URL" -ForegroundColor White
Write-Host "4. git branch -M main" -ForegroundColor White
Write-Host "5. git push -u origin main" -ForegroundColor White
