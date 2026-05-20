<#
Usage (PowerShell):
$env:JENKINS_URL = 'http://localhost:8080'
$env:JENKINS_USER = 'yahiko'
$env:JENKINS_TOKEN = 'your_api_token'
$env:JOB_NAME = 'mysystem-pipeline'
.
.
.\scripts\create_jenkins_job.ps1
#>

param(
  [string]$JenkinsUrl = $null,
  [string]$JenkinsUser = $null,
  [string]$JenkinsToken = $null,
  [string]$JobName = $null
)

# Prefer explicit parameters, fall back to environment variables
$jenkinsUrl = if ($JenkinsUrl) { $JenkinsUrl } else { $env:JENKINS_URL }
$user = if ($JenkinsUser) { $JenkinsUser } else { $env:JENKINS_USER }
$token = if ($JenkinsToken) { $JenkinsToken } else { $env:JENKINS_TOKEN }
$jobName = if ($JobName) { $JobName } else { $env:JOB_NAME }

if (-not $jenkinsUrl -or -not $user -or -not $token -or -not $jobName) {
  Write-Error "Required parameters or environment variables missing. Provide either parameters or set these environment variables: JENKINS_URL, JENKINS_USER, JENKINS_TOKEN, JOB_NAME.`nExamples (PowerShell):`n$env:JENKINS_URL = 'http://localhost:8080'`n$env:JENKINS_USER = 'yahiko'`n$env:JENKINS_TOKEN = 'token'`n$env:JOB_NAME = 'mysystem-pipeline'`nOr run the script with parameters:`n.\scripts\create_jenkins_job.ps1 -JenkinsUrl 'http://localhost:8080' -JenkinsUser 'yahiko' -JenkinsToken 'token' -JobName 'mysystem-pipeline'"
  exit 1
}

# Prepare Basic Auth header
$pair = "{0}:{1}" -f $user, $token
$bytes = [System.Text.Encoding]::ASCII.GetBytes($pair)
$base64 = [Convert]::ToBase64String($bytes)
$headers = @{ Authorization = "Basic $base64" }

# Try to get Jenkins crumb (if enabled)
try {
    $crumbResp = Invoke-RestMethod -Uri "$jenkinsUrl/crumbIssuer/api/json" -Headers $headers -Method Get -ErrorAction Stop
    if ($crumbResp -and $crumbResp.crumb) {
        $headers['Jenkins-Crumb'] = $crumbResp.crumb
    }
} catch {
    # If crumb issuer not available or request failed, continue without crumb
}

$jobXml = @'
<flow-definition plugin="workflow-job">
  <description>Pipeline from SCM (created by script)</description>
  <keepDependencies>false</keepDependencies>
  <properties/>
  <definition class="org.jenkinsci.plugins.workflow.cps.CpsScmFlowDefinition" plugin="workflow-cps">
    <scm class="hudson.plugins.git.GitSCM" plugin="git">
      <configVersion>2</configVersion>
      <userRemoteConfigs>
        <hudson.plugins.git.UserRemoteConfig>
          <url>https://github.com/abilul/Mysystem.git</url>
        </hudson.plugins.git.UserRemoteConfig>
      </userRemoteConfigs>
      <branches>
        <hudson.plugins.git.BranchSpec>
          <name>*/main</name>
        </hudson.plugins.git.BranchSpec>
      </branches>
      <doGenerateSubmoduleConfigurations>false</doGenerateSubmoduleConfigurations>
      <submoduleCfg class="list"/>
      <extensions/>
    </scm>
    <scriptPath>Jenkinsfile</scriptPath>
    <lightweight>true</lightweight>
  </definition>
  <triggers/>
  <disabled>false</disabled>
</flow-definition>
'@

Write-Output "Creating Jenkins job '$jobName' at $jenkinsUrl ..."
try {
    Invoke-RestMethod -Uri "$jenkinsUrl/createItem?name=$jobName" -Method Post -Headers $headers -ContentType 'application/xml' -Body $jobXml -ErrorAction Stop
    Write-Output "Job '$jobName' created (or already exists). Check Jenkins UI to verify."
} catch {
    Write-Error "Failed to create job: $_"
    exit 1
}
