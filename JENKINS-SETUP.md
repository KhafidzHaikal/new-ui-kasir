# Jenkins Setup Guide for Kasir Application

## Prerequisites
- Docker installed on your system
- Git installed
- Port 8007 available for Jenkins

## Quick Setup

1. **Run Jenkins Setup Script**
   ```bash
   jenkins-setup.bat
   ```

2. **Access Jenkins**
   - Open http://localhost:8007
   - Use the initial admin password displayed by the script

3. **Initial Jenkins Configuration**
   - Install suggested plugins
   - Create admin user
   - Complete setup wizard

## Manual Jenkins Setup

### 1. Run Jenkins Container
```bash
docker run -d \
  --name jenkins \
  --restart=unless-stopped \
  -p 8007:8080 \
  -p 50000:50000 \
  -v jenkins_data:/var/jenkins_home \
  -v /var/run/docker.sock:/var/run/docker.sock \
  -v "%cd%":/workspace \
  jenkins/jenkins:lts
```

### 2. Get Initial Password
```bash
docker exec jenkins cat /var/jenkins_home/secrets/initialAdminPassword
```

### 3. Install Required Plugins
- Docker Pipeline
- Git Plugin
- Pipeline Plugin
- Blue Ocean (optional)

## Create Pipeline Job

1. **New Item** → **Pipeline**
2. **Pipeline Configuration:**
   - Definition: Pipeline script from SCM
   - SCM: Git
   - Repository URL: `https://github.com/KhafidzHaikal/new-ui-kasir.git`
   - Branch: `*/main`
   - Script Path: `Jenkinsfile`

## Environment Variables (Optional)
Set these in Jenkins if needed:
- `DOCKER_REGISTRY`: Your Docker registry URL
- `APP_ENV`: Application environment
- `DB_PASSWORD`: Database password

## Pipeline Stages

The Jenkinsfile includes these stages:
1. **Checkout** - Clone repository
2. **Environment Setup** - Configure .env file
3. **Build Docker Image** - Build application image
4. **Stop Existing Containers** - Clean up old deployment
5. **Deploy** - Start new containers with docker-compose
6. **Health Check** - Verify application is running
7. **Cleanup** - Remove unused Docker resources

## Access Points After Deployment

- **Application**: http://localhost:8000
- **PhpMyAdmin**: http://localhost:8080
- **Jenkins**: http://localhost:8007

## Troubleshooting

### Jenkins Container Issues
```bash
# Check Jenkins logs
docker logs jenkins

# Restart Jenkins
docker restart jenkins
```

### Application Deployment Issues
```bash
# Check application logs
docker-compose logs

# Restart application
docker-compose restart
```

### Docker Permission Issues (Linux/Mac)
```bash
# Add user to docker group
sudo usermod -aG docker $USER
# Logout and login again
```

## Security Notes

- Change default passwords in production
- Use environment variables for sensitive data
- Configure proper firewall rules
- Enable HTTPS in production

## Backup

### Jenkins Data
```bash
docker cp jenkins:/var/jenkins_home ./jenkins_backup
```

### Application Data
```bash
docker-compose exec db mysqldump -u kasir_user -p kasir > backup.sql
```