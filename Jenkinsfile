@Library("jenkins-shared-lib@master") _

import groovy.json.JsonSlurperClassic
import groovy.json.JsonOutput
import io.leaf.sre.Builder
import io.leaf.sre.Docker
import io.leaf.sre.Notify

// Default env is "stg" (qa)
def envName = "stg"

def renamedBranch = sanitizeBranch(env.BRANCH_NAME)
def subDomain = "wellandgood.com"

def dockerMainTag
def hostName = "${renamedBranch}.${envName}.${subDomain}"

def mainNs = "wellandgoodwp"
def mysqlMasterClone = "master-clone-mysql"
def mySQLDeployName = "mysql-${renamedBranch}-wp"

def originalBranch = env.BRANCH_NAME.replaceAll(/\//, '-')

def isProduction = false
def isStaging = true

def notifyMsg
def notifyErr

def builder
def config

def dbUser
def dbPass
def dbName = 'pantheon'
def dbDump = "/var/lib/mysql/${dbName}.sql"

def cronjobSchedule = "*/30 * * * *"
def cronjobScheduleProd = "*/1 * * * *"
def jobSchedule = cronjobSchedule

node {
    stage("Checkout code") {
        checkout scm
    }

    if (env.BRANCH_NAME == 'master') {
        isProduction = true
        isStaging = false
        envName = 'master'
    }

    dockerMainTag = "${renamedBranch}-${envName}-${env.BUILD_NUMBER}"


    // We're using one DB per branch except for production
    if (!isProduction) {
        stage("MySQL clone") {
            def mysqlConfig = [
                include: [
                    [
                        name: "mysql",
                        values: [
                            renamedBranch: mySQLDeployName,
                            branch: originalBranch
                        ]
                    ]
                ]
            ]

            // Creating specific directories to run `kontemplate`
            sh "mkdir -p mysql-k8s/mysql"
            // Copying original template from sharedlibs
            writeFile file: "mysql-k8s/mysql/main.yaml", text: libraryResource("templates/wellandgood/main/mysql.yaml")
            // Generating JSON file required by `kontemplate`
            writeFile file: "mysql-k8s/mysql.json", text: JsonOutput.toJson(mysqlConfig)
            dir("mysql-k8s") {
                sh """
                kubectl scale deploy ${mysqlMasterClone} --replicas=0 -n ${mainNs}
                kontemplate template mysql.json | kubectl apply -f - --record -n ${mainNs}
                kubectl scale deploy ${mysqlMasterClone} --replicas=1 -n ${mainNs}
                """
            }

            withCredentials([string(credentialsId: 'qa-dbpass', variable: 'DB_PASSWORD')]) {
                dbPass = sh(returnStdout: true, script: 'echo $DB_PASSWORD').trim()
            }
            withCredentials([string(credentialsId: 'qa-dbuser', variable: 'DB_USER')]) {
                dbUser = sh(returnStdout: true, script: 'echo $DB_USER').trim()
            }
        }

        stage("MySQL replace URLs") {
            def podDb = ''
            // Making sure MySQL container is running. We're not using jsonpath here just to avoid `array out of bounds` error
            while (!podDb.contains("Running")) {
                podDb = sh(returnStdout: true, script: "kubectl get po -l tier=db,branch=${originalBranch},app=wp -n ${mainNs} --no-headers --field-selector status.phase=Running").trim()
            }

            sh 'sleep 10s'
            podDb = sh(returnStdout: true, script: "kubectl get po -l tier=db,branch=${originalBranch},app=wp -n ${mainNs} --no-headers --field-selector status.phase=Running -o=jsonpath='{.items[0].metadata.name}'").trim()


            // Before continuing, we're going to set DB read access only
            def vaultData = readFromVault("qa/db-staging")
            def roCmd = "DROP USER 'pantheon'@'%'; CREATE USER 'pantheon'@'%' IDENTIFIED BY PASSWORD '${vaultData.data.DB_USER_ENCODED_PW}'; GRANT SELECT ON pantheon.* TO 'pantheon'@'%'; FLUSH PRIVILEGES;"

            mysqlCmd = "set +x; kubectl exec -ti ${podDb} -n ${mainNs} -- bash -c \"mysql ${dbName} -u root -p${vaultData.data.DB_ROOT_PW} -e \\\"${roCmd}\\\" \""
            sh "${mysqlCmd}"
            println "RO is set"

            // Generating dump file
            mysqlCmd = "set +x; kubectl exec -ti ${podDb} -n ${mainNs} -- bash -c \"mysqldump ${dbName} -u root -p${vaultData.data.DB_ROOT_PW} > ${dbDump}\""
            sh "${mysqlCmd}"
            // Replacing URLs
            replaceCmd = "kubectl exec -ti ${podDb} -n ${mainNs} -- sed -r -i -e 's/(http|https):\\/\\/(www.wellandgood.com|wellandgood.com)/https:\\/\\/${hostName}/g' ${dbDump}"
            sh "${replaceCmd}"
            // Taking care of stored serialized arrays with URLs
            perlCmd = "perl -pi -e 's/(s:)(\\d+:)(\\\\\"https:[^;]+)/\\1@{[length(\$3)-4]}:\\3/g'"
            replaceCmd = "kubectl exec -ti ${podDb} -n ${mainNs} -- ${perlCmd} ${dbDump}"
            sh "${replaceCmd}"
            // Loading replaced DB
            replaceCmd = "set +x; kubectl exec -ti ${podDb} -n ${mainNs} -- bash -c \"mysql ${dbName} -u root -p${vaultData.data.DB_ROOT_PW} < ${dbDump}\""
            sh "${replaceCmd}"

            // Restoring RW permissions
            def rwCmd = "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, LOCK TABLES, INDEX ON pantheon.* TO 'pantheon'@'%'; FLUSH PRIVILEGES;"
            mysqlCmd = "set +x; kubectl exec -ti ${podDb} -n ${mainNs} -- bash -c \"mysql ${dbName} -u root -p${vaultData.data.DB_ROOT_PW} -e \\\"${rwCmd}\\\" \""
            sh "${mysqlCmd}"
            println "RW is back"
        }
    }


    stage("WP config") {
        // Reading DB credentials for prod
        if (isProduction) {
            withCredentials([string(credentialsId: 'prod-dbpass', variable: 'DB_PASSWORD')]) {
                dbPass = sh(returnStdout: true, script: 'echo $DB_PASSWORD').trim()
            }
            withCredentials([string(credentialsId: 'prod-dbuser', variable: 'DB_USER')]) {
                dbUser = sh(returnStdout: true, script: 'echo $DB_USER').trim()
            }
            jobSchedule = cronjobScheduleProd
        }

        if (env.BRANCH_NAME == 'develop') {
            jobSchedule = cronjobScheduleProd
        }

        config = [
            docker: [
                main: [
                    tag: dockerMainTag,
                    buildArgs: [
                        NPM_URL: "https://${hostName}"
                    ]
                ],
            ],
            k8s: [
                gitcommit: sh(returnStdout: true, script: "git rev-parse --short HEAD").trim(),
                renamedBranch: renamedBranch,
                deploy: "${renamedBranch}-wp",
                isProduction: isProduction,
                jobSchedule: jobSchedule,
                // `/` is not allowed as K8s label
                branch: originalBranch
            ],
            misc: [
                is_production: isProduction,
                is_staging: isStaging
            ]
        ]

        if (!isProduction) {
            config.misc.host = hostName
            config.misc.db_host = mySQLDeployName
        }

        builder = new Builder(pipeline: this, environment: envName, config: config)

        if (isProduction) {
            // Reading `hostName` from `master.json` file
            hostName = builder.context.config.misc.host
            builder.context.config.docker.main.buildArgs.NPM_URL = "https://${hostName}"
        }

        // Generating new secrets to make sure we don't get errors during login
        // New secrets will expire current logged sessions
        def wpSecrets = sh(returnStdout: true, script: "curl -s https://api.wordpress.org/secret-key/1.1/salt/")

        wpSecrets += "define('DB_PASSWORD', '${dbPass}');\n"
        wpSecrets += "define('DB_USER', '${dbUser}');\n"
        wpSecrets += "define('DB_NAME', '${dbName}');\n"
        wpSecrets += "define('DB_HOST', '${config.misc.db_host}');\n"

        def wpConfig = readFile("wp-config-k8s.php")
        def serverName = "\$_SERVER['SERVER_NAME'] = '${hostName}';"
        def configPHP = "<?php\n${wpSecrets}\n${serverName}\n"
        writeFile file: "wp-config.php", text: "${configPHP}${wpConfig}"

        // msmtp conf
        def mailCmd = "perl -pi -e 's/<hostName>/${hostName}/g' email-server/msmtprc"
        sh "${mailCmd}"

        notifyMsg = "Branch ${renamedBranch} deployed: https://${hostName}"
        notifyErr = "Something went wrong with the build: ${env.JOB_URL}${env.BUILD_NUMBER}/console"
    }

    // This command defines build and kubernetes stages
    builder.build(true, notifyMsg, notifyErr)

} // node


@NonCPS
def sanitizeBranch(branchName) {
    def truncateName = branchName.toLowerCase()
    truncateName = truncateName.replaceAll(/^(feature|task|hotfix)\//, '')
    truncateName = truncateName.replaceAll(/[\/|_|.]/, '-' )
    truncateName = truncateName.take(12)
    truncateName = truncateName.replaceAll(/-$/,'')
    truncateName
}


def readFromVault(secretPath) {
    sh """
    set +x
    curl -X GET -H "X-Vault-Token: $VAULT_TOKEN" $VAULT_ADDR/v1/secret/sre/build/project/wellandgood/${secretPath} > vault.txt
    """
    def vaultData = readFile("vault.txt").trim()
    return new JsonSlurperClassic().parseText(vaultData)
}
