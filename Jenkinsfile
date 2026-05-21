#!/usr/bin/env groovy
pipeline {
  agent any
  
  triggers {
    // GitHub webhook trigger - automatically triggers on push
    githubPush()
    // Fallback: poll SCM every 15 minutes if webhook unavailable
    pollSCM('H/15 * * * *')
  }
  
  environment {
    REGISTRY = "docker.io"
    REGISTRY_CREDS = "docker-hub-credentials"
    IMAGE_NAME = "inyakult-maker/mysystem"
    IMAGE = "${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}"
    IMAGE_LATEST = "${REGISTRY}/${IMAGE_NAME}:latest"
  }
  
  stages {
    stage('Checkout') {
      steps {
        checkout scm
      }
    }
    
    stage('Build Docker Image') {
      steps {
        script {
          echo "Building Docker image: ${IMAGE}"
          dockerImage = docker.build("${IMAGE_NAME}:${BUILD_NUMBER}")
        }
      }
    }
    
    stage('Push to Docker Hub') {
      steps {
        script {
          echo "Pushing image to Docker Hub: ${IMAGE}"
          withCredentials([usernamePassword(credentialsId: "${REGISTRY_CREDS}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
            sh '''
              echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
              docker tag ${IMAGE_NAME}:${BUILD_NUMBER} ${IMAGE}
              docker tag ${IMAGE_NAME}:${BUILD_NUMBER} ${IMAGE_LATEST}
              docker push ${IMAGE}
              docker push ${IMAGE_LATEST}
              docker logout
            '''
          }
        }
      }
    }
    
    stage('Deploy (Optional)') {
      when { expression { false } }
      steps {
        script {
          echo "Deploying with docker-compose..."
          sh 'docker-compose up -d --build'
        }
      }
    }
  }
  
  post {
    success {
      echo "Build and push successful! Image available at: ${IMAGE}"
    }
    failure {
      echo "Build or push failed. Check logs above."
    }
    always {
      archiveArtifacts artifacts: '**/logs/*.log', allowEmptyArchive: true
    }
  }
}
