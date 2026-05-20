#!/usr/bin/env groovy
pipeline {
  agent any
  environment {
    IMAGE = "abilul/mysystem:${env.BUILD_NUMBER}"
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
          dockerImage = docker.build(env.IMAGE)
        }
      }
    }
    stage('Push Image (disabled by default)') {
      when { expression { false } }
      steps {
        withCredentials([usernamePassword(credentialsId: 'registry-credentials', usernameVariable: 'REG_USER', passwordVariable: 'REG_PASS')]) {
          script {
            docker.withRegistry('', 'registry-credentials') {
              dockerImage.push()
              dockerImage.push('latest')
            }
          }
        }
      }
    }
    stage('Deploy using docker-compose (disabled by default)') {
      when { expression { false } }
      steps {
        sh 'docker-compose up -d --build'
      }
    }
  }
  post {
    always {
      archiveArtifacts artifacts: '**/logs/*.log', allowEmptyArchive: true
    }
  }
}
