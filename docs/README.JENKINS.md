# Jenkins: pipeline and remote access

Files added:
- `Jenkinsfile` (root) — Declarative pipeline that builds a Docker image. Push and Deploy stages are disabled by default and expect Jenkins credentials.

Before you enable remote push or deploy, configure the following in Jenkins (do not store secrets in the repository):

1. Credentials
   - Create a **Username with password** credential (ID: `registry-credentials`) for Docker registry pushes.
   - Optionally create Git credentials or use the built-in GitHub integration for webhooks.

2. Jenkins host
   - Ensure Jenkins can reach the Docker host (if using `docker.build` and `docker-compose` on the agent). Jenkins agents must have Docker and Docker Compose installed.

3. Create a Pipeline job
   - New Item → Pipeline → select `Pipeline script from SCM` → Git with this repository URL.
   - Use the `Jenkinsfile` from the repository.

4. Webhooks (optional)
   - For automatic builds on push, configure a GitHub webhook to `http://<jenkins-host>/github-webhook/` or use the Git plugin's polling.

Security notes:
- Do NOT commit API tokens or passwords. Use Jenkins Credentials store and reference them by ID in the `Jenkinsfile`.
- The token you provided should be entered into Jenkins or used to configure remote integrations — do not put it in code.

Questions I need from you to finish remote setup:

1. Is your Jenkins accessible from GitHub (public) or only locally at `http://localhost:8080/`? If GitHub cannot reach it, webhooks won't work.
2. Do you want the pipeline to push images to Docker Hub or to a private registry? If Docker Hub, please provide the registry/repo name (not credentials).
3. Does your Jenkins agent run on the same host as Docker (so it can build images), and does it have Docker & Docker Compose installed?
4. Do you want automatic deploy (enabled) after build, or only build artifacts?
5. Do you want me to add example Jenkins credentials configuration snippets or a small script to install Docker on the agent?

After you answer those, I'll update the `Jenkinsfile` to enable the push/deploy stages and provide exact commands to configure the Jenkins job and webhook.

Example: create a Pipeline job via script
----------------------------------------

You can create a Pipeline job that points to this repo using the included script `scripts/create_jenkins_job.sh`.

1. Export required environment variables (do not commit these values):

```bash
export JENKINS_URL=http://localhost:8080
export JENKINS_USER=yahiko
export JENKINS_TOKEN=your_api_token_here
export JOB_NAME=mysystem-pipeline
```

2. Run the script from the repository root:

```bash
bash scripts/create_jenkins_job.sh
```

Notes:
- The script uses Jenkins crumb issuer when available and posts a minimal pipeline job XML that points at `https://github.com/abilul/Mysystem.git` and the repository `Jenkinsfile`.
- The script does not store credentials in the repo. Keep the token private and prefer creating Jenkins credentials via the Jenkins UI when possible.

