#!/bin/bash

####################################################################
## This script will deploy to a pantheon site's multidev environment 
## and associated branch. Assumes CI variables populated and the 
## before_script properly adds a 'pantheon' remote to the git repo.
##
## Variables:
## - $CI_COMMIT_REF_NAME, defined by GitLab CI or by user
## - $PANTHEON_SITE_ID, defined in environment variables or by user
## - $THEME_NAME, defined by environment variables
## - $ENVIRONMENT, defined automatically
####################################################################

SCRIPT_PATH="`dirname \"$0\"`"

# Terminal colors
source $SCRIPT_PATH/colors.sh

if [ -z ${CI_COMMIT_REF_NAME+x} ]; then
    echo -e "${BLUE}Hmm... Looks like this is not being run in GitLab CI. What's the original branch name?${DEFAULT}"
    read CI_COMMIT_REF_NAME
fi

if [ -z ${PANTHEON_SITE_ID+x} ]; then
    echo -e "${BLUE}Hmm... Something is missing. What is the Pantheon Site Name?${DEFAULT}"
    read PANTHEON_SITE_ID
fi

if [ -z ${THEME_NAME+x} ]; then
    echo -e "${BLUE}Hmm... Something is missing. What is the Theme Name?${DEFAULT}"
    read THEME_NAME
fi

TARGET=$(echo $CI_COMMIT_REF_NAME | cut -d'/' -f2)
ENV=$(echo ${TARGET:0:11} | tr '[:upper:]' '[:lower:]') 
ENVIRONMENT="${ENV%-}"

echo -e "${YELLOW}Checking which database should be used...${DEFAULT}"

MATCH=$(terminus multidev:list --format=string --fields=id $PANTHEON_SITE_ID | grep $ENVIRONMENT)
MATCH_EXISTS="$?"

LIVE_TAG=$(git ls-remote --tags pantheon | grep "pantheon_live_")
LIVE_EXISTS="$?"

TEST_TAG=$(git ls-remote --tags pantheon | grep "pantheon_test_")
TEST_EXISTS="$?"

if [ "$LIVE_EXISTS" -eq "0" ]; then
    DATA_ENVIRONMENT="live"
elif [ "$TEST_EXISTS" -eq "0" ]; then
    DATA_ENVIRONMENT="test"
else
    DATA_ENVIRONMENT="dev"
fi

echo "${YELLOW}Looks like the most recent data can be found in the <$DATA_ENVIRONMENT> environment.${DEFAULT}"
echo "${YELLOW}Checking if ENV '$ENVIRONMENT' exists...${DEFAULT}"

# note potential false-positive if $PANTHEON_SITE_ID|$ENVIRONMENT is !defined
if [[ "$MATCH_EXISTS" -ne 0 ]] 
then 
    echo "${RED}Multidev not found.${DEFAULT}"
    echo "${YELLOW}Creating $ENVIRONMENT from the <$DATA_ENVIRONMENT> environment...${DEFAULT}"
    terminus multidev:create $PANTHEON_SITE_ID.$DATA_ENVIRONMENT $ENVIRONMENT
    if [[ "$?" -ne 0 ]]; then
        exit 1
    fi
    echo $DONE
else
    echo "${YELLOW}Looks like the $ENVIRONMENT environment exists.${DEFAULT}"
fi

echo "${YELLOW}Setting Pantheon '$ENVIRONMENT' to git mode...${DEFAULT}"
terminus connection:set $PANTHEON_SITE_ID.$ENVIRONMENT git
if [[ "$?" -ne 0 ]]; then
    exit 1
fi
echo $DONE

echo "${YELLOW}Pushing to '$ENVIRONMENT' on Pantheon...${DEFAULT}"
git push -f pantheon HEAD:$ENVIRONMENT
if [[ "$?" -ne 0 ]]; then
    exit 1
fi
echo $DONE
