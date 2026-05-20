#!/usr/bin/env bash
set -euo pipefail
# Usage: set env vars JENKINS_URL, JENKINS_USER, JENKINS_TOKEN, JOB_NAME then run this script

if [ -z "${JENKINS_URL:-}" ] || [ -z "${JENKINS_USER:-}" ] || [ -z "${JENKINS_TOKEN:-}" ] || [ -z "${JOB_NAME:-}" ]; then
  echo "Required environment variables: JENKINS_URL, JENKINS_USER, JENKINS_TOKEN, JOB_NAME"
  echo "Example: export JENKINS_URL=http://localhost:8080 && export JENKINS_USER=yahiko && export JOB_NAME=mysystem-pipeline"
  exit 1
fi

CRUMB_JSON=$(curl -sS --user "$JENKINS_USER:$JENKINS_TOKEN" "$JENKINS_URL/crumbIssuer/api/json" || true)
if [ -n "$CRUMB_JSON" ] && echo "$CRUMB_JSON" | grep -q "crumb"; then
  CRUMB=$(echo "$CRUMB_JSON" | sed -n 's/.*"crumb":"\([^"]*\)".*/\1/p')
  CRUMB_FIELD="-H Jenkins-Crumb:$CRUMB"
else
  CRUMB_FIELD=""
fi

read -r -d '' JOB_CONFIG_XML <<'XML'
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
XML

echo "Creating Jenkins job '$JOB_NAME' at $JENKINS_URL"
curl -sS --user "$JENKINS_USER:$JENKINS_TOKEN" $CRUMB_FIELD \
  -H "Content-Type: application/xml" \
  --data-binary @- "$JENKINS_URL/createItem?name=$JOB_NAME" <<EOF
$JOB_CONFIG_XML
EOF

echo "Job creation request sent. Check Jenkins UI for job '$JOB_NAME'."
