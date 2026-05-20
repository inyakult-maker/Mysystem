<#
Automated GitHub push script
Sets git user config, stages all files, commits, and force-pushes to GitHub
Usage:
.\scripts\push_to_github.ps1 -GitName "Your Name" -GitEmail "your@email.com" -RepoUrl "https://github.com/inyakult-maker/Mysystem.git"
#>

param(
    [string]$GitName = "Developer",
    [string]$GitEmail = "your_email@example.com",
    [string]$RepoUrl = "https://github.com/inyakult-maker/Mysystem.git"
)

Write-Output "=== Git Push Setup ==="
Write-Output "Setting git user: $GitName <$GitEmail>"

git config --global user.email $GitEmail
git config --global user.name $GitName

Write-Output "Staging all files..."
git add --all

Write-Output "Committing..."
git commit -m "Initial commit: add project, CI, Docker, Jenkins scripts, and docs"

if ($LASTEXITCODE -ne 0) {
    Write-Error "Commit failed. Check git status."
    exit 1
}

Write-Output "Ensuring main branch..."
git branch -M main

Write-Output "Configuring remote..."
git remote remove origin 2>$null
git remote add origin $RepoUrl

Write-Output "Force-pushing to $RepoUrl ..."
git push --force-with-lease origin main -u

if ($LASTEXITCODE -eq 0) {
    Write-Output "SUCCESS: Pushed to GitHub!"
    Write-Output "View your repo: $RepoUrl"
} else {
    Write-Error "Push failed. Check your credentials and internet connection."
    exit 1
}
