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
