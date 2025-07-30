@echo off
echo Setting up Jenkins with Docker...

REM Create Jenkins data directory
if not exist "jenkins_data" mkdir jenkins_data

REM Run Jenkins in Docker container
docker run -d ^
  --name jenkins ^
  --restart=unless-stopped ^
  -p 8007:8080 ^
  -p 50000:50000 ^
  -v jenkins_data:/var/jenkins_home ^
  -v /var/run/docker.sock:/var/run/docker.sock ^
  -v "%cd%":/workspace ^
  jenkins/jenkins:lts

echo Waiting for Jenkins to start...
timeout /t 30

echo Jenkins is starting up...
echo Access Jenkins at: http://localhost:8007
echo.
echo To get the initial admin password, run:
echo docker exec jenkins cat /var/jenkins_home/secrets/initialAdminPassword
echo.
echo Press any key to get the initial password...
pause

docker exec jenkins cat /var/jenkins_home/secrets/initialAdminPassword

echo.
echo Setup Instructions:
echo 1. Open http://localhost:8007 in your browser
echo 2. Use the password shown above
echo 3. Install suggested plugins
echo 4. Create your admin user
echo 5. Create a new Pipeline job
echo 6. Configure it to use the Jenkinsfile from your repository
echo.
pause