#!/bin/bash

####################################################################
## From the develop branch, this script:
## - Updates the Changelog (automatically with commit messages)
## - Opens vim to interactively confirm and complete the CHANGELOG
## - Bumps the npm version
## - Commits the above in a single commit
####################################################################


THEME_NAME="wellgood-2016"
SEM="minor"
SCRIPT_PATH="`dirname \"$0\"`"
DEFAULT_CRITICAL_URL=https://www.wellandgood.com/

# Terminal colors
source $SCRIPT_PATH/colors.sh


# handle arguments
for i in "$@"; do
  case $i in
    -v=*|--versiontype=*)
    SEM="${i#*=}"
    shift # past argument=value
    ;;
    -t=*|--themename=*)
    THEME_NAME="${i#*=}"
    shift # past argument=value
    ;;
    --help)
    echo "Utility Usage:"
    echo "--"
    echo "prepare.sh -v=major|minor|patch -f=hotfix|release -s=yes|no"
    shift # past argument with no value
    ;;
    *)
    echo "Unknown option: ${i#*=}"
    ;;
  esac
done


# Platform detection
PLATFORM='unknown'
DETECTED=$(uname | tr '[:upper:]' '[:lower:]')
if [[ "$DETECTED" == 'linux' ]]; then
   PLATFORM='linux'
elif [[ "$DETECTED" == 'darwin' ]]; then
   PLATFORM='macos'
fi


# this is to always be run from the root of the project
CWD=$(pwd)
printf "\n%s\n\n" "Current working directory is: ${YELLOW}$CWD${DEFAULT}"


# theme path
if [ $(basename $PWD) != $THEME_NAME ]; then
  THEME_PATH="./wp-content/themes/$THEME_NAME"
  printf "\nTheme path is: ${YELLOW}$THEME_PATH${DEFAULT}"
  printf "\nChanging directory to ${BLUE}$THEME_PATH${DEFAULT}"
  cd $THEME_PATH
  CWD=$(pwd)
  printf "\nCurrent working directory is now: ${BLUE}$CWD${DEFAULT}\n"
fi


# get current version, assumes prior git tag
GET_CURR_VERSION="npm -v $THEME_NAME"
CURR_VERSION="v$(node -p -e "require('./package.json').version")"


# get next version with npm, unless you find a clever regex that works
GET_NEXT_VERSION="npm version $SEM --no-git-tag"
NEXT_VERSION=$(eval $GET_NEXT_VERSION)
git reset --hard HEAD


printf "\n%s\n\n" "Current version is: ${YELLOW}$CURR_VERSION${DEFAULT}"
printf "%s\n\n" "Running ${YELLOW}$SEM${DEFAULT} release"
printf "%s\n\n" "Next version is: ${YELLOW}$NEXT_VERSION${DEFAULT}"


# Remove the "v"
ALT_CURR_VERSION=${CURR_VERSION:1}
ALT_NEXT_VERSION=${NEXT_VERSION:1}


# check to process Critical CSS before continuing
read -r -p "Do you want to build Critical CSS? [y/N] " critical_response
case "$critical_response" in
  [yY][eE][sS]|[yY])

  printf "\nInstalling locked dependencies..."
  npm ci

  read -p "Do you want to run Critical CSS against a different site than production? [https://www.wellandgood.com/]: " critical_url
  printf "\n%s\n\n" "Running Critical CSS against ${YELLOW}$critical_url${DEFAULT}"
  npm run critical -- url=${critical_url:-$DEFAULT_CRITICAL_URL}
  printf "\nCommitting styles and scripts..."
  git add --all
  git commit -am "Process scripts/styles"
  printf "\n\n${GREEN}done.${DEFAULT}\n\n"
esac


# Add new line to changelog
printf "\nUsing git messages for CHANGELOG...\n"


# Comparison branch
BRANCH="origin/master"

printf "\nTarget Branch Comparison: $BRANCH...\n\n"

DATE=$(date +%Y-%m-%d)
COMMIT_MSG_AS_CHANGE=$(git log --format="%s" --no-merges $BRANCH.. | sed -E 's/^(.*)/\- \1 \\/')

if [ "$PLATFORM" == "macos" ]; then
  sed -i '' "3i\\
  \\
  ## $ALT_NEXT_VERSION - $DATE\\
  ### CHANGED:\\
  $COMMIT_MSG_AS_CHANGE
  " ../../../CHANGELOG.md
else
  sed -i "3i\\
  \\
  ## $ALT_NEXT_VERSION - $DATE\\
  ### CHANGED:\\
  $COMMIT_MSG_AS_CHANGE " ../../../CHANGELOG.md
fi


## Finalize changelog updates
read -r -p "Finalize the CHANGELOG and continue? [y/N] " response
case "$response" in
  [yY][eE][sS]|[yY])
  vim "+4 $A" ../../../CHANGELOG.md
  ;;
  *)
  printf "\nMust finalize changes. Exiting..."
  exit 1
  ;;
esac


# replace current version with new one in style.css
printf "\nReplacing $CURR_VERSION with "
eval $GET_NEXT_VERSION
sed -i "" -e "s/Version: .*/Version: $ALT_NEXT_VERSION/g" style.css
printf "\n${YELLOW}changelog.md${DEFAULT} updated"
printf "\n${YELLOW}package.json${DEFAULT} updated"
printf "\n${YELLOW}package-lock.json${DEFAULT} updated"
printf "\n${YELLOW}style.css${DEFAULT} updated"
printf "\n\n"


MESSAGE="${NEXT_VERSION}: $(echo "${SEM}" | awk '{print toupper($0)}') RELEASE"
read -r -p "Commit versioning changes? [y/N] " response
case "$response" in
  [yY][eE][sS]|[yY])
  printf "\nCommitting updated files\n\n"
  git commit -am "$MESSAGE"
  printf "\n\n${GREEN}done.${DEFAULT}\n\n"
esac

exit
